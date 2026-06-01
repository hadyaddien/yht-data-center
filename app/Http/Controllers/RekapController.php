<?php

namespace App\Http\Controllers;

use App\Models\ProgramPendidikan;
use App\Models\Sdm;
use App\Models\Sekolah;
use App\Models\TeknologiPembelajaran;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index()
    {
        $tahun        = '2024/2025';

        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_if($user->isKepalaSekolah(), 403, 'Anda tidak memiliki akses ke halaman Rekap & Analisis.');

        $baseSekolahQuery = $user->applySekolahScope(Sekolah::query())
            ->where('status_operasional', 'aktif');

        $schoolIds = (clone $baseSekolahQuery)->pluck('id');
        $totalSekolah = (clone $baseSekolahQuery)->count();
        $terakreditasi = (clone $baseSekolahQuery)->whereNotNull('akreditasi_nilai')->count();

        $sdmAgg = Sdm::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->selectRaw('
                SUM(COALESCE(guru_pns, 0) + COALESCE(guru_honorer, 0) + COALESCE(guru_p3k, 0)) as agg_guru_total,
                SUM(COALESCE(karyawan_pns, 0) + COALESCE(karyawan_honorer, 0) + COALESCE(karyawan_p3k, 0)) as agg_karyawan_total,
                SUM(COALESCE(jumlah_rombel, 0)) as total_rombel,
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
            ')->first();

        $stats = [
            'total_sekolah'  => $totalSekolah,
            'total_guru'     => (int) ($sdmAgg->agg_guru_total ?? 0),
            'total_karyawan' => (int) ($sdmAgg->agg_karyawan_total ?? 0),
            'total_rombel'   => (int) ($sdmAgg->total_rombel ?? 0),
            'total_murid'    => (int) ($sdmAgg->total_murid ?? 0),
            'total_murid_laki' => (int) ($sdmAgg->total_murid_laki ?? 0),
            'total_murid_perempuan' => (int) ($sdmAgg->total_murid_perempuan ?? 0),
            'terakreditasi'  => $terakreditasi . '/' . $totalSekolah,
        ];

        $jenjangLabels = ['TK', 'SD', 'SMP', 'SMA', 'SMK'];
        $jenjangSekolah = (clone $baseSekolahQuery)
            ->select('jenjang', DB::raw('COUNT(*) as total'))
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang');

        $guruPerJenjang = Sdm::where('tahun_ajaran', $tahun)
            ->whereIn('sdm.sekolah_id', $schoolIds)
            ->join('sekolah', 'sdm.sekolah_id', '=', 'sekolah.id')
            ->select('sekolah.jenjang', DB::raw('SUM(COALESCE(guru_pns, 0) + COALESCE(guru_honorer, 0) + COALESCE(guru_p3k, 0)) as total_guru'))
            ->groupBy('sekolah.jenjang')
            ->pluck('total_guru', 'jenjang');

        $muridPerJenjang = Sdm::where('tahun_ajaran', $tahun)
            ->whereIn('sdm.sekolah_id', $schoolIds)
            ->join('sekolah', 'sdm.sekolah_id', '=', 'sekolah.id')
            ->select('sekolah.jenjang', DB::raw('SUM(jumlah_murid_total) as total_murid'))
            ->groupBy('sekolah.jenjang')
            ->pluck('total_murid', 'jenjang');

        $jenjangChartData = [
            'labels'  => $jenjangLabels,
            'sekolah' => array_map(fn($j) => (int) ($jenjangSekolah[$j] ?? 0), $jenjangLabels),
            'guru'    => array_map(fn($j) => (int) ($guruPerJenjang[$j] ?? 0), $jenjangLabels),
            'murid'   => array_map(fn($j) => (int) ($muridPerJenjang[$j] ?? 0), $jenjangLabels),
        ];

        $totalMurid = (int) ($sdmAgg->total_murid ?? 0);
        $komposisiOrtuRaw = [
            'TNI AL' => (int) ($sdmAgg->ortu_tni_al ?? 0),
            'TNI' => (int) ($sdmAgg->ortu_tni ?? 0),
            'Polisi' => (int) ($sdmAgg->ortu_polisi ?? 0),
            'PNS' => (int) ($sdmAgg->ortu_pns ?? 0),
            'Pengusaha' => (int) ($sdmAgg->ortu_pengusaha ?? 0),
            'Wiraswasta' => (int) ($sdmAgg->ortu_wiraswasta ?? 0),
            'Buruh' => (int) ($sdmAgg->ortu_buruh ?? 0),
            'Guru' => (int) ($sdmAgg->ortu_guru ?? 0),
            'Lainnya' => (int) ($sdmAgg->ortu_lainnya ?? 0),
        ];

        $ortuColorMap = [
            'TNI AL' => 'bg-cyan-500',
            'TNI' => 'bg-blue-500',
            'Polisi' => 'bg-indigo-500',
            'PNS' => 'bg-emerald-500',
            'Pengusaha' => 'bg-amber-500',
            'Wiraswasta' => 'bg-orange-500',
            'Buruh' => 'bg-rose-500',
            'Guru' => 'bg-violet-500',
            'Lainnya' => 'bg-slate-500',
        ];

        $komposisiOrtu = collect($komposisiOrtuRaw)
            ->map(function ($count, $label) use ($totalMurid, $ortuColorMap) {
                $persen = $totalMurid > 0 ? round(($count / $totalMurid) * 100, 1) : 0;
                return [
                    'label' => $label,
                    'count' => $count,
                    'persen' => $persen,
                    'color' => $ortuColorMap[$label] ?? 'bg-gray-400',
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->all();

        $totalTeknologi = TeknologiPembelajaran::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->count();
        $teknologiAdopsi = [];
        if ($totalTeknologi > 0) {
            $adopsiData = [
                ['label' => 'Software Pembelajaran', 'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->whereIn('sekolah_id', $schoolIds)->whereNotNull('aplikasi_pembelajaran')->count()],
                ['label' => 'LMS Kemendikdasmen',    'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->whereIn('sekolah_id', $schoolIds)->where('memiliki_lms', true)->count()],
                ['label' => 'Smart Classroom',       'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->whereIn('sekolah_id', $schoolIds)->where('memiliki_smart_classroom', true)->count()],
                ['label' => 'E-Book / E-Perpustakaan', 'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->whereIn('sekolah_id', $schoolIds)->where('memiliki_e_perpustakaan', true)->count()],
                ['label' => 'Tenaga IT',             'count' => TeknologiPembelajaran::where('tahun_ajaran', $tahun)->whereIn('sekolah_id', $schoolIds)->where('memiliki_tenaga_it', true)->count()],
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

        $sekolahList = $user->applySekolahScope(Sekolah::with(['kota', 'provinsi']))
            ->where('status_operasional', 'aktif')
            ->orderBy('jenjang')->orderBy('nama')
            ->get();

        $sdmMap = Sdm::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->get()->keyBy('sekolah_id');

        $progMap = ProgramPendidikan::where('tahun_ajaran', $tahun)
            ->whereIn('sekolah_id', $schoolIds)
            ->get()->keyBy('sekolah_id');

        $parseFunding = static function ($value): float {
            if ($value === null || $value === '') {
                return 0;
            }

            return (float) preg_replace('/[^0-9.]/', '', (string) $value);
        };

        $ringkasanSekolah = $sekolahList->map(function ($s) use ($sdmMap, $progMap, $parseFunding) {
            $sdm  = $sdmMap->get($s->id);
            $prog = $progMap->get($s->id);
            return [
                'id'         => $s->id,
                'jenjang'    => $s->jenjang,
                'akreditasi' => $s->akreditasi_label,
                'name'       => $s->nama,
                'kota'       => $s->kota?->nama ?? '',
                'lokasi'     => ($s->kota?->nama ?? '') . ', ' . ($s->provinsi?->nama ?? ''),
                'provinsi'   => $s->provinsi?->nama ?? '',
                'kepsek'     => $s->kepala_sekolah_nama,
                'npsn'       => $s->npsn,
                'luas_tanah' => $s->luas_tanah ? (float) $s->luas_tanah : 0,
                'guru'       => $sdm ? ($sdm->guru_pns + $sdm->guru_honorer + $sdm->guru_p3k) : 0,
                'guru_tetap' => $sdm ? ($sdm->guru_pns + $sdm->guru_p3k) : 0,
                'guru_sertifikasi' => (int) ($sdm?->guru_bersertifikasi ?? 0),
                'karyawan'   => $sdm ? ($sdm->karyawan_pns + $sdm->karyawan_honorer + $sdm->karyawan_p3k) : 0,
                'rombel'     => $sdm?->jumlah_rombel ?? 0,
                'murid_total' => $sdm?->jumlah_murid_total ?? 0,
                'murid_laki' => $sdm?->jumlah_murid_laki ?? 0,
                'murid_perempuan' => $sdm?->jumlah_murid_perempuan ?? 0,
                'penerimaan_bos' => $parseFunding($prog?->penerimaan_bos),
                'penerimaan_bop' => $parseFunding($prog?->penerimaan_bop),
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

        return view('rekap-analisis', compact('stats', 'jenjangChartData', 'teknologiAdopsi', 'komposisiOrtu', 'ringkasanSekolah', 'raporAvg'));
    }
}
