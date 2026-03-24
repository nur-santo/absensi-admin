@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')

    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
            margin-top: .25rem;
        }

        .setting-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .15s, transform .15s;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
        }

        .setting-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            opacity: 0;
            transition: opacity .15s;
        }

        .setting-card.c-blue::before {
            background: var(--accent);
        }

        .setting-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .setting-card:hover::before {
            opacity: 1;
        }

        .setting-card.c-gray {
            border-style: dashed;
            cursor: default;
            pointer-events: none;
            opacity: .55;
        }

        .setting-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .setting-icon.blue {
            background: var(--accent-bg);
        }

        .setting-icon.gray {
            background: var(--surface-3);
        }

        .setting-title {
            font-size: .9rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 3px;
        }

        .setting-desc {
            font-size: .75rem;
            color: var(--ink-3);
            line-height: 1.5;
        }

        .setting-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: .75rem;
            border-top: 1px solid var(--border);
            font-size: .75rem;
        }

        .setting-link-label {
            font-weight: 600;
            color: var(--accent);
        }

        .setting-soon {
            color: var(--ink-4);
            font-style: italic;
        }
    </style>

    <div class="page-topbar">
        <div>
            <h3 class="page-title">Pengaturan Sistem</h3>
        </div>
    </div>

    <div class="settings-grid">

        {{-- Shift Kerja --}}
        <a href="{{ route('admin.shifts') }}" class="setting-card c-blue">
            <div style="display:flex;align-items:flex-start;gap:.85rem">
                <div class="setting-icon blue">
                    <svg class="icon">
                        <use href="#icon-clock"></use>
                    </svg>
                </div>
                <div>
                    <div class="setting-title">Shift Kerja</div>
                    <div class="setting-desc">
                        Atur jadwal shift.
                    </div>
                </div>
            </div>
            <div class="setting-footer">
                <span class="setting-link-label">Kelola shift</span>
                <span style="color:var(--accent);font-size:.8rem">→</span>
            </div>
        </a>

        {{-- Placeholder --}}
        <div class="setting-card c-gray">
            <div style="display:flex;align-items:flex-start;gap:.85rem">
                <div class="setting-icon blue">
                    <svg class="icon">
                        <use href="#icon-clock"></use>
                    </svg>
                </div>
                <div>
                    <div class="setting-title">Fitur Lainnya</div>
                    <div class="setting-desc">
                        Belum tersedia.
                    </div>
                </div>
            </div>
            <div class="setting-footer" style="border-top-style:dashed">
                <span class="setting-soon">Segera hadir</span>
            </div>
        </div>

    </div>

@endsection
