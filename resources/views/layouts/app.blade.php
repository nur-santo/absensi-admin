<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Absensi App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* Warna */
        :root {
            --ink: #0f1117;
            --ink-2: #3d3f4a;
            --ink-3: #6b7280;
            --ink-4: #9ca3af;
            --surface: #ffffff;
            --surface-2: #f8f9fb;
            --surface-3: #f1f3f6;
            --border: #e5e7eb;
            --border-2: #d1d5db;
            --accent: #2563eb;
            --accent-bg: #eff6ff;
            --accent-bdr: #bfdbfe;
            --green: #059669;
            --green-bg: #ecfdf5;
            --green-bdr: #a7f3d0;
            --amber: #d97706;
            --amber-bg: #fffbeb;
            --amber-bdr: #fde68a;
            --red: #dc2626;
            --red-bg: #fef2f2;
            --red-bdr: #fecaca;
            --sky: #0284c7;
            --sky-bg: #f0f9ff;
            --sky-bdr: #bae6fd;
            --violet: #7c3aed;
            --violet-bg: #f5f3ff;
            --violet-bdr: #ddd6fe;
            --radius-sm: 6px;
            --radius: 10px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow: 0 4px 12px rgba(0, 0, 0, .08), 0 1px 3px rgba(0, 0, 0, .05);
            --sidebar-w: 240px;
        }

        /* Reset Animation */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--surface-2);
            color: var(--ink-2);
            overflow: hidden;
        }

        /* ── Icon system ── */
        .icon {
            width: 1em;
            height: 1em;
            stroke: currentColor;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
        }

        .icon-sm {
            width: 12px;
            height: 12px;
        }

        .icon-md {
            width: 14px;
            height: 14px;
        }

        .icon-lg {
            width: 16px;
            height: 16px;
        }

        .icon-xl {
            width: 18px;
            height: 18px;
        }

        .icon-2xl {
            width: 22px;
            height: 22px;
        }

        .icon-28 {
            width: 28px;
            height: 28px;
        }

        .icon-36 {
            width: 36px;
            height: 36px;
        }

        .icon-44 {
            width: 44px;
            height: 44px;
        }

        /* For filled icons (modal 50x50) */
        .icon-fill {
            fill: currentColor;
            stroke: none;
        }

        /* Layout */
        .app-shell {
            display: flex;
            height: 100vh;
        }

        .app-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            padding: 0;
        }

        .app-inner {
            padding: 2rem 1.75rem 3rem;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #111827;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 1.25rem 1.1rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-icon .icon {
            width: 16px;
            height: 16px;
            stroke: #fff;
        }

        .sidebar-brand-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.01em;
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, .4);
            font-weight: 400;
        }

        /* Nav section label */
        .sidebar-label {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .3);
            padding: 1.1rem 1.1rem .4rem;
        }

        /* Nav links */
        .sidebar-nav {
            flex: 1;
            padding: 0 .6rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: .55rem .75rem;
            border-radius: var(--radius-sm);
            color: rgba(255, 255, 255, .6);
            font-size: 0.83rem;
            font-weight: 500;
            text-decoration: none;
            transition: background .15s, color .15s;
            margin-bottom: 2px;
        }

        .sidebar-link .icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            opacity: .7;
            transition: opacity .15s;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
        }

        .sidebar-link:hover .icon {
            opacity: 1;
        }

        .sidebar-link.active {
            background: rgba(37, 99, 235, .25);
            color: #93c5fd;
        }

        .sidebar-link.active .icon {
            opacity: 1;
            stroke: #93c5fd;
        }

        /* Sidebar footer (user info + logout) */
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, .07);
            padding: .85rem .6rem;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: .5rem .75rem;
            border-radius: var(--radius-sm);
            margin-bottom: 4px;
        }

        .sidebar-user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .8);
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .sidebar-user-name {
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .8);
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-user-role {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, .35);
        }

        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: .5rem .75rem;
            border-radius: var(--radius-sm);
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, .45);
            font-size: 0.78rem;
            cursor: pointer;
            transition: background .15s, color .15s;
            text-align: left;
        }

        .sidebar-logout .icon {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .sidebar-logout:hover {
            background: rgba(220, 38, 38, .18);
            color: #fca5a5;
        }

        /* Judul dan topbar */
        .page-topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -.02em;
            margin: 0;
        }

        .page-subtitle {
            font-size: 0.78rem;
            color: var(--ink-3);
            margin-top: 3px;
        }

        /* Button */
        .btn-primary-app {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--accent);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
            padding: .5rem 1rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(37, 99, 235, .3);
            transition: background .15s, transform .1s;
            white-space: nowrap;
        }

        .btn-primary-app:hover {
            background: #1d4ed8;
            color: #fff;
            text-decoration: none;
        }

        .btn-primary-app:active {
            transform: scale(.98);
        }

        .btn-primary-app .icon {
            width: 14px;
            height: 14px;
        }

        /* Card */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: .75rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: .9rem 1.1rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .15s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            box-shadow: var(--shadow);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 0 0 var(--radius) var(--radius);
        }

        .stat-card.c-blue::after {
            background: var(--accent);
        }

        .stat-card.c-green::after {
            background: var(--green);
        }

        .stat-card.c-red::after {
            background: var(--red);
        }

        .stat-card.c-amber::after {
            background: var(--amber);
        }

        .stat-card.c-sky::after {
            background: var(--sky);
        }

        .stat-card.c-violet::after {
            background: var(--violet);
        }

        .stat-label {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
            margin-bottom: .35rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
            color: var(--ink);
            letter-spacing: -.03em;
        }

        .stat-note {
            font-size: 0.7rem;
            color: var(--ink-4);
            margin-top: 3px;
        }

        /* Tabel */
        .app-table-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .app-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .app-table thead th {
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
            padding: .65rem 1rem;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
            white-space: nowrap;
        }

        .app-table tbody td {
            padding: .75rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--ink-2);
            vertical-align: middle;
        }

        .app-table tbody tr:last-child td {
            border-bottom: none;
        }

        .app-table tbody tr {
            transition: background .1s;
        }

        .app-table tbody tr:hover {
            background: var(--surface-2);
        }

        /* Helper Cell */
        .cell-num {
            font-size: 0.72rem;
            color: var(--ink-4);
            font-variant-numeric: tabular-nums;
        }

        .cell-muted {
            font-size: 0.76rem;
            color: var(--ink-3);
        }

        .cell-dash {
            font-size: 0.75rem;
            color: var(--ink-4);
        }

        .name-cell {
            display: flex;
            align-items: center;
            gap: 9px;
            font-weight: 600;
            color: var(--ink);
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--accent-bg);
            color: var(--accent);
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        /* Badge dan lambang */
        .pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        .pill-blue {
            background: var(--accent-bg);
            color: var(--accent);
        }

        .pill-green {
            background: var(--green-bg);
            color: var(--green);
        }

        .pill-red {
            background: var(--red-bg);
            color: var(--red);
        }

        .pill-amber {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .pill-sky {
            background: var(--sky-bg);
            color: var(--sky);
        }

        .pill-violet {
            background: var(--violet-bg);
            color: var(--violet);
        }

        .pill-gray {
            background: var(--surface-3);
            color: var(--ink-2);
        }

        /* semantic */
        .pill-aktif {
            background: var(--green-bg);
            color: var(--green);
        }

        .pill-nonaktif {
            background: var(--red-bg);
            color: var(--red);
        }

        .pill-pkl {
            background: var(--violet-bg);
            color: var(--violet);
        }

        .pill-karyawan {
            background: var(--green-bg);
            color: var(--green);
        }

        .pill-wfo {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .pill-wfh {
            background: var(--sky-bg);
            color: var(--sky);
        }

        .pill-izin {
            background: var(--sky-bg);
            color: var(--sky);
        }

        .pill-sakit {
            background: var(--red-bg);
            color: var(--red);
        }

        .pill-cuti {
            background: var(--violet-bg);
            color: var(--violet);
        }

        /* Alert */
        .app-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: var(--radius);
            padding: .75rem 1rem;
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }

        .app-alert .icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .app-alert.success {
            background: var(--green-bg);
            border: 1px solid var(--green-bdr);
            color: var(--green);
        }

        .app-alert.error {
            background: var(--red-bg);
            border: 1px solid var(--red-bdr);
            color: var(--red);
        }

        .app-alert.info {
            background: var(--accent-bg);
            border: 1px solid var(--accent-bdr);
            color: var(--accent);
        }

        /* State Kosong */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state .icon {
            width: 44px;
            height: 44px;
            stroke: var(--border-2);
            margin: 0 auto .85rem;
            display: block;
        }

        .empty-title {
            font-size: .9rem;
            font-weight: 600;
            color: var(--ink-2);
            margin-bottom: 4px;
        }

        .empty-sub {
            font-size: .78rem;
            color: var(--ink-3);
        }

        /* Search dan Filter */
        .filter-toolbar {
            display: flex;
            gap: .6rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-wrap .icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: var(--ink-4);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: .5rem .75rem .5rem 2.1rem;
            font-size: 0.82rem;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .search-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .search-input::placeholder {
            color: var(--ink-4);
        }

        .filter-select {
            padding: .5rem .75rem;
            font-size: 0.78rem;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink-2);
            outline: none;
            cursor: pointer;
            transition: border-color .15s;
        }

        .filter-select:focus {
            border-color: var(--accent);
        }

        .result-count {
            font-size: 0.75rem;
            color: var(--ink-3);
            white-space: nowrap;
        }

        /* Loading */
        @keyframes shimmer {
            from {
                background-position: -500px 0;
            }

            to {
                background-position: 500px 0;
            }
        }

        .shimmer {
            height: 14px;
            border-radius: 4px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e4e4e4 50%, #f0f0f0 75%);
            background-size: 500px 100%;
            animation: shimmer 1s ease-in-out infinite;
            display: block;
        }

        /* Modal Popup */
        .app-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 17, 23, .45);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .app-modal-overlay.open {
            display: flex;
        }

        .app-modal {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 48px rgba(0, 0, 0, .18);
            padding: 1.75rem;
            width: 100%;
            max-width: 380px;
            margin: 1rem;
        }

        .modal-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .modal-icon .icon {
            width: 22px;
            height: 22px;
        }

        .modal-icon.success {
            background: var(--green-bg);
        }

        .modal-icon.success .icon {
            stroke: var(--green);
        }

        .modal-icon.danger {
            background: var(--red-bg);
        }

        .modal-icon.danger .icon {
            stroke: var(--red);
        }

        /* For the filled 50x50 modal icons */
        .modal-icon .icon-fill {
            width: 22px;
            height: 22px;
            fill: currentColor;
            stroke: none;
        }

        .modal-icon.success .icon-fill {
            color: var(--green);
        }

        .modal-icon.danger .icon-fill {
            color: var(--red);
        }

        .modal-title {
            text-align: center;
            font-size: 1rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .modal-sub {
            text-align: center;
            font-size: 0.8rem;
            color: var(--ink-3);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            gap: 8px;
        }

        .modal-btn {
            flex: 1;
            padding: .55rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: opacity .15s, background .15s;
            border: none;
        }

        .modal-btn.cancel {
            background: var(--surface);
            border: 1px solid var(--border-2);
            color: var(--ink-2);
        }

        .modal-btn.cancel:hover {
            background: var(--surface-2);
        }

        .modal-btn.confirm-green {
            background: var(--green);
            color: #fff;
        }

        .modal-btn.confirm-red {
            background: var(--red);
            color: #fff;
        }

        .modal-btn.confirm-green:hover,
        .modal-btn.confirm-red:hover {
            opacity: .88;
        }

        /* animasi Notifikasi  */
        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(.75);
            }
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-dot 1.6s ease-in-out infinite;
        }
    </style>
