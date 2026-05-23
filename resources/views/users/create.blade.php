@extends('app-layout')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-xl font-bold text-[#162040]">Tambah User Baru</h1>
        <p class="text-sm text-gray-400 mt-0.5">Buat akun pengguna baru dan tentukan role-nya</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @include('users._form', [
            'action' => route('users.store'),
            'method' => 'POST',
        ])
    </div>

</div>
@endsection
