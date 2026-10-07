<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Ubah Password</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f7fb;
            min-height: 100vh;
        }

        .password-page {
            width: 100%;
            min-height: 100vh;
            padding: 35px 20px 60px;
        }

        .password-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .password-header h1 {
            color: #24358f;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .password-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .password-card {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 18px;

            box-shadow:
                0 10px 30px rgba(31, 50, 100, 0.08);
        }

        .password-info {
            background: #eef2ff;
            color: #263c91;

            padding: 15px 18px;

            border-radius: 10px;

            font-size: 13px;

            line-height: 1.6;

            margin-bottom: 26px;
        }

        .password-info strong {
            font-weight: 700;
        }

        .success-message {
            background: #ecfdf3;

            color: #16803c;

            border: 1px solid #c8efd8;

            padding: 13px 15px;

            border-radius: 9px;

            font-size: 13px;

            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            color: #222;

            font-size: 14px;

            font-weight: 600;

            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;

            height: 45px;

            padding:
                0 45px 0 14px;

            border:
                1px solid #d7dce5;

            border-radius: 9px;

            background: #ffffff;

            color: #333;

            font-size: 14px;

            outline: none;

            transition: 0.2s ease;
        }

        .input-wrapper input:focus {
            border-color: #29469b;

            box-shadow:
                0 0 0 3px
                rgba(41, 70, 155, 0.10);
        }

        .input-wrapper input::placeholder {
            color: #8b93a1;
        }

        .toggle-password {
            position: absolute;

            right: 13px;

            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            cursor: pointer;

            color: #7b8494;

            font-size: 15px;

            padding: 4px;
        }

        .toggle-password:hover {
            color: #29469b;
        }

        .error-message {
            color: #d93025;

            font-size: 13px;

            margin-top: 6px;
        }

        .form-divider {
            height: 1px;

            background: #eeeeee;

            margin: 25px 0;
        }

        .form-actions {
            display: flex;

            align-items: center;

            gap: 10px;
        }

        .btn-save {
            border: none;

            background: #29469b;

            color: #ffffff;

            padding: 12px 22px;

            border-radius: 9px;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;

            transition: all 0.25s ease;
        }

        .btn-save:hover {
            background: #1f3780;

            transform: translateY(-1px);

            box-shadow:
                0 5px 12px
                rgba(41, 70, 155, 0.20);
        }

        .btn-cancel {
            display: inline-block;

            text-decoration: none;

            background: #f0f1f3;

            color: #4b5563;

            padding: 12px 22px;

            border-radius: 9px;

            font-size: 14px;

            transition: all 0.25s ease;
        }

        .btn-cancel:hover {
            background: #e4e6e9;

            color: #222;
        }

        @media (max-width: 600px) {

            .password-page {
                padding:
                    25px 15px 40px;
            }

            .password-header h1 {
                font-size: 25px;
            }

            .password-card {
                padding: 22px;
            }

            .form-actions {
                flex-direction: column;

                align-items: stretch;
            }

            .btn-save,
            .btn-cancel {
                width: 100%;

                text-align: center;
            }

        }

    </style>

</head>


<body>

    <div class="password-page">


        <!-- JUDUL -->

        <div class="password-header">

            <h1>
                Ubah Password
            </h1>

            <p>
                Ganti password akun administrator Anda.
            </p>

        </div>


        <!-- CARD -->

        <div class="password-card">


            <!-- INFORMASI -->

            <div class="password-info">

                Gunakan password yang aman dan minimal terdiri dari
                <strong>8 karakter</strong>.

            </div>


            <!-- SUCCESS -->

            @if (session('success'))

                <div class="success-message">

                    {{ session('success') }}

                </div>

            @endif


            <!-- FORM -->

            <form
                method="POST"
                action="{{ route('profil.password.update') }}"
            >

                @csrf


                <!-- PASSWORD LAMA -->

                <div class="form-group">

                    <label for="password_lama">
                        Password Lama
                    </label>


                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="password_lama"
                            name="password_lama"
                            placeholder="Masukkan password lama"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword(
                                'password_lama',
                                this
                            )"
                        >
                            👁
                        </button>

                    </div>


                    @error('password_lama')

                        <div class="error-message">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- PASSWORD BARU -->

                <div class="form-group">

                    <label for="password_baru">
                        Password Baru
                    </label>


                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="password_baru"
                            name="password_baru"
                            placeholder="Masukkan password baru"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword(
                                'password_baru',
                                this
                            )"
                        >
                            👁
                        </button>

                    </div>


                    @error('password_baru')

                        <div class="error-message">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- KONFIRMASI PASSWORD -->

                <div class="form-group">

                    <label for="password_baru_confirmation">
                        Konfirmasi Password Baru
                    </label>


                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="password_baru_confirmation"
                            name="password_baru_confirmation"
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword(
                                'password_baru_confirmation',
                                this
                            )"
                        >
                            👁
                        </button>

                    </div>


                    @error('password_baru_confirmation')

                        <div class="error-message">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- PEMISAH -->

                <div class="form-divider"></div>


                <!-- TOMBOL -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-save"
                    >
                        Ubah Password
                    </button>


                    <a
                        href="{{ route('profil.index') }}"
                        class="btn-cancel"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>


    <script>

        function togglePassword(id, button) {

            const input =
                document.getElementById(id);


            if (input.type === 'password') {

                input.type = 'text';

            } else {

                input.type = 'password';

            }

        }

    </script>

</body>

</html>