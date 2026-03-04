@extends('layouts.app')

@section('content')
<div class="container-fluid px-0 text-light">

    {{-- HEADER --}}
    <div class="mb-5 px-5">
        <h3 class="fw-semibold mb-1">Pengaturan Sistem</h3>
        <p class="mb-0" style="color:#9ca3af">
            Konfigurasi data pendukung sistem absensi
        </p>
    </div>

    {{-- CONTENT --}}
    <div class="px-5">
        <div class="row g-4">

            {{-- ===== HARI LIBUR ===== --}}
            <div class="col-md-6">
                <div class="p-2 h-100"
                    style="
                        border-radius:24px;
                        background: linear-gradient(135deg,
                            rgba(34,197,94,.55),
                            rgba(20,184,166,.35),
                            rgba(15,23,42,.9)
                        );
                        box-shadow:0 0 40px rgba(34,197,94,.45);
                    ">

                    <div class="h-100 text-center d-flex flex-column justify-content-between p-4"
                        style="
                            background:#0b1220;
                            border-radius:22px;
                            box-shadow: inset 0 0 28px rgba(34,197,94,.25);
                        ">

                        <div>
                            <div class="mb-3 fs-1"
                                style="color:#4ade80">
                                📅
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Hari Libur
                            </h5>

                            <p class="small"
                               style="color:#9ca3af">
                                Kelola tanggal merah & hari libur nasional
                            </p>
                        </div>

                        <a href="{{ route('libur.manage') }}"
                           class="btn text-white w-100 mt-3"
                           style="
                                background: rgba(34,197,94,.25);
                                border:1px solid rgba(34,197,94,.45);
                                box-shadow:0 0 14px rgba(34,197,94,.45);
                                border-radius:14px;
                           ">
                            Kelola
                        </a>

                    </div>
                </div>
            </div>

            {{-- ===== SHIFT ===== --}}
            <div class="col-md-6">
                <div class="p-2 h-100"
                    style="
                        border-radius:24px;
                        background: linear-gradient(135deg,
                            rgba(34,197,94,.55),
                            rgba(20,184,166,.35),
                            rgba(15,23,42,.9)
                        );
                        box-shadow:0 0 40px rgba(34,197,94,.45);
                    ">

                    <div class="h-100 text-center d-flex flex-column justify-content-between p-4"
                        style="
                            background:#0b1220;
                            border-radius:22px;
                            box-shadow: inset 0 0 28px rgba(34,197,94,.25);
                        ">

                        <div>
                            <div class="mb-3 fs-1"
                                style="color:#38bdf8">
                                ⏱️
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Shift Kerja
                            </h5>

                            <p class="small"
                               style="color:#9ca3af">
                                Atur jam masuk & jam keluar shift
                            </p>
                        </div>

                        <a href="{{ route('admin.shifts') }}"
                           class="btn text-white w-100 mt-3"
                           style="
                                background: rgba(56,189,248,.25);
                                border:1px solid rgba(56,189,248,.45);
                                box-shadow:0 0 14px rgba(56,189,248,.45);
                                border-radius:14px;
                           ">
                            Kelola
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
