@extends('layouts.app')
@section('title', 'Tambah Karyawan')

@section('content')

    <style>
        /* ── Form card ── */
        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            max-width: 640px;
        }

        /* ── Section header dalam form ── */
        .form-section {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .form-section-title {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--ink-3);
            margin-bottom: 1rem;
        }

        /* ── Field group ── */
        .field-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
        }

        .field-group.single {
            grid-template-columns: 1fr;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .field label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--ink-2);
        }

        .field-required::after {
            content: ' *';
            color: var(--red);
        }

        /* Input/select style — konsisten dengan halaman lain */
        .field input,
        .field select {
            padding: .48rem .75rem;
            font-size: .83rem;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            width: 100%;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .field input::placeholder {
            color: var(--ink-4);
        }

        .field select:disabled {
            background: var(--surface-3);
            color: var(--ink-4);
            cursor: not-allowed;
        }

        /* Error state */
        .field input.is-error,
        .field select.is-error {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .08);
        }

        .field-error-msg {
            font-size: .7rem;
            color: var(--red);
            margin-top: 2px;
        }

        /* Disabled overlay hint */
        .field-hint {
            font-size: .68rem;
            color: var(--ink-4);
            margin-top: 2px;
        }

        /* ── Form footer ── */
        .form-footer {
            padding: 1rem 1.5rem;
            background: var(--surface-2);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .6rem;
        }

        .btn-cancel {
            padding: .5rem 1rem;
            font-size: .8rem;
            font-weight: 600;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink-2);
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }

        .btn-cancel:hover {
            background: var(--surface-3);
            color: var(--ink-2);
            text-decoration: none;
        }
    </style>

    {{-- Header --}}
    <div class="page-topbar">
        <div>
            <h3 class="page-title">Tambah Karyawan</h3>
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" id="createForm">
        @csrf

        <div class="form-card">

            {{-- ── Seksi: Akun ── --}}
            <div class="form-section">
                <div class="form-section-title">Informasi Akun</div>
                <div class="field-group">
                    <div class="field">
                        <label class="field-required">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? 'is-error' : '' }}" required>
                        @error('name')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-required">Email</label>
                        <input type="email" name="email" placeholder="email@contoh.com" value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'is-error' : '' }}" required>
                        @error('email')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="field-group single" style="margin-top:.85rem">
                    <div class="field">
                        <label class="field-required">Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                            class="{{ $errors->has('password') ? 'is-error' : '' }}" required>
                        @error('password')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Seksi: Data Kerja ── --}}
            <div class="form-section">
                <div class="form-section-title">Data Kerja</div>
                <div class="field-group">

                    <div class="field">
                        <label class="field-required">Status</label>
                        <select name="status" id="status" required>
                            <option value="" disabled {{ old('status') ? '' : 'selected' }}>Pilih status</option>
                            <option value="PKL" {{ old('status') == 'PKL' ? 'selected' : '' }}>PKL</option>
                            <option value="KARYAWAN" {{ old('status') == 'KARYAWAN' ? 'selected' : '' }}>Karyawan</option>
                        </select>
                        @error('status')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label>Instansi</label>
                        <select name="instansi" id="instansi">
                            <option value="" disabled selected>Pilih instansi</option>
                            @foreach ($instansi as $i)
                                <option value="{{ $i->nama_instansi }}"
                                    {{ old('instansi') == $i->nama_instansi ? 'selected' : '' }}>
                                    {{ $i->nama_instansi }}
                                </option>
                            @endforeach
                        </select>
                        <span class="field-hint" id="instansiHint"></span>
                        @error('instansi')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-required">Mode Kerja</label>
                        <select name="mode_kerja" id="modeKerja" required>
                            <option value="" disabled {{ old('mode_kerja') ? '' : 'selected' }}>Pilih mode kerja
                            </option>
                            <option value="WFO" {{ old('mode_kerja') == 'WFO' ? 'selected' : '' }}>WFO — Work from
                                Office</option>
                            <option value="WFH" {{ old('mode_kerja') == 'WFH' ? 'selected' : '' }}>WFH — Work from Home
                            </option>
                        </select>
                        @error('mode_kerja')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-required">Shift</label>
                        <select name="shift_id" id="shift" required>
                            <option value="" disabled {{ old('shift_id') ? '' : 'selected' }}>Pilih shift</option>
                            @foreach ($shift as $s)
                                <option value="{{ $s->id }}" data-nama="{{ $s->nama_shift }}"
                                    {{ old('shift_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_shift }}
                                    ({{ \Carbon\Carbon::parse($s->mulai)->format('H:i') }}
                                    – {{ \Carbon\Carbon::parse($s->selesai)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                        <span class="field-hint" id="shiftHint"></span>
                        @error('shift_id')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="form-footer">
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-primary-app">
                    Simpan Karyawan
                </button>
            </div>

        </div>
    </form>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = document.getElementById('status');
            const instansi = document.getElementById('instansi');
            const instHint = document.getElementById('instansiHint');
            const modeKerja = document.getElementById('modeKerja');
            const shift = document.getElementById('shift');
            const shiftHint = document.getElementById('shiftHint');

            function handleStatusChange() {
                if (status.value === 'KARYAWAN') {
                    instansi.value = '';
                    instansi.disabled = true;
                    instHint.textContent = 'Tidak diperlukan untuk Karyawan';
                } else {
                    instansi.disabled = false;
                    instHint.textContent = '';
                }
            }

            function handleModeKerjaChange() {
                const options = shift.querySelectorAll('option[value]');

                if (modeKerja.value === 'WFH') {
                    options.forEach(opt => {
                        if (opt.value === '') return;
                        opt.disabled = opt.dataset.nama !== 'FULLTIME';
                    });

                    const fulltime = [...options].find(o => o.dataset.nama === 'FULLTIME');
                    if (fulltime) {
                        shift.value = fulltime.value;
                        shift.disabled = true;
                    }

                    shiftHint.textContent = 'WFH hanya tersedia untuk shift Fulltime';
                } else {
                    options.forEach(opt => opt.disabled = false);
                    shift.disabled = false;
                    shift.value = '';
                    shiftHint.textContent = '';
                }
            }

            status.addEventListener('change', handleStatusChange);
            modeKerja.addEventListener('change', handleModeKerjaChange);

            // Jalankan saat load jika ada old() value
            if (status.value) handleStatusChange();
            if (modeKerja.value) handleModeKerjaChange();
        });
    </script>
@endsection
