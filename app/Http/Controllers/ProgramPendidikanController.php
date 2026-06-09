<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramPendidikanController extends Controller
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
            // Filter by province ID
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

        return view('program-pendidikan', compact('sekolahList', 'total', 'provinsiList'));
    }

    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi', 'dokumen']);
        $pp = $sekolah->programPendidikan()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->programPendidikan()->orderBy('tahun_ajaran', 'desc')->first();

        return view('program-pendidikan-show', compact('sekolah', 'pp'));
    }

    public function edit(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi', 'dokumen']);
        $programPendidikan = $sekolah->programPendidikan()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->programPendidikan()->orderBy('tahun_ajaran', 'desc')->first();
        $isEdit = true;
        $moduleOnly = 'program';

        return view('sekolah.module-edit', compact('sekolah', 'programPendidikan', 'isEdit', 'moduleOnly'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate(
            (new SekolahController)->programPendidikanRules(),
            (new SekolahController)->messages()
        );
        (new SekolahController)->saveProgramPendidikan($request, $sekolah);
        (new SekolahController)->saveDokumen($request, $sekolah);

        return redirect()->route('program.index')
            ->with('success', "Data program pendidikan \"{$sekolah->nama}\" berhasil diperbarui.");
    }
}
