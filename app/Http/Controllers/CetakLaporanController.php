<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\User;

class CetakLaporanController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $sekolahList = $user->applySekolahScope(
            Sekolah::with([
                'kota',
                'provinsi',
                'sdm' => fn ($q) => $q->orderBy('tahun_ajaran', 'desc'),
                'sdmGuru',
                'saranaPrasarana' => fn ($q) => $q->orderBy('tahun_ajaran', 'desc'),
                'programPendidikan' => fn ($q) => $q->orderBy('tahun_ajaran', 'desc'),
                'teknologiPembelajaran' => fn ($q) => $q->orderBy('tahun_ajaran', 'desc'),
            ])
        )
            ->where('status_operasional', 'aktif')
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        $jenjangCounts = $sekolahList->groupBy('jenjang')->map(fn ($g) => $g->count());

        return view('cetak-laporan', compact('sekolahList', 'jenjangCounts'));
    }
}
