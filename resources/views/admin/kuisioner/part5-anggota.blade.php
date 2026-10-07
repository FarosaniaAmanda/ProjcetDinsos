@extends('admin.kuisioner.layout')

@section('content')

<style>
    .part5-anggota-wrapper {
        width: 100%;
    }

    .part5-member-header {
        background: #f5f7ff;
        border: 1px solid #dfe3f5;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .part5-member-title {
        font-size: 15px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .part5-member-info {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 8px;
    }

    .part5-member-info-item {
        font-size: 12px;
        color: #555;
    }

    .part5-member-info-item strong {
        color: #333;
    }

    .part5-question {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 15px 16px;
        margin-bottom: 14px;
    }

    .part5-question-title {
        display: flex;
        gap: 8px;
        font-size: 15px;
        font-weight: 600;
        color: #333;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .part5-question-number {
        min-width: 25px;
        font-size: 11px;
        font-weight: 700;
        color: #252A86;
    }

    .part5-option {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 8px;
        cursor: pointer;
        font-size: 14px;
        color: #444;
        line-height: 1.4;
    }

    .part5-option:last-child {
        margin-bottom: 0;
    }

    .part5-option input[type="radio"] {
        width: 14px;
        height: 14px;
        margin-top: 2px;
        flex-shrink: 0;
        accent-color: #252A86;
    }

    .part5-input-group {
        margin-top: 10px;
    }

    .part5-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
    }

    .part5-input,
    .part5-select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        background: #fff;
        outline: none;
        box-sizing: border-box;
    }

    .part5-input:focus,
    .part5-select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 2px rgba(37, 42, 134, .08);
    }

    .part5-readonly {
        background: #f5f5f5;
        color: #666;
        cursor: not-allowed;
    }

    .part5-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .part5-disability-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 20px;
    }

    .part5-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding-bottom: 20px;
    }

    .part5-back-btn,
    .part5-save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .part5-back-btn {
        background: #eeeeee;
        color: #444;
    }

    .part5-back-btn:hover {
        background: #e2e2e2;
        color: #333;
    }

    .part5-save-btn {
        background: #252A86;
        color: #fff;
    }

    .part5-save-btn:hover {
        background: #1d216d;
    }

    .part5-error {
        margin-top: 5px;
        font-size: 11px;
        color: #dc3545;
    }

    .part5-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 12px;
        padding-bottom: 7px;
        border-bottom: 1px solid #e5e5e5;
    }

    .part5-extra-input {
        margin: 10px 0 12px 22px;
        display: none;
    }

    .part5-extra-input.show {
        display: block;
    }

    .part5-extra-input .part5-label {
        margin-bottom: 6px;
    }

    .part5-question.hidden-question {
        display: none;
    }

    .part5-note {
        margin-top: 5px;
        font-size: 11px;
        color: #777;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .part5-grid,
        .part5-disability-grid {
            grid-template-columns: 1fr;
        }

        .part5-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .part5-back-btn,
        .part5-save-btn {
            width: 100%;
        }

        .part5-extra-input {
            margin-left: 22px;
        }
    }
</style>

