<!DOCTYPE html>
<html>

<head>

    <title>
        Sistem Pendataan Dinas Sosial Kota Pasuruan
    </title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI';

        }

        body{

            background:#f6f9ff;

        }

        /* =====================
           SIDEBAR
        ===================== */

        .sidebar{

            position:fixed;

            left:0;

            top:0;

            width:260px;

            height:100vh;

            background:#252A86;

            color:white;

            overflow-y:auto;


        }

        .sidebar::-webkit-scrollbar{
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb{
            background:#6670d8;
            border-radius:10px;
        }

        /* LOGO */

        .logo{

            text-align:center;

            padding:35px 10px;

            font-size:18px;

            font-weight:bold;

            line-height:1.4;

        }

        .logo-icon{

            font-size:50px;

            margin-bottom:10px;

        }

        .sidebar a{

            display:block;

            padding:17px 30px;

            color:white;

            text-decoration:none;

            cursor:pointer;
        }

        .sidebar a:hover{

            background:#3448b8;

        }

        /* =====================
           MAIN
        ===================== */

        .main{

            margin-left:260px;
        }

        /* =====================
           HEADER
        ===================== */

        .header{

            height:80px;

            background:white;

            display:flex;

            justify-content:space-between;

            align-items:center;

            padding:0 35px;

            box-shadow:0 2px 10px #ddd;

        }

        .header-left{
            display:flex;
            align-items:center;
            gap:15px;
        }

        .header-title{
            color:#252A86;
            font-weight:700;
            font-size:17px;
        }

        .header-subtitle{
            color:#777;
            font-size:13px;
        }

        /* =====================
           CONTENT
        ===================== */

        .content{

            padding:40px;

        }

        .card{
            background:white;

            padding:25px;

            border-radius:15px;

            box-shadow:0 5px 20px #ddd;

            margin-top:25px;

        }

        @media (max-width: 900px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
            }

            .header {
                padding: 12px 16px;
                height: auto;
                min-height: 70px;
            }

            .header-left {
                width: 100%;
            }

            .header-title {
                font-size: 15px;
                line-height: 1.4;
            }

            .content {
                padding: 20px;
            }
        }

        @media (max-width: 700px) {
            .sidebar a {
                padding: 14px 16px;
            }

            .logo {
                padding: 20px 12px;
                font-size: 16px;
            }

            .header {
                justify-content: center;
            }

            .header-left {
                justify-content: center;
                text-align: center;
            }

            .content {
                padding: 16px;
            }
        }

    </style>

</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">

            <div class="logo-icon">

                🏛️

            </div>

            Sistem Pendataan

            <br>

            Dinas Sosial Kota Pasuruan

        </div>

        <a href="/dashboard">
            Dashboard
        </a>

        <a href="/periode">
            Periode
        </a>

        <a>
            Petugas
        </a>

        <a>
            Responden
        </a>

        <a>
            Kuisioner
        </a>

        <a href="/verifikasi">
            Verifikasi
        </a>

        <a href="/monitoring">
            Monitoring
        </a>

        <a>
            Laporan
        </a>

        <a>
            Master
        </a>

    </div>

    <!-- MAIN CONTENT -->

    <div class="main">

        <!-- HEADER -->

        <div class="header">
            <div class="header-left">
                <div>
                    <div class="header-title">
                        Sistem Pendataan Dinas Sosial Kota Pasuruan
                    </div>
                    <div class="header-subtitle">
                        Panel Administrasi
                    </div>
                </div>
            </div>
            <div>👤 Admin</div>
        </div>

        <!-- HALAMAN ISI -->

        <div class="content">

            @yield('content')

        </div>

    </div>

</body>

</html>