@php
    $authenticatedUser = auth()->user();
    $authenticatedName = trim((string) ($authenticatedUser?->name ?? ''))
        ?: 'Nama tidak tersedia';
    $authenticatedRole = ucfirst($authenticatedUser?->role ?: 'pengguna');
    $authenticatedInitial = strtoupper(substr($authenticatedName, 0, 1));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pendataan Dinas Sosial Kota Pasuruan')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
        }

        body {
            min-height: 100vh;
            background: #f5f6fa;
            color: #252525;
            font-family: Arial, Helvetica, sans-serif;
        }

        .role-page-header {
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
            min-height: 75px;
            padding: 12px 24px;
            border-bottom: 1px solid #e8e9ef;
            background: #fff;
        }

        .role-page-brand {
            color: #252a86;
            font-size: 16px;
            font-weight: 700;
        }

        .role-page-subtitle {
            margin-top: 3px;
            color: #888;
            font-size: 12px;
        }

        .role-page-account {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .role-page-profile {
            color: inherit;
            text-align: right;
            text-decoration: none;
        }

        .role-page-name {
            font-size: 13px;
            font-weight: 700;
        }

        .role-page-role {
            margin-top: 2px;
            color: #888;
            font-size: 11px;
        }

        .role-page-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #252a86;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .role-page-logout {
            padding: 8px 12px;
            border: 1px solid #dfe1e8;
            border-radius: 7px;
            background: #fff;
            color: #555;
            font: inherit;
            font-size: 12px;
            cursor: pointer;
        }

        .role-page-logout:hover {
            border-color: #252a86;
            color: #252a86;
        }

        .role-page-content {
            width: 100%;
            min-width: 0;
        }

        @media (max-width: 600px) {
            .role-page-header {
                padding: 12px 15px;
            }

            .role-page-brand {
                font-size: 13px;
            }

            .role-page-account {
                gap: 9px;
            }

            .role-page-profile {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="role-page-header">
        <div>
            <div class="role-page-brand">Sistem Pendataan Perlindungan Dinas Sosial Kota Pasuruan</div>
            <div class="role-page-subtitle">Panel {{ $authenticatedRole }}</div>
        </div>

        <div class="role-page-account">
            <a href="{{ route('profil.index') }}" class="role-page-profile">
                <div class="role-page-name">{{ $authenticatedName }}</div>
                <div class="role-page-role">{{ $authenticatedRole }}</div>
            </a>
            <a
                href="{{ route('profil.index') }}"
                class="role-page-avatar"
                aria-label="Buka profil {{ $authenticatedName }}"
            >{{ $authenticatedInitial }}</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="role-page-logout">Keluar</button>
            </form>
        </div>
    </header>

    <main class="role-page-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
