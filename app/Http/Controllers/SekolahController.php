<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::with(['kota', 'provinsi'])
            ->where('status_operasional', 'aktif');

        if ($request->filled('jenjang') && $request->jenjang !== 'Semua Jenjang') {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhereHas('kota', fn($k) => $k->where('nama', 'like', "%{$search}%"));
            });
        }

        $sekolahList = $query->orderBy('jenjang')->orderBy('nama')->get()
            ->map(fn($s) => [
                'id'         => $s->id,
                'name'       => $s->nama,
                'jenjang'    => $s->jenjang,
                'npsn'       => $s->npsn,
                'kota'       => $s->kota?->nama ?? '-',
                'provinsi'   => $s->provinsi->nama,
                'telepon'    => $s->telepon,
                'email'      => $s->email,
                'akreditasi' => $s->akreditasi_label,
            ])->toArray();

        $total = count($sekolahList);

        return view('data-sekolah', compact('sekolahList', 'total'));
    }
}
