@extends('app-layout')

@php
    $moduleLabelMap = [
        'program' => 'Program Pendidikan',
        'teknologi' => 'Teknologi Pembelajaran',
        'sarpras' => 'Sarana Prasarana',
        'sdm' => 'SDM',
    ];
    $moduleLabel = $moduleLabelMap[$moduleOnly] ?? 'Edit Data';
@endphp

@section('title', $moduleLabel)

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-4 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-[#162040]">Edit {{ $moduleLabel }} — {{ $sekolah->nama }}</h1>
                <p class="text-sm text-gray-400 mt-0.5">{{ $sekolah->npsn }} · {{ $sekolah->jenjang }}</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route($moduleOnly . '.index') }}"
                    class="px-3.5 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    Batal
                </a>
                <button type="submit" form="sekolah-form"
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            @include('sekolah._form', [
                'action' => route($moduleOnly . '.update', $sekolah),
                'method' => 'PUT',
            ])
        </div>
    </div>
@endsection
