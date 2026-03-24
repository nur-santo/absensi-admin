@extends('layouts.app')
@section('title', 'Shift Kerja')

@section('content')

<style>
/* ── Shift table form ── */
.shift-form-table { width: 100%; border-collapse: collapse; font-size: .83rem; }

.shift-form-table thead th {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: .6rem 1rem;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--ink-3);
    white-space: nowrap;
}

.shift-form-table tbody td {
    padding: .6rem 1rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    color: var(--ink-2);
}
.shift-form-table tbody tr:last-child td { border-bottom: none; }
.shift-form-table tbody tr { transition: background .1s; }
.shift-form-table tbody tr:hover { background: var(--surface-2); }

/* ── Inline inputs ── */
.shift-input {
    width: 100%;
    padding: .4rem .65rem;
    font-size: .82rem;
    border: 1px solid var(--border-2);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--ink);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.shift-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}

/* ── Save button ── */
.shift-save-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .72rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--green-bdr);
    background: var(--green-bg);
    color: var(--green);
    cursor: pointer;
    transition: background .15s, transform .1s;
    white-space: nowrap;
}
.shift-save-btn:hover  { background: #d1fae5; }
.shift-save-btn:active { transform: scale(.97); }

/* ── Nama shift badge ── */
.shift-num {
    font-size: .72rem;
    color: var(--ink-4);
    font-variant-numeric: tabular-nums;
}
</style>

{{-- ── Header ── --}}
<div class="page-topbar">
    <div>
        <h3 class="page-title">Shift Kerja</h3>
        <p class="page-subtitle">Ubah nama, jam masuk, dan jam keluar tiap shift</p>
    </div>
</div>

{{-- ── Tabel shift ── --}}
<div class="app-table-wrap">
    <table class="shift-form-table">
        <thead>
            <tr>
                <th style="width:48px">#</th>
                <th>Nama Shift</th>
                <th style="width:160px">Jam Masuk</th>
                <th style="width:160px">Jam Keluar</th>
                <th style="width:110px; text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shifts as $shift)
                <tr>
                    <td class="shift-num">{{ $loop->iteration }}</td>

                    {{-- Wrap satu form per baris --}}
                    <form method="POST"
                          action="{{ route('admin.settings.shifts.update', $shift) }}"
                          id="form-shift-{{ $shift->id }}"
                          style="display:contents">
                        @csrf
                        @method('PATCH')

                        <td>
                            <input type="text"
                                   name="nama_shift"
                                   value="{{ $shift->nama_shift }}"
                                   class="shift-input"
                                   required>
                        </td>

                        <td>
                            <input type="time"
                                   name="mulai"
                                   value="{{ \Carbon\Carbon::parse($shift->mulai)->format('H:i') }}"
                                   class="shift-input"
                                   required>
                        </td>

                        <td>
                            <input type="time"
                                   name="selesai"
                                   value="{{ \Carbon\Carbon::parse($shift->selesai)->format('H:i') }}"
                                   class="shift-input"
                                   required>
                        </td>

                        <td style="text-align:center">
                            <button type="submit"
                                    form="form-shift-{{ $shift->id }}"
                                    class="shift-save-btn">
                                Simpan
                            </button>
                        </td>

                    </form>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-title">Belum ada data shift</div>
                            <div class="empty-sub">Tambahkan shift melalui seeder atau database</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Info --}}
<p style="font-size:.72rem;color:var(--ink-4);margin-top:.75rem">
    Perubahan jam shift berlaku untuk generate absensi berikutnya. Data absensi yang sudah di-generate tidak berubah.
</p>

@endsection