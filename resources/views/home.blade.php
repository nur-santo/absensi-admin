@extends('layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp

<style>
/* ── Layout ── */
.abs-page { padding: 1.5rem 0; }
.abs-section-title {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #6c757d;
    margin-bottom: 0.75rem;
}

/* ── Metric cards ── */
.metric-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.metric-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 0.9rem 1rem;
    position: relative;
    overflow: hidden;
}
.metric-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 10px 10px 0 0;
}
.metric-card.hadir::before  { background: #1D9E75; }
.metric-card.lambat::before { background: #EF9F27; }
.metric-card.izin::before   { background: #378ADD; }
.metric-card.sakit::before  { background: #9B59B6; }
.metric-card.alpa::before   { background: #E74C3C; }
.metric-card.cuti::before   { background: #17a2b8; }
.metric-label {
    font-size: 0.7rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.metric-value {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1;
    color: #212529;
}

/* ── Chart card ── */
.chart-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 1.25rem;
    height: 100%;
}
.chart-card-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 0.5rem;
}
.chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 12px;
    margin-bottom: 0.75rem;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    color: #6c757d;
}
.legend-dot {
    width: 9px;
    height: 9px;
    border-radius: 2px;
    flex-shrink: 0;
}
.chart-wrap { position: relative; height: 200px; }
.chart-wrap-sm { position: relative; height: 160px; width: 100%; }

/* ── Tabel terlambat ── */
.late-table-wrap {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 1.25rem;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.late-table-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.late-count-badge {
    font-size: 0.68rem;
    font-weight: 600;
    background: #ffedd5;
    color: #9a3412;
    padding: 2px 8px;
    border-radius: 20px;
}
.late-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; flex: 1; }
.late-table thead th {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6c757d;
    padding: 0 0 0.5rem;
    border-bottom: 1px solid #e9ecef;
    text-align: left;
}
.late-table thead th:last-child { text-align: right; }
.late-table tbody td {
    padding: 0.45rem 0;
    border-bottom: 1px solid #f5f5f5;
    color: #212529;
    vertical-align: middle;
}
.late-table tbody tr:last-child td { border-bottom: none; }
.late-table tbody td:last-child { text-align: right; }
.menit-pill {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
}
.menit-pill.ringan { background: #fef9c3; color: #854d0e; }
.menit-pill.sedang { background: #ffedd5; color: #9a3412; }
.menit-pill.berat  { background: #fee2e2; color: #991b1b; }
.shift-tag {
    font-size: 0.65rem;
    background: #f1f5f9;
    color: #475569;
    padding: 1px 6px;
    border-radius: 4px;
    margin-left: 4px;
}
.late-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    font-size: 0.8rem;
    gap: 6px;
    padding: 1.5rem 0;
}

/* ── Shift card ── */
.shift-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.shift-header {
    background: #212529;
    color: #fff;
    padding: 0.6rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.shift-name { font-size: 0.85rem; font-weight: 600; letter-spacing: 0.04em; }
.shift-body { display: grid; grid-template-columns: 200px 1fr; }
@media (max-width: 767px) { .shift-body { grid-template-columns: 1fr; } }
.shift-chart-col {
    border-right: 1px solid #e9ecef;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* ── Tabel per shift ── */
.abs-table { width: 100%; margin: 0; font-size: 0.82rem; border-collapse: collapse; }
.abs-table thead th {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.55rem 0.85rem;
    font-weight: 600;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6c757d;
}
.abs-table tbody td {
    padding: 0.55rem 0.85rem;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}
.abs-table tbody tr:last-child td { border-bottom: none; }
.abs-table tbody tr.row-hadir   { background: #f0fdf8; }
.abs-table tbody tr.row-alpa    { background: #fff5f5; }
.abs-table tbody tr.row-izin    { background: #fffbf0; }
.abs-table tbody tr.row-default { background: #fff; }

/* ── Status badges ── */
.sbadge {
    display: inline-flex;
    align-items: center;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.04em;
}
.sbadge-hadir   { background: #d1fae5; color: #065f46; }
.sbadge-alpa    { background: #fee2e2; color: #991b1b; }
.sbadge-izin    { background: #fef3c7; color: #92400e; }
.sbadge-sakit   { background: #ede9fe; color: #5b21b6; }
.sbadge-cuti    { background: #d1f0f8; color: #0c5e75; }
.sbadge-default { background: #e9ecef; color: #495057; }
.sbadge-tepat   { background: #d1fae5; color: #065f46; }
.sbadge-lambat  { background: #ffedd5; color: #9a3412; }

.no-data-placeholder {
    text-align: center;
    padding: 2rem;
    color: #adb5bd;
    font-size: 0.8rem;
}
</style>

<div class="container abs-page">

    {{-- HEADER --}}
    <div class="d-flex align-items-baseline justify-content-between mb-3">
        <h4 class="mb-0 fw-bold">Dashboard Absensi</h4>
        <span class="text-muted" style="font-size:0.82rem">{{ $tanggal }}</span>
    </div>

    {{-- ── METRIC CARDS ── --}}
    @php
        $summary          = $summary ?? [];
        $attendanceDetail = $attendanceDetail ?? [];

        $totalHadir  = $summary['HADIR'] ?? 0;
        $totalAlpa   = $summary['ALPA']  ?? 0;
        $totalIzin   = $summary['IZIN']  ?? 0;
        $totalSakit  = $summary['SAKIT'] ?? 0;
        $totalCuti   = $summary['CUTI']  ?? 0;
        $totalTepat  = $attendanceDetail['TEPAT WAKTU'] ?? 0;
        $totalLambat = $attendanceDetail['TERLAMBAT']   ?? 0;

        //{{-- Kumpulkan semua yang terlambat --}}
        $allLate = collect();
        foreach ($users as $shift => $list) {
            foreach ($list as $user) {
                $hadir = $user->kehadiran->first();
                if ($hadir && $hadir->status === 'HADIR' && $hadir->terlambat && $hadir->menit_telat) {
                    $allLate->push([
                        'nama'      => $user->name,
                        'shift'     => $shift,
                        'jam_masuk' => $hadir->jam_masuk ?? '-',
                        'menit'     => (int) $hadir->menit_telat,
                    ]);
                }
            }
        }
        $allLate = $allLate->sortByDesc('menit')->values();
    @endphp

    <p class="abs-section-title">Ringkasan Hari Ini</p>
    <div class="metric-grid mb-4">
        <div class="metric-card hadir">
            <div class="metric-label">Hadir</div>
            <div class="metric-value">{{ $totalHadir }}</div>
        </div>
        <div class="metric-card lambat">
            <div class="metric-label">Terlambat</div>
            <div class="metric-value">{{ $totalLambat }}</div>
        </div>
        <div class="metric-card izin">
            <div class="metric-label">Izin</div>
            <div class="metric-value">{{ $totalIzin }}</div>
        </div>
        <div class="metric-card sakit">
            <div class="metric-label">Sakit</div>
            <div class="metric-value">{{ $totalSakit }}</div>
        </div>
        <div class="metric-card alpa">
            <div class="metric-label">Alpa</div>
            <div class="metric-value">{{ $totalAlpa }}</div>
        </div>
        <div class="metric-card cuti">
            <div class="metric-label">Cuti</div>
            <div class="metric-value">{{ $totalCuti }}</div>
        </div>
    </div>

    {{-- ── CHART + TABEL TERLAMBAT ── --}}
    <p class="abs-section-title">Visualisasi & Keterlambatan</p>
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
            <div class="late-table-wrap">
                <div class="late-table-title">
                    <span>Yang Terlambat Hari Ini</span>
                    @if ($allLate->count() > 0)
                        <span class="late-count-badge">{{ $allLate->count() }} orang</span>
                    @endif
                </div>

                @if ($allLate->isEmpty())
                    <div class="late-empty">
                        <svg width="32" height="32" fill="none" stroke="#ced4da" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                        </svg>
                        <span>Tidak ada keterlambatan hari ini</span>
                    </div>
                @else
                    <div style="overflow-y:auto; max-height:230px;">
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
                                        if ($row['menit'] <= 15)       $pill = 'ringan';
                                        elseif ($row['menit'] <= 30)   $pill = 'sedang';
                                        else                           $pill = 'berat';
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $row['nama'] }}
                                            <span class="shift-tag">{{ $row['shift'] }}</span>
                                        </td>
                                        <td style="color:#6c757d">{{ $row['jam_masuk'] }}</td>
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
    <p class="abs-section-title">Detail Per Shift</p>

    @forelse ($users as $shift => $list)
        @php
            $sudahMulai = $list->first()->kehadiran->isNotEmpty();
            $slug = Str::slug($shift);
        @endphp

        <div class="shift-card">
            <div class="shift-header">
                <span class="shift-name">{{ $shift }}</span>
                <form action="{{ route('shift.mulai') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" name="shift"   value="{{ $shift }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    @if ($sudahMulai)
                        <button class="btn btn-sm btn-secondary py-0 px-2" style="font-size:0.72rem" disabled>
                            Sudah Dimulai
                        </button>
                    @else
                        <button type="submit" class="btn btn-sm btn-success py-0 px-2" style="font-size:0.72rem">
                            Mulai Shift
                        </button>
                    @endif
                </form>
            </div>

            <div class="shift-body">

                <div class="shift-chart-col">
                    @if ($sudahMulai)
                        <div class="chart-legend" id="legend-{{ $slug }}" style="justify-content:center;margin-bottom:0.5rem;font-size:0.68rem"></div>
                        <div class="chart-wrap-sm">
                            <canvas id="chart-{{ $slug }}"></canvas>
                        </div>
                    @else
                        <div class="no-data-placeholder">
                            <svg width="28" height="28" fill="none" stroke="#ced4da" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                            </svg>
                            <div>Belum dimulai</div>
                        </div>
                    @endif
                </div>

                <div>
                    <table class="abs-table">
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
                                        $rowClass      = 'row-default';
                                        $statusText    = 'Belum Digenerate';
                                        $badgeClass    = 'sbadge-default';
                                        $jamMasuk      = '-';
                                        $keterlambatan = '-';
                                        $showTepat     = false;
                                    } else {
                                        $status = $hadir->status;
                                        switch ($status) {
                                            case 'ALPA':  $rowClass = 'row-alpa';  $badgeClass = 'sbadge-alpa';  break;
                                            case 'IZIN':  $rowClass = 'row-izin';  $badgeClass = 'sbadge-izin';  break;
                                            case 'SAKIT': $rowClass = 'row-izin';  $badgeClass = 'sbadge-sakit'; break;
                                            case 'CUTI':  $rowClass = 'row-izin';  $badgeClass = 'sbadge-cuti';  break;
                                            default:      $rowClass = 'row-hadir'; $badgeClass = 'sbadge-hadir';
                                        }
                                        $statusText    = $status;
                                        $jamMasuk      = $hadir->jam_masuk ?? '-';
                                        $keterlambatan = $hadir->menit_telat ? $hadir->menit_telat . ' menit' : '-';
                                        $showTepat     = $hadir->status === 'HADIR';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $jamMasuk }}</td>
                                    <td>
                                        <span class="sbadge {{ $badgeClass }}">{{ $statusText }}</span>
                                        @if ($showTepat)
                                            @if ($hadir->terlambat)
                                                <span class="sbadge sbadge-lambat ms-1">Terlambat</span>
                                            @else
                                                <span class="sbadge sbadge-tepat ms-1">Tepat</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $keterlambatan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    @empty
        <div class="alert alert-warning">Tidak ada data user.</div>
    @endforelse

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const colorMap = {
    'HADIR':          '#1D9E75',
    'ALPA':           '#E74C3C',
    'IZIN':           '#F39C12',
    'CUTI':           '#17a2b8',
    'SAKIT':          '#9B59B6',
    'TEPAT WAKTU':    '#1D9E75',
    'TERLAMBAT':      '#EF9F27',
    'Belum ada data': '#dee2e6',
};

function buildLegend(containerId, labels, values) {
    const el = document.getElementById(containerId);
    if (!el) return;
    const total = values.reduce((a, b) => a + b, 0);
    el.innerHTML = labels.map((l, i) => {
        const pct   = total ? Math.round(values[i] / total * 100) : 0;
        const color = colorMap[l] || '#dee2e6';
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
                backgroundColor: labels.map(l => colorMap[l] || '#dee2e6'),
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
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct   = total ? Math.round(ctx.parsed / total * 100) : 0;
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
@foreach($users->keys() as $shift)
@php $slug = Str::slug($shift); @endphp
(function() {
    const raw    = shiftData['{{ $shift }}'] || [];
    const labels = raw.map(i => i.status);
    const values = raw.map(i => i.total);
    makeDonut('chart-{{ $slug }}', labels, values, 'legend-{{ $slug }}');
})();
@endforeach
</script>

@endsection