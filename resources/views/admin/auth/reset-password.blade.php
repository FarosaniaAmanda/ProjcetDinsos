<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password - Sistem Perlinsos</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f7fb;
            padding: 20px;
        }

        /* =========================
           CARD
        ========================= */

        .reset-box {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        /* =========================
           HEADER
        ========================= */

        .reset-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .reset-header h2 {
            color: #24459b;
            font-size: 25px;
            margin-bottom: 10px;
        }

        .reset-header p {
            color: #5f6f86;
            font-size: 14px;
            line-height: 1.6;
        }

        /* =========================
           FORM
        ========================= */

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #222;
        }

        .form-group input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d5dbe5;
            border-radius: 9px;
            outline: none;
            font-size: 14px;
            color: #222;
            background: #ffffff;
            transition: 0.2s ease;
        }

        .form-group input:focus {
            border-color: #29469b;
            box-shadow: 0 0 0 3px rgba(41, 70, 155, 0.10);
        }

        /* Email */
        .form-group input[readonly] {
            background: #ffffff;
            cursor: default;
        }

        /* =========================
           PASSWORD
        ========================= */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: #718096;
            opacity: 0.7;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #29469b;
            opacity: 1;
        }

        /* =========================
           ERROR
        ========================= */

        .error {
            color: #d93025;
            font-size: 13px;
            margin-bottom: 15px;
        }

        /* =========================
           BUTTON
        ========================= */

        .btn-reset {
            width: 100%;
            border: none;
            padding: 13px;
            border-radius: 9px;
            background: #29469b;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #1e3780;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(41, 70, 155, 0.20);
        }

        /* =========================
           KEMBALI LOGIN
        ========================= */

        .back-login {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #29469b;
            text-decoration: none;
            font-size: 14px;
        }

        .back-login:hover {
            color: #1e3780;
            text-decoration: underline;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 500px) {

            .reset-box {
                padding: 28px 22px;
            }

            .reset-header h2 {
                font-size: 23px;
            }

            .reset-header p {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <div class="reset-box">

        <div class="reset-header">

            <h2>Reset Password</h2>

            <p>
                Silakan masukkan password baru untuk akun Anda.
            </p>

        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">

            @csrf

            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >

            <!-- EMAIL -->

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ $email }}"
                    readonly
                >

            </div>


            <!-- PASSWORD BARU -->

            <div class="form-group">

                <label for="password">
                    Password Baru
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password baru"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password', this)"
                        aria-label="Tampilkan password"
                    >
                        👁
                    </button>

                </div>

            </div>


            <!-- KONFIRMASI PASSWORD -->

            <div class="form-group">

                <label for="password_confirmation">
                    Konfirmasi Password Baru
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password baru"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password_confirmation', this)"
                        aria-label="Tampilkan password"
                    >
                        👁
                    </button>

                </div>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                class="btn-reset"
            >
                Simpan Password Baru
            </button>

        </form>


        <!-- KEMBALI -->

        <a
            href="{{ route('login') }}"
            class="back-login"
        >
            ← Kembali ke Login
        </a>

    </div>


    <script>

        function togglePassword(id, button) {

            const input = document.getElementById(id);

            if (input.type === "password") {

                input.type = "text";
                button.textContent = "👁";

            } else {

                input.type = "password";
                button.textContent = "👁";

            }

        }

    </script>

</body>

</html>