</head>

<body>

    {{-- 
     SVG SPRITE — All icons centralized here, hidden from view.
     Usage: <svg class="icon icon-md"><use href="#icon-name"></use></svg>
      --}}
    <svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">

        {{-- Calendar / Brand --}}
        <symbol id="icon-calendar" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </symbol>

        {{-- Home / Dashboard --}}
        <symbol id="icon-home" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M 12 2.0996094 L 1 12 L 4 12 L 4 21 L 11 21 L 11 15 L 13 15 L 13 21 L 20 21 L 20 12 L 23 12 L 12 2.0996094 z M 12 4.7910156 L 18 10.191406 L 18 11 L 18 19 L 15 19 L 15 13 L 9 13 L 9 19 L 6 19 L 6 10.191406 L 12 4.7910156 z">
            </path>
        </symbol>

        {{-- Users (group) --}}
        <symbol id="icon-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </symbol>

        {{-- User (single) --}}
        <symbol id="icon-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
        </symbol>

        {{-- File-text (perizinan sidebar) --}}
        <symbol id="icon-file-text" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
            <line x1="9" y1="13" x2="15" y2="13" />
            <line x1="9" y1="17" x2="15" y2="17" />
        </symbol>

        {{-- File (export / attachment — no lines inside) --}}
        <symbol id="icon-file" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
        </symbol>

        {{-- Bar chart (laporan sidebar) --}}
        <symbol id="icon-bar-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10" />
            <line x1="12" y1="20" x2="12" y2="4" />
            <line x1="6" y1="20" x2="6" y2="14" />
        </symbol>

        {{-- Settings (gear) --}}
        <symbol id="icon-settings" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3" />
            <path
                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
        </symbol>

        {{-- Log out --}}
        <symbol id="icon-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
        </symbol>

        {{-- Check-circle (success flash, no-late empty, perizinan empty) --}}
        <symbol id="icon-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 12l2 2 4-4" />
            <circle cx="12" cy="12" r="10" />
        </symbol>

        {{-- Alert-circle / Info-circle (error flash, shift no-data, empty states) --}}
        <symbol id="icon-alert-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
        </symbol>

        {{-- Clock (shift icon in home) --}}
        <symbol id="icon-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </symbol>

        {{-- Check / tick (shift done badge, approve button) --}}
        <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </symbol>

        {{-- Plus (add employee button) --}}
        <symbol id="icon-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </symbol>

        {{-- Search / magnifier --}}
        <symbol id="icon-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </symbol>

        {{-- Close / X --}}
        <symbol id="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
        </symbol>

        {{-- Grid --}}
        <symbol id="icon-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" />
            <rect x="14" y="3" width="7" height="7" />
            <rect x="3" y="14" width="7" height="7" />
            <rect x="14" y="14" width="7" height="7" />
        </symbol>

        {{-- Modal check / approve --}}
        <symbol id="icon-modal-check" viewBox="0 0 50 50" fill="currentColor">
            <path d="M 42.875 8.625 C 42.84375 8.632813 42.8125 8.644531 42.78125 8.65625
                 C 42.519531 8.722656 42.292969 8.890625 42.15625 9.125
                 L 21.71875 40.8125 L 7.65625 28.125
                 C 7.410156 27.8125 7 27.675781 6.613281 27.777344
                 C 6.226563 27.878906 5.941406 28.203125 5.882813 28.597656
                 C 5.824219 28.992188 6.003906 29.382813 6.34375 29.59375
                 L 21.25 43.09375
                 C 21.46875 43.285156 21.761719 43.371094 22.050781 43.328125
                 C 22.339844 43.285156 22.59375 43.121094 22.75 42.875
                 L 43.84375 10.1875
                 C 44.074219 9.859375 44.085938 9.425781 43.875 9.085938
                 C 43.664063 8.746094 43.269531 8.566406 42.875 8.625 Z" />
        </symbol>

        {{-- Modal close / reject --}}
        <symbol id="icon-modal-close" viewBox="0 0 50 50" fill="currentColor">
            <path d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25
                 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375
                 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25
                 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z" />
        </symbol>

        {{-- Clock --}}
        <symbol id="icon-clock" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
            <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" />
        </symbol>

        {{-- Setting --}}
        <symbol id="icon-settings" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33
                 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51
                 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06
                 a1.65 1.65 0 0 0 .33-1.82
                 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09
                 a1.65 1.65 0 0 0 1.51-1
                 1.65 1.65 0 0 0-.33-1.82l-.06-.06
                 a2 2 0 1 1 2.83-2.83l.06.06
                 a1.65 1.65 0 0 0 1.82.33h.01
                 a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09
                 a1.65 1.65 0 0 0 1 1.51
                 1.65 1.65 0 0 0 1.82-.33l.06-.06
                 a2 2 0 1 1 2.83 2.83l-.06.06
                 a1.65 1.65 0 0 0-.33 1.82v.01
                 a1.65 1.65 0 0 0 1.51 1H21
                 a2 2 0 1 1 0 4h-.09
                 a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" />
        </symbol>

    </svg>

    <div class="app-shell">

        {{-- Sidebar --}}
        <aside class="sidebar">

            {{-- Brand --}}
            <a href="{{ route('admin.home') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-calendar"></use>
                    </svg>
                </div>
                <div>
                    <div class="sidebar-brand-name">Absensi App</div>
                    <div class="sidebar-brand-sub">Admin Panel</div>
                </div>
            </a>

            {{-- Nav --}}
            <nav class="sidebar-nav pt-2">
                <div class="sidebar-label">Menu</div>

                <a href="{{ route('admin.home') }}"
                    class="sidebar-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-home"></use>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-users"></use>
                    </svg>
                    Karyawan
                </a>

                <a href="{{ route('admin.perizinan.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.perizinan.*') ? 'active' : '' }}">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-file-text"></use>
                    </svg>
                    Perizinan
                </a>

                <a href="{{ route('admin.laporan.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-bar-chart"></use>
                    </svg>
                    Laporan
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-settings"></use>
                    </svg>
                    Pengaturan
                </a>
            </nav>

            {{-- Footer: user info + logout --}}
            <div class="sidebar-footer">
                @auth
                    <div class="sidebar-user">
                        <div class="sidebar-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div style="overflow:hidden">
                            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                            <div class="sidebar-user-role">Administrator</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-logout">
                            <svg class="icon" aria-hidden="true">
                                <use href="#icon-logout"></use>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>

        </aside>

        {{-- Main Content --}}
        <main class="app-content">
            <div class="app-inner">

                {{-- Flash messages global --}}
                @if (session('success'))
                    <div class="app-alert success mb-3">
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-check-circle"></use>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="app-alert error mb-3">
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-alert-circle"></use>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
