{{--
    Partial: resources/views/users/table_rows.blade.php
    Requires: User::with('shift')->...
--}}
@forelse ($users as $user)
    @php
        $initials = collect(explode(' ', $user->name))
            ->take(2)
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->join('');

        $statusClass = match ($user->status) {
            'PKL' => 'pill-pkl',
            'KARYAWAN' => 'pill-karyawan',
            default => 'pill-gray',
        };
        $modeClass = match ($user->mode_kerja) {
            'WFO' => 'pill-wfo',
            'WFH' => 'pill-wfh',
            default => 'pill-gray',
        };
    @endphp
    <tr>
        <td class="cell-num">{{ $loop->iteration }}</td>
        <td>
            <div class="name-cell">
                <span class="avatar">{{ $initials }}</span>
                {{ $user->name }}
            </div>
        </td>
        <td class="cell-muted">{{ $user->email }}</td>
        <td>
            @if ($user->status)
                <span class="pill {{ $statusClass }}">{{ $user->status }}</span>
            @else
                <span class="cell-dash">—</span>
            @endif
        </td>
        <td>
            @if ($user->mode_kerja)
                <span class="pill {{ $modeClass }}">{{ $user->mode_kerja }}</span>
            @else
                <span class="cell-dash">—</span>
            @endif
        </td>
        <td>{{ $user->instansi ?? '—' }}</td>
        <td>
            @if ($user->shift)
                <span class="pill pill-gray">{{ $user->shift->nama_shift }}</span>
            @else
                <span class="cell-dash">—</span>
            @endif
        </td>
    </tr>
@empty
    <tr class="empty-row">
        <td colspan="7">
            <div class="empty-state">
                {{-- SVG from sprite — accessible from parent layout --}}
                <svg class="icon" aria-hidden="true"
                    style="stroke:var(--border-2);width:44px;height:44px;display:block;margin:0 auto .85rem">
                    <use href="#icon-search"></use>
                </svg>
                <div class="empty-title">Tidak ada data ditemukan</div>
                <div class="empty-sub">Coba ubah kata kunci atau filter pencarian</div>
            </div>
        </td>
    </tr>
@endforelse
