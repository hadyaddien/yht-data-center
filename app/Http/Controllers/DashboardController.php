<?php

namespace App\Http\Controllers;

use App\Models\Sdm;
use App\Models\Sekolah;
use App\Models\SaranaPrasarana;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = '2024/2025';

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $baseSekolahQuery = $user->applySekolahScope(Sekolah::query())
            ->where('status_operasional', 'aktif');

        $schoolIds = (clone $baseSekolahQuery)->pluck('id');

        $sdmAgg = Sdm::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->selectRaw('
                SUM(COALESCE(guru_pns, 0) + COALESCE(guru_honorer, 0) + COALESCE(guru_p3k, 0)) as agg_total_guru,
                SUM(COALESCE(jumlah_murid_total, 0)) as total_murid,
                SUM(COALESCE(jumlah_murid_laki, 0)) as total_murid_laki,
                SUM(COALESCE(jumlah_murid_perempuan, 0)) as total_murid_perempuan,
                SUM(COALESCE(murid_ortu_tni_al, 0)) as ortu_tni_al,
                SUM(COALESCE(murid_ortu_tni, 0)) as ortu_tni,
                SUM(COALESCE(murid_ortu_polisi, 0)) as ortu_polisi,
                SUM(COALESCE(murid_ortu_pns, 0)) as ortu_pns,
                SUM(COALESCE(murid_ortu_pengusaha, 0)) as ortu_pengusaha,
                SUM(COALESCE(murid_ortu_wiraswasta, 0)) as ortu_wiraswasta,
                SUM(COALESCE(murid_ortu_buruh, 0)) as ortu_buruh,
                SUM(COALESCE(murid_ortu_guru, 0)) as ortu_guru,
                SUM(COALESCE(murid_ortu_lainnya_jumlah, 0)) as ortu_lainnya
            ')
            ->first();

        $totalSekolah  = (clone $baseSekolahQuery)->count();
        $totalGuru     = (int) ($sdmAgg->agg_total_guru ?? 0);
        $totalMurid    = (int) ($sdmAgg->total_murid ?? 0);
        $totalMuridL   = (int) ($sdmAgg->total_murid_laki ?? 0);
        $totalMuridP   = (int) ($sdmAgg->total_murid_perempuan ?? 0);
        $terakreditasi = (clone $baseSekolahQuery)->whereNotNull('akreditasi_nilai')->count();
        $rataSarpras   = SaranaPrasarana::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->avg('skor_rata_rata') ?? 0;

        $stats = [
            'total_sekolah' => $totalSekolah,
            'total_guru'    => $totalGuru,
            'total_murid'   => $totalMurid,
            'murid_laki'    => $totalMuridL,
            'murid_perempuan' => $totalMuridP,
            'terakreditasi' => $terakreditasi,
            'rata_sarpras'  => round($rataSarpras, 1),
        ];

        $komposisiOrtuRaw = [
            'PNS' => (int) ($sdmAgg->ortu_pns ?? 0),
            'TNI AL' => (int) ($sdmAgg->ortu_tni_al ?? 0),
            'TNI' => (int) ($sdmAgg->ortu_tni ?? 0),
            'Polisi' => (int) ($sdmAgg->ortu_polisi ?? 0),
            'Pengusaha' => (int) ($sdmAgg->ortu_pengusaha ?? 0),
            'Wiraswasta' => (int) ($sdmAgg->ortu_wiraswasta ?? 0),
            'Buruh' => (int) ($sdmAgg->ortu_buruh ?? 0),
            'Guru' => (int) ($sdmAgg->ortu_guru ?? 0),
            'Lainnya' => (int) ($sdmAgg->ortu_lainnya ?? 0),
        ];

        $komposisiOrtu = collect($komposisiOrtuRaw)
            ->map(function ($count, $label) use ($totalMurid) {
                $persen = $totalMurid > 0 ? round(($count / $totalMurid) * 100, 1) : 0;
                return [
                    'label' => $label,
                    'count' => $count,
                    'persen' => $persen,
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->take(5)
            ->all();

        $jenjangCounts = (clone $baseSekolahQuery)
            ->select('jenjang', DB::raw('COUNT(*) as total'))
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang');

        $jenjangLabels = ['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'];
        $jenjangData   = [
            'labels' => $jenjangLabels,
            'values' => array_map(fn($j) => (int) ($jenjangCounts[$j] ?? 0), $jenjangLabels),
        ];

        $provinsiCounts = (clone $baseSekolahQuery)
            ->join('provinsi', 'sekolah.provinsi_id', '=', 'provinsi.id')
            ->select('provinsi.nama', DB::raw('COUNT(*) as total'))
            ->groupBy('provinsi.nama')
            ->orderByDesc('total')
            ->get();

        $colors = ['#162040', '#f59e0b', '#10b981', '#e11d48', '#6366f1', '#0ea5e9'];
        $provinsiData = [
            'labels' => $provinsiCounts->pluck('nama')->toArray(),
            'values' => $provinsiCounts->pluck('total')->map(fn($v) => (int) $v)->toArray(),
            'colors' => array_slice($colors, 0, $provinsiCounts->count()),
        ];

        $badgeColors = [
            'KB'  => 'bg-gray-100 text-gray-600',
            'TK'  => 'bg-amber-100 text-amber-700',
            'SD'  => 'bg-blue-100 text-blue-700',
            'SMP' => 'bg-green-100 text-green-700',
            'SMA' => 'bg-purple-100 text-purple-700',
            'SMK' => 'bg-orange-100 text-orange-700',
        ];

        $recentSchools = $user->applySekolahScope(Sekolah::with(['kota', 'provinsi']))
            ->where('status_operasional', 'aktif')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($s) => [
                'name'     => $s->nama,
                'location' => ($s->kota?->nama ?? '') . ', ' . $s->provinsi->nama,
                'jenjang'  => $s->jenjang,
                'color'    => $badgeColors[$s->jenjang] ?? 'bg-gray-100 text-gray-600',
            ])->toArray();

        return view('dashboard', compact('stats', 'jenjangData', 'provinsiData', 'recentSchools', 'komposisiOrtu'));
    }
}
