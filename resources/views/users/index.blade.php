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
            <h3 class="fw-semibold mb-1">Daftar User</h3>
            <p class="mb-0">
                Manajemen data user
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="btn px-4 py-2"
           style="
                background: linear-gradient(135deg,#22c55e,#16a34a);
                border:none;
                border-radius:14px;
                box-shadow:
                    0 0 18px rgba(34,197,94,.55),
                    inset 0 0 10px rgba(34,197,94,.6);
           ">
            + Tambah User
        </a>
    </div>

    {{-- SEARCH --}}
    <div class="px-5 mb-4">
        <input type="text"
               id="search"
               placeholder="Cari ID / Nama / Email"
               style="
                    width:100%;
                    background:#020617;
                    border:1px solid rgba(34,197,94,.45);
                    color:#ffffff;
                    padding:14px 18px;
                    border-radius:16px;
                    box-shadow: inset 0 0 18px rgba(34,197,94,.25);
               ">
    </div>

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
                                <th class="px-4 py-3">#</th>
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

            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<style>
    #search::placeholder {
        color: #ffffff;
        opacity: .85;
    }

    #search:focus {
        outline: none;
        box-shadow:
            0 0 20px rgba(34,197,94,.6),
            inset 0 0 12px rgba(34,197,94,.4);
        border-color: #4ade80;
    }

    table tbody tr {
        border-top:1px solid rgba(34,197,94,.15);
        transition:.25s;
    }

    table tbody tr:hover {
        background: rgba(34,197,94,.15);
    }
</style>

<script>
let timer;
const input = document.getElementById('search');
const table = document.getElementById('user-table');

input.addEventListener('keyup', function () {
    clearTimeout(timer);

    timer = setTimeout(() => {
        fetch(`{{ route('admin.users.search') }}?q=${this.value}`)
            .then(res => res.text())
            .then(html => {
                table.innerHTML = html;
            });
    }, 150);
});
</script>
@endsection
