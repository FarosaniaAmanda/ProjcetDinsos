@extends('admin.layouts.app')

@section('content')

@if(session('success'))
    <div style="
        background:#ecfdf5;
        border:1px solid #a7f3d0;
        color:#047857;
        padding:14px 18px;
        border-radius:12px;
        margin-bottom:20px;
        font-weight:600;
    ">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div style="
        background:#fff7ed;
        border:1px solid #fed7aa;
        color:#9a3412;
        padding:14px 18px;
        border-radius:12px;
        margin-bottom:20px;
        font-weight:600;
    ">
        ⚠️ {{ session('warning') }}
    </div>
@endif

<style>
    .kuisioner-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .kuisioner-header {
        background: #fff;
        border: 1px solid #e7e8ee;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,.03);
    }

    .kuisioner-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #252A86;
        margin-bottom: 8px;
    }

    .kuisioner-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #1f2937;
    }

    .kuisioner-subtitle {
        margin: 8px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    /* =========================
       PART NAVIGATION
    ========================= */

    .part-navigation {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .part-item {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 13px 10px;
        text-align: center;
        color: #6b7280 !important;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 700;
        display: block;
        cursor: pointer;
        transition: .2s;
    }

    .part-item:link,
    .part-item:visited,
    .part-item:hover,
    .part-item:active {
        text-decoration: none !important;
    }

    .part-item:hover {
        border-color: #252A86;
        color: #252A86 !important;
        background: #f8f9ff;
    }

    .part-item.active {
        background: #252A86;
        border-color: #252A86;
        color: #fff !important;
    }

    .part-number {
        display: block;
        font-size: 11px;
        margin-bottom: 3px;
        opacity: .8;
        text-decoration: none !important;
    }

    .part-item * {
        text-decoration: none !important;
    }

    /* =========================
       CARD
    ========================= */

    .kuisioner-card {
        background: #fff;
        border: 1px solid #e7e8ee;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 10px rgba(0,0,0,.03);
    }

    .part-header {
        padding-bottom: 20px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .part-header h2 {
        margin: 0;
        font-size: 21px;
        color: #111827;
    }

    .part-header p {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    /* =========================
       QUESTION
    ========================= */

    .question {
        margin-bottom: 28px;
    }

    .question-title {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        margin-bottom: 12px;
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.5;
    }

    .question-number {
        min-width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e8edff;
        color: #252A86;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .form-group {
        margin-left: 34px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 11px 13px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
        outline: none;
        transition: .2s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37,42,134,.10);
    }

    .form-textarea {
        resize: vertical;
    }

    .location-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    /* =========================
       RADIO
    ========================= */

    .radio-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .radio-option {
        position: relative;
        cursor: pointer;
    }

    .radio-option input {
        position: absolute;
        opacity: 0;
    }

    .radio-label {
        display: block;
        padding: 11px 18px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        color: #374151;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }

    .radio-option input:checked + .radio-label {
        background: #252A86;
        border-color: #252A86;
        color: #fff;
    }

    /* =========================
       CONDITIONAL
    ========================= */

    .conditional {
        display: none;
        margin-top: 12px;
    }

    .conditional.show {
        display: block;
    }

    /* =========================
       FOOTER
    ========================= */

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .required-info {
        font-size: 12px;
        color: #6b7280;
    }

    .button-group {
        display: flex;
        gap: 10px;
    }

    .btn {
        border: none;
        border-radius: 10px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-primary {
        background: #252A86;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1e2372;
    }

    /* =========================
       MOBILE
    ========================= */

    @media (max-width: 768px) {

        .kuisioner-header {
            padding: 18px;
        }

        .kuisioner-title {
            font-size: 22px;
        }

        .part-navigation {
            grid-template-columns: repeat(2, 1fr);
        }

        .part-item:last-child {
            grid-column: span 2;
        }

        .kuisioner-card {
            padding: 18px;
        }

        .location-grid {
            grid-template-columns: 1fr;
        }

        .form-group {
            margin-left: 0;
        }

        .question-title {
            font-size: 14px;
        }

        .form-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .button-group {
            width: 100%;
        }

        .button-group .btn {
            flex: 1;
        }
    }

    @media (max-width: 480px) {

        .part-navigation {
            grid-template-columns: 1fr 1fr;
            gap: 7px;
        }

        .part-item {
            font-size: 12px;
            padding: 10px 5px;
        }

        .kuisioner-card {
            padding: 15px;
        }

        .radio-group {
            flex-direction: column;
        }

        .radio-label {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>


<div class="kuisioner-page">

    {{-- HEADER --}}
    <div class="kuisioner-header">

        <div class="kuisioner-label">
            Kuisioner Pendataan Keluarga
        </div>

        <h1 class="kuisioner-title">
            Form Kuisioner
        </h1>

        <p class="kuisioner-subtitle">
            Silakan lengkapi data keluarga sesuai dengan kondisi tempat tinggal saat ini.
        </p>

    </div>


    {{-- PART NAVIGATION --}}
    <div class="part-navigation">

        <a href="{{ route('kuisioner.index') }}" class="part-item">
            <span class="part-number">PART 1</span>
            Identitas & Tempat Tinggal
        </a>

        <a href="{{ route('kuisioner.part2') }}" class="part-item active">
            <span class="part-number">PART 2</span>
            Kondisi Tempat Tinggal
        </a>

        <a href="{{ route('kuisioner.part3') }}" class="part-item">
            <span class="part-number">PART 3</span>
            Kepemilikan Aset Keluarga
        </a>

        <a href="{{ route('kuisioner.part4') }}" class="part-item">
            <span class="part-number">PART 4</span>
            Data Anggota Keluarga
        </a>

        <a href="{{ route('kuisioner.part5') }}" class="part-item">
            <span class="part-number">PART 5</span>
            Penutup
        </a>

    </div>


    {{-- CARD PART 2 --}}
    <div class="kuisioner-card">

        <div class="part-header">

            <h2>
                Part 2 — Kondisi Tempat Tinggal
            </h2>

            <p>
                Data mengenai kondisi bangunan, fasilitas tempat tinggal,
                serta kondisi ekonomi keluarga.
            </p>

        </div>


        <form action="#" method="POST">

            @csrf


            {{-- 16 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">16</span>
                    <span>Apa jenis bangunan tempat tinggal yang Anda tempati?</span>
                </div>

                <div class="form-group">

                    <div class="radio-group">

                        @foreach([
                            'Rumah Tunggal',
                            'Apartemen',
                            'Rumah Susun',
                            'Rumah Deret',
                            'Lainnya'
                        ] as $value)

                            <label class="radio-option">

                                <input
                                    type="radio"
                                    name="jenis_bangunan"
                                    value="{{ $value }}"
                                    onchange="toggleBangunanLainnya()"
                                >

                                <span class="radio-label">
                                    {{ $value }}
                                </span>

                            </label>

                        @endforeach

                    </div>

                    <div id="bangunanLainnya" class="conditional">

                        <label class="form-label">
                            Jenis bangunan lainnya
                        </label>

                        <input
                            type="text"
                            name="jenis_bangunan_lainnya"
                            class="form-input"
                            placeholder="Masukkan jenis bangunan"
                        >

                    </div>

                </div>

            </div>


            {{-- 17 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">17</span>
                    <span>Apakah ada keluarga lain yang tinggal dalam satu bangunan?</span>
                </div>

                <div class="form-group">

                    <div class="radio-group">

                        <label class="radio-option">
                            <input
                                type="radio"
                                name="keluarga_lain"
                                value="Ya"
                                onchange="toggleKeluargaLain()"
                            >
                            <span class="radio-label">Ya</span>
                        </label>

                        <label class="radio-option">
                            <input
                                type="radio"
                                name="keluarga_lain"
                                value="Tidak"
                                onchange="toggleKeluargaLain()"
                            >
                            <span class="radio-label">Tidak</span>
                        </label>

                    </div>

                    <div id="jumlahKeluargaLain" class="conditional">

                        <label class="form-label">
                            Jumlah keluarga lain
                        </label>

                        <input
                            type="number"
                            name="jumlah_keluarga_lain"
                            class="form-input"
                            min="1"
                            placeholder="Masukkan jumlah keluarga"
                        >

                    </div>

                </div>

            </div>


            {{-- 18 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">18</span>
                    <span>Berapa jumlah orang yang tinggal dalam rumah ini?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="jumlah_orang_tinggal"
                        class="form-input"
                        min="1"
                        placeholder="Masukkan jumlah orang"
                    >

                </div>

            </div>


            {{-- 19 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">19</span>
                    <span>Bagaimana status kepemilikan tempat tinggal ini?</span>
                </div>

                <div class="form-group">

                    <select
                        name="status_kepemilikan"
                        id="status_kepemilikan"
                        class="form-select"
                        onchange="toggleKepemilikan()"
                    >

                        <option value="">-- Pilih Status Kepemilikan --</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Kontrak/Sewa">Kontrak/Sewa</option>
                        <option value="Bebas Sewa">Bebas Sewa</option>
                        <option value="Dinas">Rumah Dinas</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>


                    <div id="buktiKepemilikan" class="conditional">

                        <label class="form-label">
                            Bukti kepemilikan
                        </label>

                        <input
                            type="text"
                            name="bukti_kepemilikan"
                            class="form-input"
                            placeholder="Contoh: SHM, SHGB, Sertifikat"
                        >

                    </div>


                    <div id="hargaKontrak" class="conditional">

                        <label class="form-label">
                            Harga kontrak/sewa per tahun
                        </label>

                        <input
                            type="number"
                            name="harga_kontrak"
                            class="form-input"
                            min="0"
                            placeholder="Masukkan nominal"
                        >

                    </div>


                    <div id="hargaBebasSewa" class="conditional">

                        <label class="form-label">
                            Keterangan bebas sewa
                        </label>

                        <input
                            type="text"
                            name="harga_bebas_sewa"
                            class="form-input"
                            placeholder="Masukkan keterangan"
                        >

                    </div>


                    <div id="kepemilikanLainnya" class="conditional">

                        <label class="form-label">
                            Status kepemilikan lainnya
                        </label>

                        <input
                            type="text"
                            name="status_kepemilikan_lainnya"
                            class="form-input"
                            placeholder="Masukkan status kepemilikan"
                        >

                    </div>

                </div>

            </div>


            {{-- 20 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">20</span>
                    <span>Berapa luas lantai tempat tinggal yang ditempati?</span>
                </div>

                <div class="form-group">

                    <div class="location-grid">

                        <input
                            type="number"
                            name="luas_lantai"
                            class="form-input"
                            min="0"
                            placeholder="Luas lantai"
                        >

                        <input
                            type="text"
                            class="form-input"
                            value="m²"
                            readonly
                        >

                    </div>

                </div>

            </div>


            {{-- 21 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">21</span>
                    <span>Apa jenis lantai tempat tinggal?</span>
                </div>

                <div class="form-group">

                    <select
                        name="jenis_lantai"
                        class="form-select"
                        onchange="toggleLantaiLainnya()"
                        id="jenis_lantai"
                    >

                        <option value="">-- Pilih Jenis Lantai --</option>
                        <option value="Marmer/Granit">Marmer/Granit</option>
                        <option value="Keramik">Keramik</option>
                        <option value="Parket/Vinil/Karpet">Parket/Vinil/Karpet</option>
                        <option value="Ubin/Tegel">Ubin/Tegel</option>
                        <option value="Semen">Semen</option>
                        <option value="Kayu">Kayu</option>
                        <option value="Tanah">Tanah</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                    <div id="lantaiLainnya" class="conditional">

                        <label class="form-label">
                            Jenis lantai lainnya
                        </label>

                        <input
                            type="text"
                            name="jenis_lantai_lainnya"
                            class="form-input"
                            placeholder="Masukkan jenis lantai"
                        >

                    </div>

                </div>

            </div>


            {{-- 22 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">22</span>
                    <span>Bagaimana kondisi lantai tempat tinggal?</span>
                </div>

                <div class="form-group">

                    <select name="kondisi_lantai" class="form-select">

                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Rusak">Rusak</option>

                    </select>

                </div>

            </div>


            {{-- 23 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">23</span>
                    <span>Apa jenis dinding tempat tinggal?</span>
                </div>

                <div class="form-group">

                    <select name="jenis_dinding" class="form-select">

                        <option value="">-- Pilih Jenis Dinding --</option>
                        <option value="Tembok">Tembok</option>
                        <option value="Kayu">Kayu</option>
                        <option value="Bambu">Bambu</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                </div>

            </div>


            {{-- 24 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">24</span>
                    <span>Bagaimana kondisi dinding tempat tinggal?</span>
                </div>

                <div class="form-group">

                    <select name="kondisi_dinding" class="form-select">

                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Rusak">Rusak</option>

                    </select>

                </div>

            </div>


            {{-- 25 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">25</span>
                    <span>Apa jenis atap tempat tinggal?</span>
                </div>

                <div class="form-group">

                    <select
                        name="jenis_atap"
                        id="jenis_atap"
                        class="form-select"
                        onchange="toggleAtapLainnya()"
                    >

                        <option value="">-- Pilih Jenis Atap --</option>
                        <option value="Genteng">Genteng</option>
                        <option value="Beton">Beton</option>
                        <option value="Asbes">Asbes</option>
                        <option value="Seng">Seng</option>
                        <option value="Bambu">Bambu</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                    <div id="atapLainnya" class="conditional">

                        <label class="form-label">
                            Jenis atap lainnya
                        </label>

                        <input
                            type="text"
                            name="jenis_atap_lainnya"
                            class="form-input"
                            placeholder="Masukkan jenis atap"
                        >

                    </div>

                </div>

            </div>


            {{-- 26 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">26</span>
                    <span>Bagaimana kondisi atap tempat tinggal?</span>
                </div>

                <div class="form-group">

                    <select name="kondisi_atap" class="form-select">

                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Rusak">Rusak</option>

                    </select>

                </div>

            </div>


            {{-- 27 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">27</span>
                    <span>Apa fasilitas buang air besar yang digunakan?</span>
                </div>

                <div class="form-group">

                    <select
                        name="fasilitas_bab"
                        id="fasilitas_bab"
                        class="form-select"
                        onchange="toggleBabLainnya()"
                    >

                        <option value="">-- Pilih Fasilitas --</option>
                        <option value="Jamban Sendiri">Jamban sendiri</option>
                        <option value="Jamban Bersama">Jamban bersama</option>
                        <option value="Jamban Umum">Jamban umum</option>
                        <option value="Tidak Ada">Tidak ada</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                    <div id="babLainnya" class="conditional">

                        <label class="form-label">
                            Fasilitas lainnya
                        </label>

                        <input
                            type="text"
                            name="fasilitas_bab_lainnya"
                            class="form-input"
                            placeholder="Masukkan fasilitas"
                        >

                    </div>

                </div>

            </div>


            {{-- 28 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">28</span>
                    <span>Apa jenis kloset yang digunakan?</span>
                </div>

                <div class="form-group">

                    <select name="jenis_kloset" class="form-select">

                        <option value="">-- Pilih Jenis Kloset --</option>
                        <option value="Leher Angsa">Leher angsa</option>
                        <option value="Plengsengan">Plengsengan</option>
                        <option value="Cemplung">Cemplung</option>
                        <option value="Tidak Ada">Tidak ada</option>

                    </select>

                </div>

            </div>


            {{-- 29 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">29</span>
                    <span>Apa sumber air minum utama keluarga?</span>
                </div>

                <div class="form-group">

                    <select
                        name="sumber_air_minum"
                        id="sumber_air_minum"
                        class="form-select"
                        onchange="toggleAirLainnya()"
                    >

                        <option value="">-- Pilih Sumber Air --</option>
                        <option value="Air Kemasan">Air kemasan</option>
                        <option value="Ledeng">Ledeng</option>
                        <option value="Sumur">Sumur</option>
                        <option value="Mata Air">Mata air</option>
                        <option value="Sungai">Sungai</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                    <div id="airLainnya" class="conditional">

                        <label class="form-label">
                            Sumber air lainnya
                        </label>

                        <input
                            type="text"
                            name="sumber_air_lainnya"
                            class="form-input"
                            placeholder="Masukkan sumber air"
                        >

                    </div>

                </div>

            </div>


            {{-- 30 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">30</span>
                    <span>Apa sumber penerangan utama keluarga?</span>
                </div>

                <div class="form-group">

                    <select name="sumber_penerangan" class="form-select">

                        <option value="">-- Pilih Sumber Penerangan --</option>
                        <option value="Listrik PLN">Listrik PLN</option>
                        <option value="Listrik Non PLN">Listrik Non PLN</option>
                        <option value="Bukan Listrik">Bukan listrik</option>

                    </select>

                </div>

            </div>


            {{-- 31 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">31</span>
                    <span>Berapa daya listrik yang digunakan?</span>
                </div>

                <div class="form-group">

                    <select name="daya_listrik" class="form-select">

                        <option value="">-- Pilih Daya --</option>
                        <option value="450 VA">450 VA</option>
                        <option value="900 VA">900 VA</option>
                        <option value="1300 VA">1300 VA</option>
                        <option value="2200 VA">2200 VA</option>
                        <option value="3500 VA">3500 VA</option>
                        <option value="> 3500 VA">&gt; 3500 VA</option>

                    </select>

                </div>

            </div>


            {{-- 32 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">32</span>
                    <span>Masukkan ID pelanggan PLN atau nomor meteran listrik</span>
                </div>

                <div class="form-group">

                    <div class="location-grid">

                        <div>
                            <label class="form-label">
                                ID Pelanggan PLN
                            </label>

                            <input
                                type="text"
                                name="id_pelanggan_pln"
                                class="form-input"
                                inputmode="numeric"
                                placeholder="ID pelanggan PLN"
                            >
                        </div>

                        <div>
                            <label class="form-label">
                                Nomor Meteran
                            </label>

                            <input
                                type="text"
                                name="no_meteran_listrik"
                                class="form-input"
                                inputmode="numeric"
                                placeholder="Nomor meteran"
                            >
                        </div>

                    </div>

                </div>

            </div>


            {{-- 33 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">33</span>
                    <span>Berapa jumlah meteran listrik yang digunakan?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="jumlah_meteran_listrik"
                        class="form-input"
                        min="1"
                        placeholder="Jumlah meteran"
                    >

                </div>

            </div>


            {{-- 34 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">34</span>
                    <span>Berapa pengeluaran listrik per bulan?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="pengeluaran_listrik"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan nominal"
                    >

                </div>

            </div>


            {{-- 35 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">35</span>
                    <span>Berapa pengeluaran pulsa per bulan?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="pengeluaran_pulsa"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan nominal"
                    >

                </div>

            </div>


            {{-- 36 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">36</span>
                    <span>Berapa pengeluaran internet per bulan?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="pengeluaran_internet"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan nominal"
                    >

                </div>

            </div>


            {{-- 37 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">37</span>
                    <span>Berapa pengeluaran makanan per minggu?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="pengeluaran_makanan_mingguan"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan nominal"
                    >

                </div>

            </div>


            {{-- 38 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">38</span>
                    <span>Berapa pengeluaran non-makanan per bulan?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="pengeluaran_non_makanan_bulanan"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan nominal"
                    >

                </div>

            </div>


            {{-- 39 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">39</span>
                    <span>Berapa pengeluaran non-makanan per tahun?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="pengeluaran_non_makanan_tahunan"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan nominal"
                    >

                </div>

            </div>


            {{-- 40 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">40</span>
                    <span>Apakah ada anggota keluarga yang memiliki pendapatan dari bekerja?</span>
                </div>

                <div class="form-group">

                    <div class="radio-group">

                        <label class="radio-option">
                            <input
                                type="radio"
                                name="pendapatan_bekerja"
                                value="Ya"
                                onchange="togglePendapatanBekerja()"
                            >
                            <span class="radio-label">Ya</span>
                        </label>

                        <label class="radio-option">
                            <input
                                type="radio"
                                name="pendapatan_bekerja"
                                value="Tidak"
                                onchange="togglePendapatanBekerja()"
                            >
                            <span class="radio-label">Tidak</span>
                        </label>

                    </div>

                    <div id="totalPendapatanBekerja" class="conditional">

                        <label class="form-label">
                            Total pendapatan dari bekerja per bulan
                        </label>

                        <input
                            type="number"
                            name="total_pendapatan_bekerja"
                            class="form-input"
                            min="0"
                            placeholder="Masukkan total pendapatan"
                        >

                    </div>

                </div>

            </div>


            {{-- 41 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">41</span>
                    <span>Berapa total pendapatan dari usaha?</span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="total_pendapatan_usaha"
                        class="form-input"
                        min="0"
                        placeholder="Masukkan total pendapatan usaha"
                    >

                </div>

            </div>


            {{-- 42 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">42</span>
                    <span>Apakah keluarga memiliki pendapatan lainnya?</span>
                </div>

                <div class="form-group">

                    <div class="radio-group">

                        <label class="radio-option">

                            <input
                                type="radio"
                                name="pendapatan_lainnya"
                                value="Ya"
                                onchange="togglePendapatanLainnya()"
                            >

                            <span class="radio-label">
                                Ya
                            </span>

                        </label>

                        <label class="radio-option">

                            <input
                                type="radio"
                                name="pendapatan_lainnya"
                                value="Tidak"
                                onchange="togglePendapatanLainnya()"
                            >

                            <span class="radio-label">
                                Tidak
                            </span>

                        </label>

                    </div>


                    <div
                        id="totalPendapatanLainnya"
                        class="conditional"
                    >

                        <label class="form-label">
                            Total pendapatan lainnya per bulan
                        </label>

                        <input
                            type="number"
                            name="total_pendapatan_lainnya"
                            class="form-input"
                            min="0"
                            placeholder="Masukkan total pendapatan"
                        >

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="form-footer">

                <div class="required-info">
                    * Pastikan data yang dimasukkan sudah benar.
                </div>

                <div class="button-group">

                    <a
                        href="{{ route('kuisioner.index') }}"
                        class="btn btn-secondary"
                    >
                        ← Kembali ke Part 1
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan & Lanjut
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

function hideElement(id)
{
    const element = document.getElementById(id);

    if (element) {
        element.classList.remove('show');
    }
}


/* =========================================
   16. JENIS BANGUNAN
========================================= */

function toggleBangunanLainnya()
{
    const selected =
        document.querySelector(
            'input[name="jenis_bangunan"]:checked'
        );

    const element =
        document.getElementById('bangunanLainnya');

    if (selected && selected.value === 'Lainnya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   17. KELUARGA LAIN
========================================= */

function toggleKeluargaLain()
{
    const selected =
        document.querySelector(
            'input[name="keluarga_lain"]:checked'
        );

    const element =
        document.getElementById('jumlahKeluargaLain');

    if (selected && selected.value === 'Ya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   19. STATUS KEPEMILIKAN
========================================= */

function toggleKepemilikan()
{
    const value =
        document.getElementById('status_kepemilikan').value;

    const bukti =
        document.getElementById('buktiKepemilikan');

    const kontrak =
        document.getElementById('hargaKontrak');

    const bebas =
        document.getElementById('hargaBebasSewa');

    const lainnya =
        document.getElementById('kepemilikanLainnya');


    bukti.classList.remove('show');
    kontrak.classList.remove('show');
    bebas.classList.remove('show');
    lainnya.classList.remove('show');


    if (value === 'Milik Sendiri') {
        bukti.classList.add('show');
    }

    if (value === 'Kontrak/Sewa') {
        kontrak.classList.add('show');
    }

    if (value === 'Bebas Sewa') {
        bebas.classList.add('show');
    }

    if (value === 'Lainnya') {
        lainnya.classList.add('show');
    }
}


/* =========================================
   21. LANTAI
========================================= */

function toggleLantaiLainnya()
{
    const value =
        document.getElementById('jenis_lantai').value;

    const element =
        document.getElementById('lantaiLainnya');

    if (value === 'Lainnya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   25. ATAP
========================================= */

function toggleAtapLainnya()
{
    const value =
        document.getElementById('jenis_atap').value;

    const element =
        document.getElementById('atapLainnya');

    if (value === 'Lainnya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   27. BAB
========================================= */

function toggleBabLainnya()
{
    const value =
        document.getElementById('fasilitas_bab').value;

    const element =
        document.getElementById('babLainnya');

    if (value === 'Lainnya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   29. AIR
========================================= */

function toggleAirLainnya()
{
    const value =
        document.getElementById('sumber_air_minum').value;

    const element =
        document.getElementById('airLainnya');

    if (value === 'Lainnya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   40. PENDAPATAN BEKERJA
========================================= */

function togglePendapatanBekerja()
{
    const selected =
        document.querySelector(
            'input[name="pendapatan_bekerja"]:checked'
        );

    const element =
        document.getElementById('totalPendapatanBekerja');

    if (selected && selected.value === 'Ya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}


/* =========================================
   42. PENDAPATAN LAINNYA
========================================= */

function togglePendapatanLainnya()
{
    const selected =
        document.querySelector(
            'input[name="pendapatan_lainnya"]:checked'
        );

    const element =
        document.getElementById('totalPendapatanLainnya');

    if (selected && selected.value === 'Ya') {
        element.classList.add('show');
    } else {
        element.classList.remove('show');

        const input =
            element.querySelector('input');

        if (input) {
            input.value = '';
        }
    }
}

</script>

@endsection