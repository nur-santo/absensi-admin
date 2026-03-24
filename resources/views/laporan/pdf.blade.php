<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Absensi</title>
    <style>
        /* DomPDF hanya support CSS2 — tidak ada flexbox, grid, atau var() */

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── Header ── */
        .header {
            border-bottom: 2px solid #1a2130;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a2130;
            margin-bottom: 2px;
        }
        .header-sub {
            font-size: 8px;
            color: #6b7280;
        }
        .header-meta {
            float: right;
            text-align: right;
            font-size: 8px;
            color: #6b7280;
            margin-top: -24px;
        }
        .clearfix::after { content: ''; display: table; clear: both; }

        /* ── Info strip ── */
        .info-strip {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 8px;
            margin-bottom: 10px;
            font-size: 8px;
            color: #475569;
        }
        .info-strip strong { color: #1a2130; }

        /* ── Summary cards (pakai tabel untuk DomPDF) ── */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .summary-table td {
            width: 14.28%;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 6px 8px;
            text-align: center;
            background: #f8fafc;
        }
        .summary-val {
            font-size: 16px;
            font-weight: bold;
            color: #1a2130;
            line-height: 1;
            margin-bottom: 2px;
        }
        .summary-lbl {
            font-size: 7px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .summary-val.hadir  { color: #059669; }
        .summary-val.alpa   { color: #dc2626; }
        .summary-val.izin   { color: #0284c7; }
        .summary-val.sakit  { color: #7c3aed; }
        .summary-val.cuti   { color: #0891b2; }
        .summary-val.telat  { color: #d97706; }

        /* ── Section title ── */
        .section-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
        }

        /* ── Main table ── */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .main-table thead tr {
            background: #1a2130;
            color: #fff;
        }
        .main-table thead th {
            padding: 5px 6px;
            text-align: center;
            font-size: 7.5px;
            font-weight: bold;
            letter-spacing: 0.04em;
            border: 1px solid #2d3748;
        }
        .main-table thead th.text-left { text-align: left; }

        .main-table tbody tr { border-bottom: 1px solid #f0f0f0; }
        .main-table tbody tr:nth-child(even) { background: #f8fafc; }
        .main-table tbody td {
            padding: 4px 6px;
            text-align: center;
            font-size: 8px;
            color: #374151;
            border-right: 1px solid #e5e7eb;
        }
        .main-table tbody td:last-child { border-right: none; }
        .main-table tbody td.text-left { text-align: left; }

        /* Sub-header baris kedua */
        .main-table .sub-header tr {
            background: #374151;
        }
        .main-table .sub-header th {
            padding: 3px 6px;
            font-size: 7px;
            color: #d1d5db;
            text-align: center;
            border: 1px solid #4b5563;
        }

        /* ── Badge persentase ── */
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: bold;
        }
        .badge-high { background: #d1fae5; color: #065f46; }
        .badge-mid  { background: #fef3c7; color: #92400e; }
        .badge-low  { background: #fee2e2; color: #991b1b; }

        /* ── Status pill ── */
        .pill {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
        }
        .pill-pkl      { background: #f5f3ff; color: #6d28d9; }
        .pill-karyawan { background: #ecfdf5; color: #065f46; }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
            font-size: 7px;
            color: #9ca3af;
            text-align: center;
        }

        /* ── Tanda tangan ── */
        .ttd-wrap {
            margin-top: 20px;
            width: 100%;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-table td {
            width: 33.33%;
            text-align: center;
            padding: 0 12px;
            vertical-align: bottom;
        }
        .ttd-line {
            border-top: 1px solid #374151;
            margin-top: 40px;
            padding-top: 3px;
            font-size: 8px;
            color: #374151;
        }
        .ttd-role {
            font-size: 7px;
            color: #6b7280;
            margin-top: 1px;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header clearfix">
        <div class="header-title">Laporan Absensi Karyawan</div>
        <div class="header-sub">Sistem Manajemen Absensi</div>
        <div class="header-meta">
            Dicetak: {{ now()->format('d M Y, H:i') }}<br>
            Oleh: {{ auth()->user()->name ?? 'Admin' }}
        </div>
    </div>

    {{-- ── INFO PERIODE ── --}}
    <div class="info-strip">
        <strong>Periode:</strong>
        {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}
        s/d
        {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Hari Kerja (di-generate):</strong> {{ $hariKerja }} hari
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Total Karyawan:</strong> {{ $users->count() }} orang
    </div>

    {{-- ── SUMMARY CARDS ── --}}
    @php
        $allK     = $users->flatMap->kehadiran;
        $totHadir = $allK->where('status','HADIR')->count();
        $totAlpa  = $allK->where('status','ALPA')->count();
        $totIzin  = $allK->where('status','IZIN')->count();
        $totSakit = $allK->where('status','SAKIT')->count();
        $totCuti  = $allK->where('status','CUTI')->count();
        $totTelat = $allK->where('terlambat', true)->count();
        $avgTelat = round($allK->where('terlambat', true)->avg('menit_telat') ?? 0);
    @endphp

    <p class="section-title">Ringkasan Keseluruhan</p>
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-val hadir">{{ $totHadir }}</div>
                <div class="summary-lbl">Hadir</div>
            </td>
            <td>
                <div class="summary-val alpa">{{ $totAlpa }}</div>
                <div class="summary-lbl">Alpa</div>
            </td>
            <td>
                <div class="summary-val izin">{{ $totIzin }}</div>
                <div class="summary-lbl">Izin</div>
            </td>
            <td>
                <div class="summary-val sakit">{{ $totSakit }}</div>
                <div class="summary-lbl">Sakit</div>
            </td>
            <td>
                <div class="summary-val cuti">{{ $totCuti }}</div>
                <div class="summary-lbl">Cuti</div>
            </td>
            <td>
                <div class="summary-val telat">{{ $totTelat }}</div>
                <div class="summary-lbl">Terlambat</div>
            </td>
            <td>
                <div class="summary-val" style="color:#374151">{{ $avgTelat }} mnt</div>
                <div class="summary-lbl">Rata-rata Telat</div>
            </td>
        </tr>
    </table>

    {{-- ── TABEL UTAMA ── --}}
    <p class="section-title">Detail Per Karyawan</p>

    <table class="main-table">
        <thead>
            {{-- Baris 1: group header --}}
            <tr>
                <th rowspan="2" class="text-left" style="width:18%">Nama</th>
                <th rowspan="2" style="width:8%">Status</th>
                <th colspan="2">Mode Kerja</th>
                <th colspan="3">Shift</th>
                <th colspan="5">Status Kehadiran</th>
                <th rowspan="2" style="width:6%">Telat</th>
                <th rowspan="2" style="width:7%">Kehadiran</th>
            </tr>
            {{-- Baris 2: sub-header --}}
            <tr>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">WFO</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">WFH</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">Pagi</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">Siang</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">Fulltime</th>
                <th style="background:#2d3748;font-size:7px;color:#9ae6b4">Hadir</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">Izin</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">Cuti</th>
                <th style="background:#2d3748;font-size:7px;color:#d1d5db">Sakit</th>
                <th style="background:#2d3748;font-size:7px;color:#fc8181">Alpa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                @php
                    $k        = $user->kehadiran;
                    $hadir    = $k->where('status','HADIR')->count();
                    $izin     = $k->where('status','IZIN')->count();
                    $cuti     = $k->where('status','CUTI')->count();
                    $sakit    = $k->where('status','SAKIT')->count();
                    $alpa     = $k->where('status','ALPA')->count();
                    $telat    = $k->where('terlambat', true)->count();
                    $wfo      = $k->where('mode_kerja','WFO')->count();
                    $wfh      = $k->where('mode_kerja','WFH')->count();
                    $pagi     = $k->where('shift','PAGI')->count();
                    $siang    = $k->where('shift','SIANG')->count();
                    $fulltime = $k->where('shift','FULLTIME')->count();

                    $totalValid = $hadir + $izin + $cuti + $sakit;
                    $persen = $hariKerja > 0
                        ? round($totalValid / $hariKerja * 100)
                        : 0;

                    $badgePct = $persen >= 90 ? 'badge-high'
                              : ($persen >= 75 ? 'badge-mid' : 'badge-low');

                    $pillStatus = strtolower($user->status ?? '') === 'pkl'
                        ? 'pill-pkl' : 'pill-karyawan';
                @endphp
                <tr>
                    <td class="text-left" style="font-weight:bold;color:#1a2130">
                        {{ $user->name }}
                    </td>
                    <td>
                        <span class="pill {{ $pillStatus }}">{{ $user->status }}</span>
                    </td>
                    <td>{{ $wfo }}</td>
                    <td>{{ $wfh }}</td>
                    <td>{{ $pagi }}</td>
                    <td>{{ $siang }}</td>
                    <td>{{ $fulltime }}</td>
                    <td style="color:#059669;font-weight:bold">{{ $hadir }}</td>
                    <td>{{ $izin }}</td>
                    <td>{{ $cuti }}</td>
                    <td>{{ $sakit }}</td>
                    <td style="color:#dc2626;font-weight:bold">{{ $alpa }}</td>
                    <td style="color:#d97706">{{ $telat }}</td>
                    <td>
                        <span class="badge {{ $badgePct }}">{{ $persen }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="text-align:center;color:#9ca3af;padding:12px">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── TANDA TANGAN ── --}}
    <div class="ttd-wrap">
        <table class="ttd-table">
            <tr>
                <td>
                    <div style="font-size:8px;color:#374151;margin-bottom:2px">Mengetahui,</div>
                    <div class="ttd-line">Kepala Bagian</div>
                    <div class="ttd-role">(...............................)</div>
                </td>
                <td>
                    <div style="font-size:8px;color:#374151;margin-bottom:2px">Diperiksa,</div>
                    <div class="ttd-line">HRD</div>
                    <div class="ttd-role">(...............................)</div>
                </td>
                <td>
                    <div style="font-size:8px;color:#374151;margin-bottom:2px">Dibuat oleh,</div>
                    <div class="ttd-line">{{ auth()->user()->name ?? 'Administrator' }}</div>
                    <div class="ttd-role">Administrator</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem Absensi &nbsp;·&nbsp;
        {{ now()->format('d M Y H:i:s') }} &nbsp;·&nbsp;
        Halaman <span style="font-style:italic">—</span>
    </div>

</body>
</html>