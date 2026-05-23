<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SarprasController extends Controller
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
                    ->orWhereHas('kota', fn($k) => $k->where('nama', 'like', "%{$search}%"));
            });
        }

        $sekolahList = $query->orderBy('jenjang')->orderBy('nama')->get()
            ->map(fn($s) => [
                'id'       => $s->id,
                'name'     => $s->nama,
                'jenjang'  => $s->jenjang,
                'kota'     => $s->kota?->nama ?? '-',
                'provinsi' => $s->provinsi->nama,
            ])->toArray();

        return view('sarana-prasarana', compact('sekolahList'));
    }
}
