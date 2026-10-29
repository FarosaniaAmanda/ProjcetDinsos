<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Sistem | Dinsos Kota Pasuruan</title>

    <meta name="theme-color" content="#243b8f">
    <meta name="description" content="Login Sistem Pendataan Perlindungan Sosial Kota Pasuruan">

    <style>
        /* =========================================
           RESET DAN VARIABEL
        ========================================= */

        :root {
            --primary: #243b8f;
            --primary-dark: #1b2e73;
            --text: #222;
            --muted: #777;
            --border: #d9dce3;
            --surface: #ffffff;
            --danger: #c62828;
            --success: #2e7d32;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            -webkit-text-size-adjust: 100%;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;

            font-family: "Segoe UI", Arial, sans-serif;
            background: #ffffff;
            color: var(--text);

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 24px;

            position: relative;
            isolation: isolate;

            overflow-x: hidden;
            overflow-y: auto;
        }

        button,
        input {
            font: inherit;
        }

        button {
            -webkit-tap-highlight-color: transparent;
        }

        /* =========================================
           BACKGROUND LOGO - DEFAULT LAPTOP
           Laptop tetap landscape
        ========================================= */

        .bg-logo {
            position: fixed;

            width: 100vw;
            height: 100vh;

            left: 50%;
            top: 50%;

            transform: translate(-50%, -50%);

            object-fit: cover;
            object-position: center;

            opacity: 0.20;

            z-index: -1;

            pointer-events: none;
            user-select: none;
        }

        /* =========================================
           CONTAINER LOGIN - DEFAULT LAPTOP
        ========================================= */

        .login-container {
            position: relative;
            z-index: 1;

            width: 100%;
            max-width: 420px;

            background: rgba(255, 255, 255, 0.97);

            border: 1px solid rgba(220, 220, 220, 0.85);
            border-radius: 16px;

            padding: 34px 38px 28px;

            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.10);

            animation: cardEnter 0.55s ease both;
        }

        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================================
           HEADER
        ========================================= */

        .login-header {
            text-align: center;
            margin-bottom: 26px;
        }

        .login-header h1 {
            font-size: 27px;
            line-height: 1.3;
            font-weight: 700;
            color: #182b63;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* =========================================
           PESAN ERROR DAN SUKSES
        ========================================= */

        .login-error,
        .login-success {
            padding: 12px 13px;
            border-radius: 8px;

            font-size: 12.5px;
            line-height: 1.6;

            margin-bottom: 17px;

            text-align: left;
            overflow-wrap: anywhere;

            animation: messageEnter 0.25s ease both;
        }

        .login-error {
            background: #fff1f1;
            border: 1px solid #f3b5b5;
            color: var(--danger);
        }

        .login-success {
            background: #effaf1;
            border: 1px solid #b9dfc0;
            color: var(--success);
        }

        @keyframes messageEnter {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================================
           FORM
        ========================================= */

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;

            font-size: 13px;
            font-weight: 600;
            color: #222;

            margin-bottom: 8px;
        }

        /* =========================================
           INPUT DAN IKON
        ========================================= */

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-icon {
            position: absolute;

            left: 13px;
            top: 50%;

            transform: translateY(-50%);

            width: 18px;
            height: 18px;

            stroke: #777;
            fill: none;

            pointer-events: none;

            transition: stroke 0.2s ease;
        }

        .input-wrapper input {
            display: block;

            width: 100%;
            height: 47px;

            padding: 0 45px 0 42px;

            border: 1px solid var(--border);
            border-radius: 8px;

            outline: none;

            background: var(--surface);
            color: #222;

            font-size: 13px;
            line-height: normal;

            transition:
                border-color 0.22s ease,
                box-shadow 0.22s ease,
                background 0.22s ease;
        }

        .input-wrapper input::placeholder {
            color: #a5a9b2;
            opacity: 1;
        }

        .input-wrapper:focus-within .input-icon {
            stroke: var(--primary);
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(36, 59, 143, 0.10);
        }

        /* =========================================
           TOMBOL TAMPIL / SEMBUNYIKAN PASSWORD
        ========================================= */

        .toggle-password {
            position: absolute;

            right: 11px;
            top: 50%;

            transform: translateY(-50%);

            width: 30px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 6px;

            background: transparent;

            padding: 0;
            color: #777;

            cursor: pointer;

            transition:
                color 0.2s ease,
                background 0.2s ease;
        }

        .toggle-password:hover {
            color: var(--primary);
            background: #f1f3fb;
        }

        .toggle-password:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .toggle-password svg {
            width: 19px;
            height: 19px;

            stroke: currentColor;
            fill: none;

            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* =========================================
           TOMBOL LOGIN
        ========================================= */

        .login-btn {
            width: 100%;
            min-height: 47px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            margin-top: 5px;
            padding: 12px 16px;

            border: none;
            border-radius: 8px;

            background: var(--primary);
            color: #ffffff;

            font-size: 13.5px;
            font-weight: 600;

            cursor: pointer;

            box-shadow: 0 4px 10px rgba(36, 59, 143, 0.10);

            transition:
                background 0.22s ease,
                transform 0.22s ease,
                box-shadow 0.22s ease,
                opacity 0.22s ease;
        }

        .login-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);

            box-shadow: 0 7px 17px rgba(36, 59, 143, 0.20);
        }

        .login-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .login-btn:focus-visible {
            outline: 3px solid rgba(36, 59, 143, 0.30);
            outline-offset: 3px;
        }

        .login-btn:disabled {
            cursor: wait;
            opacity: 0.78;
        }

        .button-spinner {
            display: none;

            width: 16px;
            height: 16px;

            border: 2px solid rgba(255, 255, 255, 0.40);
            border-top-color: #ffffff;

            border-radius: 50%;

            animation: spin 0.7s linear infinite;
        }

        .login-btn.is-loading .button-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* =========================================
           FOOTER
        ========================================= */

        .login-footer {
            text-align: center;
            margin-top: 20px;

            font-size: 10.5px;
            line-height: 1.6;

            color: #999;
        }

        /* =========================================
           TABLET
        ========================================= */

        @media screen and (min-width: 451px) and (max-width: 700px) {
            body {
                padding: 22px;
            }

            .bg-logo {
                width: 100vw;
                height: 100dvh;

                object-fit: contain;
                object-position: center;

                opacity: 0.18;
            }

            .login-container {
                width: 100%;
                max-width: 400px;

                padding: 31px 30px 26px;
            }
        }

        @media screen and (max-width: 450px) and (orientation: portrait) {
            html,
            body {
                width: 100%;
                min-height: 100%;
            }

            body {
                min-height: 100vh;
                min-height: 100dvh;
                padding: 20px 16px;
                align-items: center;
                justify-content: center;
                overflow-x: hidden;
                overflow-y: auto;
            }

            .bg-logo {
            position: fixed;
            inset: 0;

            width: 100%;
            height: 100%;
            max-width: none;
            max-height: none;

            object-fit: cover;
            object-position: 45% center;

            opacity: 0.35;
            transform: scale(1.15);
            transform-origin: 35% center;

            pointer-events: none;
            user-select: none;
        }

            .login-container {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 370px;
                padding: 25px 22px 22px;
                border-radius: 16px;
                margin: 0 auto;
                background: rgba(255, 255, 255, 0.97);
            }

            .login-header {
                margin-bottom: 22px;
            }

            .login-header h1 {
                font-size: 24px;
                line-height: 1.3;
                margin-bottom: 7px;
            }

            .login-header p {
                font-size: 12px;
                line-height: 1.6;
            }

            .login-error,
            .login-success {
                padding: 11px 12px;
                font-size: 12px;
                margin-bottom: 15px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .form-group label {
                font-size: 13px;
                margin-bottom: 7px;
            }

            .input-wrapper input {
                width: 100%;
                height: 48px;
                font-size: 13px;
                padding-left: 42px;
                padding-right: 43px;
            }

            .input-icon {
                width: 18px;
                height: 18px;
                left: 12px;
            }

            .toggle-password {
                right: 7px;
                width: 30px;
                height: 34px;
            }

            .toggle-password svg {
                width: 19px;
                height: 19px;
            }

            .login-btn {
                width: 100%;
                min-height: 48px;
                font-size: 13px;
                padding: 12px 14px;
            }

            .login-footer {
                margin-top: 17px;
                font-size: 10px;
                line-height: 1.5;
            }
        }

        @media screen and (max-width: 900px) and (orientation: landscape) {
            body {
                padding: 16px;
            }

            .bg-logo {
                width: 100vw;
                height: 100dvh;

                object-fit: cover;
                object-position: center;

                opacity: 0.16;
            }

            .login-container {
                width: 100%;
                max-width: 380px;

                padding: 23px 25px 20px;
            }

            .login-header {
                margin-bottom: 17px;
            }

            .login-header h1 {
                font-size: 23px;
            }

            .form-group {
                margin-bottom: 13px;
            }

            .login-footer {
                margin-top: 13px;
            }
        }

        @media screen and (max-height: 500px) {
            body {
                align-items: flex-start;
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .login-container {
                margin-top: auto;
                margin-bottom: auto;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>

    {{-- LOGO PEMERINTAH --}}

    <img
        src="{{ asset('images/logopemerintah.png') }}"
        alt=""
        aria-hidden="true"
        class="bg-logo"
    >

    {{-- LOGIN CONTAINER --}}

    <main class="login-container">

        {{-- HEADER --}}

        <header class="login-header">
            <h1>Login Sistem</h1>

            <p>
                Silakan masuk untuk mengakses panel administrasi.
            </p>
        </header>

        {{-- PESAN ERROR LOGIN --}}

        @if ($errors->any())
            <div class="login-error" role="alert" aria-live="polite">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- PESAN SUKSES --}}

        @if (session('success'))
            <div class="login-success" role="status" aria-live="polite">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM LOGIN --}}

        <form
            id="loginForm"
            method="POST"
            action="{{ route('login.process') }}"
            autocomplete="on"
        >
            @csrf

            {{-- NOMOR IDENTITAS --}}

            <div class="form-group">
                <label for="nomor_identitas">
                    Nomor Identitas
                </label>

                <div class="input-wrapper">

                    <svg
                        class="input-icon"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            d="M20 21a8 8 0 0 0-16 0"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>

                        <circle
                            cx="12"
                            cy="7"
                            r="4"
                            stroke-width="2"
                        ></circle>
                    </svg>

                    <input
                        type="text"
                        id="nomor_identitas"
                        name="nomor_identitas"
                        placeholder="Masukkan nomor identitas"
                        value="{{ old('nomor_identitas') }}"
                        autocomplete="username"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        required
                        autofocus
                        @if ($errors->has('nomor_identitas'))
                            aria-invalid="true"
                            aria-describedby="identitasError"
                        @endif
                    >
                </div>

                @error('nomor_identitas')
                    <small
                        id="identitasError"
                        style="display:block;color:#c62828;margin-top:6px;font-size:12px;"
                    >
                        {{ $message }}
                    </small>
                @enderror
            </div>

            {{-- PASSWORD --}}

            <div class="form-group">
                <label for="password">
                    Password
                </label>

                <div class="input-wrapper">

                    <svg
                        class="input-icon"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <rect
                            x="3"
                            y="11"
                            width="18"
                            height="10"
                            rx="2"
                            stroke-width="2"
                        ></rect>

                        <path
                            d="M7 11V7a5 5 0 0 1 10 0v4"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                    </svg>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        required
                        @if ($errors->has('password'))
                            aria-invalid="true"
                            aria-describedby="passwordError"
                        @endif
                    >

                    {{-- TOMBOL MATA PASSWORD --}}

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                        title="Tampilkan password"
                    >
                        <svg
                            id="eyeIcon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.5"
                            ></circle>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <small
                        id="passwordError"
                        style="display:block;color:#c62828;margin-top:6px;font-size:12px;"
                    >
                        {{ $message }}
                    </small>
                @enderror
            </div>

            {{-- TOMBOL MASUK --}}

            <button
                type="submit"
                class="login-btn"
                id="loginButton"
            >
                <span class="button-spinner" aria-hidden="true"></span>
                <span id="buttonText">Masuk →</span>
            </button>

        </form>

        {{-- FOOTER --}}

        <footer class="login-footer">
            Sistem Pendataan Perlinsos Kota Pasuruan
        </footer>

    </main>

    {{-- JAVASCRIPT INTERAKTIF --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');

            let isSubmitting = false;

            /*
             * TAMPIL / SEMBUNYIKAN PASSWORD
             */

            togglePassword.addEventListener('click', function () {

                const isPasswordHidden = passwordInput.type === 'password';

                passwordInput.type = isPasswordHidden ? 'text' : 'password';

                togglePassword.setAttribute(
                    'aria-label',
                    isPasswordHidden
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );

                togglePassword.setAttribute(
                    'title',
                    isPasswordHidden
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );

                togglePassword.setAttribute(
                    'aria-pressed',
                    isPasswordHidden ? 'true' : 'false'
                );

                if (isPasswordHidden) {

                    eyeIcon.innerHTML = `
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                        <circle cx="12" cy="12" r="2.5"></circle>
                    `;

                } else {

                    eyeIcon.innerHTML = `
                        <path d="M3 3l18 18"></path>
                        <path d="M10.6 6.2A10.8 10.8 0 0 1 12 6c6.5 0 10 6 10 6a18.5 18.5 0 0 1-3.2 3.8"></path>
                        <path d="M6.1 6.1C3.5 8 2 12 2 12s3.5 6 10 6c1.5 0 2.8-.3 4-.8"></path>
                        <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"></path>
                    `;

                }
            });

            /*
             * STATUS LOADING SAAT FORM DIKIRIM
             */

            loginForm.addEventListener('submit', function (event) {

                if (isSubmitting) {
                    event.preventDefault();
                    return;
                }

                if (!loginForm.checkValidity()) {
                    return;
                }

                isSubmitting = true;

                loginButton.disabled = true;
                loginButton.classList.add('is-loading');

                buttonText.textContent = 'Memproses...';

            });

            /*
             * RESET STATUS TOMBOL SAAT HALAMAN
             * DIBUKA KEMBALI DARI CACHE BROWSER
             */

            window.addEventListener('pageshow', function () {

                isSubmitting = false;

                loginButton.disabled = false;
                loginButton.classList.remove('is-loading');

                buttonText.textContent = 'Masuk →';

            });

        });
    </script>

</body>
</html>