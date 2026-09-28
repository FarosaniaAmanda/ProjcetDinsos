<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Sistem</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            min-height: 100vh;

            font-family: "Segoe UI", Arial, sans-serif;

            background: #ffffff;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;

            overflow: hidden;

            position: relative;
        }


        /* =================================
           LOGO PEMERINTAH
        ================================= */

        .bg-logo {
            position: fixed;

            width: 1450px;
            height: 1450px;

            max-width: 135vw;
            max-height: 135vh;

            object-fit: contain;

            left: 50%;
            top: 50%;

            transform: translate(-50%, -50%);

            opacity: 0.20;

            z-index: 1;

            pointer-events: none;
            user-select: none;
        }


        /* =================================
           LOGIN CONTAINER
        ================================= */

        .login-container {
            position: relative;

            z-index: 5;

            width: 420px;

            background: rgba(255, 255, 255, 0.97);

            border-radius: 16px;

            padding: 32px 40px 28px;

            border: 1px solid rgba(220, 220, 220, 0.8);

            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.10);
        }


        /* =================================
           HEADER
        ================================= */

        .login-header {
            text-align: center;

            margin-bottom: 25px;
        }


        .login-header h1 {
            font-size: 27px;

            font-weight: 700;

            color: #182b63;

            margin-bottom: 7px;
        }


        .login-header p {
            font-size: 12.5px;

            color: #777;

            line-height: 1.5;
        }


        /* =================================
           ERROR
        ================================= */

        .login-error {
            background: #fff1f1;

            border: 1px solid #f3b5b5;

            color: #c62828;

            padding: 10px 12px;

            border-radius: 8px;

            font-size: 12px;

            margin-bottom: 15px;

            text-align: center;
        }


        /* =================================
           SUCCESS
        ================================= */

        .login-success {
            background: #effaf1;

            border: 1px solid #b9dfc0;

            color: #2e7d32;

            padding: 10px 12px;

            border-radius: 8px;

            font-size: 12px;

            margin-bottom: 15px;

            text-align: center;
        }


        /* =================================
           FORM
        ================================= */

        .form-group {
            margin-bottom: 17px;
        }


        .form-group label {
            display: block;

            font-size: 13px;

            font-weight: 600;

            color: #222;

            margin-bottom: 7px;
        }


        /* =================================
           INPUT WRAPPER
        ================================= */

        .input-wrapper {
            position: relative;
        }


        .input-wrapper svg.input-icon {
            position: absolute;

            left: 13px;
            top: 50%;

            transform: translateY(-50%);

            width: 17px;
            height: 17px;

            stroke: #777;

            pointer-events: none;
        }


        .input-wrapper input {
            width: 100%;

            height: 46px;

            padding: 0 45px 0 42px;

            border: 1px solid #d9dce3;

            border-radius: 8px;

            outline: none;

            background: #ffffff;

            color: #222;

            font-size: 13px;

            transition: .25s ease;
        }


        .input-wrapper input::placeholder {
            color: #a5a9b2;
        }


        .input-wrapper input:focus {
            border-color: #243b8f;

            box-shadow:
                0 0 0 3px rgba(36, 59, 143, 0.08);
        }


        /* =================================
           MATA PASSWORD
        ================================= */

        .toggle-password {
            position: absolute;

            right: 13px;
            top: 50%;

            transform: translateY(-50%);

            width: 22px;
            height: 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;

            background: transparent;

            padding: 0;

            cursor: pointer;

            color: #777;

            opacity: 0.70;

            transition: .2s ease;
        }


        .toggle-password:hover {
            opacity: 1;

            color: #555;
        }


        .toggle-password svg {
            width: 18px;
            height: 18px;

            stroke: currentColor;

            fill: none;

            stroke-width: 1.7;

            stroke-linecap: round;

            stroke-linejoin: round;
        }


        /* =================================
           LUPA PASSWORD
        ================================= */

        .forgot-wrapper {
            display: flex;

            justify-content: flex-end;

            margin-top: -3px;

            margin-bottom: 20px;
        }


        .forgot-wrapper a {
            text-decoration: none;

            font-size: 11.5px;

            color: #243b8f;

            font-weight: 600;

            transition: .2s ease;
        }


        .forgot-wrapper a:hover {
            text-decoration: underline;

            color: #1b2e73;
        }


        /* =================================
           BUTTON LOGIN
        ================================= */

        .login-btn {
            width: 100%;

            height: 46px;

            border: none;

            border-radius: 8px;

            background: #243b8f;

            color: #ffffff;

            font-size: 13px;

            font-weight: 600;

            cursor: pointer;

            transition: .25s ease;
        }


        .login-btn:hover {
            background: #1b2e73;

            transform: translateY(-1px);

            box-shadow:
                0 7px 17px rgba(36, 59, 143, 0.20);
        }


        .login-btn:active {
            transform: translateY(0);
        }


        /* =================================
           FOOTER
        ================================= */

        .login-footer {
            text-align: center;

            margin-top: 18px;

            font-size: 10.5px;

            color: #999;
        }


        /* =================================
           TABLET
        ================================= */

        @media (max-width: 700px) {

            .bg-logo {
                width: 1050px;
                height: 1050px;

                max-width: 150vw;
                max-height: 150vh;

                opacity: 0.11;
            }


            .login-container {
                width: 400px;

                max-width: 92vw;

                padding: 30px 30px 25px;
            }

        }


        /* =================================
           HP
        ================================= */

        @media (max-width: 450px) {

            body {
                padding: 15px;
            }


            .bg-logo {
                width: 800px;
                height: 800px;

                max-width: 170vw;
                max-height: 170vh;

                opacity: 0.10;
            }


            .login-container {
                width: 100%;

                padding: 28px 24px 24px;

                border-radius: 15px;
            }


            .login-header h1 {
                font-size: 24px;
            }

        }

    </style>

