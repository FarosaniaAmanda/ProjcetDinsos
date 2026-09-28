<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kuisioner - Part 5</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6fb;
            color: #1f2937;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 35px 20px 60px;
        }

        .header {
            background: #252A86;
            color: white;
            border-radius: 16px;
            padding: 25px 30px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 25px;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            opacity: .9;
        }

        .progress {
            margin-top: 20px;
            height: 8px;
            background: rgba(255,255,255,.25);
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #252A86;
            margin: 30px 0 15px;
        }

        .question-card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 18px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
            border: 1px solid #e5e7eb;
        }

        .question-title {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        .form-input:focus {
            border-color: #252A86;
            box-shadow: 0 0 0 3px rgba(37,42,134,.10);
        }

        textarea.form-input {
            resize: vertical;
            min-height: 120px;
        }

        .option-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .radio-option input {
            display: none;
        }

        .radio-label {
            display: block;
            padding: 12px 15px;
            border: 1px solid #d8dce8;
            border-radius: 9px;
            cursor: pointer;
            transition: .2s;
        }

        .radio-label:hover {
            border-color: #252A86;
            background: #f5f6ff;
        }

        .radio-option input:checked + .radio-label {
            background: #252A86;
            border-color: #252A86;
            color: white;
        }

        .finish-box {
            text-align: center;
            padding: 40px 25px;
        }

        .finish-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #eef0ff;
            color: #252A86;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
        }

        .finish-box h2 {
            margin: 0 0 10px;
            color: #252A86;
        }

        .finish-box p {
            margin: 0 auto;
            max-width: 650px;
            color: #6b7280;
            line-height: 1.7;
            font-size: 14px;
        }

        .button-area {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 22px;
            border-radius: 9px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-back {
            background: white;
            color: #252A86;
            border: 1px solid #252A86;
        }

        .btn-back:hover {
            background: #f4f5ff;
        }

        .btn-finish {
            background: #252A86;
            color: white;
        }

        .btn-finish:hover {
            background: #1d216d;
        }

        @media (max-width: 640px) {
            .container {
                padding: 20px 12px 40px;
            }

            .header {
                padding: 20px;
            }

            .question-card {
                padding: 18px;
            }

            .button-area {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <h1>Kuisioner Keluarga</h1>
        <p>Part 5 — Penutup</p>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </div>

    <form method="POST" action="#">
        @csrf

        <div class="section-title">
            Penutup
        </div>

        {{-- INFORMASI --}}
        <div class="question-card">
            <div class="finish-box">

                <div class="finish-icon">
                    ✓
                </div>

                <h2>Hampir Selesai</h2>

                <p>
                    Terima kasih telah meluangkan waktu untuk mengisi
                    kuisioner keluarga. Pastikan seluruh data yang telah
                    dimasukkan sudah benar sebelum menyelesaikan kuisioner.
                </p>

            </div>
        </div>

        {{-- CATATAN --}}
        <div class="question-card">

            <div class="question-title">
                Catatan Tambahan
            </div>

            <label class="form-label">
                Jika ada informasi tambahan yang ingin disampaikan,
                silakan tuliskan di bawah ini.
            </label>

            <textarea
                name="catatan_tambahan"
                class="form-input"
                placeholder="Tuliskan catatan tambahan..."
            ></textarea>

        </div>

        {{-- KONFIRMASI --}}
        <div class="question-card">

            <div class="question-title">
                Konfirmasi Data
            </div>

            <div class="option-list">

                <label class="radio-option">
                    <input
                        type="radio"
                        name="konfirmasi_data"
                        value="1"
                        required
                    >

                    <span class="radio-label">
                        Saya telah memeriksa dan memastikan data
                        yang saya masukkan sudah benar.
                    </span>
                </label>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="button-area">

            <a
                href="{{ route('kuisioner.part4') }}"
                class="btn btn-back"
            >
                ← Kembali
            </a>

            <button
                type="submit"
                class="btn btn-finish"
            >
                Selesaikan Kuisioner ✓
            </button>

        </div>

    </form>

</div>

</body>
</html>