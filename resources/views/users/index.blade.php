@extends('layouts.app')
@section('title', 'Karyawan')

@section('content')

    @php
        $totalUser = $users->count();
        $totalPkl = $users->where('status', 'PKL')->count();
        $totalKar = $users->where('status', 'KARYAWAN')->count();
        $totalWfo = $users->where('mode_kerja', 'WFO')->count();
        $totalWfh = $users->where('mode_kerja', 'WFH')->count();
    @endphp

    {{-- Topbar --}}
    <div class="page-topbar">
        <div>
            <h3 class="page-title">Manajemen Karyawan</h3>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary-app">
            <svg class="icon" aria-hidden="true">
                <use href="#icon-plus"></use>
            </svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="stat-grid">
        <div class="stat-card c-blue">
            <div class="stat-label">Total Karyawan</div>
            <div class="stat-value">{{ $totalUser }}</div>
            <div class="stat-note">terdaftar di sistem</div>
        </div>
        <div class="stat-card c-violet">
            <div class="stat-label">PKL</div>
            <div class="stat-value">{{ $totalPkl }}</div>
            <div class="stat-note">{{ $totalUser > 0 ? round(($totalPkl / $totalUser) * 100) . '%' : '—' }} dari total</div>
        </div>
        <div class="stat-card c-green">
            <div class="stat-label">Karyawan</div>
            <div class="stat-value">{{ $totalKar }}</div>
            <div class="stat-note">{{ $totalUser > 0 ? round(($totalKar / $totalUser) * 100) . '%' : '—' }} dari total</div>
        </div>
        <div class="stat-card c-amber">
            <div class="stat-label">WFO</div>
            <div class="stat-value">{{ $totalWfo }}</div>
            <div class="stat-note">work from office</div>
        </div>
        <div class="stat-card c-sky">
            <div class="stat-label">WFH</div>
            <div class="stat-value">{{ $totalWfh }}</div>
            <div class="stat-note">work from home</div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="filter-toolbar">
        <div class="search-wrap">
            <svg class="icon" aria-hidden="true">
                <use href="#icon-search"></use>
            </svg>
            <input type="text" id="search" class="search-input" placeholder="Cari nama atau email…"
                autocomplete="off">
        </div>
        <select class="filter-select" id="filter-status">
            <option value="">Semua status</option>
            <option value="PKL">PKL</option>
            <option value="KARYAWAN">Karyawan</option>
        </select>
        <select class="filter-select" id="filter-mode">
            <option value="">Semua mode</option>
            <option value="WFO">WFO</option>
            <option value="WFH">WFH</option>
        </select>
        <span class="result-count" id="result-count"></span>
    </div>

    {{-- Table --}}
    <div class="app-table-wrap">
        <table class="app-table">
            <thead>
                <tr>
                    <th style="width:48px">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Mode Kerja</th>
                    <th>Instansi</th>
                    <th>Shift</th>
                </tr>
            </thead>
            <tbody id="user-table">
                @include('users.table_rows', ['users' => $users])
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script>
        (function() {
            const searchEl = document.getElementById('search');
            const fStatus = document.getElementById('filter-status');
            const fMode = document.getElementById('filter-mode');
            const tbody = document.getElementById('user-table');
            const countEl = document.getElementById('result-count');
            let timer;

            function countRows(html) {
                const tmp = document.createElement('table');
                tmp.innerHTML = '<tbody>' + html + '</tbody>';
                const n = tmp.querySelectorAll('tbody tr:not(.empty-row)').length;
                countEl.textContent = n ? n + ' data' : '';
            }

            function showShimmer() {
                tbody.innerHTML = Array.from({
                    length: 5
                }).map(() => `
            <tr>
                <td><span class="shimmer" style="width:24px"></span></td>
                <td><span class="shimmer" style="width:150px"></span></td>
                <td><span class="shimmer" style="width:180px"></span></td>
                <td><span class="shimmer" style="width:60px"></span></td>
                <td><span class="shimmer" style="width:50px"></span></td>
                <td><span class="shimmer" style="width:100px"></span></td>
                <td><span class="shimmer" style="width:70px"></span></td>
                <td><span class="shimmer" style="width:48px"></span></td>
            </tr>`).join('');
            }

            function load() {
                const p = new URLSearchParams();
                const q = searchEl.value.trim();
                if (q) p.set('q', q);
                if (fStatus.value) p.set('status', fStatus.value);
                if (fMode.value) p.set('mode', fMode.value);
                showShimmer();
                fetch(`{{ route('admin.users.search') }}?${p}`)
                    .then(r => r.text())
                    .then(html => {
                        tbody.innerHTML = html;
                        countRows(html);
                    })
                    .catch(() => {
                        tbody.innerHTML = `<tr class="empty-row"><td colspan="7">
                    <div class="empty-state">
                        <svg class="icon" aria-hidden="true" style="stroke:var(--border-2);width:44px;height:44px;display:block;margin:0 auto .85rem">
                            <use href="#icon-alert-circle"></use>
                        </svg>
                        <div class="empty-title">Gagal memuat data</div>
                        <div class="empty-sub">Periksa koneksi lalu coba lagi</div>
                    </div>
                </td></tr>`;
                    });
            }

            searchEl.addEventListener('keyup', () => {
                clearTimeout(timer);
                timer = setTimeout(load, 250);
            });
            fStatus.addEventListener('change', load);
            fMode.addEventListener('change', load);
            countRows(tbody.innerHTML);
        })();
    </script>
@endsection
