<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kuisioner - Part 4</title>

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
            width: 80%;
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

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .hint {
            margin-top: 7px;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }

        .date-input {
            max-width: 250px;
        }

        .upload-box {
            border: 2px dashed #cfd3e1;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: .2s;
        }

        .upload-box:hover {
            border-color: #252A86;
            background: #f8f8ff;
        }

        .upload-box input {
            width: 100%;
            margin-bottom: 12px;
        }

        .preview {
            display: none;
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 9px;
            margin-top: 10px;
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
        <p>Part 4 — Data Anggota Keluarga</p>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </div>

    <form method="POST" action="#" enctype="multipart/form-data">
        @csrf

        <div class="section-title">
            Keluarga
        </div>

        {{-- 52 --}}
        <div class="question-card">
            <div class="question-title">
                52. Dimana keberadaan MUKHAROMAH sekarang?
            </div>

            <div class="option-list">
                @foreach([
                    'Tinggal bersama keluarga',
                    'Meninggal',
                    'Pindah ke daerah lain di Indonesia',
                    'Pindah ke luar negeri',
                    'Sudah Pisah kartu keluarga'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="keberadaan"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 53 --}}
        <div class="question-card">
            <div class="question-title">
                53. Nomor Handphone MUKHAROMAH
            </div>

            <label class="form-label">
                Nomor Handphone
            </label>

            <input
                type="tel"
                name="nomor_handphone"
                class="form-input"
                placeholder="Masukkan nomor handphone"
            >

            <div class="hint">
                Kosongkan bagian ini jika yang bersangkutan tidak memiliki nomor handphone.
            </div>
        </div>

        {{-- 54 --}}
        <div class="question-card">
            <div class="question-title">
                54. Pilih Jenis Kelamin MUKHAROMAH
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input
                        type="radio"
                        name="jenis_kelamin"
                        value="Laki-laki"
                    >
                    <span class="radio-label">Laki-laki</span>
                </label>

                <label class="radio-option">
                    <input
                        type="radio"
                        name="jenis_kelamin"
                        value="Perempuan"
                    >
                    <span class="radio-label">Perempuan</span>
                </label>
            </div>

            <div style="margin-top:20px;">
                <label class="form-label">
                    Tanggal Lahir
                </label>

                <input
                    type="date"
                    name="tanggal_lahir"
                    class="form-input date-input"
                >
            </div>
        </div>

        {{-- 55 --}}
        <div class="question-card">
            <div class="question-title">
                55. Apa status hubungan MUKHAROMAH dengan kepala keluarga?
            </div>

            <div class="option-list">
                @foreach([
                    'Kepala keluarga',
                    'Istri/Suami',
                    'Anak',
                    'Menantu',
                    'Cucu',
                    'Lainnya'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="status_hubungan"
                            value="{{ $option }}"
                            onchange="toggleLainnya('hubunganLainnya', this.value)"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>

            <div
                id="hubunganLainnya"
                style="display:none; margin-top:15px;"
            >
                <input
                    type="text"
                    name="status_hubungan_lainnya"
                    class="form-input"
                    placeholder="Masukkan status hubungan"
                >
            </div>
        </div>

        {{-- 56 --}}
        <div class="question-card">
            <div class="question-title">
                56. Apa status perkawinan MUKHAROMAH?
            </div>

            <div class="option-list">
                @foreach([
                    'Belum Kawin',
                    'Kawin/Nikah',
                    'Cerai Hidup',
                    'Cerai Mati'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="status_perkawinan"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 57 --}}
        <div class="question-card">
            <div class="question-title">
                57. Apa Status Partisipasi Sekolah?
            </div>

            <div class="option-list">
                @foreach([
                    'Tidak/belum pernah sekolah',
                    'Masih sekolah',
                    'Tidak bersekolah lagi'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="partisipasi_sekolah"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 58 --}}
        <div class="question-card">
            <div class="question-title">
                58. Apa Ijazah/STTB tertinggi yang dimiliki MUKHAROMAH?
            </div>

            <div class="option-list">
                @foreach([
                    'Tidak Punya Ijazah SD',
                    'SD/Sederajat',
                    'SMP/Sederajat',
                    'SMA/Sederajat',
                    'Diploma (D1/D2/D3)'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="ijazah_tertinggi"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 59 --}}
        <div class="question-card">
            <div class="question-title">
                59. Apa profesi pekerjaan utama MUKHAROMAH?
            </div>

            <div class="option-list">
                @foreach([
                    'Tidak Bekerja',
                    'Agen Tenaga Kerja',
                    'Ahli Sejarah dan Cagar Budaya',
                    'Akuntan',
                    'Analisis Keuangan'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="profesi"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>

            <div class="hint">
                Pilihan profesi mengikuti data yang tersedia pada daftar kuisioner.
            </div>
        </div>

        {{-- 60 --}}
        <div class="question-card">
            <div class="question-title">
                60. Apa status kedudukan MUKHAROMAH dalam pekerjaan utama?
            </div>

            <div class="option-list">
                @foreach([
                    'Berusaha sendiri',
                    'Berusaha dibantu buruh',
                    'Buruh/karyawan/pegawai swasta',
                    'ASN/TNI/POLRI/BUMN/BUMD/pejabat negara/kades'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="status_pekerjaan"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 61 --}}
        <div class="question-card">
            <div class="question-title">
                61. Apakah MUKHAROMAH memiliki rekening aktif atau dompet digital?
            </div>

            <div class="option-list">
                @foreach([
                    'Ya untuk usaha',
                    'Ya untuk pribadi',
                    'Ya untuk usaha dan pribadi',
                    'Tidak ada'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="rekening_dompet_digital"
                            value="{{ $option }}"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 62 --}}
        <div class="question-card">
            <div class="question-title">
                62. Apakah MUKHAROMAH menyandang Disabilitas Fisik?
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input type="radio" name="disabilitas_fisik" value="Ya">
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="disabilitas_fisik" value="Tidak">
                    <span class="radio-label">Tidak</span>
                </label>
            </div>
        </div>

        {{-- 63 --}}
        <div class="question-card">
            <div class="question-title">
                63. Apakah MUKHAROMAH menyandang Disabilitas Mental?
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input type="radio" name="disabilitas_mental" value="Ya">
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="disabilitas_mental" value="Tidak">
                    <span class="radio-label">Tidak</span>
                </label>
            </div>
        </div>

        {{-- 64 --}}
        <div class="question-card">
            <div class="question-title">
                64. Apakah MUKHAROMAH menyandang Disabilitas Intelektual?
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input type="radio" name="disabilitas_intelektual" value="Ya">
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="disabilitas_intelektual" value="Tidak">
                    <span class="radio-label">Tidak</span>
                </label>
            </div>
        </div>

        {{-- 65 --}}
        <div class="question-card">
            <div class="question-title">
                65. Apakah MUKHAROMAH menyandang Disabilitas Sensorik Netra?
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input type="radio" name="disabilitas_netra" value="Ya">
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="disabilitas_netra" value="Tidak">
                    <span class="radio-label">Tidak</span>
                </label>
            </div>
        </div>

        {{-- 66 --}}
        <div class="question-card">
            <div class="question-title">
                66. Apakah MUKHAROMAH menyandang Disabilitas Sensorik Rungu?
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input type="radio" name="disabilitas_rungu" value="Ya">
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="disabilitas_rungu" value="Tidak">
                    <span class="radio-label">Tidak</span>
                </label>
            </div>
        </div>

        {{-- 67 --}}
        <div class="question-card">
            <div class="question-title">
                67. Apakah MUKHAROMAH menyandang Disabilitas Sensorik Wicara?
            </div>

            <div class="option-list">
                <label class="radio-option">
                    <input type="radio" name="disabilitas_wicara" value="Ya">
                    <span class="radio-label">Ya</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="disabilitas_wicara" value="Tidak">
                    <span class="radio-label">Tidak</span>
                </label>
            </div>
        </div>

        {{-- 68 --}}
        <div class="question-card">
            <div class="question-title">
                68. Apakah MUKHAROMAH memiliki keluhan kesehatan kronis/menahun?
            </div>

            <div class="option-list">
                @foreach([
                    'Tidak ada',
                    'Hipertensi (tekanan darah tinggi)',
                    'Rematik',
                    'Asma',
                    'Masalah jantung',
                    'Diabetes (kencing manis)',
                    'Tuberculosis (TBC)',
                    'Stroke',
                    'Kanker atau tumor ganas',
                    'Gagal ginjal',
                    'Haemophilia',
                    'HIV/Aids',
                    'Kolestrol',
                    'Sirosis Hati',
                    'Thalasemia',
                    'Leukimia',
                    'Alzheimer',
                    'Lainnya'
                ] as $option)
                    <label class="radio-option">
                        <input
                            type="radio"
                            name="keluhan_kesehatan"
                            value="{{ $option }}"
                            onchange="toggleLainnya('kesehatanLainnya', this.value)"
                        >
                        <span class="radio-label">{{ $option }}</span>
                    </label>
                @endforeach
            </div>

            <div
                id="kesehatanLainnya"
                style="display:none; margin-top:15px;"
            >
                <input
                    type="text"
                    name="keluhan_kesehatan_lainnya"
                    class="form-input"
                    placeholder="Masukkan keluhan kesehatan lainnya"
                >
            </div>
        </div>

        {{-- 69 --}}
        <div class="question-card">
            <div class="question-title">
                69. Foto Rumah
            </div>

            {{-- FOTO DEPAN --}}
            <div style="margin-bottom:25px;">
                <label class="form-label">
                    Tampak Depan
                </label>

                <div class="upload-box">
                    <input
                        type="file"
                        name="foto_depan"
                        accept="image/*"
                        onchange="previewImage(this, 'previewDepan')"
                    >

                    <div class="hint">
                        Upload foto tampak depan rumah
                    </div>

                    <img
                        id="previewDepan"
                        class="preview"
                        alt="Preview foto depan"
                    >
                </div>
            </div>

            {{-- RUANG TAMU --}}
            <div style="margin-bottom:25px;">
                <label class="form-label">
                    Ruang Tamu
                </label>

                <div class="upload-box">
                    <input
                        type="file"
                        name="foto_ruang_tamu"
                        accept="image/*"
                        onchange="previewImage(this, 'previewRuangTamu')"
                    >

                    <div class="hint">
                        Upload foto ruang tamu
                    </div>

                    <img
                        id="previewRuangTamu"
                        class="preview"
                        alt="Preview foto ruang tamu"
                    >
                </div>
            </div>

            {{-- KAMAR MANDI --}}
            <div>
                <label class="form-label">
                    Foto Kamar Mandi
                </label>

                <div class="upload-box">
                    <input
                        type="file"
                        name="foto_kamar_mandi"
                        accept="image/*"
                        onchange="previewImage(this, 'previewKamarMandi')"
                    >

                    <div class="hint">
                        Upload foto kamar mandi
                    </div>

                    <img
                        id="previewKamarMandi"
                        class="preview"
                        alt="Preview foto kamar mandi"
                    >
                </div>
            </div>
        </div>


        {{-- BUTTON --}}
        <div class="button-area">

            <a
                href="{{ route('kuisioner.part3') }}"
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
    function toggleLainnya(id, value) {
        const element = document.getElementById(id);

        if (value === 'Lainnya') {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';

            const input = element.querySelector('input');

            if (input) {
                input.value = '';
            }
        }
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>

</body>
</html>