@extends('layouts.app')

@section('content')

    {{-- ===== FORCE DARK TABLE + WHITE TEXT ===== --}}
    <style>
        table,
        thead,
        tbody,
        tr,
        th,
        td {
            background-color: transparent !important;
        }

        .dashboard-container,
        .dashboard-container * {
            color: #ffffff !important;
        }

        .dashboard-container .badge {
            color: inherit !important;
        }
    </style>

    <div class="container-fluid px-0 dashboard-container">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-5 px-5">

            <div>
                <h3 class="fw-semibold mb-1">Dashboard Absensi</h3>
                <p class="mb-0">
                    Tanggal: <strong>{{ $tanggal }}</strong>
                </p>
            </div>

            <div class="d-flex align-items-center gap-4">

                <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">

                    {{-- NOTIFIKASI --}}
                    <a href="{{ route('admin.perizinan.index') }}" class="btn btn-light notif-btn position-relative">
                        🔔

                        @if(($jumlahPerizinanPending ?? 0) > 0)
                            <span
                                class="badge bg-danger rounded-pill notif-badge position-absolute top-0 start-100 translate-middle">
                                {{ $jumlahPerizinanPending }}
                            </span>
                        @endif
                    </a>

                </div>

                {{-- GENERATE BUTTON --}}
                <form action="{{ route('admin.generate.kehadiran') }}" method="POST">
                    @csrf
                    <button class="btn px-4 py-2" style="
                                        background: linear-gradient(135deg,#22c55e,#16a34a);
                                        border:none;
                                        border-radius:14px;
                                        box-shadow:
                                            0 0 18px rgba(34,197,94,.55),
                                            inset 0 0 10px rgba(34,197,94,.6);
                                    ">
                        Generate Kehadiran Hari Ini
                    </button>
                </form>

                {{-- LOGOUT BUTTON --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn px-4 py-2" style="
                                        background: linear-gradient(135deg,#ef4444,#dc2626);
                                        border:none;
                                        border-radius:14px;
                                        color:white;
                                        font-weight:600;
                                        box-shadow:
                                            0 0 18px rgba(239,68,68,.55),
                                            inset 0 0 10px rgba(239,68,68,.6);
                                    ">
                        Logout
                    </button>
                </form>

            </div>

        </div>

        {{-- ================= CONTENT ================= --}}
        <div class="px-5">

            @forelse ($users as $shift => $list)

                <div class="mb-5 p-2 w-100" style="
                                                    border-radius:24px;
                                                    background: linear-gradient(135deg,
                                                        rgba(34,197,94,.55),
                                                        rgba(20,184,166,.35),
                                                        rgba(15,23,42,.9)
                                                    );
                                                    box-shadow:0 0 45px rgba(34,197,94,.45);
                                                ">

                    <div style="
                                                        background:#0b1220;
                                                        border-radius:22px;
                                                        box-shadow: inset 0 0 32px rgba(34,197,94,.25);
                                                    ">

                        {{-- SHIFT HEADER --}}
                        <div class="d-flex justify-content-between align-items-center px-5 py-4" style="
                                                            border-bottom:1px solid rgba(34,197,94,.35);
                                                            background: linear-gradient(90deg,
                                                                rgba(34,197,94,.3),
                                                                rgba(15,23,42,.95)
                                                            );
                                                            border-radius:22px 22px 0 0;
                                                        ">
                            <span style="
                                                                font-weight:800;
                                                                letter-spacing:1.4px;
                                                                font-size:16px;
                                                                text-transform:uppercase;">
                                Shift: {{ $shift }}
                            </span>

                            <span>
                                Total: {{ count($list) }} orang
                            </span>
                        </div>

                        {{-- TABLE --}}
                        <div class="table-responsive px-3 py-4">
                            <table class="table table-borderless mb-0 align-middle">

                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Nama</th>
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
                                                $statusText = 'Belum Digenerate';
                                                $statusBadge = 'secondary';
                                                $jamMasuk = '-';
                                                $keterlambatan = '-';
                                            } else {
                                                $jamMasuk = $hadir->jam_masuk ?? '-';
                                                $status = $hadir->status;

                                                switch ($status) {
                                                    case 'ALPA':
                                                        $statusBadge = 'danger';
                                                        break;
                                                    case 'IZIN':
                                                    case 'SAKIT':
                                                    case 'CUTI':
                                                        $statusBadge = 'warning';
                                                        break;
                                                    default:
                                                        $statusBadge = 'success';
                                                        break;
                                                }

                                                $statusText = $status;
                                                $keterlambatan = $hadir->keterlambatan ?? '-';
                                            }
                                        @endphp

                                        <tr style="
                                                                                            border-top:1px solid rgba(34,197,94,.15);
                                                                                            transition:.25s;
                                                                                        "
                                            onmouseover="this.style.background='rgba(34,197,94,.15)'"
                                            onmouseout="this.style.background='transparent'">

                                            <td class="px-4 fw-semibold">
                                                {{ $user->name }}
                                            </td>

                                            <td>{{ $jamMasuk }}</td>

                                            <td>
                                                <span class="badge px-3 py-2" style="
                                                                                                    background:
                                                                                                        @if($statusBadge == 'success')
                                                                                                            rgba(34,197,94,.28)
                                                                                                        @elseif($statusBadge == 'warning')
                                                                                                            rgba(234,179,8,.32)
                                                                                                        @elseif($statusBadge == 'danger')
                                                                                                            rgba(239,68,68,.32)
                                                                                                        @else
                                                                                                            rgba(148,163,184,.28)
                                                                                                        @endif;
                                                                                                    border:1px solid rgba(255,255,255,.25);
                                                                                                    box-shadow:0 0 12px rgba(255,255,255,.15);
                                                                                                ">
                                                    {{ $statusText }}
                                                </span>
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
                <div class="text-center py-5">
                    Tidak ada data user.
                </div>
            @endforelse

        </div>
    </div>

@endsection