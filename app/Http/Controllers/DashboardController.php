<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sekolah'   => 5,
            'total_guru'      => 128,
            'terakreditasi'   => 4,
            'rata_sarpras'    => 20,
        ];

        $jenjangData = [
            'labels' => ['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'],
            'values' => [1, 1, 1, 1, 1, 1],
        ];

        $provinsiData = [
            'labels' => ['Jakarta', 'Sulawesi Selatan', 'Jawa Timur', 'Sulawesi Utara'],
            'values' => [2, 1, 1, 1],
            'colors' => ['#162040', '#f59e0b', '#10b981', '#e11d48'],
        ];

        $recentSchools = [
            ['name' => 'SD Hang Tuah 6 Makassar',   'location' => 'Makassar',       'jenjang' => 'SD',  'color' => 'bg-blue-100 text-blue-700'],
            ['name' => 'SMP Hang Tuah 2 Jakarta',    'location' => 'Jakarta Selatan','jenjang' => 'SMP', 'color' => 'bg-green-100 text-green-700'],
            ['name' => 'SMA Hang Tuah 1 Surabaya',   'location' => 'Surabaya',       'jenjang' => 'SMA', 'color' => 'bg-purple-100 text-purple-700'],
            ['name' => 'TK Hang Tuah 3 Manado',      'location' => 'Manado',         'jenjang' => 'TK',  'color' => 'bg-amber-100 text-amber-700'],
            ['name' => 'SMK Hang Tuah 1 Jakarta',    'location' => 'Jakarta Utara',  'jenjang' => 'SMK', 'color' => 'bg-orange-100 text-orange-700'],
        ];

        return view('dashboard', compact('stats', 'jenjangData', 'provinsiData', 'recentSchools'));
    }
}
