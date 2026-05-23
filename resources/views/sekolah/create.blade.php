@extends('app-layout')

@section('title', 'Tambah Sekolah Baru')

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-4 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-[#162040]">Tambah Sekolah Baru</h1>
                <p class="text-sm text-gray-400 mt-0.5">Instrumen Pendataan Satuan Pendidikan Yayasan Hang Tuah</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('sekolah.index') }}"
                    class="px-3.5 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    Batal
                </a>
                <button type="submit" form="sekolah-form"
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h9l3 3v13a2 2 0 01-2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 21v-6h6v6M9 3v5h6" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            @include('sekolah._form', [
                'action' => route('sekolah.store'),
                'method' => 'POST',
            ])
        </div>

    </div>
@endsection
