<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;

class TeknologiController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $sekolahList = $user
            ->applySekolahScope(Sekolah::with(['kota', 'provinsi', 'teknologiPembelajaran']))
            ->where('status_operasional', 'aktif')
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('teknologi-pembelajaran', compact('sekolahList'));
    }
}
