@extends('layouts.app')
@section('title', 'Perizinan')

@section('content')

    {{-- Topbar --}}
    <div class="page-topbar">
        <div>
            <h3 class="page-title">Persetujuan Perizinan</h3>
        </div>
        {{-- @if ($perizinan->count() > 0)
            <div
                style="display:inline-flex;align-items:center;gap:6px;
                    background:var(--amber-bg);border:1px solid var(--amber-bdr);
                    color:var(--amber);font-size:0.75rem;font-weight:600;
                    padding:.35rem .85rem;border-radius:20px;">
                <span class="pulse-dot" style="background:var(--amber)"></span>
                {{ $perizinan->count() }} menunggu persetujuan
            </div>
        @endif --}}
    </div>

    {{-- Table --}}
    <div class="app-table-wrap">
        <table class="app-table">
            <thead>
                <tr>
                    <th style="width:44px">#</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis</th>
                    <th>Periode</th>
                    <th>Keterangan</th>
                    <th>Lampiran</th>
                    <th style="width:168px; text-align:center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perizinan as $item)
                    @php
                        $nama = $item->user->name ?? '-';
                        $initials = collect(explode(' ', $nama))
                            ->take(2)
                            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                            ->join('');

                        $jenisClass = match (strtolower($item->jenis)) {
                            'izin' => 'pill-izin',
                            'sakit' => 'pill-sakit',
                            'cuti' => 'pill-cuti',
                            default => 'pill-gray',
                        };

                        $tglMulai = \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y');
                        $tglSelesai = \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y');
                        $sameDay = $item->tanggal_mulai === $item->tanggal_selesai;
                    @endphp
                    <tr>
                        <td class="cell-num">{{ $loop->iteration }}</td>

                        <td>
                            <div class="name-cell">
                                <span class="avatar">{{ $initials }}</span>
                                {{ $nama }}
                            </div>
                        </td>

                        <td>
                            <span class="pill {{ $jenisClass }}">{{ strtoupper($item->jenis) }}</span>
                        </td>

                        <td>
                            <span style="font-size:.8rem;white-space:nowrap">
                                {{ $tglMulai }}
                                @if (!$sameDay)
                                    <span style="color:var(--ink-4);margin:0 3px">→</span>
                                    {{ $tglSelesai }}
                                @endif
                            </span>
                        </td>

                        <td>
                            <div
                                style="max-width:180px;font-size:.78rem;color:var(--ink-3);
                                    overflow:hidden;display:-webkit-box;
                                    -webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $item->keterangan ?? '—' }}
                            </div>
                        </td>

                        <td>
                            @if ($item->lampiran)
                                <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank"
                                    style="display:inline-flex;align-items:center;gap:5px;
                                      font-size:.72rem;font-weight:600;color:var(--accent);
                                      text-decoration:none;padding:3px 9px;
                                      border:1px solid var(--accent-bdr);
                                      border-radius:var(--radius-sm);
                                      background:var(--accent-bg);
                                      transition:background .15s"
                                    onmouseover="this.style.background='#dbeafe'"
                                    onmouseout="this.style.background='var(--accent-bg)'">
                                    <svg class="icon icon-sm" aria-hidden="true">
                                        <use href="#icon-file"></use>
                                    </svg>
                                    Lihat
                                </a>
                            @else
                                <span class="cell-dash">—</span>
                            @endif
                        </td>

                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px">

                                {{-- Setujui --}}
                                <button class="action-btn approve"
                                    onclick="openModal('approve',
                                        '{{ addslashes($nama) }}',
                                        '{{ addslashes(strtoupper($item->jenis)) }}',
                                        '{{ route('admin.perizinan.approve', $item->id) }}')">
                                    <svg class="icon" aria-hidden="true">
                                        <use href="#icon-check"></use>
                                    </svg>
                                    Setujui
                                </button>

                                {{-- Tolak --}}
                                <button class="action-btn reject"
                                    onclick="openModal('reject',
                                        '{{ addslashes($nama) }}',
                                        '{{ addslashes(strtoupper($item->jenis)) }}',
                                        '{{ route('admin.perizinan.reject', $item->id) }}')">
                                    <svg class="icon" aria-hidden="true">
                                        <use href="#icon-close"></use>
                                    </svg>
                                    Tolak
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg class="icon" aria-hidden="true"
                                    style="stroke:var(--border-2);width:44px;height:44px;display:block;margin:0 auto .85rem">
                                    <use href="#icon-check-circle"></use>
                                </svg>
                                <div class="empty-title">Semua beres!</div>
                                <div class="empty-sub">Tidak ada perizinan yang menunggu persetujuan</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Confirm modal --}}
    <div class="app-modal-overlay" id="pzModal">
        <div class="app-modal">
            {{-- Icon container — filled via JS using <use> swap --}}
            <div class="modal-icon" id="modalIcon">
                <svg class="icon icon-2xl" id="modalIconSvg" aria-hidden="true">
                    <use id="modalIconUse" href=""></use>
                </svg>
            </div>
            <div class="modal-title" id="modalTitle"></div>
            <div class="modal-sub" id="modalSub"></div>
            <div class="modal-actions">
                <button class="modal-btn cancel" onclick="closeModal()">Batal</button>
                <button class="modal-btn" id="modalConfirm" onclick="submitModal()"></button>
            </div>
        </div>
    </div>

    <form id="pzForm" method="POST" style="display:none">@csrf</form>

    <style>
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .72rem;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: var(--radius-sm);
            border: 1px solid;
            cursor: pointer;
            transition: opacity .15s, transform .1s;
        }

        .action-btn:active {
            transform: scale(.97);
        }

        .action-btn .icon {
            width: 12px;
            height: 12px;
        }

        .action-btn.approve {
            background: var(--green-bg);
            color: var(--green);
            border-color: var(--green-bdr);
        }

        .action-btn.approve:hover {
            background: #d1fae5;
        }

        .action-btn.reject {
            background: var(--red-bg);
            color: var(--red);
            border-color: var(--red-bdr);
        }

        .action-btn.reject:hover {
            background: #fee2e2;
        }

        /* Filled modal icons use fill, not stroke */
        #modalIconSvg {
            stroke: none;
            fill: currentColor;
        }
    </style>

