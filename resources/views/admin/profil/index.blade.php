<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Operator | Sistem Pendataan Perlinsos</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f3f7fd;
            color: #252525;
        }


        /* =====================================================
           CONTAINER
        ====================================================== */

        .profile-container {
            min-height: 100vh;

            padding: 35px 20px;

            display: flex;
            flex-direction: column;

            align-items: center;
        }


        /* =====================================================
           HEADER PROFIL
        ====================================================== */

        .profile-header {
            width: 100%;
            max-width: 700px;

            margin: 0 auto 20px;

            text-align: center;
        }

        .profile-header h1 {
            font-size: 27px;

            color: #252A86;

            margin-bottom: 7px;
        }

        .profile-header p {
            color: #777;

            font-size: 14px;
        }


        /* =====================================================
           PROFILE CARD
        ====================================================== */

        .profile-card {
            width: 100%;
            max-width: 600px;

            margin: 0 auto;

            background: #fff;

            border-radius: 15px;

            padding: 22px;

            box-shadow: 0 5px 20px rgba(0,0,0,.08);
        }


        /* =====================================================
           PROFILE TOP
        ====================================================== */

        .profile-top {
            display: flex;

            align-items: center;

            gap: 18px;

            padding-bottom: 20px;

            border-bottom: 1px solid #eee;

            margin-bottom: 20px;
        }


        .profile-avatar {
            width: 78px;
            height: 78px;

            border-radius: 50%;

            background: #252A86;

            color: #ffffff;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 30px;

            font-weight: bold;

            flex-shrink: 0;
        }


        .profile-name h2 {
            font-size: 21px;

            margin-bottom: 4px;

            color: #252A86;
        }


        .profile-name span {
            color: #777;

            font-size: 13px;
        }


        /* =====================================================
           PROFILE INFORMATION
        ====================================================== */

        .profile-info {
            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 16px;
        }


        .info-item {
            background: #f7f9fc;

            border-radius: 9px;

            padding: 15px;
        }


        .info-item label {
            display: block;

            font-size: 11px;

            color: #888;

            margin-bottom: 6px;

            text-transform: uppercase;
        }


        .info-item strong {
            font-size: 14px;

            color: #333;
        }


        .status {
            color: #16803c !important;
        }


        /* =====================================================
           ACTION BUTTON
        ====================================================== */

        .profile-actions {
            margin-top: 25px;

            padding-top: 20px;

            border-top: 1px solid #eee;

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .btn-password {
            display: inline-block;

            background: #252A86;

            color: #ffffff;

            text-decoration: none;

            padding: 11px 18px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: 600;

            transition: .2s;
        }


        .btn-password:hover {
            background: #1d216d;
        }


        .btn-back {
            display: inline-block;

            padding: 11px 18px;

            border-radius: 8px;

            text-decoration: none;

            background: #eeeeee;

            color: #444;

            font-size: 13px;

            transition: .2s;
        }


        .btn-back:hover {
            background: #e2e2e2;
        }


        /* =====================================================
           SUCCESS MESSAGE
        ====================================================== */

        .success-message {
            width: 100%;

            max-width: 600px;

            background: #e8f7ed;

            color: #16803c;

            padding: 13px 17px;

            border-radius: 8px;

            margin-bottom: 18px;

            font-size: 13px;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 700px) {

            .profile-container {
                padding: 20px;
            }


            .profile-header {
                margin-bottom: 18px;
            }


            .profile-header h1 {
                font-size: 24px;
            }


            .profile-card {
                padding: 20px;
            }


            .profile-info {
                grid-template-columns: 1fr;
            }


            .profile-top {
                align-items: flex-start;
            }


            .profile-actions {
                flex-direction: column;

                align-items: stretch;
            }


            .btn-password,
            .btn-back {
                display: block;

                text-align: center;

                width: 100%;
            }

        }


        @media (max-width: 450px) {

            .profile-container {
                padding: 15px;
            }


            .profile-card {
                padding: 17px;
            }


            .profile-top {
                gap: 13px;
            }


            .profile-avatar {
                width: 62px;
                height: 62px;

                font-size: 24px;
            }


            .profile-name h2 {
                font-size: 18px;
            }


            .profile-name span {
                font-size: 11px;
            }

        }

    </style>

</head>


<body>


<div class="profile-container">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="profile-header">

        <h1>
            Profil Operator
        </h1>

        <p>
            Informasi akun administrator sistem.
        </p>

    </div>


    <!-- =====================================================
         SUCCESS MESSAGE
    ====================================================== -->

    @if(session('success'))

        <div class="success-message">

            {{ session('success') }}

        </div>

    @endif


    <!-- =====================================================
         PROFILE CARD
    ====================================================== -->

    <div class="profile-card">


        <!-- =================================================
             PROFILE TOP
        ================================================== -->

        <div class="profile-top">


            <!-- AVATAR OTOMATIS DARI HURUF PERTAMA NAMA -->

            <div class="profile-avatar">

                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

            </div>


            <!-- NAMA USER YANG SEDANG LOGIN -->

            <div class="profile-name">

                <h2>

                    {{ Auth::user()->name }}

                </h2>

                <span>

                    Administrator Sistem Pendataan Perlinsos

                </span>

            </div>


        </div>


        <!-- =================================================
             PROFILE INFORMATION
        ================================================== -->

        <div class="profile-info">


            <!-- NAMA OPERATOR -->

            <div class="info-item">

                <label>
                    Nama Operator
                </label>

                <strong>

                    {{ Auth::user()->name }}

                </strong>

            </div>


            <!-- JABATAN -->

            <div class="info-item">

                <label>
                    Jabatan
                </label>

                <strong>

                    {{ ucfirst(Auth::user()->role) }}

                </strong>

            </div>


            <!-- STATUS -->

            <div class="info-item">

                <label>
                    Status
                </label>

                <strong class="status">

                    Aktif

                </strong>

            </div>


            <!-- HAK AKSES -->

            <div class="info-item">

                <label>
                    Hak Akses
                </label>

                <strong>

                    {{ ucfirst(Auth::user()->role) }}

                </strong>

            </div>


        </div>


        <!-- =================================================
             BUTTON
        ================================================== -->

        <div class="profile-actions">


            <a
                href="{{ route('profil.password') }}"
                class="btn-password"
            >

                Ubah Password

            </a>


            <a
                href="{{ route('dashboard') }}"
                class="btn-back"
            >

                ← Kembali ke Dashboard

            </a>


        </div>


    </div>


</div>


</body>

</html>