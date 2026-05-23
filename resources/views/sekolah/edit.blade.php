@extends('app-layout')

@section('title', 'Edit Sekolah')

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-4 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-[#162040]">Edit Sekolah</h1>
                <p class="text-sm text-gray-400 mt-0.5">{{ $sekolah->nama }}</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('sekolah.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" form="sekolah-form"
                    class="inline-flex items-center gap-2 px-5 py-2 bg-[#162040] hover:bg-[#1e2f5a] text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            @include('sekolah._form', [
                'action' => route('sekolah.update', $sekolah),
                'method' => 'PUT',
                'kecamatanList' => $kecamatanList,
                'kelurahanList' => $kelurahanList,
            ])
        </div>

    </div>
@endsection
