@extends('layouts.app')

@section('content')

{{-- ===== FORCE DARK TABLE + WHITE TEXT ===== --}}
<style>
    table, thead, tbody, tr, th, td {
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

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5 px-5">
        <div>
            <h3 class="fw-semibold mb-1">Approval Perizinan</h3>
            <p class="mb-0">
                Daftar perizinan yang menunggu persetujuan
            </p>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="px-5 mb-4">
            <div
                style="
                    background: rgba(34,197,94,.18);
                    border:1px solid rgba(34,197,94,.45);
                    color:#4ade80;
                    border-radius:18px;
                    padding:16px 22px;
                    box-shadow:0 0 22px rgba(34,197,94,.45);
                ">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- CONTENT --}}
    <div class="px-5">

        <div class="mb-5 p-2 w-100"
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

                {{-- TABLE --}}
                <div class="table-responsive px-3 py-4">
                    <table class="table table-borderless mb-0 align-middle">

                        <thead>
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th>Nama User</th>
                                <th>Jenis</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Keterangan</th>
                                <th>Lampiran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($perizinan as $item)
                                <tr
                                    style="
                                        border-top:1px solid rgba(34,197,94,.15);
                                        transition:.25s;
                                    "
                                    onmouseover="this.style.background='rgba(34,197,94,.15)'"
                                    onmouseout="this.style.background='transparent'">

                                    <td class="px-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $item->user->name ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge px-3 py-2"
                                            style="
                                                background: rgba(56,189,248,.25);
                                                border:1px solid rgba(56,189,248,.45);
                                                box-shadow:0 0 12px rgba(56,189,248,.45);
                                            ">
                                            {{ $item->jenis }}
                                        </span>
                                    </td>

                                    <td style="color:#9ca3af">
                                        {{ $item->tanggal_mulai }}
                                    </td>

                                    <td style="color:#9ca3af">
                                        {{ $item->tanggal_selesai }}
                                    </td>

                                    <td style="color:#9ca3af">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>

                                    <td>
                                        @if ($item->lampiran)
                                            <a href="{{ asset('storage/' . $item->lampiran) }}"
                                               target="_blank"
                                               class="btn btn-sm px-3"
                                               style="
                                                    background: rgba(56,189,248,.28);
                                                    border:1px solid rgba(56,189,248,.45);
                                                    box-shadow:0 0 12px rgba(56,189,248,.45);
                                               ">
                                                Lihat
                                            </a>
                                        @else
                                            <span style="color:#64748b">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        {{-- SETUJUI --}}
                                        <form action="{{ route('admin.perizinan.approve', $item->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            <button
                                                onclick="return confirm('Setujui perizinan ini?')"
                                                class="btn btn-sm px-3"
                                                style="
                                                    background: rgba(34,197,94,.32);
                                                    border:1px solid rgba(34,197,94,.5);
                                                    box-shadow:0 0 14px rgba(34,197,94,.5);
                                                ">
                                                Setujui
                                            </button>
                                        </form>

                                        {{-- TOLAK --}}
                                        <form action="{{ route('admin.perizinan.reject', $item->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            <button
                                                onclick="return confirm('Tolak perizinan ini?')"
                                                class="btn btn-sm px-3"
                                                style="
                                                    background: rgba(239,68,68,.32);
                                                    border:1px solid rgba(239,68,68,.5);
                                                    box-shadow:0 0 14px rgba(239,68,68,.5);
                                                ">
                                                Tolak
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="text-center py-5"
                                        style="color:#9ca3af">
                                        Tidak ada perizinan menunggu persetujuan
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
