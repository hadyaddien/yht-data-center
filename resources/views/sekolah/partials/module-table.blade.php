<table class="w-full text-sm">
    <thead>
        <tr class="border-b border-gray-100 bg-[#F59E0B]">
            <th class="px-5 py-3 text-left text-[11px] font-semibold text-[#162040] uppercase tracking-wide">Nama Sekolah
                &amp; Alamat</th>
            <th class="px-5 py-3 text-center text-[11px] font-semibold text-[#162040] uppercase tracking-wide w-[130px]">
                NPSN</th>
            <th class="px-5 py-3 text-center text-[11px] font-semibold text-[#162040] uppercase tracking-wide w-[90px]">
                Jenjang</th>
            <th class="px-5 py-3 text-center text-[11px] font-semibold text-[#162040] uppercase tracking-wide w-[150px]">
                Akreditasi</th>
            <th class="px-5 py-3 text-center text-[11px] font-semibold text-[#162040] uppercase tracking-wide w-[120px]">
                Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-50">
        @foreach ($sekolahList as $sekolah)
            @php
                $badgeConfig = [
                    'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                    'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                ][$sekolah->jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                $lokasi = collect([$sekolah->kota?->nama, $sekolah->provinsi?->nama])
                    ->filter()
                    ->implode(', ');
            @endphp
            <tr class="hover:bg-gray-50/70 transition-colors">
                <td class="px-5 py-4 align-middle">
                    <p class="text-sm font-semibold text-[#162040] leading-tight">{{ $sekolah->nama }}</p>
                    <div class="flex items-center gap-1.5 mt-1 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="truncate">{{ $lokasi ?: '-' }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 align-middle text-center"><span
                        class="text-sm font-medium text-gray-600">{{ $sekolah->npsn ?: '-' }}</span></td>
                <td class="px-5 py-4 align-middle text-center">
                    <span
                        class="inline-flex items-center justify-center min-w-[44px] px-2.5 py-1 rounded-md text-xs font-bold {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }}">{{ $sekolah->jenjang ?: '-' }}</span>
                </td>
                <td class="px-5 py-4 align-middle text-center">
                    @if ($sekolah->akreditasi_label)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-100">{{ $sekolah->akreditasi_label }}</span>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 align-middle text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route($routePrefix . '.show', $sekolah) }}" title="Lihat"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-[#162040] hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route($routePrefix . '.edit', $sekolah) }}" title="Edit"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-[#162040] hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
