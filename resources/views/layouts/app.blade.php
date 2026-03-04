<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            background: radial-gradient(circle at top, #0f172a, #020617);
            color: #e5e7eb;
            font-family: 'Inter', system-ui, sans-serif;
            overflow-x: hidden;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 24px 20px;
            background: linear-gradient(180deg, #020617, #020617);
            box-shadow:
                inset -1px 0 0 rgba(34,197,94,.25),
                0 0 40px rgba(34,197,94,.25);
            z-index: 100;
        }

        /* BRAND */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
        }

        .sidebar-brand img {
            width: 28px;
            height: 28px;
            object-fit: contain;
            display: block;
        }

        .sidebar-brand span {
            font-size: 22px; /* ⬅️ DIGEDEIN LAGI */
            font-weight: 800;
            color: #4ade80;
            letter-spacing: .8px;
            line-height: 1;
            text-shadow:
                0 0 10px rgba(34,197,94,.6),
                0 0 20px rgba(34,197,94,.35);
        }

        /* NAV */
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #cbd5f5;
            font-size: 15px;
            transition: .25s ease;
        }

        .sidebar .nav-link:hover {
            color: #4ade80;
            background: linear-gradient(
                90deg,
                rgba(34,197,94,.18),
                rgba(15,23,42,.7)
            );
            box-shadow:
                inset 0 0 12px rgba(34,197,94,.35),
                0 0 12px rgba(34,197,94,.35);
        }

        .sidebar .nav-link.active {
            color: #4ade80;
            background: linear-gradient(
                90deg,
                rgba(34,197,94,.25),
                rgba(15,23,42,.9)
            );
            box-shadow:
                inset 0 0 15px rgba(34,197,94,.5),
                0 0 18px rgba(34,197,94,.4);
        }

        /* ================= CONTENT ================= */
        .content {
            margin-left: 260px;
            min-height: 100vh;
            width: calc(100% - 260px);
            padding: 32px 40px;
            overflow-y: auto;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(34,197,94,.4);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo">
            <span>SkodePresent</span>
        </div>

        <ul class="nav flex-column gap-2">
            <li>
                <a href="{{ route('admin.home') }}"
                   class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    Home
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    Users
                </a>
            </li>

            <li>
                <a href="{{ route('admin.perizinan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.perizinan.*') ? 'active' : '' }}">
                    Perizinan
                </a>
            </li>

            <li>
                <a href="{{ route('admin.laporan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    Laporan
                </a>
            </li>

            <li>
                <a href="{{ route('admin.settings.index') }}"
                   class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    Settings
                </a>
            </li>
        </ul>
    </aside>

    {{-- CONTENT --}}
    <main class="content">
        @yield('content')
        @yield('scripts')
    </main>

</body>
</html>
