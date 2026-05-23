@extends('app-layout')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-xl font-bold text-[#162040]">Edit User</h1>
        <p class="text-sm text-gray-400 mt-0.5">{{ $user->name }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @include('users._form', [
            'action' => route('users.update', $user),
            'method' => 'PUT',
        ])
    </div>

</div>
@endsection
