<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class ProgramPendidikanController extends Controller
{
    public function index(Request $request)
    {
        $sekolahList = Sekolah::with(['kota', 'provinsi', 'programPendidikan'])
            ->where('status_operasional', 'aktif')
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('program-pendidikan', compact('sekolahList'));
    }
}