<div class="part5-anggota-wrapper">

    {{-- ========================================================= --}}
    {{-- HEADER ANGGOTA --}}
    {{-- ========================================================= --}}

    <div class="part5-member-header">

        <div class="part5-member-title">
            Data Anggota Keluarga
        </div>

        <div class="part5-member-info">

            <div class="part5-member-info-item">
                <strong>Nama:</strong>
                {{ $anggota->nama_lengkap }}
            </div>

            <div class="part5-member-info-item">
                <strong>NIK:</strong>
                {{ $anggota->nik }}
            </div>

            <div class="part5-member-info-item">
                <strong>Status Keluarga:</strong>
                {{ $anggota->status_keluarga }}
            </div>

        </div>

    </div>


    <form
        action="{{ route('kuisioner.part5.anggota.store', $anggota->kode) }}"
        method="POST"
    >

        @csrf


        {{-- ========================================================= --}}
        {{-- 52. KEBERADAAN --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">52.</span>
                <span>
                    Dimana keberadaan {{ $anggota->nama_lengkap }} sekarang?
                </span>
            </div>

            @php
                $keberadaanOptions = [
                    'Tinggal bersama keluarga',
                    'Meninggal',
                    'Pindah ke daerah lain di Indonesia',
                    'Pindah ke luar negeri',
                    'Sudah Pisah kartu keluarga',
                ];
            @endphp

            @foreach ($keberadaanOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="keberadaan"
                        value="{{ $option }}"
                        {{ old('keberadaan', $dataPart5->keberadaan ?? '') === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            @error('keberadaan')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 53. NOMOR HANDPHONE --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">53.</span>
                <span>
                    Nomor Handphone {{ $anggota->nama_lengkap }}
                </span>
            </div>

            <div class="part5-input-group">

                <label class="part5-label">
                    Nomor Handphone
                </label>

                <input
                    type="text"
                    name="no_hp"
                    class="part5-input"
                    value="{{ old('no_hp', $dataPart5->no_hp ?? '') }}"
                    maxlength="16"
                    placeholder="Kosongkan jika tidak punya"
                >

            </div>

            @error('no_hp')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 54. JENIS KELAMIN + TANGGAL LAHIR --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">54.</span>
                <span>
                    Jenis Kelamin {{ $anggota->nama_lengkap }}
                </span>
            </div>

            @php
                $jenisKelaminOptions = [
                    'Laki-laki',
                    'Perempuan',
                ];
            @endphp

            @foreach ($jenisKelaminOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="jenis_kelamin"
                        value="{{ $option }}"
                        {{ old('jenis_kelamin', $dataPart5->jenis_kelamin ?? '') === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            <div class="part5-input-group">

                <label class="part5-label">
                    Tanggal Lahir
                </label>

                <input
                    type="date"
                    name="tanggal_lahir"
                    class="part5-input"
                    value="{{ old('tanggal_lahir', $dataPart5->tanggal_lahir ?? '') }}"
                >

            </div>

            @error('jenis_kelamin')
                <div class="part5-error">{{ $message }}</div>
            @enderror

            @error('tanggal_lahir')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 55. STATUS HUBUNGAN --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">55.</span>
                <span>
                    Status hubungan {{ $anggota->nama_lengkap }}
                    dengan kepala keluarga
                </span>
            </div>

            <input
                type="text"
                class="part5-input part5-readonly"
                value="{{ $anggota->status_keluarga }}"
                readonly
            >

            <div class="part5-note">
                Data hubungan keluarga diambil dari data anggota keluarga.
            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 56. STATUS PERKAWINAN --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">56.</span>
                <span>Status perkawinan</span>
            </div>

            @php
                $perkawinanOptions = [
                    'Belum Kawin',
                    'Kawin/Nikah',
                    'Cerai Hidup',
                    'Cerai Mati',
                ];
            @endphp

            @foreach ($perkawinanOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="status_perkawinan"
                        value="{{ $option }}"
                        {{ old('status_perkawinan', $dataPart5->status_perkawinan ?? '') === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            @error('status_perkawinan')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 57. STATUS PARTISIPASI SEKOLAH --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">57.</span>
                <span>Status partisipasi sekolah</span>
            </div>

            @php
                $sekolahOptions = [
                    'Tidak/belum pernah sekolah',
                    'Masih sekolah',
                    'Tidak bersekolah lagi',
                ];
            @endphp

            @foreach ($sekolahOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="status_sekolah"
                        value="{{ $option }}"
                        {{ old('status_sekolah', $dataPart5->status_sekolah ?? '') === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            @error('status_sekolah')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 58. IJAZAH / STTB --}}
        {{-- ========================================================= --}}

        @php
            $ijazahValue = old(
                'ijazah_tertinggi',
                $dataPart5->ijazah_tertinggi ?? ''
            );

            $ijazahLainnyaValue = old(
                'ijazah_lainnya',
                $dataPart5->ijazah_lainnya ?? ''
            );

            $ijazahLainnyaAktif =
                $ijazahValue === 'Lainnya';
        @endphp

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">58.</span>
                <span>Ijazah/STTB tertinggi</span>
            </div>

            @php
                $ijazahOptions = [
                    'Tidak Punya Ijazah SD',
                    'SD/Sederajat',
                    'SMP/Sederajat',
                    'SMA/Sederajat',
                    'Diploma (D1/D2/D3)',
                    'S1',
                    'S2',
                    'S3',
                    'Lainnya',
                ];
            @endphp

            @foreach ($ijazahOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="ijazah_tertinggi"
                        value="{{ $option }}"
                        class="ijazah-radio"
                        {{ $ijazahValue === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            <div
                id="ijazah-lainnya-wrapper"
                class="part5-extra-input {{ $ijazahLainnyaAktif ? 'show' : '' }}"
            >

                <label class="part5-label">
                    Keterangan ijazah lainnya
                </label>

                <input
                    type="text"
                    name="ijazah_lainnya"
                    id="ijazah_lainnya"
                    class="part5-input"
                    value="{{ $ijazahLainnyaValue }}"
                    placeholder="Tuliskan ijazah/STTB lainnya"
                    {{ $ijazahLainnyaAktif ? 'required' : '' }}
                >

            </div>

            @error('ijazah_tertinggi')
                <div class="part5-error">{{ $message }}</div>
            @enderror

            @error('ijazah_lainnya')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 59. PROFESI / PEKERJAAN --}}
        {{-- ========================================================= --}}

        @php
            $pekerjaanValue = old(
                'pekerjaan_utama',
                $dataPart5->pekerjaan_utama ?? ''
            );

            $pekerjaanLainnyaValue = old(
                'pekerjaan_lainnya',
                $dataPart5->pekerjaan_lainnya ?? ''
            );

            $pekerjaanLainnyaAktif =
                $pekerjaanValue === 'Lainnya';

            $tidakBekerja =
                $pekerjaanValue === 'Tidak Bekerja';
        @endphp

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">59.</span>
                <span>Profesi pekerjaan utama</span>
            </div>

            @php
                $pekerjaanOptions = [
                    'Tidak Bekerja',
                    'Agen Tenaga Kerja',
                    'Ahli Sejarah dan Cagar Budaya',
                    'Akuntan',
                    'Analisis Keuangan',
                    'Tenaga Pengajar',
                    'Wiraswasta',
                    'Lainnya',
                ];
            @endphp

            @foreach ($pekerjaanOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="pekerjaan_utama"
                        value="{{ $option }}"
                        class="pekerjaan-radio"
                        {{ $pekerjaanValue === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            <div
                id="pekerjaan-lainnya-wrapper"
                class="part5-extra-input {{ $pekerjaanLainnyaAktif ? 'show' : '' }}"
            >

                <label class="part5-label">
                    Keterangan pekerjaan lainnya
                </label>

                <input
                    type="text"
                    name="pekerjaan_lainnya"
                    id="pekerjaan_lainnya"
                    class="part5-input"
                    value="{{ $pekerjaanLainnyaValue }}"
                    placeholder="Tuliskan pekerjaan lainnya"
                    {{ $pekerjaanLainnyaAktif ? 'required' : '' }}
                >

            </div>

            @error('pekerjaan_utama')
                <div class="part5-error">{{ $message }}</div>
            @enderror

            @error('pekerjaan_lainnya')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 60. STATUS KEDUDUKAN PEKERJAAN --}}
        {{-- ========================================================= --}}

        <div
            id="part5-question-60"
            class="part5-question {{ $tidakBekerja ? 'hidden-question' : '' }}"
        >

            <div class="part5-question-title">

                <span class="part5-question-number">60.</span>

                <span>
                    Status kedudukan dalam pekerjaan
                </span>

            </div>

            @php
                $statusPekerjaanOptions = [
                    'Berusaha sendiri',
                    'Berusaha dibantu buruh',
                    'Buruh/karyawan/pegawai swasta',
                    'ASN/TNI/POLRI/BUMN/BUMD/pejabat negara/kades',
                ];

                $statusPekerjaanValue = old(
                    'status_pekerjaan',
                    $dataPart5->status_pekerjaan ?? ''
                );
            @endphp

            @foreach ($statusPekerjaanOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="status_pekerjaan"
                        value="{{ $option }}"
                        class="status-pekerjaan-radio"
                        {{ $statusPekerjaanValue === $option ? 'checked' : '' }}
                        {{ $tidakBekerja ? 'disabled' : 'required' }}
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            @error('status_pekerjaan')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 61. REKENING / DOMPET DIGITAL --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">
                <span class="part5-question-number">61.</span>
                <span>Rekening aktif/dompet digital</span>
            </div>

            @php
                $rekeningOptions = [
                    'Ya untuk usaha',
                    'Ya untuk pribadi',
                    'Ya untuk usaha dan pribadi',
                    'Tidak ada',
                ];
            @endphp

            @foreach ($rekeningOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="kepemilikan_rekening"
                        value="{{ $option }}"
                        {{ old('kepemilikan_rekening', $dataPart5->kepemilikan_rekening ?? '') === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            @error('kepemilikan_rekening')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- 62-67. DISABILITAS --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-section-title">
                Disabilitas
            </div>


            {{-- 62 --}}

            <div class="part5-question-title">
                <span class="part5-question-number">62.</span>
                <span>Disabilitas fisik</span>
            </div>

            <div class="part5-disability-grid">

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_fisik"
                        value="1"
                        {{ old('is_disabilitas_fisik', $dataPart5->is_disabilitas_fisik ?? '') == '1' ? 'checked' : '' }}
                        required
                    >

                    <span>Ya</span>

                </label>

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_fisik"
                        value="0"
                        {{ old('is_disabilitas_fisik', $dataPart5->is_disabilitas_fisik ?? '') == '0' ? 'checked' : '' }}
                    >

                    <span>Tidak</span>

                </label>

            </div>


            {{-- 63 --}}

            <div class="part5-question-title" style="margin-top: 15px;">

                <span class="part5-question-number">63.</span>
                <span>Disabilitas mental</span>

            </div>

            <div class="part5-disability-grid">

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_mental"
                        value="1"
                        {{ old('is_disabilitas_mental', $dataPart5->is_disabilitas_mental ?? '') == '1' ? 'checked' : '' }}
                        required
                    >

                    <span>Ya</span>

                </label>

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_mental"
                        value="0"
                        {{ old('is_disabilitas_mental', $dataPart5->is_disabilitas_mental ?? '') == '0' ? 'checked' : '' }}
                    >

                    <span>Tidak</span>

                </label>

            </div>


            {{-- 64 --}}

            <div class="part5-question-title" style="margin-top: 15px;">

                <span class="part5-question-number">64.</span>
                <span>Disabilitas intelektual</span>

            </div>

            <div class="part5-disability-grid">

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_intelektual"
                        value="1"
                        {{ old('is_disabilitas_intelektual', $dataPart5->is_disabilitas_intelektual ?? '') == '1' ? 'checked' : '' }}
                        required
                    >

                    <span>Ya</span>

                </label>

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_intelektual"
                        value="0"
                        {{ old('is_disabilitas_intelektual', $dataPart5->is_disabilitas_intelektual ?? '') == '0' ? 'checked' : '' }}
                    >

                    <span>Tidak</span>

                </label>

            </div>


            {{-- 65 --}}

            <div class="part5-question-title" style="margin-top: 15px;">

                <span class="part5-question-number">65.</span>
                <span>Disabilitas sensorik netra</span>

            </div>

            <div class="part5-disability-grid">

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_netra"
                        value="1"
                        {{ old('is_disabilitas_netra', $dataPart5->is_disabilitas_netra ?? '') == '1' ? 'checked' : '' }}
                        required
                    >

                    <span>Ya</span>

                </label>

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_netra"
                        value="0"
                        {{ old('is_disabilitas_netra', $dataPart5->is_disabilitas_netra ?? '') == '0' ? 'checked' : '' }}
                    >

                    <span>Tidak</span>

                </label>

            </div>


            {{-- 66 --}}

            <div class="part5-question-title" style="margin-top: 15px;">

                <span class="part5-question-number">66.</span>
                <span>Disabilitas sensorik rungu</span>

            </div>

            <div class="part5-disability-grid">

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_rungu"
                        value="1"
                        {{ old('is_disabilitas_rungu', $dataPart5->is_disabilitas_rungu ?? '') == '1' ? 'checked' : '' }}
                        required
                    >

                    <span>Ya</span>

                </label>

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_rungu"
                        value="0"
                        {{ old('is_disabilitas_rungu', $dataPart5->is_disabilitas_rungu ?? '') == '0' ? 'checked' : '' }}
                    >

                    <span>Tidak</span>

                </label>

            </div>


            {{-- 67 --}}

            <div class="part5-question-title" style="margin-top: 15px;">

                <span class="part5-question-number">67.</span>
                <span>Disabilitas sensorik wicara</span>

            </div>

            <div class="part5-disability-grid">

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_wicara"
                        value="1"
                        {{ old('is_disabilitas_wicara', $dataPart5->is_disabilitas_wicara ?? '') == '1' ? 'checked' : '' }}
                        required
                    >

                    <span>Ya</span>

                </label>

                <label class="part5-option">

                    <input
                        type="radio"
                        name="is_disabilitas_wicara"
                        value="0"
                        {{ old('is_disabilitas_wicara', $dataPart5->is_disabilitas_wicara ?? '') == '0' ? 'checked' : '' }}
                    >

                    <span>Tidak</span>

                </label>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- 68. KELUHAN KESEHATAN --}}
        {{-- ========================================================= --}}

        <div class="part5-question">

            <div class="part5-question-title">

                <span class="part5-question-number">68.</span>

                <span>
                    Keluhan kesehatan kronis
                </span>

            </div>

            @php
                $kesehatanOptions = [
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
                    'Lainnya',
                ];
            @endphp

            @foreach ($kesehatanOptions as $option)

                <label class="part5-option">

                    <input
                        type="radio"
                        name="keluhan_kesehatan"
                        value="{{ $option }}"
                        {{ old('keluhan_kesehatan', $dataPart5->keluhan_kesehatan ?? '') === $option ? 'checked' : '' }}
                        required
                    >

                    <span>{{ $option }}</span>

                </label>

            @endforeach

            @error('keluhan_kesehatan')
                <div class="part5-error">{{ $message }}</div>
            @enderror

        </div>


        {{-- ========================================================= --}}
        {{-- FOOTER --}}
        {{-- ========================================================= --}}

        <div class="part5-footer">

            <a
                href="{{ route('kuisioner.part5') }}"
                class="part5-back-btn"
            >
                ← Kembali ke Daftar Anggota
            </a>

            <button
                type="submit"
                class="part5-save-btn"
            >
                Simpan Data Anggota →
            </button>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | PERTANYAAN 58 - IJAZAH LAINNYA
    |--------------------------------------------------------------------------
    */

    const ijazahRadios = document.querySelectorAll(
        'input[name="ijazah_tertinggi"]'
    );

    const ijazahLainnyaWrapper = document.getElementById(
        'ijazah-lainnya-wrapper'
    );

    const ijazahLainnyaInput = document.getElementById(
        'ijazah_lainnya'
    );


    function toggleIjazahLainnya() {

        const selected = document.querySelector(
            'input[name="ijazah_tertinggi"]:checked'
        );

        if (!selected) {
            ijazahLainnyaWrapper.classList.remove('show');
            ijazahLainnyaInput.required = false;
            return;
        }

        if (selected.value === 'Lainnya') {

            ijazahLainnyaWrapper.classList.add('show');
            ijazahLainnyaInput.required = true;

        } else {

            ijazahLainnyaWrapper.classList.remove('show');
            ijazahLainnyaInput.required = false;

        }
    }


    ijazahRadios.forEach(function (radio) {

        radio.addEventListener('change', function () {
            toggleIjazahLainnya();
        });

    });


    toggleIjazahLainnya();


    /*
    |--------------------------------------------------------------------------
    | PERTANYAAN 59 - PEKERJAAN LAINNYA
    |--------------------------------------------------------------------------
    */

    const pekerjaanRadios = document.querySelectorAll(
        'input[name="pekerjaan_utama"]'
    );

    const pekerjaanLainnyaWrapper = document.getElementById(
        'pekerjaan-lainnya-wrapper'
    );

    const pekerjaanLainnyaInput = document.getElementById(
        'pekerjaan_lainnya'
    );


    function togglePekerjaanLainnya() {

        const selected = document.querySelector(
            'input[name="pekerjaan_utama"]:checked'
        );

        if (!selected) {

            pekerjaanLainnyaWrapper.classList.remove('show');
            pekerjaanLainnyaInput.required = false;

            return;
        }


        if (selected.value === 'Lainnya') {

            pekerjaanLainnyaWrapper.classList.add('show');
            pekerjaanLainnyaInput.required = true;

        } else {

            pekerjaanLainnyaWrapper.classList.remove('show');
            pekerjaanLainnyaInput.required = false;

        }
    }


    pekerjaanRadios.forEach(function (radio) {

        radio.addEventListener('change', function () {
            togglePekerjaanLainnya();
        });

    });


    togglePekerjaanLainnya();


    /*
    |--------------------------------------------------------------------------
    | PERTANYAAN 59 -> 60
    |--------------------------------------------------------------------------
    |
    | Jika pekerjaan = Tidak Bekerja:
    | - Pertanyaan 60 disembunyikan
    | - Radio 60 disabled
    | - Required dihapus
    | - Pilihan 60 dihapus
    |
    | Jika pekerjaan selain Tidak Bekerja:
    | - Pertanyaan 60 ditampilkan
    | - Radio 60 aktif
    | - Required kembali
    |
    */

    const pekerjaanQuestionRadios = document.querySelectorAll(
    'input[name="pekerjaan_utama"]'
    );

    const question60 = document.getElementById(
        'part5-question-60'
    );

    const statusPekerjaanRadios = document.querySelectorAll(
        'input[name="status_pekerjaan"]'
    );

    function toggleQuestion60() {

        const selected = document.querySelector(
            'input[name="pekerjaan_utama"]:checked'
        );

        if (!selected) {

            question60.classList.remove('hidden-question');

            statusPekerjaanRadios.forEach(function (radio) {
                radio.disabled = false;
                radio.required = true;
            });

            return;
        }

        if (selected.value === 'Tidak Bekerja') {

            // Sembunyikan pertanyaan 60
            question60.classList.add('hidden-question');

            // Nonaktifkan dan kosongkan jawaban pertanyaan 60
            statusPekerjaanRadios.forEach(function (radio) {
                radio.checked = false;
                radio.disabled = true;
                radio.required = false;
            });

        } else {

            // Tampilkan pertanyaan 60
            question60.classList.remove('hidden-question');

            // Aktifkan kembali pilihan pertanyaan 60
            statusPekerjaanRadios.forEach(function (radio) {
                radio.disabled = false;
                radio.required = true;
            });
        }
    }

    pekerjaanQuestionRadios.forEach(function (radio) {

        radio.addEventListener('change', function () {
            toggleQuestion60();
        });

    });
    /*
    |--------------------------------------------------------------------------
    | JALANKAN SAAT HALAMAN PERTAMA KALI DIBUKA
    |--------------------------------------------------------------------------
    */

    toggleQuestion60();

});
</script>

@endsection