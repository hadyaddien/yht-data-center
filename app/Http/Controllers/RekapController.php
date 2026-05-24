<?php

namespace App\Http\Controllers;

use App\Models\ProgramPendidikan;
use App\Models\Sdm;
use App\Models\Sekolah;
use App\Models\TeknologiPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index()
    {
        $tahun        = '2024/2025';
        $totalSekolah = Sekolah::where('status_operasional', 'aktif')->count();
        $terakreditasi = Sekolah::whereNotNull('akreditasi_nilai')->count();

        $sdmAgg = Sdm::where('tahun_ajaran', $tahun)
            ->selectRaw('
                SUM(guru_pns + guru_honorer + guru_p3k) as total_guru,
                SUM(karyawan_pns + karyawan_honorer + karyawan_p3k) as total_karyawan,
                SUM(jumlah_rombel) as total_rombel
            ')->first();

        $stats = [
            'total_sekolah'  => $totalSekolah,
            'total_guru'     => (int) ($sdmAgg->total_guru ?? 0),
            'total_karyawan' => (int) ($sdmAgg->total_karyawan ?? 0),
            'total_rombel'   => (int) ($sdmAgg->total_rombel ?? 0),
            'terakreditasi'  => $terakreditasi . '/' . $totalSekolah,
        ];

        $jenjangLabels = ['TK', 'SD', 'SMP', 'SMA', 'SMK'];
        $jenjangSekolah = Sekolah::where('status_operasional', 'aktif')
            ->select('jenjang', DB::raw('COUNT(*) as total'))
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang');

        $guruPerJenjang = Sdm::where('tahun_ajaran', $tahun)
            ->join('sekolah', 'sdm.sekolah_id', '=', 'sekolah.id')
            ->select('sekolah.jenjang', DB::raw('SUM(guru_pns + guru_honorer + guru_p3k) as total_guru'))
            ->groupBy('sekolah.jenjang')
            ->pluck('total_guru', 'jenjang');

        $jenjangChartData = [
            'labels'  => $jenjangLabels,
            'sekolah' => array_map(fn($j) => (int) ($jenjangSekolah[$j] ?? 0), $jenjangLabels),
            'guru'    => array_map(fn($j) => (int) ($guruPerJenjang[$j] ?? 0), $jenjangLabels),
        ];

        $totalTeknologi = TeknologiPembelajaran::where('tahun_ajaran', $tahun)->count();
        $teknologiAdopsi = [];
        if ($totalTeknologi > 0) {
            $adopsiData = [
                ['label' => 'Software Pembelajaran', 'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->whereNotNull('aplikasi_pembelajaran')->count()],
                ['label' => 'LMS Kemendikdasmen',    'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->where('memiliki_lms', true)->count()],
                ['label' => 'Smart Classroom',       'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->where('memiliki_smart_classroom', true)->count()],
                ['label' => 'E-Book / E-Perpustakaan', 'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->where('memiliki_e_perpustakaan', true)->count()],
                ['label' => 'Tenaga IT',             'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->where('memiliki_tenaga_it', true)->count()],
            ];
            foreach ($adopsiData as $item) {
                $persen = round($item['count'] / $totalTeknologi * 100);
                $teknologiAdopsi[] = [
                    'label'  => $item['label'],
                    'persen' => $persen,
                    'color'  => $persen >= 60 ? 'bg-green-400' : ($persen >= 40 ? 'bg-amber-400' : 'bg-rose-400'),
                ];
            }
        }

        $sekolahList = Sekolah::with(['kota', 'provinsi'])
            ->where('status_operasional', 'aktif')
            ->orderBy('jenjang')->orderBy('nama')
            ->get();

        $sdmMap = Sdm::where('tahun_ajaran', $tahun)
            ->get()->keyBy('sekolah_id');

        $progMap = ProgramPendidikan::where('tahun_ajaran', $tahun)
            ->get()->keyBy('sekolah_id');

        $ringkasanSekolah = $sekolahList->map(function ($s) use ($sdmMap, $progMap) {
            $sdm  = $sdmMap->get($s->id);
            $prog = $progMap->get($s->id);
            return [
                'id'         => $s->id,
                'jenjang'    => $s->jenjang,
                'akreditasi' => $s->akreditasi_label,
                'name'       => $s->nama,
                'lokasi'     => ($s->kota?->nama ?? '') . ', ' . ($s->provinsi?->nama ?? ''),
                'provinsi'   => $s->provinsi?->nama ?? '',
                'kepsek'     => $s->kepala_sekolah_nama,
                'npsn'       => $s->npsn,
                'guru'       => $sdm ? ($sdm->guru_pns + $sdm->guru_honorer + $sdm->guru_p3k) : 0,
                'karyawan'   => $sdm ? ($sdm->karyawan_pns + $sdm->karyawan_honorer + $sdm->karyawan_p3k) : 0,
                'rombel'     => $sdm?->jumlah_rombel ?? 0,
                'rapor'      => [
                    'literasi'            => $s->rapor_literasi ? (float) $s->rapor_literasi : null,
                    'numerasi'            => $s->rapor_numerasi ? (float) $s->rapor_numerasi : null,
                    'karakter'            => $s->rapor_karakter ? (float) $s->rapor_karakter : null,
                    'kualitas_pbm'        => $prog?->pbd_kualitas_pembelajaran ? (float) $prog->pbd_kualitas_pembelajaran : null,
                    'iklim_keamanan'      => $prog?->pbd_iklim_keamanan        ? (float) $prog->pbd_iklim_keamanan        : null,
                    'iklim_kebhinekaan'   => $prog?->pbd_iklim_kebhinekaan     ? (float) $prog->pbd_iklim_kebhinekaan     : null,
                ],
            ];
        })->toArray();

        // Compute average rapor scores for radar chart (6 axes)
        $radarKeys = ['literasi', 'numerasi', 'karakter', 'kualitas_pbm', 'iklim_keamanan', 'iklim_kebhinekaan'];
        $raporAvg  = [];
        foreach ($radarKeys as $key) {
            $vals = array_filter(array_column(array_column($ringkasanSekolah, 'rapor'), $key), fn($v) => $v !== null);
            $raporAvg[$key] = count($vals) ? round(array_sum($vals) / count($vals), 1) : 0;
        }

        return view('rekap-analisis', compact('stats', 'jenjangChartData', 'teknologiAdopsi', 'ringkasanSekolah', 'raporAvg'));
    }
}
