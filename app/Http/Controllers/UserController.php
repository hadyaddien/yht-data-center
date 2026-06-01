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
    private function currentUser(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }

    private function authorizeManageUsersPage(): User
    {
        $user = $this->currentUser();

        abort_unless(
            $user->isSuperAdmin() || $user->isAdminWilayah(),
            403,
            'Anda tidak memiliki akses ke manajemen user.'
        );

        return $user;
    }

    private function canAdminWilayahManageKepalaSekolah(User $adminWilayah, User $target): bool
    {
        if (! $adminWilayah->isAdminWilayah() || ! $adminWilayah->provinsi_id) {
            return false;
        }

        if (! $target->isKepalaSekolah() || ! $target->sekolah_id) {
            return false;
        }

        return Sekolah::query()
            ->whereKey($target->sekolah_id)
            ->where('provinsi_id', $adminWilayah->provinsi_id)
            ->exists();
    }

    private function authorizeEditUser(User $target): User
    {
        $actor = $this->authorizeManageUsersPage();

        if ($actor->isSuperAdmin()) {
            return $actor;
        }

        abort_unless(
            $this->canAdminWilayahManageKepalaSekolah($actor, $target),
            403,
            'Admin Wilayah hanya dapat mengelola akun Kepala Sekolah di wilayahnya.'
        );

        return $actor;
    }

    private function checkSuperAdmin(): User
    {
        $user = $this->currentUser();

        abort_unless($user->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengakses halaman ini.');

        return $user;
    }

    /* ─── INDEX ─────────────────────────────────────────── */
    public function index(Request $request)
    {
        $actor = $this->authorizeManageUsersPage();

        $query = User::with(['provinsi', 'sekolah'])->orderBy('role')->orderBy('name');

        if ($actor->isAdminWilayah()) {
            if (! $actor->provinsi_id) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('role', 'kepala_sekolah')
                    ->whereHas('sekolah', fn ($q) => $q->where('provinsi_id', $actor->provinsi_id));
            }
        }

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
        $canCreateUser = $actor->isSuperAdmin();
        $actorIsAdminWilayah = $actor->isAdminWilayah();

        return view('users.index', compact('users', 'total', 'canCreateUser', 'actorIsAdminWilayah'));
    }

    /* ─── CREATE ─────────────────────────────────────────── */
    public function create()
    {
        $this->checkSuperAdmin();

        $provinsiList = Provinsi::orderBy('name')->get();
        $sekolahList = Sekolah::orderBy('nama')->get();

        return view('users.create', compact('provinsiList', 'sekolahList'));
    }

    /* ─── STORE ─────────────────────────────────────────── */
    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:superadmin,admin_wilayah,kepala_sekolah'],
            'provinsi_id' => ['nullable', 'exists:indonesia_provinces,id', 'required_if:role,admin_wilayah'],
            'sekolah_id' => ['nullable', 'exists:sekolah,id', 'required_if:role,kepala_sekolah'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'provinsi_id.required_if' => 'Provinsi wajib dipilih untuk Admin Wilayah.',
            'sekolah_id.required_if' => 'Sekolah wajib dipilih untuk Kepala Sekolah.',
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
        $actor = $this->authorizeEditUser($user);

        $provinsiList = $actor->isSuperAdmin()
            ? Provinsi::orderBy('name')->get()
            : collect();

        $sekolahList = $actor->isSuperAdmin()
            ? Sekolah::orderBy('nama')->get()
            : Sekolah::where('provinsi_id', $actor->provinsi_id)->orderBy('nama')->get();

        $forceRoleKepsek = $actor->isAdminWilayah();

        return view('users.edit', compact('user', 'provinsiList', 'sekolahList', 'forceRoleKepsek'));
    }

    /* ─── UPDATE ─────────────────────────────────────────── */
    public function update(Request $request, User $user)
    {
        $actor = $this->authorizeEditUser($user);

        if ($actor->isSuperAdmin()) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'in:superadmin,admin_wilayah,kepala_sekolah'],
                'provinsi_id' => ['nullable', 'exists:indonesia_provinces,id', 'required_if:role,admin_wilayah'],
                'sekolah_id' => ['nullable', 'exists:sekolah,id', 'required_if:role,kepala_sekolah'],
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan akun lain.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'role.required' => 'Role wajib dipilih.',
                'provinsi_id.required_if' => 'Provinsi wajib dipilih untuk Admin Wilayah.',
                'sekolah_id.required_if' => 'Sekolah wajib dipilih untuk Kepala Sekolah.',
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
        } else {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'in:kepala_sekolah'],
                'sekolah_id' => ['required', 'exists:sekolah,id'],
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan akun lain.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'role.in' => 'Admin Wilayah hanya dapat mengelola role Kepala Sekolah.',
                'sekolah_id.required' => 'Sekolah wajib dipilih untuk Kepala Sekolah.',
            ]);

            $allowedSchool = Sekolah::query()
                ->whereKey($validated['sekolah_id'])
                ->where('provinsi_id', $actor->provinsi_id)
                ->exists();

            abort_unless($allowedSchool, 403, 'Sekolah yang dipilih harus berada di wilayah Anda.');

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

    public function toggleActive(User $user)
    {
        $actor = $this->authorizeManageUsersPage();

        if ($actor->isSuperAdmin()) {
            abort_if($user->id === $actor->id, 422, 'Anda tidak dapat menonaktifkan akun sendiri.');
        } else {
            abort_unless(
                $this->canAdminWilayahManageKepalaSekolah($actor, $user),
                403,
                'Admin Wilayah hanya dapat menonaktifkan akun Kepala Sekolah di wilayahnya.'
            );
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun \"{$user->name}\" berhasil {$status}.");
    }
}
