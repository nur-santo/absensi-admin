@extends('layouts.app')

@section('content')

{{-- ===== FORCE DARK + WHITE TEXT ===== --}}
<style>
    table, thead, tbody, tr, th, td {
        background-color: transparent !important;
    }

    .dashboard-container,
    .dashboard-container * {
        color:#ffffff !important;
    }

    .dashboard-container .badge {
        color: inherit !important;
    }
</style>

<div class="container-fluid px-0 dashboard-container">

    {{-- HEADER --}}
    <div class="px-5 mb-5">
        <h3 class="fw-semibold mb-1">Laporan Absensi</h3>
        <p class="mb-0">Rekap kehadiran berdasarkan periode</p>
    </div>

    {{-- FILTER --}}
    <div class="px-5 mb-5">
        <div
            style="
                background:#0b1220;
                border-radius:22px;
                padding:28px;
                box-shadow:
                    inset 0 0 28px rgba(34,197,94,.25),
                    0 0 40px rgba(34,197,94,.35);
            ">

            <form method="GET"
                  action="{{ route('admin.laporan.index') }}"
                  class="row g-4 align-items-end">

                <div class="col-lg-3">
                    <label class="mb-2">Status User</label>
                    <select name="status"
                        style="
                            width:100%;
                            background:#020617;
                            border:1px solid rgba(34,197,94,.4);
                            padding:12px 14px;
                            border-radius:14px;
                        ">
                        <option value="">-- Semua --</option>
                        <option value="PKL" @selected(request('status')=='PKL')>PKL</option>
                        <option value="KARYAWAN" @selected(request('status')=='KARYAWAN')>Karyawan</option>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label class="mb-2">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal"
                           value="{{ $tanggalAwal }}"
                           style="
                                width:100%;
                                background:#020617;
                                border:1px solid rgba(34,197,94,.4);
                                padding:12px 14px;
                                border-radius:14px;
                           ">
                </div>

                <div class="col-lg-3">
                    <label class="mb-2">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir"
                           value="{{ $tanggalAkhir }}"
                           style="
                                width:100%;
                                background:#020617;
                                border:1px solid rgba(34,197,94,.4);
                                padding:12px 14px;
                                border-radius:14px;
                           ">
                </div>

                <div class="col-lg-3">
                    <button
                        style="
                            width:100%;
                            padding:14px;
                            border:none;
                            border-radius:16px;
                            font-weight:700;
                            background: linear-gradient(135deg,#22c55e,#16a34a);
                            box-shadow:0 0 18px rgba(34,197,94,.55);
                        ">
                        🔍 Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- INFO --}}
    <div class="px-5 mb-5">
        <div class="d-flex justify-content-between flex-wrap gap-3 px-4 py-3"
             style="
                background: rgba(56,189,248,.18);
                border:1px solid rgba(56,189,248,.45);
                border-radius:18px;
                box-shadow:0 0 20px rgba(56,189,248,.4);
             ">
            <div>
                <strong>Periode:</strong> {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}
            </div>
            <div>
                <strong>Hari Kerja:</strong> {{ $hariKerja }} hari
            </div>
        </div>
    </div>

    {{-- EXPORT --}}
    <div class="px-5 mb-5 d-flex gap-3 flex-wrap">
        <form method="POST" action="{{ route('admin.laporan.export') }}">
            @csrf
            <input type="hidden" name="format" value="pdf">
            <input type="hidden" name="tanggal_awal" value="{{ $tanggalAwal }}">
            <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button
                style="
                    padding:12px 22px;
                    border-radius:14px;
                    background: rgba(239,68,68,.3);
                    border:1px solid rgba(239,68,68,.45);
                    box-shadow:0 0 16px rgba(239,68,68,.45);
                ">
                Export PDF
            </button>
        </form>

        <form method="POST" action="{{ route('admin.laporan.export') }}">
            @csrf
            <input type="hidden" name="format" value="excel">
            <input type="hidden" name="tanggal_awal" value="{{ $tanggalAwal }}">
            <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button
                style="
                    padding:12px 22px;
                    border-radius:14px;
                    background: rgba(34,197,94,.3);
                    border:1px solid rgba(34,197,94,.45);
                    box-shadow:0 0 16px rgba(34,197,94,.45);
                ">
                Export Excel
            </button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="px-5 mb-5">
        <div class="p-2"
            style="
                border-radius:24px;
                background: linear-gradient(135deg,
                    rgba(34,197,94,.55),
                    rgba(20,184,166,.35),
                    rgba(15,23,42,.9)
                );
                box-shadow:0 0 45px rgba(34,197,94,.45);
            ">

            <div
                style="
                    background:#0b1220;
                    border-radius:22px;
                    box-shadow: inset 0 0 32px rgba(34,197,94,.25);
                ">

                <div class="table-responsive px-3 py-4">
                    <table class="table table-borderless mb-0 align-middle text-center">

                        <thead>
                            <tr>
                                <th rowspan="2">Nama</th>
                                <th rowspan="2">Status</th>
                                <th colspan="2">Mode</th>
                                <th colspan="3">Shift</th>
                                <th colspan="5">Kehadiran</th>
                                <th rowspan="2">Telat</th>
                                <th rowspan="2">%</th>
                            </tr>
                            <tr style="font-size:13px;color:#9ca3af">
                                <th>WFO</th><th>WFH</th>
                                <th>Pagi</th><th>Siang</th><th>Full</th>
                                <th>H</th><th>I</th><th>C</th><th>S</th><th>A</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    $hadir = $user->kehadiran->where('status','HADIR')->count();
                                    $izin  = $user->kehadiran->where('status','IZIN')->count();
                                    $cuti  = $user->kehadiran->where('status','CUTI')->count();
                                    $sakit = $user->kehadiran->where('status','SAKIT')->count();
                                    $alpa  = $user->kehadiran->where('status','ALPA')->count();
                                    $telat = $user->kehadiran->where('terlambat', true)->count();
                                    $pagi = $user->kehadiran->where('shift','PAGI')->count();
                                    $siang = $user->kehadiran->where('shift','SIANG')->count();
                                    $fulltime = $user->kehadiran->where('shift','FULLTIME')->count();
                                    $wfo = $user->kehadiran->where('mode_kerja','WFO')->count();
                                    $wfh = $user->kehadiran->where('mode_kerja','WFH')->count();
                                    $totalValid = $hadir + $izin + $cuti + $sakit;
                                    $persentase = $hariKerja>0 ? round(($totalValid/$hariKerja)*100) : 0;
                                @endphp

                                <tr
                                    style="
                                        border-top:1px solid rgba(34,197,94,.15);
                                        transition:.25s;
                                    "
                                    onmouseover="this.style.background='rgba(34,197,94,.15)'"
                                    onmouseout="this.style.background='transparent'">

                                    <td class="text-start px-4 fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->status }}</td>
                                    <td>{{ $wfo }}</td>
                                    <td>{{ $wfh }}</td>
                                    <td>{{ $pagi }}</td>
                                    <td>{{ $siang }}</td>
                                    <td>{{ $fulltime }}</td>
                                    <td>{{ $hadir }}</td>
                                    <td>{{ $izin }}</td>
                                    <td>{{ $cuti }}</td>
                                    <td>{{ $sakit }}</td>
                                    <td>{{ $alpa }}</td>
                                    <td>{{ $telat }}</td>
                                    <td>
                                        <span class="badge px-3 py-2"
                                            style="
                                                background:
                                                    {{ $persentase>=90?'rgba(34,197,94,.28)':($persentase>=75?'rgba(234,179,8,.28)':'rgba(239,68,68,.28)') }};
                                                border:1px solid rgba(255,255,255,.25);
                                                box-shadow:0 0 12px rgba(255,255,255,.25);
                                            ">
                                            {{ $persentase }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="py-5" style="color:#9ca3af">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
