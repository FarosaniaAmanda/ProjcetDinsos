@extends('admin.layouts.app')

@section('content')

<style>
    :root {
        --primary:#292D8F;
        --primary-dark:#222675;
        --primary-soft:#F0F1FF;
        --border:#E2E4EF;
        --text:#25283A;
        --muted:#777D91;
        --background:#F5F6FB;
        --white:#fff;
        --danger:#D64545;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--background);
        color: var(--text);
    }

    .kuisioner-page {
        min-height: 100vh;
        background: var(--background);
    }

    .kuisioner-container {
        max-width: 1200px;
        margin: auto;
        padding: 24px;
    }

    /* HEADER */
    .kuisioner-header {
        background: linear-gradient(135deg,var(--primary-dark),var(--primary));
        color: var(--white);
        padding: 28px 30px;
        border-radius: 16px;
        margin-bottom: 18px;
    }

    .kuisioner-header h1 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
    }

    .kuisioner-header p {
        margin: 7px 0 0;
        font-size: 14px;
        opacity: .9;
    }

    /* PART NAVIGATION */
    .part-navigation {
        position: sticky;
        top: 0;
        z-index: 20;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 10px;
        margin-bottom: 18px;
        display: flex;
        gap: 8px;
        overflow-x: auto;
    }

    .part-navigation a {
        text-decoration: none;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 9px;
        white-space: nowrap;
        transition: .2s;
    }

    .part-navigation a:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .part-navigation a.active {
        background: var(--primary);
        color: var(--white);
    }

    /* CARD */
    .kuisioner-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    /* CARD HEADER */
    .part-header {
        padding: 22px 26px;
        border-bottom: 1px solid var(--border);
        background: var(--white);
    }

    .part-header h2 {
        margin: 0;
        color: var(--primary);
        font-size: 20px;
        font-weight: 700;
    }

    .part-header p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 13px;
    }

    /* FORM */
    .form-content {
        padding: 26px;
    }

    .question {
        padding: 20px 0;
        border-bottom: 1px solid var(--border);
    }

    .question:first-child {
        padding-top: 0;
    }

    .question:last-child {
        border-bottom: none;
    }

    .question-title {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 16px;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
    }

    .question-number {
        min-width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
    }

    .question-text {
        padding-top: 4px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .form-input,
    .form-select {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 9px;
        padding: 11px 13px;
        font-size: 14px;
        color: var(--text);
        background: var(--white);
        outline: none;
        transition: .2s;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(41,45,143,.08);
    }

    /* RADIO */
    .radio-group {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .radio-option {
        position: relative;
    }

    .radio-option input {
        position: absolute;
        opacity: 0;
    }

    .radio-option label {
        display: block;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px 14px;
        cursor: pointer;
        font-size: 14px;
        color: var(--text);
        background: var(--white);
        transition: .2s;
        line-height: 1.5;
    }

    .radio-option label:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .radio-option input:checked + label {
        border-color: var(--primary);
        background: var(--primary-soft);
        color: var(--primary);
        font-weight: 600;
    }

    /* CONDITIONAL */
    .conditional-box {
        display: none;
        margin-top: 15px;
        padding: 17px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: #FAFAFD;
    }

    .conditional-box.show {
        display: block;
    }

    .conditional-title {
        margin-bottom: 13px;
        font-size: 14px;
        font-weight: 600;
        color: var(--primary);
    }

    /* INPUT UNIT */
    .input-unit {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .input-unit .form-input {
        flex: 1;
    }

    .input-unit span {
        font-size: 14px;
        color: var(--muted);
        white-space: nowrap;
    }

    /* WARNING */
    .warning-box {
        display: none;
        margin-bottom: 18px;
        padding: 13px 16px;
        border-radius: 10px;
        background: #FFF4F4;
        border: 1px solid #F0CACA;
        color: var(--danger);
        font-size: 13px;
    }

    /* FOOTER */
    .footer-buttons {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 20px 26px;
        border-top: 1px solid var(--border);
        background: #FAFAFD;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 18px;
        border-radius: 9px;
        border: none;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .btn-back {
        background: #EEF0F5;
        color: var(--text);
    }

    .btn-back:hover {
        background: #E3E5EC;
    }

    .btn-next {
        background: var(--primary);
        color: var(--white);
    }

    .btn-next:hover {
        background: var(--primary-dark);
    }

    @media (max-width: 768px) {

        .kuisioner-container {
            padding: 14px;
        }

        .kuisioner-header {
            padding: 22px;
        }

        .kuisioner-header h1 {
            font-size: 21px;
        }

        .form-content {
            padding: 20px;
        }

        .radio-group {
            grid-template-columns: 1fr;
        }

        .footer-buttons {
            padding: 16px 20px;
        }

        .btn {
            padding: 10px 14px;
        }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="kuisioner-page">

    <div class="kuisioner-container">

        {{-- HEADER --}}
        <div class="kuisioner-header">
            <h1>Kuesioner Pendataan Keluarga</h1>
            <p>Lengkapi data keluarga sesuai dengan kondisi sebenarnya.</p>
        </div>

        {{-- NAVIGASI PART --}}
        <div class="part-navigation">

            <a href="{{ route('kuisioner.part1') }}">
                1. Keluarga
            </a>

            <a href="{{ route('kuisioner.part2') }}" class="active">
                2. Kondisi Rumah
            </a>

            <a href="#" onclick="belumSelesai(event)">
                3. Keuangan Keluarga
            </a>

            <a href="#" onclick="belumSelesai(event)">
                4. Aset Keluarga
            </a>

            <a href="#" onclick="belumSelesai(event)">
                5. Anggota Keluarga
            </a>

        </div>

        <div id="warningBox" class="warning-box">
            Silakan selesaikan Part 2 terlebih dahulu sebelum melanjutkan ke bagian berikutnya.
        </div>

        {{-- CARD --}}
        <div class="kuisioner-card">

            {{-- CARD HEADER --}}
            <div class="part-header">
                <h2>Part 2 — Kondisi Tempat Tinggal</h2>
                <p>Silakan lengkapi informasi kondisi tempat tinggal keluarga.</p>
            </div>

            <div class="form-content">

                {{-- 16 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">16</div>

                        <div class="question-text">
                            Apa jenis bangunan tempat tinggal yang Anda tempati?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_bangunan"
                                   id="bangunan_1"
                                   value="Rumah Tunggal">

                            <label for="bangunan_1">
                                Rumah Tunggal
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_bangunan"
                                   id="bangunan_2"
                                   value="Apartemen">

                            <label for="bangunan_2">
                                Apartemen
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_bangunan"
                                   id="bangunan_3"
                                   value="Rumah Susun">

                            <label for="bangunan_3">
                                Rumah Susun
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_bangunan"
                                   id="bangunan_4"
                                   value="Rumah Deret">

                            <label for="bangunan_4">
                                Rumah Deret
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_bangunan"
                                   id="bangunan_5"
                                   value="Lainnya">

                            <label for="bangunan_5">
                                Lainnya
                            </label>
                        </div>

                    </div>
                </div>

                {{-- 17 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">17</div>

                        <div class="question-text">
                            Selain keluarga Anda, apakah ada keluarga lain yang tinggal di rumah/tempat tinggal ini?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="keluarga_lain"
                                   id="keluarga_lain_ya"
                                   value="Ya"
                                   onchange="toggleKeluargaLain()">

                            <label for="keluarga_lain_ya">
                                Ya
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="keluarga_lain"
                                   id="keluarga_lain_tidak"
                                   value="Tidak"
                                   onchange="toggleKeluargaLain()">

                            <label for="keluarga_lain_tidak">
                                Tidak
                            </label>
                        </div>

                    </div>

                    <div id="keluargaLainBox" class="conditional-box">

                        <div class="conditional-title">
                            Jika Ya:
                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Berapa jumlah keluarga (selain keluarga Anda) yang tinggal dalam rumah/tempat tinggal ini?
                            </label>

                            <div class="input-unit">

                                <input type="number"
                                       name="jumlah_keluarga_lain"
                                       class="form-input"
                                       min="0">

                                <span>
                                    keluarga
                                </span>

                            </div>

                        </div>

                    </div>
                </div>

                {{-- 18 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">18</div>

                        <div class="question-text">
                            Berapa orang yang tinggal dalam 1 rumah/tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="input-unit">

                        <input type="number"
                               name="jumlah_penghuni"
                               class="form-input"
                               min="1">

                        <span>
                            orang
                        </span>

                    </div>

                </div>

                {{-- 19 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">19</div>

                        <div class="question-text">
                            Status kepemilikan bangunan tempat tinggal yang ditempati?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="status_kepemilikan"
                                   id="kepemilikan_1"
                                   value="Milik Sendiri"
                                   onchange="toggleKepemilikan()">

                            <label for="kepemilikan_1">
                                Milik Sendiri
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="status_kepemilikan"
                                   id="kepemilikan_2"
                                   value="Kontrak/Sewa"
                                   onchange="toggleKepemilikan()">

                            <label for="kepemilikan_2">
                                Kontrak/Sewa
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="status_kepemilikan"
                                   id="kepemilikan_3"
                                   value="Bebas Sewa"
                                   onchange="toggleKepemilikan()">

                            <label for="kepemilikan_3">
                                Bebas Sewa
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="status_kepemilikan"
                                   id="kepemilikan_4"
                                   value="Dinas"
                                   onchange="toggleKepemilikan()">

                            <label for="kepemilikan_4">
                                Dinas
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="status_kepemilikan"
                                   id="kepemilikan_5"
                                   value="Lainnya"
                                   onchange="toggleKepemilikan()">

                            <label for="kepemilikan_5">
                                Lainnya
                            </label>
                        </div>

                    </div>

                    {{-- MILIK SENDIRI --}}
                    <div id="milikSendiriBox" class="conditional-box">

                        <div class="conditional-title">
                            Jika Milik Sendiri:
                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Apa bukti kepemilikan tanah bangunan tempat tinggal yang Anda tempati?
                            </label>

                            <div class="radio-group">

                                <div class="radio-option">
                                    <input type="radio"
                                           name="bukti_kepemilikan"
                                           id="bukti_1"
                                           value="SHM">

                                    <label for="bukti_1">
                                        SHM
                                    </label>
                                </div>

                                <div class="radio-option">
                                    <input type="radio"
                                           name="bukti_kepemilikan"
                                           id="bukti_2"
                                           value="Sertifikat selain SHM (SHGB, SHSRS)">

                                    <label for="bukti_2">
                                        Sertifikat selain SHM (SHGB, SHSRS)
                                    </label>
                                </div>

                                <div class="radio-option">
                                    <input type="radio"
                                           name="bukti_kepemilikan"
                                           id="bukti_3"
                                           value="Surat bukti lainnya (Girik, Letter C, dll)">

                                    <label for="bukti_3">
                                        Surat bukti lainnya (Girik, Letter C, dll)
                                    </label>
                                </div>

                                <div class="radio-option">
                                    <input type="radio"
                                           name="bukti_kepemilikan"
                                           id="bukti_4"
                                           value="Tidak punya">

                                    <label for="bukti_4">
                                        Tidak punya
                                    </label>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- KONTRAK/SEWA --}}
                    <div id="kontrakSewaBox" class="conditional-box">

                        <div class="conditional-title">
                            Jika Kontrak/Sewa:
                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Berapa harga kontrak/sewa bangunan tempat tinggal Anda selama sebulan?
                            </label>

                            <div class="input-unit">

                                <span>Rp.</span>

                                <input type="number"
                                       name="harga_kontrak_sewa"
                                       class="form-input"
                                       min="50000">

                            </div>

                            <small style="display:block;margin-top:7px;color:var(--muted);">
                                Minimal Rp50.000
                            </small>

                        </div>

                    </div>

                    {{-- BEBAS SEWA --}}
                    <div id="bebasSewaBox" class="conditional-box">

                        <div class="conditional-title">
                            Jika Bebas Sewa:
                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Berapa perkiraan harga sewa bangunan tempat tinggal Anda selama sebulan?
                            </label>

                            <div class="input-unit">

                                <span>Rp.</span>

                                <input type="number"
                                       name="harga_bebas_sewa"
                                       class="form-input"
                                       min="50000">

                            </div>

                            <small style="display:block;margin-top:7px;color:var(--muted);">
                                Minimal Rp50.000
                            </small>

                        </div>

                    </div>

                    {{-- LAINNYA --}}
                    <div id="lainnyaBox" class="conditional-box">

                        <div class="conditional-title">
                            Jika Lainnya:
                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Status Kepemilikan Lainnya
                            </label>

                            <input type="text"
                                   name="status_kepemilikan_lainnya"
                                   class="form-input">

                        </div>

                    </div>

                </div>

                {{-- 20 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">20</div>

                        <div class="question-text">
                            Berapa luas lantai bangunan tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="input-unit">

                        <span>
                            Luas lantai:
                        </span>

                        <input type="number"
                               name="luas_lantai"
                               class="form-input"
                               min="0"
                               step="0.01">

                        <span>
                            m²
                        </span>

                    </div>

                </div>

                {{-- 21 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">21</div>

                        <div class="question-text">
                            Apa jenis lantai terluas di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_lantai"
                                   id="lantai_1"
                                   value="Marmer/granit">

                            <label for="lantai_1">
                                Marmer/granit
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_lantai"
                                   id="lantai_2"
                                   value="Keramik">

                            <label for="lantai_2">
                                Keramik
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_lantai"
                                   id="lantai_3"
                                   value="Parket/vili/karpet">

                            <label for="lantai_3">
                                Parket/vili/karpet
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_lantai"
                                   id="lantai_4"
                                   value="Ubin/tegel/teraso">

                            <label for="lantai_4">
                                Ubin/tegel/teraso
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_lantai"
                                   id="lantai_5"
                                   value="Kayu/papan">

                            <label for="lantai_5">
                                Kayu/papan
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_lantai"
                                   id="lantai_6"
                                   value="Lainnya">

                            <label for="lantai_6">
                                Lainnya
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 22 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">22</div>

                        <div class="question-text">
                            Bagaimana kondisi lantai di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_lantai"
                                   id="kondisi_lantai_1"
                                   value="Baik">

                            <label for="kondisi_lantai_1">
                                Baik
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_lantai"
                                   id="kondisi_lantai_2"
                                   value="Rusak Ringan">

                            <label for="kondisi_lantai_2">
                                Rusak Ringan
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_lantai"
                                   id="kondisi_lantai_3"
                                   value="Rusak Sedang">

                            <label for="kondisi_lantai_3">
                                Rusak Sedang
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_lantai"
                                   id="kondisi_lantai_4"
                                   value="Rusak Berat">

                            <label for="kondisi_lantai_4">
                                Rusak Berat
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 23 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">23</div>

                        <div class="question-text">
                            Apa jenis dinding terluas di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_dinding"
                                   id="dinding_1"
                                   value="Tembok">

                            <label for="dinding_1">
                                Tembok
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_dinding"
                                   id="dinding_2"
                                   value="Plesteran anyaman bambu/kawat">

                            <label for="dinding_2">
                                Plesteran anyaman bambu/kawat
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_dinding"
                                   id="dinding_3"
                                   value="Kayu/papan/Gypsum/GRC/Calciboard">

                            <label for="dinding_3">
                                Kayu/papan/Gypsum/GRC/Calciboard
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_dinding"
                                   id="dinding_4"
                                   value="Anyaman Bambu">

                            <label for="dinding_4">
                                Anyaman Bambu
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_dinding"
                                   id="dinding_5"
                                   value="Batang Kayu">

                            <label for="dinding_5">
                                Batang Kayu
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 24 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">24</div>

                        <div class="question-text">
                            Bagaimana kondisi dinding di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_dinding"
                                   id="kondisi_dinding_1"
                                   value="Baik">

                            <label for="kondisi_dinding_1">
                                Baik
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_dinding"
                                   id="kondisi_dinding_2"
                                   value="Rusak Ringan">

                            <label for="kondisi_dinding_2">
                                Rusak Ringan
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_dinding"
                                   id="kondisi_dinding_3"
                                   value="Rusak Sedang">

                            <label for="kondisi_dinding_3">
                                Rusak Sedang
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_dinding"
                                   id="kondisi_dinding_4"
                                   value="Rusak Berat">

                            <label for="kondisi_dinding_4">
                                Rusak Berat
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 25 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">25</div>

                        <div class="question-text">
                            Apa jenis atap terluas di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_atap"
                                   id="atap_1"
                                   value="Beton">

                            <label for="atap_1">
                                Beton
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_atap"
                                   id="atap_2"
                                   value="Genteng">

                            <label for="atap_2">
                                Genteng
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_atap"
                                   id="atap_3"
                                   value="Seng">

                            <label for="atap_3">
                                Seng
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_atap"
                                   id="atap_4"
                                   value="Asbes">

                            <label for="atap_4">
                                Asbes
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_atap"
                                   id="atap_5"
                                   value="Kayu/Sirap">

                            <label for="atap_5">
                                Kayu/Sirap
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_atap"
                                   id="atap_6"
                                   value="Lainnya">

                            <label for="atap_6">
                                Lainnya
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 26 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">26</div>

                        <div class="question-text">
                            Bagaimana kondisi atap tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_atap"
                                   id="kondisi_atap_1"
                                   value="Baik">

                            <label for="kondisi_atap_1">
                                Baik
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_atap"
                                   id="kondisi_atap_2"
                                   value="Rusak Ringan">

                            <label for="kondisi_atap_2">
                                Rusak Ringan
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_atap"
                                   id="kondisi_atap_3"
                                   value="Rusak Sedang">

                            <label for="kondisi_atap_3">
                                Rusak Sedang
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="kondisi_atap"
                                   id="kondisi_atap_4"
                                   value="Rusak Berat">

                            <label for="kondisi_atap_4">
                                Rusak Berat
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 27 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">27</div>

                        <div class="question-text">
                            Di tempat tinggal Anda, apakah memiliki fasilitas tempat buang air besar (BAB) dan siapa saja yang menggunakan?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="fasilitas_bab"
                                   id="bab_1"
                                   value="Ada, digunakan oleh anggota keluarga dalam satu rumah">

                            <label for="bab_1">
                                Ada, digunakan oleh anggota keluarga dalam satu rumah
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="fasilitas_bab"
                                   id="bab_2"
                                   value="Ada, digunakan bersama oleh anggota keluarga dari beberapa rumah">

                            <label for="bab_2">
                                Ada, digunakan bersama oleh anggota keluarga dari beberapa rumah
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="fasilitas_bab"
                                   id="bab_3"
                                   value="Ada, di MCK komunal">

                            <label for="bab_3">
                                Ada, di MCK komunal
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="fasilitas_bab"
                                   id="bab_4"
                                   value="Ada, di MCK umum/siapapun menggunakan">

                            <label for="bab_4">
                                Ada, di MCK umum/siapapun menggunakan
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="fasilitas_bab"
                                   id="bab_5"
                                   value="Tidak ada">

                            <label for="bab_5">
                                Tidak ada
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="fasilitas_bab"
                                   id="bab_6"
                                   value="Lainnya">

                            <label for="bab_6">
                                Lainnya
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 28 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">28</div>

                        <div class="question-text">
                            Apa jenis kloset yang digunakan di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_kloset"
                                   id="kloset_1"
                                   value="Leher angsa">

                            <label for="kloset_1">
                                Leher angsa
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_kloset"
                                   id="kloset_2"
                                   value="Plengsengan dengan tutup">

                            <label for="kloset_2">
                                Plengsengan dengan tutup
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_kloset"
                                   id="kloset_3"
                                   value="Plengsengan tanpa tutup">

                            <label for="kloset_3">
                                Plengsengan tanpa tutup
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="jenis_kloset"
                                   id="kloset_4"
                                   value="Cemplung/cebluk">

                            <label for="kloset_4">
                                Cemplung/cebluk
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 29 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">29</div>

                        <div class="question-text">
                            Apa sumber air minum utama di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_air_minum"
                                   id="air_1"
                                   value="Air kemasan bermerk">

                            <label for="air_1">
                                Air kemasan bermerk
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_air_minum"
                                   id="air_2"
                                   value="Air isi ulang">

                            <label for="air_2">
                                Air isi ulang
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_air_minum"
                                   id="air_3"
                                   value="Leding">

                            <label for="air_3">
                                Leding
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_air_minum"
                                   id="air_4"
                                   value="Sumur bor/pompa">

                            <label for="air_4">
                                Sumur bor/pompa
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_air_minum"
                                   id="air_5"
                                   value="Sumur terlindung">

                            <label for="air_5">
                                Sumur terlindung
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_air_minum"
                                   id="air_6"
                                   value="Lainnya">

                            <label for="air_6">
                                Lainnya
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 30 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">30</div>

                        <div class="question-text">
                            Apa sumber penerangan utama di tempat tinggal Anda?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_penerangan"
                                   id="penerangan_1"
                                   value="Listrik PLN dengan meteran">

                            <label for="penerangan_1">
                                Listrik PLN dengan meteran
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_penerangan"
                                   id="penerangan_2"
                                   value="Listrik PLN tanpa meteran">

                            <label for="penerangan_2">
                                Listrik PLN tanpa meteran
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="sumber_penerangan"
                                   id="penerangan_3"
                                   value="Listrik Non-PLN">

                            <label for="penerangan_3">
                                Listrik Non-PLN
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 31 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">31</div>

                        <div class="question-text">
                            Berapa daya pada meteran listrik ke-1 yang terpasang?
                        </div>
                    </div>

                    <div class="radio-group">

                        <div class="radio-option">
                            <input type="radio"
                                   name="daya_listrik"
                                   id="daya_1"
                                   value="450 watt">

                            <label for="daya_1">
                                450 watt
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="daya_listrik"
                                   id="daya_2"
                                   value="900 watt">

                            <label for="daya_2">
                                900 watt
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="daya_listrik"
                                   id="daya_3"
                                   value="1300 watt">

                            <label for="daya_3">
                                1300 watt
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="daya_listrik"
                                   id="daya_4"
                                   value="2200 watt">

                            <label for="daya_4">
                                2200 watt
                            </label>
                        </div>

                        <div class="radio-option">
                            <input type="radio"
                                   name="daya_listrik"
                                   id="daya_5"
                                   value="2200 watt">

                            <label for="daya_5">
                                2200 watt
                            </label>
                        </div>

                    </div>

                </div>

                {{-- 32 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">32</div>

                        <div class="question-text">
                            Masukkan minimal salah satu nomor berikut:
                        </div>
                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            ID Pelanggan PLN
                        </label>

                        <input type="text"
                               name="id_pelanggan_pln"
                               class="form-input"
                               placeholder="Masukkan ID Pelanggan PLN">

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            No Meteran Listrik
                        </label>

                        <input type="text"
                               name="no_meteran_listrik"
                               class="form-input"
                               placeholder="Masukkan No Meteran Listrik">

                    </div>

                </div>

                {{-- 33 --}}
                <div class="question">

                    <div class="question-title">
                        <div class="question-number">33</div>

                        <div class="question-text">
                            Berapa jumlah meteran listrik yang terpasang di rumah Anda?
                        </div>
                    </div>

                    <div class="input-unit">

                        <span>
                            Jumlah meteran listrik:
                        </span>

                        <input type="number"
                               name="jumlah_meteran_listrik"
                               class="form-input"
                               min="1">

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="footer-buttons">

                <a href="{{ route('kuisioner.part1') }}"
                   class="btn btn-back">
                    ← Kembali
                </a>

                <button type="button"
                        class="btn btn-next"
                        onclick="belumSelesai(event)">
                    Simpan & Lanjut
                </button>

            </div>

        </div>

    </div>

</div>

<script>

    function toggleKeluargaLain() {

        const ya = document.getElementById('keluarga_lain_ya');
        const box = document.getElementById('keluargaLainBox');

        if (ya.checked) {
            box.classList.add('show');
        } else {
            box.classList.remove('show');
        }
    }


    function toggleKepemilikan() {

        const boxes = [
            'milikSendiriBox',
            'kontrakSewaBox',
            'bebasSewaBox',
            'lainnyaBox'
        ];

        boxes.forEach(function(id) {
            document.getElementById(id).classList.remove('show');
        });

        const selected = document.querySelector(
            'input[name="status_kepemilikan"]:checked'
        );

        if (!selected) {
            return;
        }

        if (selected.value === 'Milik Sendiri') {
            document
                .getElementById('milikSendiriBox')
                .classList.add('show');
        }

        if (selected.value === 'Kontrak/Sewa') {
            document
                .getElementById('kontrakSewaBox')
                .classList.add('show');
        }

        if (selected.value === 'Bebas Sewa') {
            document
                .getElementById('bebasSewaBox')
                .classList.add('show');
        }

        if (selected.value === 'Lainnya') {
            document
                .getElementById('lainnyaBox')
                .classList.add('show');
        }
    }


    function belumSelesai(event) {

        if (event) {
            event.preventDefault();
        }

        const warning = document.getElementById('warningBox');

        warning.style.display = 'block';

        warning.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

</script>

@endsection