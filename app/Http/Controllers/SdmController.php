<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;

class SdmController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $query = $user
            ->applySekolahScope(Sekolah::with(['kota', 'provinsi']))
            ->where('status_operasional', 'aktif');

        if ($request->filled('jenjang') && $request->jenjang !== 'Semua Jenjang') {
            $query->where('jenjang', $request->jenjang);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('npsn', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('lokasi')) {
            $query->where('provinsi_id', $request->lokasi);
        }
        if ($request->filled('akreditasi')) {
            if ($request->akreditasi === 'belum') {
                $query->whereNull('akreditasi_predikat');
            } else {
                $query->where('akreditasi_predikat', strtoupper($request->akreditasi));
            }
        }

        $sekolahList = $query->orderBy('jenjang')->orderBy('nama')->get();
        $total = $sekolahList->count();
        $provinsiList = Provinsi::orderBy('name')->get();

        return view('sdm', compact('sekolahList', 'total', 'provinsiList'));
    }

    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi', 'dokumen']);
        $sdm = $sekolah->sdm()->orderBy('tahun_ajaran', 'desc')->first();

        return view('sdm-show', compact('sekolah', 'sdm'));
    }

    public function edit(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi', 'dokumen']);
        $sdmData = $sekolah->sdm()->orderBy('tahun_ajaran', 'desc')->first();
        $isEdit = true;
        $moduleOnly = 'sdm';

        return view('sekolah.module-edit', compact('sekolah', 'sdmData', 'isEdit', 'moduleOnly'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate(
            (new SekolahController)->sdmRules(),
            (new SekolahController)->messages()
        );
        (new SekolahController)->saveSdm($request, $sekolah);
        (new SekolahController)->saveDokumen($request, $sekolah);

        return redirect()->route('sdm.index')
            ->with('success', "Data SDM \"{$sekolah->nama}\" berhasil diperbarui.");
    }
}
