<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kuisioner - Part 3</title>

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
            width: 60%;
            height: 100%;
            background: white;
            border-radius: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #252A86;
            margin: 30px 0 8px;
        }

        .instruction {
            background: #eef0ff;
            color: #4b5563;
            padding: 13px 16px;
            border-radius: 9px;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .question-card {
            background: white;
            border-radius: 14px;
            padding: 23px 25px;
            margin-bottom: 18px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
            border: 1px solid #e5e7eb;
        }

        .question-title {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.6;
            margin-bottom: 18px;
            color: #1f2937;
        }

        .option-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .radio-option {
            position: relative;
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
            background: white;
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

        .jumlah {
            display: none;
            margin-top: 15px;
            padding: 16px;
            background: #f7f8ff;
            border-left: 4px solid #252A86;
            border-radius: 8px;
        }

        .jumlah.show {
            display: block;
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

        .btn-next {
            background: #252A86;
            color: white;
        }

        .btn-next:hover {
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
        <p>Part 3 — Kepemilikan Aset Keluarga</p>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </div>

    <form method="POST" action="#">
        @csrf

        {{-- ============================= --}}
        {{-- ASET BERGERAK --}}
        {{-- ============================= --}}

        <div class="section-title">
            Kepemilikan Aset Bergerak Keluarga
        </div>

        <div class="instruction">
            <strong>Petunjuk:</strong>
            Pilih <strong>Ya</strong> dan isikan jumlahnya jika memiliki,
            atau pilih <strong>Tidak</strong> jika tidak memiliki.
        </div>


        {{-- 43 --}}
        <div class="question-card">
            <div class="question-title">
                43. Tabung Gas 3 KG
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="gas_3kg"
                        value="1"
                        onchange="toggleJumlah('gas3kg', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="gas_3kg"
                        value="0"
                        onchange="toggleJumlah('gas3kg', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="gas3kg" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_gas_3kg"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 44 --}}
        <div class="question-card">
            <div class="question-title">
                44. Tabung Gas 5,5 KG atau Lebih
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="gas_55kg"
                        value="1"
                        onchange="toggleJumlah('gas55kg', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="gas_55kg"
                        value="0"
                        onchange="toggleJumlah('gas55kg', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="gas55kg" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_gas_55kg"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 45 --}}
        <div class="question-card">
            <div class="question-title">
                45. Lemari Es/Kulkas
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="kulkas"
                        value="1"
                        onchange="toggleJumlah('kulkas', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="kulkas"
                        value="0"
                        onchange="toggleJumlah('kulkas', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="kulkas" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_kulkas"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 46 --}}
        <div class="question-card">
            <div class="question-title">
                46. AC (Air Conditioner)
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="ac"
                        value="1"
                        onchange="toggleJumlah('ac', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="ac"
                        value="0"
                        onchange="toggleJumlah('ac', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="ac" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_ac"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 47 --}}
        <div class="question-card">
            <div class="question-title">
                47. Emas/Perhiasan
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="emas_perhiasan"
                        value="1"
                        onchange="toggleJumlah('emas', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="emas_perhiasan"
                        value="0"
                        onchange="toggleJumlah('emas', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="emas" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_emas_perhiasan"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 48 --}}
        <div class="question-card">
            <div class="question-title">
                48. Komputer/Laptop/Tablet
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="komputer_laptop_tablet"
                        value="1"
                        onchange="toggleJumlah('komputer', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="komputer_laptop_tablet"
                        value="0"
                        onchange="toggleJumlah('komputer', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="komputer" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_komputer_laptop_tablet"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 49 --}}
        <div class="question-card">
            <div class="question-title">
                49. Sepeda Motor
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="sepeda_motor"
                        value="1"
                        onchange="toggleJumlah('motor', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="sepeda_motor"
                        value="0"
                        onchange="toggleJumlah('motor', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="motor" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_sepeda_motor"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- 50 --}}
        <div class="question-card">
            <div class="question-title">
                50. Mobil
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="mobil"
                        value="1"
                        onchange="toggleJumlah('mobil', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="mobil"
                        value="0"
                        onchange="toggleJumlah('mobil', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="mobil" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_mobil"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- ============================= --}}
        {{-- ASET TIDAK BERGERAK --}}
        {{-- ============================= --}}

        <div class="section-title">
            Kepemilikan Aset Tidak Bergerak Keluarga
        </div>

        <div class="instruction">
            <strong>Petunjuk:</strong>
            Pilih <strong>Ya</strong> dan isikan jumlahnya jika memiliki,
            atau pilih <strong>Tidak</strong> jika tidak memiliki.
        </div>


        {{-- 51 --}}
        <div class="question-card">
            <div class="question-title">
                51. Rumah/Bangunan (selain yang ditempati)
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="rumah_bangunan_lain"
                        value="1"
                        onchange="toggleJumlah('rumahBangunan', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="rumah_bangunan_lain"
                        value="0"
                        onchange="toggleJumlah('rumahBangunan', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="rumahBangunan" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_rumah_bangunan_lain"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- LAHAN LAINNYA --}}
        <div class="question-card">
            <div class="question-title">
                Lahan Lainnya
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="lahan_lainnya"
                        value="1"
                        onchange="toggleJumlah('lahan', true)"
                    >
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="lahan_lainnya"
                        value="0"
                        onchange="toggleJumlah('lahan', false)"
                    >
                    <span class="radio-label">Tidak</span>
                </label>
            </div>

            <div id="lahan" class="jumlah">
                <label class="form-label">Jumlah</label>
                <input
                    type="number"
                    name="jumlah_lahan_lainnya"
                    class="form-input"
                    min="1"
                    placeholder="Masukkan jumlah"
                >
            </div>
        </div>


        {{-- BUTTON --}}
        <div class="button-area">

            <a
                href="{{ route('kuisioner.part2') }}"
                class="btn btn-back"
            >
                ← Kembali
            </a>

            <button
                type="submit"
                class="btn btn-next"
            >
                Simpan & Lanjut →
            </button>

        </div>

    </form>

</div>


<script>
    function toggleJumlah(id, show) {
        const element = document.getElementById(id);

        if (show) {
            element.classList.add('show');
        } else {
            element.classList.remove('show');

            const input = element.querySelector('input');

            if (input) {
                input.value = '';
            }
        }
    }
</script>

</body>
</html>