</head>


<body>


    {{-- =================================
         LOGO PEMERINTAH
    ================================== --}}

    <img
        src="{{ asset('images/logopemerintah.png') }}"
        alt="Logo Pemerintah"
        class="bg-logo"
    >


    {{-- =================================
         LOGIN
    ================================== --}}

    <div class="login-container">


        {{-- HEADER --}}

        <div class="login-header">

            <h1>Login Sistem</h1>

            <p>
                Silakan masuk untuk mengakses panel administrasi.
            </p>

        </div>


        {{-- =================================
             ERROR LOGIN
        ================================== --}}

        @if ($errors->any())

            <div class="login-error">

                {{ $errors->first() }}

            </div>

        @endif


        {{-- =================================
             SUCCESS
        ================================== --}}

        @if (session('success'))

            <div class="login-success">

                {{ session('success') }}

            </div>

        @endif


        {{-- =================================
             FORM LOGIN
        ================================== --}}

        <form
            method="POST"
            action="{{ route('login.process') }}"
        >

            @csrf


            {{-- ID --}}

            <div class="form-group">

                <label for="id">
                    ID
                </label>


                <div class="input-wrapper">


                    {{-- ICON USER --}}

                    <svg
                        class="input-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <path
                            d="M20 21a8 8 0 0 0-16 0"
                        ></path>

                        <circle
                            cx="12"
                            cy="7"
                            r="4"
                        ></circle>

                    </svg>


                    <input
                        type="text"
                        id="id"
                        name="id"
                        placeholder="Masukkan ID"
                        value="{{ old('id') }}"
                        required
                        autofocus
                    >

                </div>

            </div>


            {{-- PASSWORD --}}

            <div class="form-group">

                <label for="password">
                    Password
                </label>


                <div class="input-wrapper">


                    {{-- ICON LOCK --}}

                    <svg
                        class="input-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <rect
                            x="3"
                            y="11"
                            width="18"
                            height="10"
                            rx="2"
                        ></rect>

                        <path
                            d="M7 11V7a5 5 0 0 1 10 0v4"
                        ></path>

                    </svg>


                    {{-- INPUT PASSWORD --}}

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >


                    {{-- TOMBOL MATA --}}

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        title="Tampilkan password"
                    >

                        <svg
                            id="eyeIcon"
                            viewBox="0 0 24 24"
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

            </div>


            {{-- =================================
                 LUPA PASSWORD
            ================================== --}}

            <div class="forgot-wrapper">

                <a href="{{ route('password.request') }}">
                    Lupa Password?
                </a>

            </div>


            {{-- =================================
                 BUTTON LOGIN
            ================================== --}}

            <button
                type="submit"
                class="login-btn"
            >

                Masuk →

            </button>


        </form>


        {{-- FOOTER --}}

        <div class="login-footer">

            Sistem Pendataan Perlinsos Kota Pasuruan

        </div>


    </div>


    {{-- =================================
         JAVASCRIPT SHOW / HIDE PASSWORD
    ================================== --}}

    <script>

        const passwordInput =
            document.getElementById('password');

        const togglePassword =
            document.getElementById('togglePassword');

        const eyeIcon =
            document.getElementById('eyeIcon');


        togglePassword.addEventListener('click', function () {

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';


                togglePassword.setAttribute(
                    'aria-label',
                    'Sembunyikan password'
                );


                togglePassword.setAttribute(
                    'title',
                    'Sembunyikan password'
                );


                /* MATA TERBUKA */

                eyeIcon.innerHTML = `

                    <path
                        d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                    ></path>

                    <circle
                        cx="12"
                        cy="12"
                        r="2.5"
                    ></circle>

                `;

            } else {

                passwordInput.type = 'password';


                togglePassword.setAttribute(
                    'aria-label',
                    'Tampilkan password'
                );


                togglePassword.setAttribute(
                    'title',
                    'Tampilkan password'
                );


                /* MATA TERTUTUP */

                eyeIcon.innerHTML = `

                    <path
                        d="M3 3l18 18"
                    ></path>

                    <path
                        d="M10.6 6.2A10.8 10.8 0 0 1 12 6c6.5 0 10 6 10 6a18.5 18.5 0 0 1-3.2 3.8"
                    ></path>

                    <path
                        d="M6.1 6.1C3.5 8 2 12 2 12s3.5 6 10 6c1.5 0 2.8-.3 4-.8"
                    ></path>

                    <path
                        d="M9.9 9.9a3 3 0 0 0 4.2 4.2"
                    ></path>

                `;

            }

        });

    </script>


</body>

</html>