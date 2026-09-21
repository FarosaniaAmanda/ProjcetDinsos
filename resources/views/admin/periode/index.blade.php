<!DOCTYPE html>
<html>

<head>

<title>
Manajemen Periode
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



/* PERIODE */

.title{

    color:#252A86;
    font-size:35px;
    margin-bottom:10px;

}



.btn-tambah{

    background:#252A86;
    color:white;
    padding:12px 22px;
    border-radius:8px;
    text-decoration:none;
    float:right;
    margin-top:-45px;

}



.card{

    background:white;
    padding:25px;
    border-radius:15px;
    margin-top:25px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);

}



.filter{

    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;

}



label{

    font-weight:600;
    color:#333;

}



input,
select{

    width:100%;
    padding:12px;
    margin-top:8px;
    border:1px solid #ddd;
    border-radius:8px;

}



table{

    width:100%;
    border-collapse:collapse;
    margin-top:20px;

}



th{

    background:#edf3ff;
    color:#252A86;

}



td,
th{

    padding:15px;
    border-bottom:1px solid #ddd;
    text-align:left;

}



.status-aktif{

    background:#299447;
    color:white;
    padding:6px 15px;
    border-radius:20px;
    font-size:13px;

}



.status-selesai{

    background:#F5C928;
    color:white;
    padding:6px 15px;
    border-radius:20px;
    font-size:13px;

}



.btn-edit{

    background:#55B5D5;
    color:white;
    padding:7px 15px;
    border-radius:6px;
    text-decoration:none;
    margin-right:5px;

}



.btn-hapus{

    background:#D9364F;
    color:white;
    padding:7px 15px;
    border-radius:6px;
    text-decoration:none;

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



<h1 class="title">

Manajemen Periode

</h1>


<a href="/periode/tambah" class="btn-tambah">

+ Tambah Periode

</a>




<div class="card">


<h3>
Filter Periode
</h3>


<br>


<div class="filter">


<div>

<label>
Nama Kegiatan
</label>

<input type="text" placeholder="Cari nama kegiatan">

</div>



<div>

<label>
Tanggal
</label>

<input type="date">

</div>



<div>

<label>
Bulan
</label>


<select>

<option>Pilih Bulan</option>
<option>Januari</option>
<option>Februari</option>
<option>Maret</option>
<option>September</option>

</select>

</div>




<div>

<label>
Tahun
</label>

<select>

<option>2026</option>
<option>2027</option>

</select>


</div>


</div>


</div>





<div class="card">


<h3>
Daftar Periode
</h3>



<table>


<thead>

<tr>

<th>No</th>
<th>Nama Kegiatan</th>
<th>Tanggal Mulai</th>
<th>Tanggal Selesai</th>
<th>Status</th>
<th>Aksi</th>

</tr>

</thead>



<tbody>


<tr>


<td>1</td>

<td>
Pendataan Penduduk September 2026
</td>


<td>
01 September 2026
</td>


<td>
07 September 2026
</td>


<td>

<span class="status-aktif">
Aktif
</span>

</td>


<td>

<a href="/periode/edit" class="btn-edit">
Edit
</a>


<a href="#" class="btn-hapus">
Hapus
</a>

</td>


</tr>



<tr>


<td>2</td>


<td>
Pendataan Penduduk Agustus 2026
</td>


<td>
01 Agustus 2026
</td>


<td>
07 Agustus 2026
</td>


<td>

<span class="status-selesai">
Selesai
</span>

</td>


<td>

<a href="/periode/edit" class="btn-edit">
Edit
</a>


<a href="#" class="btn-hapus">
Hapus
</a>


</td>


</tr>


</tbody>


</table>


</div>



</div>


</div>


</body>

</html>