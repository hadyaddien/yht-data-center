<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Sdm;
use App\Models\Sekolah;
use App\Models\SaranaPrasarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = '2024/2025';

        $totalSekolah  = Sekolah::where('status_operasional', 'aktif')->count();
        $totalGuru     = Sdm::where('tahun_ajaran', $tahun)
            ->selectRaw('SUM(guru_pns + guru_honorer + guru_p3k) as total')
            ->value('total') ?? 0;
        $terakreditasi = Sekolah::whereNotNull('akreditasi_nilai')->count();
        $rataSarpras   = SaranaPrasarana::where('tahun_ajaran', $tahun)->avg('skor_rata_rata') ?? 0;

        $stats = [
            'total_sekolah' => $totalSekolah,
            'total_guru'    => (int) $totalGuru,
            'terakreditasi' => $terakreditasi,
            'rata_sarpras'  => round($rataSarpras, 1),
        ];

        $jenjangCounts = Sekolah::where('status_operasional', 'aktif')
            ->select('jenjang', DB::raw('COUNT(*) as total'))
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang');

        $jenjangLabels = ['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'];
        $jenjangData   = [
            'labels' => $jenjangLabels,
            'values' => array_map(fn($j) => (int) ($jenjangCounts[$j] ?? 0), $jenjangLabels),
        ];

        $provinsiCounts = Sekolah::where('status_operasional', 'aktif')
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

        $recentSchools = Sekolah::with(['kota', 'provinsi'])
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

        return view('dashboard', compact('stats', 'jenjangData', 'provinsiData', 'recentSchools'));
    }
}
