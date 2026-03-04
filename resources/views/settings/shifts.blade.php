@extends('layouts.app')

@section('content')
<div class="container-fluid px-0 dashboard-container">

    {{-- HEADER --}}
    <div class="px-5 mb-4 d-flex justify-content-between align-items-center">
        <h3 class="fw-semibold mb-0">Data Shift Kerja</h3>

        {{-- Tombol Back --}}
        <a href="javascript:history.back()"
           class="btn text-white"
           style="
                background: rgba(56,189,248,.25);
                border:1px solid rgba(56,189,248,.45);
                box-shadow:0 0 14px rgba(56,189,248,.45);
                border-radius:12px;
                padding:6px 18px;
                font-weight:600;
           ">
            ← Kembali
        </a>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="px-5 mb-4">
            <div class="alert"
                 style="background: rgba(34,197,94,.15); color:#4ade80; border:1px solid rgba(34,197,94,.35); box-shadow:0 0 18px rgba(34,197,94,.45); border-radius:16px;">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- TABLE WRAPPER --}}
    <div class="px-5 mb-5">
        <div style="
            border-radius:24px;
            background: linear-gradient(135deg, rgba(34,197,94,.55), rgba(20,184,166,.35), rgba(15,23,42,.9));
            box-shadow:0 0 45px rgba(34,197,94,.45);
        ">
            <div style="
                background:#0b1220;
                border-radius:22px;
                padding:20px;
                box-shadow: inset 0 0 32px rgba(34,197,94,.25);
            ">

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 align-middle text-light" style="background:transparent !important;">
                        <thead>
                            <tr style="color:#9ca3af;">
                                <th width="50">#</th>
                                <th>Nama Shift</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse ($shifts as $shift)
                            <form method="POST" action="{{ route('admin.settings.shifts.update', $shift) }}">
                                @csrf
                                @method('PATCH')
                                <tr style="background:transparent !important;">
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <input type="text"
                                               name="nama_shift"
                                               value="{{ $shift->nama_shift }}"
                                               class="form-control form-control-sm bg-dark text-white border-light"
                                               required>
                                    </td>

                                    <td>
                                        <input type="time"
                                               name="mulai"
                                               value="{{ $shift->mulai }}"
                                               class="form-control form-control-sm bg-dark text-white border-light"
                                               required>
                                    </td>

                                    <td>
                                        <input type="time"
                                               name="selesai"
                                               value="{{ $shift->selesai }}"
                                               class="form-control form-control-sm bg-dark text-white border-light"
                                               required>
                                    </td>

                                    <td>
                                        <button type="submit"
                                                class="btn btn-sm w-100"
                                                style="
                                                    background: linear-gradient(135deg,#22c55e,#16a34a);
                                                    color:#fff;
                                                    border:none;
                                                    border-radius:12px;
                                                    box-shadow:0 0 12px rgba(34,197,94,.45);
                                                    font-weight:600;
                                                ">
                                            Simpan
                                        </button>
                                    </td>
                                </tr>
                            </form>
                        @empty
                            <tr style="background:transparent !important;">
                                <td colspan="5" class="text-center text-white py-3">
                                    Belum ada data shift
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

{{-- CSS tambahan --}}
<style>
    table, thead, tbody, tr, th, td {
        background-color: transparent !important;
        color: #e5e7eb !important;
    }

    table tbody tr {
        border-top:1px solid rgba(34,197,94,.15);
        transition:.25s;
    }

    table tbody tr:hover {
        background: rgba(34,197,94,.12);
    }

    input.form-control {
        padding: 8px 12px;
        border-radius: 10px;
    }
</style>
@endsection
