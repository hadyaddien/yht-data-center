<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $sekolahList = $user
            ->applySekolahScope(Sekolah::with(['kota', 'provinsi', 'saranaPrasarana']))
            ->where('status_operasional', 'aktif')
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('sarana-prasarana', compact('sekolahList'));
    }
}
