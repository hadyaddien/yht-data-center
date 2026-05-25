<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function checkSuperAdmin(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
    }

    /* ─── INDEX ─────────────────────────────────────────── */
    public function index(Request $request)
    {
        $this->checkSuperAdmin();

        $query = User::with(['provinsi', 'sekolah'])->orderBy('role')->orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        $total = $users->count();

        return view('users.index', compact('users', 'total'));
    }

    /* ─── CREATE ─────────────────────────────────────────── */
    public function create()
    {
        $this->checkSuperAdmin();

        $provinsiList = Provinsi::orderBy('nama')->get();
        $sekolahList  = Sekolah::orderBy('nama')->get();

        return view('users.create', compact('provinsiList', 'sekolahList'));
    }

    /* ─── STORE ─────────────────────────────────────────── */
    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'role'                  => ['required', 'in:superadmin,admin_wilayah,kepala_sekolah'],
            'provinsi_id'           => ['nullable', 'exists:provinsi,id', 'required_if:role,admin_wilayah'],
            'sekolah_id'            => ['nullable', 'exists:sekolah,id', 'required_if:role,kepala_sekolah'],
        ], [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'provinsi_id.required_if' => 'Provinsi wajib dipilih untuk Admin Wilayah.',
            'sekolah_id.required_if'  => 'Sekolah wajib dipilih untuk Kepala Sekolah.',
        ]);

        if ($validated['role'] === 'superadmin') {
            $validated['provinsi_id'] = null;
            $validated['sekolah_id'] = null;
        }

        if ($validated['role'] === 'admin_wilayah') {
            $validated['sekolah_id'] = null;
        }

        if ($validated['role'] === 'kepala_sekolah') {
            $validated['provinsi_id'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan.");
    }

    /* ─── EDIT ───────────────────────────────────────────── */
    public function edit(User $user)
    {
        $this->checkSuperAdmin();

        $provinsiList = Provinsi::orderBy('nama')->get();
        $sekolahList  = Sekolah::orderBy('nama')->get();

        return view('users.edit', compact('user', 'provinsiList', 'sekolahList'));
    }

    /* ─── UPDATE ─────────────────────────────────────────── */
    public function update(Request $request, User $user)
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'                  => ['required', 'in:superadmin,admin_wilayah,kepala_sekolah'],
            'provinsi_id'           => ['nullable', 'exists:provinsi,id', 'required_if:role,admin_wilayah'],
            'sekolah_id'            => ['nullable', 'exists:sekolah,id', 'required_if:role,kepala_sekolah'],
        ], [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan akun lain.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'provinsi_id.required_if' => 'Provinsi wajib dipilih untuk Admin Wilayah.',
            'sekolah_id.required_if'  => 'Sekolah wajib dipilih untuk Kepala Sekolah.',
        ]);

        if ($validated['role'] === 'superadmin') {
            $validated['provinsi_id'] = null;
            $validated['sekolah_id'] = null;
        }

        if ($validated['role'] === 'admin_wilayah') {
            $validated['sekolah_id'] = null;
        }

        if ($validated['role'] === 'kepala_sekolah') {
            $validated['provinsi_id'] = null;
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" berhasil diperbarui.");
    }

    /* ─── DESTROY ────────────────────────────────────────── */
    public function destroy(User $user)
    {
        $this->checkSuperAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User \"{$nama}\" berhasil dihapus.");
    }
}
