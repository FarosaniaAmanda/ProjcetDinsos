<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Password</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            min-height: 100vh;

            font-family: "Segoe UI", Arial, sans-serif;

            background: #f4f7fb;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
        }


        .forgot-container {
            width: 420px;

            background: white;

            padding: 35px 40px;

            border-radius: 16px;

            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.10);
        }


        h1 {
            text-align: center;

            color: #182b63;

            font-size: 26px;

            margin-bottom: 8px;
        }


        .description {
            text-align: center;

            color: #777;

            font-size: 13px;

            line-height: 1.5;

            margin-bottom: 25px;
        }


        label {
            display: block;

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 7px;

            color: #222;
        }


        input {
            width: 100%;

            height: 46px;

            border: 1px solid #d9dce3;

            border-radius: 8px;

            padding: 0 14px;

            outline: none;

            font-size: 13px;
        }


        input:focus {
            border-color: #243b8f;

            box-shadow:
                0 0 0 3px rgba(36, 59, 143, .08);
        }


        .btn {
            width: 100%;

            height: 46px;

            margin-top: 20px;

            border: none;

            border-radius: 8px;

            background: #243b8f;

            color: white;

            font-weight: 600;

            cursor: pointer;
        }


        .btn:hover {
            background: #1b2e73;
        }


        .back {
            display: block;

            text-align: center;

            margin-top: 18px;

            font-size: 12px;

            color: #243b8f;

            text-decoration: none;
        }


        .error {
            background: #fff1f1;

            border: 1px solid #f3b5b5;

            color: #c62828;

            padding: 10px;

            border-radius: 8px;

            font-size: 12px;

            margin-bottom: 15px;

            text-align: center;
        }


        .success {
            background: #effaf1;

            border: 1px solid #b9dfc0;

            color: #2e7d32;

            padding: 10px;

            border-radius: 8px;

            font-size: 12px;

            margin-bottom: 15px;

            text-align: center;
        }

    </style>

</head>


<body>


    <div class="forgot-container">

        <h1>Lupa Password?</h1>

        <p class="description">
            Masukkan email yang terdaftar pada akun kamu.
            Kami akan mengirimkan link untuk membuat password baru.
        </p>


        @if ($errors->any())

            <div class="error">
                {{ $errors->first() }}
            </div>

        @endif


        @if (session('success'))

            <div class="success">
                {{ session('success') }}
            </div>

        @endif


        <form
            method="POST"
            action="{{ route('password.email') }}"
        >

            @csrf


            <label for="email">
                Email
            </label>


            <input
                type="email"
                id="email"
                name="email"
                placeholder="Masukkan email"
                value="{{ old('email') }}"
                required
                autofocus
            >


            <button
                type="submit"
                class="btn"
            >
                Kirim Link Reset Password
            </button>

        </form>


        <a
            href="{{ route('login') }}"
            class="back"
        >
            ← Kembali ke Login
        </a>


    </div>


</body>

</html>