{{--
    Shared user form partial.
    Variables: $user (for edit), $provinsiList, $sekolahList,
               $action, $method ('POST' | 'PUT')
--}}
@php
    $isEdit = isset($user) && $user->exists;
    $v = fn($field, $default = '') => old($field, $isEdit ? $user->$field ?? $default : $default);
@endphp

@if ($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-5">
        <svg class="w-5 h-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" id="user-form">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="flex items-center justify-between pb-5 mb-5 border-b border-gray-100">
        <a href="{{ route('users.index') }}"
            class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
            Batal
        </a>
        <button type="submit"
            class="flex items-center gap-2 px-6 py-2.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            Simpan
        </button>
    </div>

    {{-- INFORMASI AKUN --}}
    <div class="pb-6 mb-6 border-b border-gray-100">
        <div class="flex items-center gap-2.5 mb-4">
            <span class="w-[3px] h-5 bg-amber-400 rounded-sm flex-shrink-0"></span>
            <span class="text-xs font-bold uppercase tracking-wider text-[#162040]">Informasi Akun</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
            <div class="sm:col-span-2">
                <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ $v('name') }}" placeholder="Nama lengkap pengguna"
                    class="form-input @error('name') !border-red-400 @enderror">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ $v('email') }}" placeholder="email@example.com"
                    class="form-input @error('email') !border-red-400 @enderror">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label">Role <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="role" id="role-select" class="form-select @error('role') !border-red-400 @enderror"
                        onchange="onRoleChange(this.value)">
                        <option value="" disabled hidden>— Pilih Role —</option>
                        <option value="superadmin" {{ $v('role') === 'superadmin' ? 'selected' : '' }}>Super Admin
                        </option>
                        <option value="admin_wilayah" {{ $v('role') === 'admin_wilayah' ? 'selected' : '' }}>Admin
                            Wilayah</option>
                        <option value="kepala_sekolah" {{ $v('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala
                            Sekolah</option>
                    </select>
                    <x-select-chevron />
                </div>
                @error('role')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Provinsi (admin_wilayah) --}}
            <div id="field-provinsi" class="{{ in_array($v('role'), ['admin_wilayah']) ? '' : 'hidden' }}">
                <label class="form-label">Provinsi Wilayah</label>
                <div class="relative">
                    <select name="provinsi_id" class="form-select">
                        <option value="" disabled hidden>— Pilih Provinsi —</option>
                        @foreach ($provinsiList as $prov)
                            <option value="{{ $prov->id }}"
                                {{ (string) $v('provinsi_id') === (string) $prov->id ? 'selected' : '' }}>
                                {{ $prov->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-select-chevron />
                </div>
                @error('provinsi_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sekolah (kepala_sekolah) --}}
            <div id="field-sekolah" class="{{ in_array($v('role'), ['kepala_sekolah']) ? '' : 'hidden' }}">
                <label class="form-label">Sekolah</label>
                <div class="relative">
                    <select name="sekolah_id" class="form-select">
                        <option value="" disabled hidden>— Pilih Sekolah —</option>
                        @foreach ($sekolahList as $sk)
                            <option value="{{ $sk->id }}"
                                {{ (string) $v('sekolah_id') === (string) $sk->id ? 'selected' : '' }}>
                                {{ $sk->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-select-chevron />
                </div>
                @error('sekolah_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- PASSWORD --}}
    <div>
        <div class="flex items-center gap-2.5 mb-4">
            <span class="w-[3px] h-5 bg-amber-400 rounded-sm flex-shrink-0"></span>
            <span class="text-xs font-bold uppercase tracking-wider text-[#162040]">
                {{ $isEdit ? 'Ganti Password' : 'Password' }}
            </span>
            @if ($isEdit)
                <span class="text-xs text-gray-400 font-normal">(kosongkan jika tidak ingin mengubah)</span>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
            <div>
                <label class="form-label">
                    Password @if (!$isEdit)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password-field"
                        placeholder="{{ $isEdit ? 'Password baru (opsional)' : 'Minimal 8 karakter' }}"
                        class="form-input pr-10 @error('password') !border-red-400 @enderror">
                    <button type="button" onclick="togglePassword('password-field', 'eye-pass')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="eye-pass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label">
                    Konfirmasi Password @if (!$isEdit)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password-conf-field"
                        placeholder="Ulangi password" class="form-input pr-10">
                    <button type="button" onclick="togglePassword('password-conf-field', 'eye-conf')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="eye-conf" class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>

<script>
    function onRoleChange(role) {
        document.getElementById('field-provinsi').classList.toggle('hidden', role !== 'admin_wilayah');
        document.getElementById('field-sekolah').classList.toggle('hidden', role !== 'kepala_sekolah');
    }

    function togglePassword(fieldId, eyeId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(eyeId);
        const isHidden = field.type === 'password';
        field.type = isHidden ? 'text' : 'password';
        eye.innerHTML = isHidden ?
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>` :
            `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }

    // ── Clear button for <select> dropdowns ──────────────────
    function syncClearBtn(sel) {
        const btn = sel._clearBtn;
        if (!btn) return;
        if (sel.value) {
            btn.style.display = 'flex';
            sel.style.paddingRight = '3.5rem';
        } else {
            btn.style.display = 'none';
            sel.style.paddingRight = '';
        }
    }

    function initSelectClear() {
        document.querySelectorAll('select.form-select').forEach(function(sel) {
            const wrapper = sel.parentElement;
            if (!wrapper || !wrapper.classList.contains('relative')) return;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.title = 'Hapus pilihan';
            Object.assign(btn.style, {
                position: 'absolute',
                right: '2rem',
                top: '50%',
                transform: 'translateY(-50%)',
                width: '15px',
                height: '15px',
                borderRadius: '50%',
                background: '#d1d5db',
                border: 'none',
                cursor: 'pointer',
                display: 'none',
                alignItems: 'center',
                justifyContent: 'center',
                padding: '0',
                fontSize: '8px',
                color: '#6b7280',
                lineHeight: '1',
                zIndex: '10',
            });
            btn.innerHTML = '✕';
            btn.onmouseenter = () => {
                btn.style.background = '#9ca3af';
                btn.style.color = '#fff';
            };
            btn.onmouseleave = () => {
                btn.style.background = '#d1d5db';
                btn.style.color = '#6b7280';
            };
            wrapper.appendChild(btn);
            sel._clearBtn = btn;

            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                sel.value = '';
                sel.dispatchEvent(new Event('change'));
                syncClearBtn(sel);
            });

            sel.addEventListener('change', function() {
                syncClearBtn(sel);
            });
            syncClearBtn(sel);
        });
    }

    document.addEventListener('DOMContentLoaded', initSelectClear);
</script>
