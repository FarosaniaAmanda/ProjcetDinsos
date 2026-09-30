<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Link Reset Password - Sistem Perlinsos</title>

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

        .reset-link-box {
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            padding: 38px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        /* =========================
           JUDUL
        ========================= */

        .reset-link-box h2 {
            color: #24459b;
            font-size: 26px;
            margin-bottom: 18px;
        }

        /* =========================
           TEXT
        ========================= */

        .reset-link-box p {
            color: #4f5f78;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        /* =========================
           LINK RESET
        ========================= */

        .link-box {
            background: #eef4ff;
            border: 1px solid #d7e3ff;
            padding: 16px;
            border-radius: 10px;
            margin-top: 8px;
            margin-bottom: 22px;
            word-break: break-all;
        }

        .link-box a {
            color: #1f5fd1;
            font-size: 14px;
            line-height: 1.6;
            text-decoration: underline;
        }

        .link-box a:hover {
            color: #1749a3;
        }

        /* =========================
           TOMBOL
        ========================= */

        .btn-login {
            display: inline-block;
            padding: 12px 22px;
            background: #29469b;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #1e3780;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(41, 70, 155, 0.20);
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 600px) {

            .reset-link-box {
                padding: 28px 22px;
            }

            .reset-link-box h2 {
                font-size: 23px;
            }

            .reset-link-box p {
                font-size: 14px;
            }

            .link-box a {
                font-size: 13px;
            }

            .btn-login {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="reset-link-box">

        <h2>Link Reset Password</h2>

        <p>
            Klik link berikut untuk membuat password baru:
        </p>

        <div class="link-box">
            <a href="{{ $resetUrl }}">
                {{ $resetUrl }}
            </a>
        </div>

        <a href="{{ route('login') }}" class="btn-login">
            Kembali ke Login
        </a>

    </div>

</body>

</html>