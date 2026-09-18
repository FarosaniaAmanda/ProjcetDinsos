@extends('admin.layouts.app')

@section('content')

<style>

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

<div class="form-container">

<h1 class="title">

    Tambah Periode

</h1>

<div class="form-group">

<label>

    Nama Periode

</label>

<input type="text" placeholder="Masukkan nama periode">

</div>

<div class="form-group">

<label>

    Tanggal Mulai

</label>

<input type="date">

</div>

<div class="form-group">

<label>

    Tanggal Selesai

</label>

<input type="date">

</div>

<div class="form-group">

<label>

    Status

</label>

<select>

<option>

    Pilih Status

</option>

<option>

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

    Simpan Periode

</button>

</div>

</div>

@endsection