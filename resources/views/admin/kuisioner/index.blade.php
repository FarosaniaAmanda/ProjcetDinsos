@extends('admin.layouts.app')

@section('content')
    <div style="display: grid; gap: 20px;">
        <div style="background: #fff; border: 1px solid #e7e8ee; border-radius: 16px; padding: 24px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
            <div style="font-size: 13px; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; font-weight: 700; margin-bottom: 10px;">
                Kuisioner
            </div>
            <h1 style="margin: 0; font-size: 28px; color: #1f2937;">Manajemen Kuisioner</h1>
            <p style="margin: 10px 0 0; color: #6b7280;">Halaman ini siap digunakan untuk pengelolaan formulir dan pertanyaan.</p>
        </div>

        <div style="background: #fff; border: 1px solid #e7e8ee; border-radius: 16px; padding: 24px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <h2 style="margin: 0; font-size: 20px; color: #111827;">Daftar Kuisioner</h2>
                <button type="button" style="background: #7c3aed; color: #fff; border: none; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer;">Tambah Pertanyaan</button>
            </div>
            <div style="padding: 18px; border: 1px dashed #d1d5db; border-radius: 12px; color: #4b5563; background: #f9fafb;">
                Belum ada daftar kuisioner. Modul ini dapat dikembangkan sesuai kebutuhan data survei.
            </div>
        </div>
    </div>
@endsection
