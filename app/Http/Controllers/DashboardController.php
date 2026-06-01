<?php

namespace App\Http\Controllers;

use App\Models\SaranaPrasarana;
use App\Models\Sdm;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = '2024/2025';

        /** @var User $user */
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

        $totalSekolah = (clone $baseSekolahQuery)->count();
        $totalGuru = (int) ($sdmAgg->agg_total_guru ?? 0);
        $totalMurid = (int) ($sdmAgg->total_murid ?? 0);
        $totalMuridL = (int) ($sdmAgg->total_murid_laki ?? 0);
        $totalMuridP = (int) ($sdmAgg->total_murid_perempuan ?? 0);
        $terakreditasi = (clone $baseSekolahQuery)->whereNotNull('akreditasi_nilai')->count();
        $rataSarpras = SaranaPrasarana::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->avg('skor_rata_rata') ?? 0;

        $stats = [
            'total_sekolah' => $totalSekolah,
            'total_guru' => $totalGuru,
            'total_murid' => $totalMurid,
            'murid_laki' => $totalMuridL,
            'murid_perempuan' => $totalMuridP,
            'terakreditasi' => $terakreditasi,
            'rata_sarpras' => round($rataSarpras, 1),
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
        $jenjangData = [
            'labels' => $jenjangLabels,
            'values' => array_map(fn ($j) => (int) ($jenjangCounts[$j] ?? 0), $jenjangLabels),
        ];

        $jenjangCardColors = [
            'KB' => 'bg-pink-100 text-pink-700',
            'TK' => 'bg-violet-100 text-violet-700',
            'SD' => 'bg-blue-100 text-blue-700',
            'SMP' => 'bg-emerald-100 text-emerald-700',
            'SMA' => 'bg-amber-100 text-amber-700',
            'SMK' => 'bg-rose-100 text-rose-700',
        ];

        $jenjangCards = collect($jenjangLabels)
            ->map(fn ($jenjang) => [
                'label' => $jenjang,
                'count' => (int) ($jenjangCounts[$jenjang] ?? 0),
                'badge' => $jenjangCardColors[$jenjang] ?? 'bg-gray-100 text-gray-600',
            ])
            ->all();

        $wilayahLabelExpr = "COALESCE(indonesia_provinces.name, 'Belum Ditentukan')";
        $wilayahCounts = (clone $baseSekolahQuery)
            ->leftJoin('indonesia_provinces', 'sekolah.provinsi_id', '=', 'indonesia_provinces.id')
            ->selectRaw("{$wilayahLabelExpr} as nama, COUNT(*) as total")
            ->groupBy(DB::raw($wilayahLabelExpr))
            ->orderByDesc('total')
            ->get();

        $colors = ['#162040', '#f59e0b', '#10b981', '#e11d48', '#6366f1', '#0ea5e9', '#14b8a6', '#f97316'];
        $provinsiColors = [];
        for ($i = 0; $i < $wilayahCounts->count(); $i++) {
            $provinsiColors[] = $colors[$i % count($colors)];
        }

        $provinsiData = [
            'labels' => $wilayahCounts->pluck('nama')->toArray(),
            'values' => $wilayahCounts->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
            'colors' => $provinsiColors,
        ];

        $maxWilayahCount = max(1, (int) ($wilayahCounts->max('total') ?? 0));
        $wilayahSummary = $wilayahCounts
            ->map(fn ($item) => [
                'label' => $item->nama,
                'count' => (int) $item->total,
                'percent' => round(((int) $item->total / $maxWilayahCount) * 100, 1),
            ])
            ->values()
            ->all();

        $akreditasiCounts = (clone $baseSekolahQuery)
            ->select(['akreditasi_predikat', 'akreditasi_nilai'])
            ->get()
            ->map(function ($item) {
                $predikat = strtoupper(trim((string) ($item->akreditasi_predikat ?? '')));
                if ($predikat === '') {
                    $nilai = $item->akreditasi_nilai;
                    if ($nilai === null) {
                        return 'Belum Akreditasi';
                    }

                    return match (true) {
                        $nilai >= 91 => 'Unggul',
                        $nilai >= 71 => 'Baik Sekali',
                        $nilai >= 51 => 'Baik',
                        default => 'Cukup',
                    };
                }

                return match ($predikat) {
                    'UNGGUL' => 'Unggul',
                    'BAIK SEKALI' => 'Baik Sekali',
                    'BAIK' => 'Baik',
                    'CUKUP' => 'Cukup',
                    default => 'Belum Akreditasi',
                };
            })
            ->countBy();

        $akreditasiOrder = ['Unggul', 'Baik Sekali', 'Baik', 'Cukup', 'Belum Akreditasi'];
        $akreditasiSummary = collect($akreditasiOrder)
            ->map(fn ($label) => [
                'label' => $label,
                'count' => (int) ($akreditasiCounts[$label] ?? 0),
            ])
            ->filter(fn ($item) => $item['count'] > 0)
            ->values();

        if ($akreditasiSummary->isEmpty()) {
            $akreditasiSummary = collect([
                ['label' => 'Belum Akreditasi', 'count' => 0],
            ]);
        }

        $maxAkreditasiCount = max(1, (int) $akreditasiSummary->max('count'));
        $akreditasiSummary = $akreditasiSummary
            ->map(fn ($item) => $item + [
                'percent' => round(($item['count'] / $maxAkreditasiCount) * 100, 1),
            ])
            ->all();

        $badgeColors = [
            'KB' => 'bg-gray-100 text-gray-600',
            'TK' => 'bg-amber-100 text-amber-700',
            'SD' => 'bg-blue-100 text-blue-700',
            'SMP' => 'bg-green-100 text-green-700',
            'SMA' => 'bg-purple-100 text-purple-700',
            'SMK' => 'bg-orange-100 text-orange-700',
        ];

        $recentSchools = $user->applySekolahScope(Sekolah::with(['kota', 'provinsi']))
            ->where('status_operasional', 'aktif')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($s) => [
                'name' => $s->nama,
                'location' => ($s->kota?->nama ?? '').', '.$s->provinsi->nama,
                'jenjang' => $s->jenjang,
                'color' => $badgeColors[$s->jenjang] ?? 'bg-gray-100 text-gray-600',
            ])->toArray();

        return view('dashboard', compact(
            'stats',
            'jenjangData',
            'provinsiData',
            'recentSchools',
            'komposisiOrtu',
            'jenjangCards',
            'wilayahSummary',
            'akreditasiSummary'
        ));
    }
}