@endsection

@section('scripts')
    <script>
        let pendingUrl = null;

        function openModal(type, nama, jenis, url) {
            pendingUrl = url;
            const icon = document.getElementById('modalIcon');
            const iconUse = document.getElementById('modalIconUse');
            const title = document.getElementById('modalTitle');
            const sub = document.getElementById('modalSub');
            const confirm = document.getElementById('modalConfirm');

            if (type === 'approve') {
                icon.className = 'modal-icon success';
                // Swap sprite reference — no raw SVG string in JS
                iconUse.setAttribute('href', '#icon-modal-check');
                title.textContent = 'Setujui Perizinan';
                sub.innerHTML = `Perizinan <strong>${jenis}</strong> dari <strong>${nama}</strong> akan disetujui.`;
                confirm.className = 'modal-btn confirm-green';
                confirm.textContent = 'Ya, Setujui';
            } else {
                icon.className = 'modal-icon danger';
                iconUse.setAttribute('href', '#icon-modal-close');
                title.textContent = 'Tolak Perizinan';
                sub.innerHTML = `Perizinan <strong>${jenis}</strong> dari <strong>${nama}</strong> akan ditolak.`;
                confirm.className = 'modal-btn confirm-red';
                confirm.textContent = 'Ya, Tolak';
            }
            document.getElementById('pzModal').classList.add('open');
        }

        function closeModal() {
            document.getElementById('pzModal').classList.remove('open');
            pendingUrl = null;
        }

        function submitModal() {
            if (!pendingUrl) return;
            const form = document.getElementById('pzForm');
            form.action = pendingUrl;
            form.submit();
        }

        document.getElementById('pzModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeModal();
        });
    </script>
@endsection
