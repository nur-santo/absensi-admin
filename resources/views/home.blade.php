@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    @php use Illuminate\Support\Str; @endphp

    @php
        $summary = $summary ?? [];
        $attendanceDetail = $attendanceDetail ?? [];

        $totalHadir = $summary['HADIR'] ?? 0;
        $totalAlpa = $summary['ALPA'] ?? 0;
        $totalIzin = $summary['IZIN'] ?? 0;
        $totalSakit = $summary['SAKIT'] ?? 0;
        $totalCuti = $summary['CUTI'] ?? 0;
        $totalLambat = $attendanceDetail['TERLAMBAT'] ?? 0;

        $allLate = collect();
        foreach ($users as $shift => $list) {
            foreach ($list as $user) {
                $hadir = $user->kehadiran->first();
                if ($hadir && $hadir->status === 'HADIR' && $hadir->terlambat && $hadir->menit_telat) {
                    $allLate->push([
                        'nama' => $user->name,
                        'shift' => $shift,
                        'jam_masuk' => $hadir->jam_masuk ?? '-',
                        'menit' => (int) $hadir->menit_telat,
                    ]);
                }
            }
        }
        $allLate = $allLate->sortByDesc('menit')->values();
    @endphp

    <style>
        /* ── Section label ── */
        .section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ink-3);
            margin-bottom: .6rem;
        }

        /* ── Stat card accent overrides (harian) ── */
        .stat-card.c-hadir::after {
            background: var(--green);
        }

        .stat-card.c-lambat::after {
            background: var(--amber);
        }

        .stat-card.c-izin::after {
            background: var(--accent);
        }

        .stat-card.c-sakit::after {
            background: var(--violet);
        }

        .stat-card.c-alpa::after {
            background: var(--red);
        }

        .stat-card.c-cuti::after {
            background: var(--sky);
        }

        /* ── Chart card ── */
        .chart-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            height: 100%;
        }

        .chart-card-title {
            font-size: .8rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: .5rem;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 12px;
            margin-bottom: .75rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .7rem;
            color: var(--ink-3);
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .chart-wrap {
            position: relative;
            height: 200px;
        }

        .chart-wrap-sm {
            position: relative;
            height: 160px;
            width: 100%;
        }

        /* ── Tabel terlambat ── */
        .late-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .late-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .75rem;
        }

        .late-title {
            font-size: .8rem;
            font-weight: 600;
            color: var(--ink);
        }

        .late-badge {
            font-size: .68rem;
            font-weight: 600;
            background: var(--amber-bg);
            color: var(--amber);
            padding: 2px 8px;
            border-radius: 20px;
        }

        .late-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
        }

        .late-table thead th {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--ink-3);
            padding: 0 0 .5rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .late-table thead th:last-child {
            text-align: right;
        }

        .late-table tbody td {
            padding: .42rem 0;
            border-bottom: 1px solid var(--surface-3);
            color: var(--ink-2);
            vertical-align: middle;
            font-size: .78rem;
        }

        .late-table tbody tr:last-child td {
            border-bottom: none;
        }

        .late-table tbody td:last-child {
            text-align: right;
        }

        .menit-pill {
            display: inline-block;
            font-size: .7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .menit-pill.ringan {
            background: #fef9c3;
            color: #854d0e;
        }

        .menit-pill.sedang {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .menit-pill.berat {
            background: var(--red-bg);
            color: var(--red);
        }

        .shift-tag {
            font-size: .63rem;
            background: var(--surface-3);
            color: var(--ink-3);
            padding: 1px 6px;
            border-radius: 4px;
            margin-left: 4px;
        }

        /* ── Shift card ── */
        .shift-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }

        /* ── Shift header — konsisten dengan sidebar & perizinan ── */
        .shift-header {
            background: #1a2130;
            padding: .7rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .shift-header-left {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .shift-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .shift-icon svg {
            width: 14px;
            height: 14px;
            stroke: rgba(255, 255, 255, .7);
        }

        .shift-name {
            font-size: .83rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: .03em;
        }

        .shift-meta {
            font-size: .68rem;
            color: rgba(255, 255, 255, .4);
            margin-top: 1px;
        }

        /* Tombol mulai shift — konsisten dengan action-btn di perizinan ── */
        .shift-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .72rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid;
            cursor: pointer;
            transition: opacity .15s, transform .1s;
            white-space: nowrap;
            text-decoration: none;
        }

        .shift-btn:active {
            transform: scale(.97);
        }

        .shift-btn svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        .shift-btn-start {
            background: var(--green-bg);
            color: var(--green);
            border-color: var(--green-bdr);
        }

        .shift-btn-start:hover {
            background: #d1fae5;
        }

        .shift-btn-done {
            background: rgba(255, 255, 255, .06);
            color: rgba(255, 255, 255, .4);
            border-color: rgba(255, 255, 255, .12);
            cursor: default;
        }

        /* ── Shift body layout ── */
        .shift-body {
            display: grid;
            grid-template-columns: 210px 1fr;
        }

        @media (max-width: 767px) {
            .shift-body {
                grid-template-columns: 1fr;
            }
        }

        .shift-chart-col {
            border-right: 1px solid var(--border);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .shift-no-data {
            text-align: center;
            color: var(--ink-4);
            font-size: .75rem;
            padding: 1.5rem 0;
        }

        .shift-no-data svg {
            display: block;
            margin: 0 auto .5rem;
        }

        /* ── Shift tabel — konsisten dengan app-table di layout ── */
        .shift-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
        }

        .shift-table thead th {
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
            padding: .55rem .9rem;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
            white-space: nowrap;
        }

        .shift-table tbody td {
            padding: .6rem .9rem;
            border-bottom: 1px solid var(--surface-3);
            vertical-align: middle;
            color: var(--ink-2);
        }

        .shift-table tbody tr:last-child td {
            border-bottom: none;
        }

        .shift-table tbody tr {
            transition: background .1s;
        }

        .shift-table tbody tr:hover {
            background: var(--surface-2);
        }

        .shift-table tbody tr.row-hadir {
            background: #f0fdf8;
        }

        .shift-table tbody tr.row-hadir:hover {
            background: #e6fcf5;
        }

        .shift-table tbody tr.row-alpa {
            background: #fff5f5;
        }

        .shift-table tbody tr.row-alpa:hover {
            background: #fee2e2;
        }

        .shift-table tbody tr.row-izin {
            background: #fffbf0;
        }

        .shift-table tbody tr.row-izin:hover {
            background: #fef3c7;
        }
    </style>

    {{-- ── HEADER ── --}}
    <div class="page-topbar">
        <div>
            <h3 class="page-title">Dashboard</h3>
        </div>
        <span style="font-size:.8rem;color:var(--ink-3);font-weight:500">{{ $tanggal }}</span>
    </div>

    {{-- ── METRIC CARDS ── --}}
    <p class="section-label">Ringkasan Hari Ini</p>
    <div class="stat-grid" style="grid-template-columns:repeat(6,1fr);margin-bottom:1.5rem">
        <div class="stat-card c-hadir">
            <div class="stat-label">Hadir</div>
            <div class="stat-value">{{ $totalHadir }}</div>
        </div>
        <div class="stat-card c-lambat">
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">{{ $totalLambat }}</div>
        </div>
        <div class="stat-card c-izin">
            <div class="stat-label">Izin</div>
            <div class="stat-value">{{ $totalIzin }}</div>
        </div>
        <div class="stat-card c-sakit">
            <div class="stat-label">Sakit</div>
            <div class="stat-value">{{ $totalSakit }}</div>
        </div>
        <div class="stat-card c-alpa">
            <div class="stat-label">Alpa</div>
            <div class="stat-value">{{ $totalAlpa }}</div>
        </div>
        <div class="stat-card c-cuti">
            <div class="stat-label">Cuti</div>
            <div class="stat-value">{{ $totalCuti }}</div>
        </div>
    </div>

    {{-- ── CHART + TABEL TERLAMBAT ── --}}
    <p class="section-label">Grafik dan Daftar karyawan Terlambat</p>
    <div class="row g-3 mb-4">

        <div class="col-md-5">
            <div class="chart-card">
                <div class="chart-card-title">Status Kehadiran</div>
                <div class="chart-legend" id="legend-global"></div>
                <div class="chart-wrap">
                    <canvas id="globalChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="late-wrap">
                <div class="late-header">
                    <span class="late-title">Karyawan Terlambat Hari Ini</span>
                    @if ($allLate->count() > 0)
                        <span class="late-badge">{{ $allLate->count() }} orang</span>
                    @endif
                </div>

                @if ($allLate->isEmpty())
                    <div class="empty-state" style="padding:1.5rem 0">
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-alert-circle"></use>
                        </svg>
                        <div class="empty-title">Tidak ada keterlambatan</div>
                        <div class="empty-sub">Semua karyawan hadir tepat waktu</div>
                    </div>
                @else
                    <div style="overflow-y:auto;max-height:230px;flex:1">
                        <table class="late-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jam Masuk</th>
                                    <th style="text-align:right">Telat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allLate as $row)
                                    @php
                                        $pill =
                                            $row['menit'] <= 15 ? 'ringan' : ($row['menit'] <= 30 ? 'sedang' : 'berat');
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $row['nama'] }}
                                            <span class="shift-tag">{{ $row['shift'] }}</span>
                                        </td>
                                        <td style="color:var(--ink-3)">{{ $row['jam_masuk'] }}</td>
                                        <td>
                                            <span class="menit-pill {{ $pill }}">
                                                {{ $row['menit'] }} mnt
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── PER SHIFT ── --}}
    <p class="section-label">Detail Per Shift</p>

    @forelse ($users as $shift => $list)
        @php
            $sudahMulai = $list->first()->kehadiran->isNotEmpty();
            $slug = Str::slug($shift);
            $jumlahUser = $list->count();
            $jumlahHadir = $list->filter(fn($u) => optional($u->kehadiran->first())->status === 'HADIR')->count();
        @endphp

        <div class="shift-card">

            {{-- ── Header shift ── --}}
            <div class="shift-header">
                <div class="shift-header-left">
                    <div class="shift-icon">
                        <svg class="icon" color="white" aria-hidden="true">
                            <use href="#icon-clock"></use>
                        </svg>
                    </div>
                    <div>
                        <div class="shift-name">{{ $shift }}</div>
                        <div class="shift-meta">
                            {{ $jumlahUser }} karyawan
                            @if ($sudahMulai)
                                · {{ $jumlahHadir }} hadir
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ route('shift.mulai') }}" method="POST" style="margin:0">
                    @csrf
                    <input type="hidden" name="shift" value="{{ $shift }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                    @if ($sudahMulai)
                        <span class="shift-btn shift-btn-done">
                            <svg class="icon" aria-hidden="true">
                                <use href="#icon-check"></use>
                            </svg>
                            Sudah Dimulai
                        </span>
                    @else
                        <button type="submit" class="shift-btn shift-btn-start">
                            Mulai Shift
                        </button>
                    @endif
                </form>
            </div>

            {{--  Body: chart kiri + tabel kanan  --}}
            <div class="shift-body">

                {{-- Mini donut chart --}}
                <div class="shift-chart-col">
                    @if ($sudahMulai)
                        <div class="chart-legend" id="legend-{{ $slug }}"
                            style="justify-content:center;margin-bottom:.5rem;font-size:.66rem"></div>
                        <div class="chart-wrap-sm">
                            <canvas id="chart-{{ $slug }}"></canvas>
                        </div>
                    @else
                        <div class="shift-no-data">
                            <svg class="icon" aria-hidden="true">
                                <use href="#icon-alert-circle"></use>
                            </svg>
                            Belum dimulai
                        </div>
                    @endif
                </div>

                {{-- Tabel absensi shift --}}
                <div>
                    <table class="shift-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jam Masuk</th>
                                <th>Status</th>
                                <th>Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $user)
                                @php
                                    $hadir = $user->kehadiran->first();
                                    if (!$hadir) {
                                        $rowClass = '';
                                        $statusText = 'Belum Dimulai';
                                        $badgeClass = 'pill pill-gray';
                                        $jamMasuk = '—';
                                        $keterlambatan = '—';
                                        $showTepat = false;
                                    } else {
                                        $status = $hadir->status;
                                        $rowClass = match ($status) {
                                            'ALPA' => 'row-alpa',
                                            'IZIN', 'SAKIT', 'CUTI' => 'row-izin',
                                            default => 'row-hadir',
                                        };
                                        $badgeClass =
                                            'pill ' .
                                            match ($status) {
                                                'ALPA' => 'pill-red',
                                                'IZIN' => 'pill-sky',
                                                'SAKIT' => 'pill-violet',
                                                'CUTI' => 'pill-sky',
                                                default => 'pill-green',
                                            };
                                        $statusText = $status;
                                        $jamMasuk = $hadir->jam_masuk ?? '—';
                                        $keterlambatan = $hadir->menit_telat ? $hadir->menit_telat . ' menit' : '—';
                                        $showTepat = $hadir->status === 'HADIR';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td style="font-weight:500;color:var(--ink)">
                                        {{ $user->name }}
                                    </td>
                                    <td style="font-size:.78rem;color:var(--ink-3)">
                                        {{ $jamMasuk }}
                                    </td>
                                    <td>
                                        <span class="{{ $badgeClass }}">{{ $statusText }}</span>
                                        @if ($showTepat)
                                            @if ($hadir->terlambat)
                                                <span class="pill pill-amber" style="margin-left:4px">Terlambat</span>
                                            @else
                                                <span class="pill pill-green" style="margin-left:4px">Tepat</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td style="font-size:.78rem;color:var(--ink-3)">
                                        {{ $keterlambatan }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    @empty
        <div class="app-alert info">
            <svg class="icon" aria-hidden="true">
                <use href="#icon-alert-circle"></use>
            </svg>
            Tidak ada data karyawan untuk ditampilkan.
        </div>
    @endforelse

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const colorMap = {
            'HADIR': '#1D9E75',
            'ALPA': '#E74C3C',
            'IZIN': '#378ADD',
            'CUTI': '#17a2b8',
            'SAKIT': '#9B59B6',
            'TERLAMBAT': '#EF9F27',
            'Belum ada data': '#e5e7eb',
        };

        function buildLegend(id, labels, values) {
            const el = document.getElementById(id);
            if (!el) return;
            const total = values.reduce((a, b) => a + b, 0);
            el.innerHTML = labels.map((l, i) => {
                const pct = total ? Math.round(values[i] / total * 100) : 0;
                const color = colorMap[l] || '#e5e7eb';
                return `<span class="legend-item">
            <span class="legend-dot" style="background:${color}"></span>
            ${l} (${values[i]}${total ? ' · ' + pct + '%' : ''})
        </span>`;
            }).join('');
        }

        function makeDonut(canvasId, labels, values, legendId) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            if (!labels.length || values.every(v => v === 0)) {
                labels = ['Belum ada data'];
                values = [1];
            }
            if (legendId) buildLegend(legendId, labels, values);
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: labels.map(l => colorMap[l] || '#e5e7eb'),
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total ? Math.round(ctx.parsed / total * 100) : 0;
                                    return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        const globalData = @json($summary ?? []);
        makeDonut('globalChart', Object.keys(globalData), Object.values(globalData), 'legend-global');

        const shiftData = @json($shiftSummary ?? []);
        @foreach ($users->keys() as $shift)
            @php $slug = Str::slug($shift); @endphp
                (function() {
                    const raw = shiftData['{{ $shift }}'] || [];
                    const labels = raw.map(i => i.status);
                    const values = raw.map(i => i.total);
                    makeDonut('chart-{{ $slug }}', labels, values, 'legend-{{ $slug }}');
                })();
        @endforeach
    </script>

@endsection
