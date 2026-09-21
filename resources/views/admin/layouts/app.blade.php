<!DOCTYPE html>
<html>

<head>

    <title>
        Sistem Pendataan Sensus
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

        /* LOGO */

        .logo{

            text-align:center;

            padding:35px 10px;

            font-size:22px;

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

            justify-content:flex-end;

            align-items:center;

            padding-right:40px;

            box-shadow:0 2px 10px #ddd;

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

            Sensus

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
            👤 Admin
        </div>

        <!-- HALAMAN ISI -->

        <div class="content">

            @yield('content')

        </div>

    </div>

</body>

</html>