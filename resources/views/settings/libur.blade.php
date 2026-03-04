@extends('layouts.app')

@section('content')
<div class="container-fluid px-0 dashboard-container">

    {{-- HEADER --}}
    <div class="px-5 mb-5 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-semibold mb-1">Kelola Data Libur</h3>
            <p class="mb-0">Tambah, edit, atau hapus tanggal libur</p>
        </div>

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

    {{-- FORM TAMBAH / EDIT --}}
    <div class="px-5 mb-5">
        <div style="
            border-radius:24px;
            background: linear-gradient(135deg, rgba(34,197,94,.55), rgba(20,184,166,.35), rgba(15,23,42,.9));
            box-shadow:0 0 45px rgba(34,197,94,.45);
        ">
            <div style="
                background:#0b1220;
                border-radius:22px;
                padding:28px;
                box-shadow: inset 0 0 32px rgba(34,197,94,.25);
            ">
                
                <h5 class="mb-4">{{ isset($editLibur) ? 'Edit Libur' : 'Tambah Libur Baru' }}</h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($editLibur) ? route('libur.update', $editLibur->id) : route('libur.store') }}" method="POST">
                    @csrf
                    @if(isset($editLibur)) @method('PUT') @endif

                    <div class="mb-3">
                        <label for="tanggal" class="form-label text-white">Tanggal Libur</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control bg-dark text-white border-light" value="{{ old('tanggal', $editLibur->tanggal ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label text-white">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control bg-dark border-light text-keterangan" value="{{ old('keterangan', $editLibur->keterangan ?? 'Hari Raya Nyepi') }}" placeholder="Misal: Hari Raya Nyepi">
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-save">{{ isset($editLibur) ? 'Update' : 'Simpan' }}</button>
                        @if(isset($editLibur))
                            <a href="{{ route('libur.manage') }}" class="btn btn-cancel">Batal</a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- TABEL DATA LIBUR --}}
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
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 align-middle text-light" style="background:transparent !important;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($libur as $item)
                                <tr style="background:transparent !important;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                    <td class="text-keterangan">{{ $item->keterangan ?? 'Hari Raya Nyepi' }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('libur.manage', $item->id) }}" class="btn btn-action edit">Edit</a>
                                        <form action="{{ route('libur.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-action delete">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr style="background:transparent !important;">
                                    <td colspan="5" class="text-center text-white py-4">Belum ada data libur.</td>
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
    /* TOMBOL */
    .dashboard-container .btn-save { background: linear-gradient(135deg,#22c55e,#16a34a); color:#fff; border:none; border-radius:14px; padding:10px 20px; font-weight:600; box-shadow:0 0 18px rgba(34,197,94,.55), inset 0 0 12px rgba(34,197,94,.4);}
    .dashboard-container .btn-cancel { background: rgba(148,163,184,.4); color:#fff; border:none; border-radius:14px; padding:10px 20px; font-weight:500; box-shadow:0 0 12px rgba(148,163,184,.35), inset 0 0 8px rgba(148,163,184,.25);}
    .dashboard-container .btn-action.edit { background: rgba(250,204,21,.25); color:#facc15; border:1px solid rgba(250,204,21,.45); box-shadow:0 0 10px rgba(250,204,21,.35);}
    .dashboard-container .btn-action.delete { background: rgba(239,68,68,.25); color:#f87171; border:1px solid rgba(239,68,68,.45); box-shadow:0 0 10px rgba(239,68,68,.35);}

    /* TABLE DARK */
    table, thead, tbody, tr, th, td { background-color: transparent !important; color:#e5e7eb !important; }

    /* Keterangan lebih samar */
    .text-keterangan { color: rgba(255,255,255,.6) !important; font-style: italic; }

</style>
@endsection
