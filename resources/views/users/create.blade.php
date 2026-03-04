@extends('layouts.app')

@section('content')

<div class="container-fluid px-0 dashboard-container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5 px-5">
        <div>
            <h3 class="fw-semibold mb-1">Tambah User Baru</h3>
            <p class="mb-0">Masukkan data user baru dengan lengkap</p>
        </div>

        <div class="d-flex gap-2">
            {{-- Tombol Back --}}
            <a href="{{ route('admin.users.index') }}"
               class="btn px-4 py-2"
               style="
                    background: rgba(148,163,184,.4);
                    border:none;
                    border-radius:14px;
                    box-shadow:
                        0 0 12px rgba(148,163,184,.35),
                        inset 0 0 8px rgba(148,163,184,.25);
                    color:#ffffff;
                    font-weight:500;
               ">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- FORM WRAPPER --}}
    <div class="px-5 mb-5">
        <div
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
                    padding:28px;
                    box-shadow: inset 0 0 32px rgba(34,197,94,.25);
                ">

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name"
                               class="form-control"
                               style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                               "
                               required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control"
                               style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                               "
                               required>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                               class="form-control"
                               style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                               "
                               required>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status"
                                class="form-select"
                                style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                                ">
                            <option value="">-- Pilih Status --</option>
                            <option value="PKL">PKL</option>
                            <option value="KARYAWAN">Karyawan</option>
                        </select>
                    </div>

                    {{-- Instansi --}}
                    <div class="mb-3">
                        <label class="form-label">Instansi</label>
                        <select name="instansi" id="instansi"
                                class="form-select"
                                style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                                ">
                            <option value="">-- Pilih Instansi --</option>
                            @foreach ($instansi as $i)
                                <option value="{{ $i->nama_instansi }}">
                                    {{ $i->nama_instansi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mode Kerja --}}
                    <div class="mb-3">
                        <label class="form-label">Mode Kerja</label>
                        <select name="mode_kerja" id="modeKerja"
                                class="form-select"
                                style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                                ">
                            <option value="">-- Pilih Mode Kerja --</option>
                            <option value="WFO">WFO</option>
                            <option value="WFH">WFH</option>
                        </select>
                    </div>

                    {{-- Shift --}}
                    <div class="mb-4">
                        <label class="form-label">Shift</label>
                        <select name="shift_id" id="shift"
                                class="form-select"
                                style="
                                    background:#020617;
                                    border:1px solid rgba(34,197,94,.45);
                                    color:#ffffff;
                                    padding:12px 14px;
                                    border-radius:14px;
                                "
                                required>
                            <option value="">-- Pilih Shift --</option>
                            @foreach ($shift as $s)
                                <option value="{{ $s->id }}" data-nama="{{ $s->nama_shift }}">
                                    {{ $s->nama_shift }} ({{ $s->mulai }} - {{ $s->selesai }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BUTTON SIMPAN --}}
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit"
                                style="
                                    background: linear-gradient(135deg,#22c55e,#16a34a);
                                    border:none;
                                    border-radius:14px;
                                    padding:12px 24px;
                                    font-weight:600;
                                    box-shadow:0 0 18px rgba(34,197,94,.55),
                                               inset 0 0 12px rgba(34,197,94,.4);
                                ">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const status     = document.getElementById('status');
    const instansi   = document.getElementById('instansi');
    const modeKerja  = document.getElementById('modeKerja');
    const shift      = document.getElementById('shift');

    function handleStatusChange() {
        if (status.value === 'KARYAWAN') {
            instansi.value = '';
            instansi.disabled = true;
        } else {
            instansi.disabled = false;
        }
    }

    function handleModeKerjaChange() {
        const options = shift.querySelectorAll('option');

        options.forEach(option => option.disabled = false);

        if (modeKerja.value === 'WFH') {
            options.forEach(option => {
                if (option.dataset.nama !== 'FULLTIME' && option.value !== '') {
                    option.disabled = true;
                }
            });

            const fulltime = [...options].find(
                opt => opt.dataset.nama === 'FULLTIME'
            );

            if (fulltime) {
                shift.value = fulltime.value;
                shift.disabled = true;
            }

        } else {
            shift.disabled = false;
            shift.value = '';
        }
    }

    status.addEventListener('change', handleStatusChange);
    modeKerja.addEventListener('change', handleModeKerjaChange);
});
</script>
@endsection
