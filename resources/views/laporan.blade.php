@extends('layouts.app')
@section('title', 'Laporan Absensi')

@section('content')

    <style>
        .filter-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .filter-row {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-group label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
        }

        .filter-group select,
        .filter-group input[type=date] {
            padding: .45rem .75rem;
            font-size: .82rem;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            min-width: 150px;
        }

        .filter-group select:focus,
        .filter-group input[type=date]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .period-strip {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: .65rem 1rem;
            background: var(--accent-bg);
            border: 1px solid var(--accent-bdr);
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: .8rem;
            flex-wrap: wrap;
        }

        .period-strip strong {
            color: var(--accent);
            font-weight: 700;
        }

        .period-strip span {
            color: var(--ink-3);
        }

        .tab-nav {
            display: flex;
            gap: 2px;
            border-bottom: 2px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .tab-btn {
            padding: .55rem 1.1rem;
            font-size: .82rem;
            font-weight: 600;
            color: var(--ink-3);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            transition: color .15s, border-color .15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tab-btn svg {
            width: 14px;
            height: 14px;
        }

        .tab-btn:hover {
            color: var(--ink);
        }

        .tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 767px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .chart-card-title {
            font-size: .8rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: .85rem;
        }

        .chart-wrap-lg {
            position: relative;
            height: 240px;
        }

        .chart-wrap-md {
            position: relative;
            height: 200px;
        }

        .ind-search-wrap {
            position: relative;
            max-width: 280px;
            margin-bottom: 1rem;
        }

        .ind-search-wrap svg {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: var(--ink-4);
            pointer-events: none;
        }

        .ind-search {
            width: 100%;
            padding: .45rem .75rem .45rem 2rem;
            font-size: .8rem;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            outline: none;
            transition: border-color .15s;
        }

        .ind-search:focus {
            border-color: var(--accent);
        }

        .pct-badge {
            display: inline-flex;
            align-items: center;
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .pct-high {
            background: var(--green-bg);
            color: var(--green);
        }

        .pct-mid {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .pct-low {
            background: var(--red-bg);
            color: var(--red);
        }

        .ind-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 17, 23, .5);
            z-index: 200;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem;
            overflow-y: auto;
        }

        .ind-modal-overlay.open {
            display: flex;
        }

        .ind-modal {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: 0 24px 48px rgba(0, 0, 0, .2);
            width: 100%;
            max-width: 720px;
            overflow: hidden;
        }

        .ind-modal-head {
            background: #1a2130;
            color: #fff;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ind-modal-name {
            font-size: .95rem;
            font-weight: 700;
        }

        .ind-modal-sub {
            font-size: .72rem;
            color: rgba(255, 255, 255, .5);
            margin-top: 2px;
        }

        .ind-modal-close {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, .6);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: color .15s;
        }

        .ind-modal-close:hover {
            color: #fff;
        }

        .ind-modal-close svg {
            width: 18px;
            height: 18px;
        }

        .ind-modal-body {
            padding: 1.25rem;
        }

        .modal-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: .6rem;
            margin-bottom: 1.25rem;
        }

        .modal-stat {
            background: var(--surface-2);
            border-radius: var(--radius);
            padding: .65rem .85rem;
            text-align: center;
        }

        .modal-stat-val {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .modal-stat-lbl {
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
            margin-top: 3px;
        }

        .modal-chart-wrap {
            position: relative;
            height: 180px;
            margin-bottom: 1.25rem;
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .78rem;
        }

        .log-table thead th {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
            padding: .4rem .6rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface-2);
        }

        .log-table tbody td {
            padding: .4rem .6rem;
            border-bottom: 1px solid var(--surface-3);
            vertical-align: middle;
        }

        .log-table tbody tr:last-child td {
            border-bottom: none;
        }

        .log-table tbody tr.log-hadir {
            background: #f0fdf8;
        }

        .log-table tbody tr.log-alpa {
            background: #fff5f5;
        }

        .log-table tbody tr.log-izin {
            background: #fffbf0;
        }

        .log-wrap {
            max-height: 280px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .export-bar {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .export-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            font-weight: 600;
            padding: .4rem .9rem;
            border-radius: var(--radius-sm);
            border: 1px solid;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s;
        }

        .export-btn:hover {
            opacity: .85;
            text-decoration: none;
        }

        .export-btn svg {
            width: 13px;
            height: 13px;
        }

        .export-btn.pdf {
            background: var(--red-bg);
            color: var(--red);
            border-color: var(--red-bdr);
        }

        .export-btn.excel {
            background: var(--green-bg);
            color: var(--green);
            border-color: var(--green-bdr);
        }
    </style>

    {{-- HEADER --}}
    <div class="page-topbar">
        <div>
            <h3 class="page-title">Laporan Absensi</h3>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-row">
            <div class="filter-group">
                <label>Status Karyawan</label>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="PKL" @selected(request('status') == 'PKL')>PKL</option>
                    <option value="KARYAWAN" @selected(request('status') == 'KARYAWAN')>Karyawan</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}">
            </div>
            <div class="filter-group">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
            </div>
            <div class="filter-group" style="flex-shrink:0">
                <label>&nbsp;</label>
                <button type="submit" class="btn-primary-app">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-search"></use>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- PERIOD INFO --}}
    <div class="period-strip">
        <span>Periode
            <strong>{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}</strong>
            —
            <strong>{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</strong>
        </span>
        <span>·</span>
        <span><strong>{{ $hariKerja }}</strong> hari kerja</span>
        <span>·</span>
        <span><strong>{{ $usersData->count() }}</strong> karyawan</span>
    </div>

    {{-- TAB NAV --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('summary', this)">
            <svg class="icon" aria-hidden="true">
                <use href="#icon-grid"></use>
            </svg>
            Rekap Laporan
        </button>
        <button class="tab-btn" onclick="switchTab('individual', this)">
            <svg class="icon" aria-hidden="true">
                <use href="#icon-user"></use>
            </svg>
            Laporan Individu
        </button>
    </div>

    {{--  TAB 1: SUMMARY  --}}
    <div id="tab-summary" class="tab-panel active">

        {{-- Stat cards — Terlambat DIHAPUS --}}
        <div class="stat-grid" style="grid-template-columns:repeat(auto-fit,minmax(120px,1fr));margin-bottom:1.25rem">
            <div class="stat-card c-green">
                <div class="stat-label">Total Hadir</div>
                <div class="stat-value">{{ $summary['total_hadir'] }}</div>
                <div class="stat-note">record kehadiran</div>
            </div>
            <div class="stat-card c-red">
                <div class="stat-label">Total Alpa</div>
                <div class="stat-value">{{ $summary['total_alpa'] }}</div>
                <div class="stat-note">tidak hadir tanpa ket.</div>
            </div>
            <div class="stat-card c-sky">
                <div class="stat-label">Izin</div>
                <div class="stat-value">{{ $summary['total_izin'] }}</div>
                <div class="stat-note">izin resmi</div>
            </div>
            <div class="stat-card c-violet">
                <div class="stat-label">Sakit</div>
                <div class="stat-value">{{ $summary['total_sakit'] }}</div>
                <div class="stat-note">sakit</div>
            </div>
            <div class="stat-card c-blue">
                <div class="stat-label">Cuti</div>
                <div class="stat-value">{{ $summary['total_cuti'] }}</div>
                <div class="stat-note">hari cuti</div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="summary-grid">
            <div class="chart-card" style="grid-column:1 / -1">
                <div class="chart-card-title">Tren Kehadiran Harian</div>
                <div class="chart-wrap-lg"><canvas id="chartTren"></canvas></div>
            </div>
        </div>

        <div class="summary-grid">
            <div class="chart-card">
                <div class="chart-card-title">Komposisi Status</div>
                <div class="chart-wrap-md"><canvas id="chartStatus"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-card-title">Mode Kerja & Shift</div>
                <div class="chart-wrap-md"><canvas id="chartMode"></canvas></div>
            </div>
        </div>

        {{-- Export --}}
        <div class="export-bar">
            <span style="font-size:.75rem;color:var(--ink-3);font-weight:600">Export:</span>
            <form method="POST" action="{{ route('admin.laporan.export') }}" style="display:inline">
                @csrf
                <input type="hidden" name="format" value="pdf">
                <input type="hidden" name="tanggal_awal" value="{{ $tanggalAwal }}">
                <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <button type="submit" class="export-btn pdf">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-file"></use>
                    </svg> PDF
                </button>
            </form>
            <form method="POST" action="{{ route('admin.laporan.export') }}" style="display:inline">
                @csrf
                <input type="hidden" name="format" value="excel">
                <input type="hidden" name="tanggal_awal" value="{{ $tanggalAwal }}">
                <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <button type="submit" class="export-btn excel">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-file"></use>
                    </svg> Excel
                </button>
            </form>
        </div>

    </div>{{-- /tab-summary --}}


    {{--  TAB 2: INDIVIDUAL  --}}
    <div id="tab-individual" class="tab-panel">

        <div class="ind-search-wrap">
            <svg class="icon" aria-hidden="true">
                <use href="#icon-search"></use>
            </svg>
            <input type="text" class="ind-search" id="indSearch" placeholder="Cari nama karyawan…">
        </div>

        <div class="app-table-wrap">
            <table class="app-table" id="indTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th title="Hadir">H</th>
                        <th title="Izin">I</th>
                        <th title="Cuti">C</th>
                        <th title="Sakit">S</th>
                        <th title="Alpa">A</th>
                        <th title="Terlambat">Telat</th>
                        <th title="Rata-rata telat">⌀ Mnt</th>
                        <th>WFO</th>
                        <th>WFH</th>
                        <th>Kehadiran</th>
                        <th style="width:80px">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usersData as $i => $u)
                        @php
                            $pctClass = $u['persen'] >= 90 ? 'pct-high' : ($u['persen'] >= 75 ? 'pct-mid' : 'pct-low');
                            $initials = collect(explode(' ', $u['name']))
                                ->take(2)
                                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                ->join('');
                        @endphp
                        <tr data-name="{{ strtolower($u['name']) }}">
                            <td class="cell-num">{{ $i + 1 }}</td>
                            <td>
                                <div class="name-cell">
                                    <span class="avatar">{{ $initials }}</span>
                                    {{ $u['name'] }}
                                </div>
                            </td>
                            <td>
                                <span class="pill {{ $u['status'] === 'PKL' ? 'pill-violet' : 'pill-green' }}">
                                    {{ $u['status'] }}
                                </span>
                            </td>
                            <td style="color:var(--green);font-weight:600">{{ $u['hadir'] }}</td>
                            <td style="color:var(--sky)">{{ $u['izin'] }}</td>
                            <td style="color:var(--sky)">{{ $u['cuti'] }}</td>
                            <td style="color:var(--violet)">{{ $u['sakit'] }}</td>
                            <td style="color:var(--red);font-weight:600">{{ $u['alpa'] }}</td>
                            <td style="color:var(--amber)">{{ $u['telat'] }}</td>
                            <td class="cell-muted">{{ $u['avg_telat'] > 0 ? $u['avg_telat'] . ' mnt' : '—' }}</td>
                            <td class="cell-muted">{{ $u['wfo'] }}</td>
                            <td class="cell-muted">{{ $u['wfh'] }}</td>
                            <td><span class="pct-badge {{ $pctClass }}">{{ $u['persen'] }}%</span></td>
                            <td>
                                <button class="pill pill-blue" style="border:none;cursor:pointer;font-size:.68rem"
                                    onclick="openDetail({{ $i }})">
                                    Lihat →
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14">
                                <div class="empty-state">
                                    <svg class="icon" aria-hidden="true">
                                        <use href="#icon-alert-circle"></use>
                                    </svg>
                                    <div class="empty-title">Tidak ada data</div>
                                    <div class="empty-sub">Coba ubah filter periode atau status</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>{{-- /tab-individual --}}


    {{-- MODAL DETAIL --}}
    <div class="ind-modal-overlay" id="indModal">
        <div class="ind-modal">
            <div class="ind-modal-head">
                <div>
                    <div class="ind-modal-name" id="mName"></div>
                    <div class="ind-modal-sub" id="mSub"></div>
                </div>
                <button class="ind-modal-close" onclick="closeDetail()">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-close"></use>
                    </svg>
                </button>
            </div>
            <div class="ind-modal-body">
                <div class="modal-stats" id="mStats"></div>

                <div
                    style="font-size:.75rem;font-weight:600;color:var(--ink-3);margin-bottom:.5rem;
                        text-transform:uppercase;letter-spacing:.07em">
                    Kehadiran per Bulan</div>
                <div class="modal-chart-wrap"><canvas id="mChart"></canvas></div>

                <div
                    style="font-size:.75rem;font-weight:600;color:var(--ink-3);margin-bottom:.5rem;
                        text-transform:uppercase;letter-spacing:.07em">
                    Log Harian</div>
                <div class="log-wrap">
                    <table class="log-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Jam Masuk</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th style="text-align:right">Telat</th>
                            </tr>
                        </thead>
                        <tbody id="mLogBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- 
     SCRIPT — Chart.js DULU, baru semua JS
 --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ── Data dari controller ─────────────────────────────────────
        const trenData = @json($trenHarian);
        const summaryData = @json($summary);
        const usersData = @json($usersData);

        // Hitung total mode/shift dari JS (bukan Blade ->sum() yg tidak reliable)
        const totalWFO = usersData.reduce((s, u) => s + (u.wfo ?? 0), 0);
        const totalWFH = usersData.reduce((s, u) => s + (u.wfh ?? 0), 0);
        const totalPagi = usersData.reduce((s, u) => s + (u.pagi ?? 0), 0);
        const totalSiang = usersData.reduce((s, u) => s + (u.siang ?? 0), 0);
        const totalFulltime = usersData.reduce((s, u) => s + (u.fulltime ?? 0), 0);

        // ── Tab switch ───────────────────────────────────────────────
        function switchTab(name, btn) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            btn.classList.add('active');
        }

        // ── Individual search ────────────────────────────────────────
        document.getElementById('indSearch').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#indTable tbody tr[data-name]').forEach(row => {
                row.style.display = row.dataset.name.includes(q) ? '' : 'none';
            });
        });

        // ── Modal detail ─────────────────────────────────────────────
        let mChartInst = null;

        function openDetail(idx) {
            const u = usersData[idx];
            document.getElementById('mName').textContent = u.name;
            document.getElementById('mSub').textContent =
                (u.status ?? '') + ' · Kehadiran ' + (u.persen ?? 0) + '%';

            const pctColor = u.persen >= 90 ? '#059669' : (u.persen >= 75 ? '#d97706' : '#dc2626');
            document.getElementById('mStats').innerHTML = `
            <div class="modal-stat"><div class="modal-stat-val" style="color:#059669">${u.hadir  ?? 0}</div><div class="modal-stat-lbl">Hadir</div></div>
            <div class="modal-stat"><div class="modal-stat-val" style="color:#dc2626">${u.alpa   ?? 0}</div><div class="modal-stat-lbl">Alpa</div></div>
            <div class="modal-stat"><div class="modal-stat-val" style="color:#0284c7">${u.izin   ?? 0}</div><div class="modal-stat-lbl">Izin</div></div>
            <div class="modal-stat"><div class="modal-stat-val" style="color:#7c3aed">${u.sakit  ?? 0}</div><div class="modal-stat-lbl">Sakit</div></div>
            <div class="modal-stat"><div class="modal-stat-val" style="color:#0284c7">${u.cuti   ?? 0}</div><div class="modal-stat-lbl">Cuti</div></div>
            <div class="modal-stat"><div class="modal-stat-val" style="color:#d97706">${u.telat  ?? 0}</div><div class="modal-stat-lbl">Telat</div></div>
            <div class="modal-stat"><div class="modal-stat-val" style="color:${pctColor}">${u.persen ?? 0}%</div><div class="modal-stat-lbl">Kehadiran</div></div>
        `;

            // Chart per bulan
            if (mChartInst) mChartInst.destroy();
            const perBulan = u.per_bulan ?? {};
            const bulanKeys = Object.keys(perBulan);
            const bulanLabels = bulanKeys.map(k => {
                const [y, m] = k.split('-');
                return new Date(y, m - 1).toLocaleDateString('id-ID', {
                    month: 'short',
                    year: '2-digit'
                });
            });

            mChartInst = new Chart(document.getElementById('mChart'), {
                type: 'bar',
                data: {
                    labels: bulanLabels.length ? bulanLabels : ['Tidak ada data'],
                    datasets: [{
                            label: 'Hadir',
                            data: bulanKeys.map(k => perBulan[k]?.hadir ?? 0),
                            backgroundColor: '#1D9E75'
                        },
                        {
                            label: 'Alpa',
                            data: bulanKeys.map(k => perBulan[k]?.alpa ?? 0),
                            backgroundColor: '#E74C3C'
                        },
                        {
                            label: 'Izin/Sakit/Cuti',
                            data: bulanKeys.map(k => perBulan[k]?.izin ?? 0),
                            backgroundColor: '#378ADD'
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 11
                                },
                                boxWidth: 10
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: {
                                color: '#f1f3f6'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                stepSize: 1
                            }
                        },
                    }
                }
            });

            // Log harian
            const pillMap = {
                HADIR: 'pill-green',
                ALPA: 'pill-red',
                IZIN: 'pill-sky',
                SAKIT: 'pill-violet',
                CUTI: 'pill-sky'
            };
            const rowCls = {
                HADIR: 'log-hadir',
                ALPA: 'log-alpa',
                IZIN: 'log-izin',
                SAKIT: 'log-izin',
                CUTI: 'log-izin'
            };
            const log = Array.isArray(u.log) ? u.log : [];

            document.getElementById('mLogBody').innerHTML = log.length ?
                log.map(r => `
                <tr class="${rowCls[r.status] ?? ''}">
                    <td>${r.tanggal ?? '—'}</td>
                    <td><span class="pill pill-gray" style="font-size:.65rem">${r.shift ?? '—'}</span></td>
                    <td style="color:var(--ink-3)">${r.jam_masuk ?? '—'}</td>
                    <td><span class="pill ${r.mode_kerja === 'WFH' ? 'pill-sky' : 'pill-amber'}" style="font-size:.65rem">${r.mode_kerja ?? '—'}</span></td>
                    <td><span class="pill ${pillMap[r.status] ?? 'pill-gray'}" style="font-size:.65rem">${r.status ?? '—'}</span></td>
                    <td style="text-align:right;color:var(--amber);font-size:.75rem">
                        ${r.terlambat ? (r.menit_telat ?? 0) + ' mnt' : '—'}
                    </td>
                </tr>`).join('') :
                '<tr><td colspan="6" style="text-align:center;color:var(--ink-4);padding:1rem">Belum ada data</td></tr>';

            document.getElementById('indModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDetail() {
            document.getElementById('indModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('indModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeDetail();
        });

        // ── Chart: Tren harian ───────────────────────────────────────
        new Chart(document.getElementById('chartTren'), {
            type: 'line',
            data: {
                labels: trenData.map(d => {
                    const dt = new Date(d.tanggal);
                    return dt.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    });
                }),
                datasets: [{
                        label: 'Hadir',
                        data: trenData.map(d => d.hadir ?? 0),
                        borderColor: '#1D9E75',
                        backgroundColor: 'rgba(29,158,117,.1)',
                        tension: .3,
                        fill: true,
                        pointRadius: 3,
                    },
                    {
                        label: 'Alpa',
                        data: trenData.map(d => d.alpa ?? 0),
                        borderColor: '#E74C3C',
                        backgroundColor: 'rgba(231,76,60,.07)',
                        tension: .3,
                        fill: true,
                        pointRadius: 3,
                    },
                    {
                        label: 'Izin/Sakit/Cuti',
                        data: trenData.map(d => d.izin ?? 0),
                        borderColor: '#378ADD',
                        backgroundColor: 'rgba(55,138,221,.07)',
                        tension: .3,
                        fill: true,
                        pointRadius: 3,
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 10
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            autoSkip: true,
                            maxTicksLimit: 15
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f3f6'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            stepSize: 1
                        }
                    },
                }
            }
        });

        // ── Chart: Donut komposisi status ────────────────────────────
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Alpa', 'Izin', 'Sakit', 'Cuti'],
                datasets: [{
                    data: [
                        summaryData.total_hadir ?? 0,
                        summaryData.total_alpa ?? 0,
                        summaryData.total_izin ?? 0,
                        summaryData.total_sakit ?? 0,
                        summaryData.total_cuti ?? 0,
                    ],
                    backgroundColor: ['#1D9E75', '#E74C3C', '#378ADD', '#9B59B6', '#17a2b8'],
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
                        position: 'right',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 10
                        }
                    }
                },
            }
        });

        // ── Chart: Bar mode kerja & shift ────────────────────────────
        new Chart(document.getElementById('chartMode'), {
            type: 'bar',
            data: {
                labels: ['WFO', 'WFH', 'Pagi', 'Siang', 'Fulltime'],
                datasets: [{
                    // Dihitung dari JS, bukan Blade ->sum() yg tidak reliable
                    data: [totalWFO, totalWFH, totalPagi, totalSiang, totalFulltime],
                    backgroundColor: ['#EF9F27', '#378ADD', '#1D9E75', '#9B59B6', '#E74C3C'],
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f3f6'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            stepSize: 1
                        }
                    },
                }
            }
        });
    </script>

@endsection
