<!DOCTYPE html>
<html>

<head>

<title>
Edit Periode
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



/* SIDEBAR */

.sidebar{

    position:fixed;
    left:0;
    top:0;
    width:260px;
    height:100vh;
    background:#252A86;
    color:white;

}


.logo{

    text-align:center;
    padding:35px 10px;
    font-size:20px;
    font-weight:bold;

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

}



.sidebar a:hover{

    background:#3448b8;

}



/* MAIN */

.main{

    margin-left:260px;

}



/* HEADER */

.header{

    height:80px;
    background:white;
    display:flex;
    justify-content:flex-end;
    align-items:center;
    padding-right:40px;
    box-shadow:0 2px 10px #ddd;

}



/* CONTENT */

.content{

    padding:40px;

}



/* FORM */

.form-container{

    width:700px;
    margin:20px auto;
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);

}



.title{

    color:#252A86;
    font-size:35px;
    margin-bottom:30px;

}



.form-group{

    margin-bottom:20px;

}



label{

    display:block;
    font-weight:600;
    margin-bottom:8px;
    color:#333;

}



input,
select{

    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
    font-size:15px;

}



input:focus,
select:focus{

    border-color:#252A86;

}



.button-group{

    margin-top:30px;
    display:flex;
    gap:15px;

}



.btn-simpan{

    background:#252A86;
    color:white;
    border:none;
    padding:13px 30px;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;

}



.btn-kembali{

    background:white;
    color:#252A86;
    border:1px solid #252A86;
    padding:13px 30px;
    border-radius:8px;
    text-decoration:none;
    font-size:16px;

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


<a>
Verifikasi
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





<!-- MAIN -->

<div class="main">



<div class="header">

👤 Admin

</div>





<div class="content">



<div class="form-container">


<h1 class="title">

Edit Periode

</h1>



<div class="form-group">


<label>

Nama Periode

</label>


<input 
type="text"
value="Pendataan Penduduk September 2026">


</div>





<div class="form-group">


<label>

Tanggal Mulai

</label>


<input 
type="date"
value="2026-09-01">


</div>





<div class="form-group">


<label>

Tanggal Selesai

</label>


<input 
type="date"
value="2026-09-07">


</div>





<div class="form-group">


<label>

Status

</label>



<select>


<option selected>

Aktif

</option>


<option>

Selesai

</option>


</select>



</div>





<div class="button-group">



<a href="/periode" class="btn-kembali">

Kembali

</a>




<button class="btn-simpan">

Simpan Perubahan

</button>



</div>




</div>


</div>


</div>


</body>

</html>