<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Periode</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6f8;
            color: #333;
        }

        .periode-page {
            min-height: 100vh;
            padding: 40px 5%;
        }

        .periode-container {
            max-width: 850px;
            margin: 0 auto;
        }

        /* HEADER */

        .periode-header {
            margin-bottom: 25px;
        }

        .periode-header h1 {
            font-size: 30px;
            color: #222;
            margin-bottom: 8px;
        }

        .periode-header p {
            font-size: 14px;
            color: #777;
        }

        /* CARD */

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 25px rgba(0,0,0,.06);
        }

        .form-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 18px;
            margin-bottom: 25px;
        }

        .form-title h2 {
            font-size: 21px;
            margin-bottom: 6px;
        }

        .form-title p {
            color: #888;
            font-size: 14px;
        }

        /* FORM */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .required {
            color: #8f211a;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        .form-control:focus {
            border-color: #8f211a;
            box-shadow: 0 0 0 3px rgba(143,33,26,.08);
        }

        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        /* ERROR */

        .alert-error {
            background: #fff1f1;
            border: 1px solid #efc0c0;
            color: #a12626;
            padding: 14px 16px;
            border-radius: 9px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .alert-error ul {
            margin: 8px 0 0 18px;
        }

        .input-error {
            border-color: #dc3545 !important;
        }

        .error-text {
            display: block;
            margin-top: 6px;
            color: #dc3545;
            font-size: 12px;
        }

        /* BUTTON */

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 22px;
            border-top: 1px solid #eee;
        }

        .btn {
            border: none;
            border-radius: 9px;
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel {
            background: #eee;
            color: #555;
        }

        .btn-cancel:hover {
            background: #ddd;
        }

        .btn-save {
            background: #8f211a;
            color: white;
        }

        .btn-save:hover {
            background: #731a15;
        }

        /* RESPONSIVE */

        @media (max-width: 650px) {

            .periode-page {
                padding: 25px 4%;
            }

            .form-card {
                padding: 22px;
            }

            .date-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
            }

        }

    </style>

</head>


<body>

<div class="periode-page">

    <div class="periode-container">

        <div class="periode-header">

            <h1>Tambah Periode</h1>

            <p>
                Tambahkan periode pendataan baru ke dalam sistem.
            </p>

        </div>


        <div class="form-card">

            <div class="form-title">

                <h2>Data Periode</h2>

                <p>
                    Silakan lengkapi data periode di bawah ini.
                </p>

            </div>


            {{-- ERROR VALIDASI --}}

            @if ($errors->any())

                <div class="alert-error">

                    <strong>
                        Data belum dapat disimpan.
                    </strong>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- FORM --}}

            <form
                action="{{ route('periode.store') }}"
                method="POST"
            >

                @csrf


                {{-- NAMA PERIODE --}}

                <div class="form-group">

                    <label for="nama_periode">

                        Nama Periode

                        <span class="required">*</span>

                    </label>


                    <input
                        type="text"
                        id="nama_periode"
                        name="nama_periode"
                        class="form-control @error('nama_periode') input-error @enderror"
                        value="{{ old('nama_periode') }}"
                        placeholder="Contoh: Pendataan Awal 2026"
                        required
                    >


                    @error('nama_periode')

                        <span class="error-text">
                            {{ $message }}
                        </span>

                    @enderror

                </div>


                {{-- TANGGAL --}}

                <div class="date-row">


                    {{-- TANGGAL MULAI --}}

                    <div class="form-group">

                        <label for="tanggal_mulai">

                            Tanggal Mulai

                            <span class="required">*</span>

                        </label>


                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            class="form-control @error('tanggal_mulai') input-error @enderror"
                            value="{{ old('tanggal_mulai') }}"
                            required
                        >


                        @error('tanggal_mulai')

                            <span class="error-text">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- TANGGAL SELESAI --}}

                    <div class="form-group">

                        <label for="tanggal_selesai">

                            Tanggal Selesai

                            <span class="required">*</span>

                        </label>


                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') input-error @enderror"
                            value="{{ old('tanggal_selesai') }}"
                            required
                        >


                        @error('tanggal_selesai')

                            <span class="error-text">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="form-group">

                    <label for="status">

                        Status

                        <span class="required">*</span>

                    </label>


                    <select
                        id="status"
                        name="status"
                        class="form-control @error('status') input-error @enderror"
                        required
                    >

                        <option value="">
                            -- Pilih Status --
                        </option>


                        <option
                            value="Aktif"
                            {{ old('status') == 'Aktif' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>


                        <option
                            value="Selesai"
                            {{ old('status') == 'Selesai' ? 'selected' : '' }}
                        >
                            Selesai
                        </option>

                    </select>


                    @error('status')

                        <span class="error-text">
                            {{ $message }}
                        </span>

                    @enderror

                </div>


                {{-- BUTTON --}}

                <div class="form-actions">

                    <a
                        href="{{ route('periode.index') }}"
                        class="btn btn-cancel"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="btn btn-save"
                    >
                        Simpan Periode
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

</body>

</html>