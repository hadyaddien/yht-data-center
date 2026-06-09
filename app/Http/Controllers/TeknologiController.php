<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;

class TeknologiController extends Controller
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

        return view('teknologi-pembelajaran', compact('sekolahList', 'total', 'provinsiList'));
    }

    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi', 'dokumen']);
        $tp = $sekolah->teknologiPembelajaran()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->teknologiPembelajaran()->orderBy('tahun_ajaran', 'desc')->first();

        return view('teknologi-pembelajaran-show', compact('sekolah', 'tp'));
    }

    public function edit(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi', 'dokumen']);
        $teknologiPembelajaran = $sekolah->teknologiPembelajaran()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->teknologiPembelajaran()->orderBy('tahun_ajaran', 'desc')->first();
        $isEdit = true;
        $moduleOnly = 'teknologi';

        return view('sekolah.module-edit', compact('sekolah', 'teknologiPembelajaran', 'isEdit', 'moduleOnly'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate(
            (new SekolahController)->teknologiRules(),
            (new SekolahController)->messages()
        );
        (new SekolahController)->saveTeknologiPembelajaran($request, $sekolah);
        (new SekolahController)->saveDokumen($request, $sekolah);

        return redirect()->route('teknologi.index')
            ->with('success', "Data teknologi \"{$sekolah->nama}\" berhasil diperbarui.");
    }
}
