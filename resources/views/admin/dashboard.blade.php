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
    font-family:'Segoe UI',sans-serif;
}


body{

    background:#f5f8ff;

}



/* SIDEBAR */

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



/* scrollbar sidebar */

.sidebar::-webkit-scrollbar{

    width:6px;

}


.sidebar::-webkit-scrollbar-thumb{

    background:#6670d8;

    border-radius:10px;

}



/* LOGO */

.logo{

    text-align:center;

    padding:35px 10px;

    font-size:20px;

    font-weight:bold;

    line-height:1.4;

}



.logo img{

    width:70px;

    height:70px;

    object-fit:contain;

    margin-bottom:10px;

}



/* MENU */

.sidebar a{

    display:block;

    padding:16px 30px;

    color:white;

    text-decoration:none;

    font-size:16px;

}



.sidebar a:hover{

    background:#3448b8;

}



.sidebar .active{

    background:#3448b8;

    margin:0 12px;

    border-radius:10px;

}


/* ================= MAIN ================= */


.main{

    margin-left:260px;

}



/* HEADER */


.header{

    height:75px;

    background:white;

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:0 35px;

    box-shadow:0 3px 10px rgba(0,0,0,.08);

}



.header-left{

    display:flex;

    align-items:center;

    gap:15px;

}



.header-logo{

    width:50px;

    height:50px;

    object-fit:contain;

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



.admin{

    font-weight:600;

}





/* ================= CONTENT ================= */


.content{

    padding:35px;

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

    .content {
        padding: 20px;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 700px) {
    .sidebar {
        overflow: visible;
    }

    .menu {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 8px 12px 12px;
    }

    .sidebar a {
        flex: 1 1 calc(50% - 8px);
        min-width: 120px;
        padding: 12px 14px;
        text-align: center;
        border-radius: 8px;
    }

    .sidebar .active {
        margin: 0;
    }

    .header {
        justify-content: center;
        text-align: center;
    }

    .header-left {
        justify-content: center;
        text-align: center;
    }

    .header-title {
        font-size: 15px;
        line-height: 1.4;
    }

    .header-subtitle {
        font-size: 12px;
    }

    .welcome-banner {
        height: auto;
        padding: 24px 20px;
        flex-direction: column;
        text-align: center;
    }

    .welcome-title {
        font-size: 28px;
    }

    .welcome-subtitle {
        font-size: 16px;
    }

    .banner-image {
        display: none;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}



/* ================= WELCOME ================= */


.welcome-banner{


    height:190px;


    background:

    linear-gradient(
    90deg,
    rgba(255,255,255,.95),
    rgba(234,243,255,.85)
    ),

    url('/images/banner-bg.png');


    background-size:cover;

    background-position:center;


    border-radius:20px;


    padding:40px;


    display:flex;

    justify-content:space-between;

    align-items:center;


    box-shadow:
    0 8px 25px rgba(0,0,0,.08);


}



.welcome-title{

    font-size:42px;

    font-weight:900;

    color:#252A86;

}



.welcome-subtitle{

    margin-top:15px;

    font-size:18px;

    color:#4b5563;

}



.welcome-desc{

    margin-top:20px;

    color:#7b8596;

}



.banner-image img{


    width:260px;

    opacity:.15;


}





/* ================= STATISTIK ================= */


.section-title{

    margin-top:35px;

    margin-bottom:20px;

    color:#252A86;

    font-size:28px;

}




.stats-grid{


    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:20px;


}




.stat-card{


    background:white;


    padding:25px;


    border-radius:18px;


    box-shadow:

    0 8px 20px rgba(0,0,0,.06);


}



.stat-label{


    color:#6b7280;

    font-size:15px;

}



.stat-number{


    margin-top:10px;


    font-size:42px;


    font-weight:900;


    color:#252A86;


}



.stat-info{


    margin-top:10px;


    color:#16A34A;


}





</style>

</head>


<body>



<!-- SIDEBAR -->


<div class="sidebar">



<div class="logo">


<img src="{{ asset('images/dinsos.png') }}">


<div>

Sistem Pendataan

<br>

Dinas Sosial Kota Pasuruan

</div>


</div>





<div class="menu">


<a class="active" href="/dashboard">

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


<a href="{{ route('verifikasi.index') }}">
    <i class="fa-solid fa-user-check"></i>
    <span>Verifikasi</span>
</a>



<a>

Monitoring

</a>



<a>

Laporan

</a>



<a>

Master

</a>



</div>




<div class="logout">


<a href="/logout">

Logout

</a>


</div>



</div>







<!-- MAIN -->


<div class="main">



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





<div class="admin">

Admin

</div>




</div>







<div class="content">





<div class="welcome-banner">



<div>


<h1 class="welcome-title">

Selamat Datang Admin

</h1>



<p class="welcome-subtitle">

Kelola sistem pendataan Dinsos dengan mudah dan efisien.

</p>

</div>

<div class="banner-image">


<img src="{{ asset('images/banner-bg.png') }}">


</div>




</div>







<h2 class="section-title">

Statistik

</h2>







<div class="stats-grid">





<div class="stat-card">


<div class="stat-label">

Total Petugas

</div>


<div class="stat-number">

24

</div>


<div class="stat-info">

+2 dari periode sebelumnya

</div>


</div>







<div class="stat-card">


<div class="stat-label">

Total Responden

</div>


<div class="stat-number">

1.248

</div>


<div class="stat-info">

+86 dari periode sebelumnya

</div>


</div>








<div class="stat-card">


<div class="stat-label">

Periode Aktif

</div>


<div class="stat-number">

1

</div>


<div class="stat-info">

Periode berjalan

</div>


</div>








<div class="stat-card">


<div class="stat-label">

Data Masuk Hari Ini

</div>


<div class="stat-number">

186

</div>


<div class="stat-info">

+12 dari kemarin

</div>


</div>





</div>





</div>



</div>




</body>

</html>