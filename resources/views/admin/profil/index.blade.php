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

            padding: 40px;

            display: flex;
            flex-direction: column;

            align-items: center;
        }


        /* =====================================================
           HEADER PROFIL
        ====================================================== */

        .profile-header {
             width: 100%;
             max-width: 850px;
             margin: 0 auto 25px;
             text-align: center;
        }
        


        .profile-header h1 {
            font-size: 28px;

            color: #252A86;

            margin-bottom: 8px;
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
            max-width: 650px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
}


        /* =====================================================
           PROFILE TOP
        ====================================================== */

        .profile-top {

            display: flex;

            align-items: center;

            gap: 20px;

            padding-bottom: 25px;

            border-bottom: 1px solid #eee;

            margin-bottom: 25px;
        }


        .profile-avatar {

            width: 85px;
            height: 85px;

            border-radius: 50%;

            background: #252A86;

            color: #ffffff;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 32px;

            font-weight: bold;

            flex-shrink: 0;
        }


        .profile-name h2 {

            font-size: 22px;

            margin-bottom: 5px;

            color: #252A86;
        }


        .profile-name span {

            color: #777;

            font-size: 14px;
        }


        /* =====================================================
           PROFILE INFORMATION
        ====================================================== */

        .profile-info {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 20px;
        }


        .info-item {

            background: #f7f9fc;

            border-radius: 10px;

            padding: 18px;
        }


        .info-item label {

            display: block;

            font-size: 12px;

            color: #888;

            margin-bottom: 7px;

            text-transform: uppercase;
        }


        .info-item strong {

            font-size: 15px;

            color: #333;
        }


        .status {

            color: #16803c !important;
        }


        /* =====================================================
           ACTION BUTTON
        ====================================================== */

        .profile-actions {

            margin-top: 30px;

            padding-top: 25px;

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

            padding: 12px 20px;

            border-radius: 9px;

            font-size: 14px;

            font-weight: 600;

            transition: .2s;
        }


        .btn-password:hover {

            background: #1d216d;
        }


        .btn-back {

            display: inline-block;

            padding: 12px 20px;

            border-radius: 9px;

            text-decoration: none;

            background: #eeeeee;

            color: #444;

            font-size: 14px;

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

            max-width: 650px;

            background: #e8f7ed;

            color: #16803c;

            padding: 14px 18px;

            border-radius: 9px;

            margin-bottom: 20px;

            font-size: 14px;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 700px) {

            .profile-container {

                padding: 20px;
            }


            .profile-header {

                margin-bottom: 20px;
            }


            .profile-header h1 {

                font-size: 24px;
            }


            .profile-card {

                padding: 22px;
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

                padding: 18px;
            }


            .profile-top {

                gap: 14px;
            }


            .profile-avatar {

                width: 65px;
                height: 65px;

                font-size: 25px;
            }


            .profile-name h2 {

                font-size: 19px;
            }


            .profile-name span {

                font-size: 12px;
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


            <div class="profile-avatar">
                A
            </div>


            <div class="profile-name">

                <h2>
                    Operator
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


            <!-- NAMA -->

            <div class="info-item">

                <label>
                    Nama Operator
                </label>

                <strong>
                    Operator
                </strong>

            </div>


            <!-- JABATAN -->

            <div class="info-item">

                <label>
                    Jabatan
                </label>

                <strong>
                    Admin
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
                    Administrator
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