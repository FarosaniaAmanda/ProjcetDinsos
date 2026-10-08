@extends('admin.layouts.app')

@section('title', 'Detail Kuisioner Selesai')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    .detail-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 20px 22px 40px;
        font-family: 'Inter', Arial, sans-serif;
        color: #252525;
    }

    /* =========================================================
       HEADER
    ========================================================= */
    .detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 18px;
    }

    .detail-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .detail-header h1 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #222675;
    }

    .detail-header p {
        margin: 3px 0 0;
        color: #7b7f91;
        font-size: 12px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border-radius: 8px;
        background: #f0f1f8;
        color: #252A86;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-back:hover {
        background: #e5e7f4;
        color: #252A86;
    }

    /* =========================================================
       CARD
    ========================================================= */
    .detail-card {
        background: #fff;
        border: 1px solid #e5e6ef;
        border-radius: 12px;
        margin-bottom: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(30, 35, 80, .04);
    }

    .detail-card-header {
        padding: 12px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        background: #fff;
    }

    .detail-card-title {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .detail-card-title h3 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #252A86;
    }

    .detail-card-title span {
        display: block;
        margin-top: 2px;
        color: #9295a5;
        font-size: 10px;
        font-weight: 400;
    }

    .detail-card-arrow {
        font-size: 12px;
        color: #888da0;
        transition: transform .2s;
    }

    .detail-card.active .detail-card-arrow {
        transform: rotate(180deg);
    }

    .detail-card-body {
        display: none;
        border-top: 1px solid #eeeeF3;
        padding: 15px;
    }

    .detail-card.active .detail-card-body {
        display: block;
    }

    /* =========================================================
       INFORMASI
    ========================================================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .info-item {
        background: #f7f8fc;
        border: 1px solid #eceef5;
        border-radius: 8px;
        padding: 10px 11px;
    }

    .info-label {
        font-size: 9px;
        color: #8b8e9e;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .info-value {
        font-size: 12px;
        font-weight: 600;
        color: #30324b;
        word-break: break-word;
    }

    /* =========================================================
       LAYOUT PART
    ========================================================= */
    .questionnaire-layout {
        display: grid;
        grid-template-columns: 190px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
    }

    /* =========================================================
       NAV PART KIRI
    ========================================================= */
    .part-nav {
        background: #f7f8fc;
        border: 1px solid #e5e7f0;
        border-radius: 10px;
        padding: 8px;
        position: sticky;
        top: 90px;
    }

    .part-nav-title {
        font-size: 9px;
        font-weight: 700;
        color: #9699a8;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 5px 7px 8px;
    }

    .part-nav-item {
        width: 100%;
        border: 0;
        background: transparent;
        border-radius: 7px;
        padding: 9px 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-align: left;
        cursor: pointer;
        color: #5d6070;
        margin-bottom: 3px;
        transition: .18s;
    }

    .part-nav-item:hover {
        background: #eceef8;
    }

    .part-nav-item.active {
        background: #252A86;
        color: #fff;
    }

    .part-nav-number {
        width: 23px;
        height: 23px;
        border-radius: 6px;
        background: #e6e8f5;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .part-nav-item.active .part-nav-number {
        background: rgba(255,255,255,.18);
        color: #fff;
    }

    .part-nav-text {
        font-size: 10.5px;
        font-weight: 600;
        line-height: 1.3;
    }

    /* =========================================================
       PANEL JAWABAN
    ========================================================= */
    .part-panel {
        min-width: 0;
    }

    .part-content {
        display: none;
        background: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        padding: 15px;
    }

    .part-content.active {
        display: block;
    }

    .part-content-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 13px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eeeeF3;
    }

    .part-content-title {
        font-size: 14px;
        font-weight: 700;
        color: #252A86;
        margin: 0;
    }

    .part-content-subtitle {
        margin: 3px 0 0;
        color: #999cab;
        font-size: 10px;
    }

    /* =========================================================
       JAWABAN
    ========================================================= */
    .answers-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .answer-item {
        background: #fafbfe;
        border: 1px solid #e8e9f0;
        border-radius: 7px;
        padding: 9px 10px;
        min-height: 55px;
    }

    .answer-label {
        font-size: 9.5px;
        color: #777b8d;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .answer-value {
        font-size: 11px;
        line-height: 1.45;
        color: #292b3d;
        font-weight: 500;
        word-break: break-word;
    }

    .empty-answer {
        padding: 20px;
        text-align: center;
        color: #999cab;
        font-size: 11px;
        background: #fafbfe;
        border: 1px dashed #dfe1ea;
        border-radius: 8px;
    }

    /* =========================================================
       RECORD
    ========================================================= */
    .record-card {
        border: 1px solid #e6e7ef;
        border-radius: 8px;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .record-card:last-child {
        margin-bottom: 0;
    }

    .record-card-title {
        padding: 8px 10px;
        background: #f7f8fc;
        border-bottom: 1px solid #e8e9ef;
        color: #252A86;
        font-size: 10px;
        font-weight: 700;
    }

    .record-card-body {
        padding: 9px;
    }

    /* =========================================================
       ANGGOTA
    ========================================================= */
    .member-card {
        border: 1px solid #e5e7ef;
        border-radius: 8px;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .member-card:last-child {
        margin-bottom: 0;
    }

    .member-header {
        padding: 9px 10px;
        background: #f7f8fc;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid #e8e9ef;
    }

    .member-number {
        width: 23px;
        height: 23px;
        border-radius: 6px;
        background: #252A86;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 700;
    }

    .member-name {
        font-size: 11px;
        font-weight: 700;
        color: #30324b;
    }

    .member-body {
        padding: 9px;
    }

    /* =========================================================
       FOTO RUMAH
    ========================================================= */
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .photo-card {
        border: 1px solid #e3e5ee;
        border-radius: 9px;
        overflow: hidden;
        background: #fff;
    }

    .photo-image-wrapper {
        position: relative;
        width: 100%;
        height: 155px;
        background: #f4f5f9;
        overflow: hidden;
    }

    .photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        cursor: pointer;
        transition: .2s;
    }

    .photo-preview:hover {
        transform: scale(1.02);
    }

    .photo-error {
        display: none;
        position: absolute;
        inset: 0;
        background: #f4f5f9;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 5px;
    }

    .photo-error-icon,
    .photo-empty-icon,
    .empty-photo-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: #e8eaf5;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .photo-error-text,
    .photo-empty-text {
        font-size: 9px;
        color: #85899b;
    }

    .photo-image-wrapper.no-photo {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 6px;
    }

    .photo-title {
        padding: 8px 9px;
        font-size: 10px;
        font-weight: 600;
        color: #44475a;
        background: #fff;
    }

    .empty-photo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 25px;
        border: 1px dashed #dfe1ea;
        border-radius: 9px;
        background: #fafbfe;
    }

    .empty-photo strong {
        display: block;
        color: #55586b;
        font-size: 11px;
    }

    .empty-photo span {
        display: block;
        margin-top: 2px;
        color: #999cab;
        font-size: 9px;
    }

    .photo-view-icon {
        position: absolute;
        right: 8px;
        bottom: 8px;
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: rgba(37,42,134,.9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        pointer-events: none;
    }

    /* =========================================================
       MODAL FOTO
    ========================================================= */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        inset: 0;
        background: rgba(15, 18, 45, .88);
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .image-modal.show {
        display: flex;
    }

    .image-modal img {
        max-width: 88vw;
        max-height: 82vh;
        object-fit: contain;
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 15px 50px rgba(0,0,0,.25);
    }

    .image-modal-close {
        position: absolute;
        top: 18px;
        right: 22px;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        background: #fff;
        color: #252A86;
        cursor: pointer;
        font-size: 18px;
        font-weight: 700;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */
    @media (max-width: 900px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .questionnaire-layout {
            grid-template-columns: 165px minmax(0, 1fr);
        }

        .answers-grid {
            grid-template-columns: 1fr;
        }

        .photo-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .detail-page {
            padding: 15px 12px 30px;
        }

        .detail-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .questionnaire-layout {
            grid-template-columns: 1fr;
        }

        .part-nav {
            position: static;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
        }

        .part-nav-title {
            grid-column: 1 / -1;
        }

        .part-nav-item {
            margin: 0;
        }

        .part-nav-text {
            font-size: 9px;
        }
    }

    @media (max-width: 480px) {
        .part-nav {
            grid-template-columns: 1fr 1fr;
        }

        .info-grid,
        .answers-grid,
        .photo-grid {
            grid-template-columns: 1fr;
        }

        .detail-card-body,
        .part-content {
            padding: 11px;
        }

        .part-content-title {
            font-size: 13px;
        }
    }
</style>


<div class="detail-page">

    {{-- =========================================================
        HEADER
    ========================================================= --}}
    <div class="detail-header">

        <div class="detail-header-left">

            <div>
                <h1>Detail Kuisioner Selesai</h1>
                <p>Detail jawaban pendataan keluarga</p>
            </div>

        </div>

        <a href="{{ route('kuisioner.selesai') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>


    {{-- =========================================================
        INFORMASI RESPONDEN
    ========================================================= --}}
    <div class="detail-card active">

        <div class="detail-card-header"
             onclick="toggleCard(this)">

            <div class="detail-card-title">

                <div>
                    <h3>Informasi Responden</h3>
                    <span>Identitas keluarga yang didata</span>
                </div>

            </div>

            <i class="fas fa-chevron-down detail-card-arrow"></i>

        </div>


        <div class="detail-card-body">

            @if($dataPart1)

                @php
                    $p1 = $dataPart1;

                    /*
                    * RT / RW
                    * Prioritas dari data Part 1.
                    * Jika kosong, coba ambil dari relasi keluarga jika tersedia.
                    */
                    $rt = $p1->rt
                        ?? ($p1->keluarga->rt ?? null);

                    $rw = $p1->rw
                        ?? ($p1->keluarga->rw ?? null);

                    /*
                    * Kota / Kabupaten
                    */
                    $kota = $p1->kabupaten
                        ?? $p1->kabupaten_kota
                        ?? $p1->kota
                        ?? ($p1->keluarga->kabupaten ?? null)
                        ?? ($p1->keluarga->kota ?? null)
                        ?? '-';
                @endphp

                <div class="info-grid">

                    {{-- NO KK --}}
                    <div class="info-item">
                        <div class="info-label">No. KK</div>
                        <div class="info-value">
                            {{ $p1->no_kk ?? $p1->nomor_kk ?? '-' }}
                        </div>
                    </div>

                    {{-- NAMA KEPALA KELUARGA --}}
                    <div class="info-item">
                        <div class="info-label">Nama Kepala Keluarga</div>
                        <div class="info-value">
                            {{ $p1->nama_kepala_keluarga ?? '-' }}
                        </div>
                    </div>

                    {{-- NIK --}}
                    <div class="info-item">
                        <div class="info-label">NIK</div>
                        <div class="info-value">
                            {{ $p1->nik ?? '-' }}
                        </div>
                    </div>

                    {{-- JUMLAH KELUARGA --}}
                    <div class="info-item">
                        <div class="info-label">Jumlah Keluarga</div>
                        <div class="info-value">
                            {{ $p1->jml_keluarga ?? '-' }}
                        </div>
                    </div>

                    {{-- PROVINSI --}}
                    <div class="info-item">
                        <div class="info-label">Provinsi</div>
                        <div class="info-value">
                            {{ $p1->provinsi ?? '-' }}
                        </div>
                    </div>

                    {{-- KOTA --}}
                    <div class="info-item">
                        <div class="info-label">Kota / Kabupaten</div>
                        <div class="info-value">
                            {{ $kota }}
                        </div>
                    </div>

                    {{-- KECAMATAN --}}
                    <div class="info-item">
                        <div class="info-label">Kecamatan</div>
                        <div class="info-value">
                            {{ $p1->kecamatan ?? '-' }}
                        </div>
                    </div>

                    {{-- KELURAHAN --}}
                    <div class="info-item">
                        <div class="info-label">Kelurahan / Desa</div>
                        <div class="info-value">
                            {{ $p1->kelurahan ?? $p1->desa ?? '-' }}
                        </div>
                    </div>

                    {{-- RT / RW --}}
                    <div class="info-item">
                        <div class="info-label">RT / RW</div>
                        <div class="info-value">
                            @if($rt || $rw)
                                {{ $rt ?? '-' }} / {{ $rw ?? '-' }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    {{-- KODE POS --}}
                    <div class="info-item">
                        <div class="info-label">Kode Pos</div>
                        <div class="info-value">
                            {{ $p1->kode_pos ?? '-' }}
                        </div>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="info-item">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">
                            {{ $p1->alamat ?? $p1->alamat_lengkap ?? '-' }}
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            {{ $p1->status ?? '-' }}
                        </div>
                    </div>

                </div>

            @else

                <div class="empty-answer">
                    Data identitas responden belum tersedia.
                </div>

            @endif

        </div>


    {{-- =========================================================
        PART NAVIGATION + JAWABAN
    ========================================================= --}}
    <div class="questionnaire-layout">

        {{-- =====================================================
            NAVIGASI KIRI
        ===================================================== --}}
        <div class="part-nav">

            <div class="part-nav-title">
                Bagian Kuisioner
            </div>

            <button type="button"
                    class="part-nav-item active"
                    onclick="selectPart('part1', this)">

                <div class="part-nav-number">1</div>

                <div class="part-nav-text">
                    Identitas & Tempat Tinggal
                </div>

            </button>


            <button type="button"
                    class="part-nav-item"
                    onclick="selectPart('part2', this)">

                <div class="part-nav-number">2</div>

                <div class="part-nav-text">
                    Kondisi Tempat Tinggal
                </div>

            </button>


            <button type="button"
                    class="part-nav-item"
                    onclick="selectPart('part3', this)">

                <div class="part-nav-number">3</div>

                <div class="part-nav-text">
                    Keuangan Keluarga
                </div>

            </button>


            <button type="button"
                    class="part-nav-item"
                    onclick="selectPart('part4', this)">

                <div class="part-nav-number">4</div>

                <div class="part-nav-text">
                    Aset Keluarga
                </div>

            </button>


            <button type="button"
                    class="part-nav-item"
                    onclick="selectPart('part5', this)">

                <div class="part-nav-number">5</div>

                <div class="part-nav-text">
                    Anggota Keluarga
                </div>

            </button>


            <button type="button"
                    class="part-nav-item"
                    onclick="selectPart('foto', this)">

                <i class="fas fa-camera"
                   style="font-size: 13px; width: 23px; text-align: center;"></i>

                <div class="part-nav-text">
                    Foto Rumah
                </div>

            </button>

        </div>


        {{-- =====================================================
            PANEL KANAN
        ===================================================== --}}
        <div class="part-panel">


            {{-- =================================================
                PART 1
            ================================================= --}}
            <div class="part-content active" id="part1">

                <div class="part-content-header">

                    <div>
                        <h3 class="part-content-title">
                            Part 1 — Identitas & Tempat Tinggal
                        </h3>

                        <p class="part-content-subtitle">
                            Data identitas dan lokasi tempat tinggal keluarga.
                        </p>
                    </div>

                </div>


                @if($dataPart1)

                    @php
                        $attributes = $dataPart1->getAttributes();

                        $hiddenPart1 = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_periode_kode',
                            'status',
                            'current_part',
                            'created_by',
                            'updated_by'
                        ];
                    @endphp

                    <div class="answers-grid">

                        @foreach($attributes as $field => $value)

                            @if(!in_array($field, $hiddenPart1))

                                <div class="answer-item">

                                    <div class="answer-label">
                                        {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                    </div>

                                    <div class="answer-value">

                                        @if(is_array($value))

                                            {{ implode(', ', $value) }}

                                        @elseif(is_object($value))

                                            {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                        @else

                                            {{ $value !== null && $value !== '' ? $value : '-' }}

                                        @endif

                                    </div>

                                </div>

                            @endif

                        @endforeach

                    </div>

                @else

                    <div class="empty-answer">
                        Tidak ada jawaban Part 1.
                    </div>

                @endif

            </div>


            {{-- =================================================
                PART 2
            ================================================= --}}
            <div class="part-content" id="part2">

                <div class="part-content-header">

                    <div>
                        <h3 class="part-content-title">
                            Part 2 — Kondisi Tempat Tinggal
                        </h3>

                        <p class="part-content-subtitle">
                            Jawaban mengenai kondisi rumah dan tempat tinggal.
                        </p>
                    </div>

                </div>


                @if($dataPart2)

                    @php
                        $part2Records = $dataPart2 instanceof \Illuminate\Support\Collection
                            ? $dataPart2
                            : collect([$dataPart2]);

                        $hiddenPart2 = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_periode_kode',
                            'created_by',
                            'updated_by'
                        ];
                    @endphp

                    @foreach($part2Records as $record)

                        @php
                            $attributes = method_exists($record, 'getAttributes')
                                ? $record->getAttributes()
                                : (is_array($record)
                                    ? $record
                                    : get_object_vars($record));
                        @endphp

                        <div class="answers-grid">

                            @foreach($attributes as $field => $value)

                                @if(!in_array($field, $hiddenPart2))

                                    <div class="answer-item">

                                        <div class="answer-label">
                                            {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                        </div>

                                        <div class="answer-value">

                                            @if(is_array($value))

                                                {{ implode(', ', $value) }}

                                            @elseif(is_object($value))

                                                {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                            @else

                                                {{ $value !== null && $value !== '' ? $value : '-' }}

                                            @endif

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    @endforeach

                @else

                    <div class="empty-answer">
                        Tidak ada jawaban Part 2.
                    </div>

                @endif

            </div>


            {{-- =================================================
                PART 3
            ================================================= --}}
            <div class="part-content" id="part3">

                <div class="part-content-header">

                    <div>
                        <h3 class="part-content-title">
                            Part 3 — Keuangan Keluarga
                        </h3>

                        <p class="part-content-subtitle">
                            Jawaban mengenai kondisi ekonomi dan keuangan keluarga.
                        </p>
                    </div>

                </div>


                @if($dataPart3)

                    @php
                        $part3Records = $dataPart3 instanceof \Illuminate\Support\Collection
                            ? $dataPart3
                            : collect([$dataPart3]);

                        $hiddenPart3 = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_periode_kode',
                            'created_by',
                            'updated_by'
                        ];
                    @endphp

                    @foreach($part3Records as $record)

                        @php
                            $attributes = method_exists($record, 'getAttributes')
                                ? $record->getAttributes()
                                : (is_array($record)
                                    ? $record
                                    : get_object_vars($record));
                        @endphp

                        <div class="answers-grid">

                            @foreach($attributes as $field => $value)

                                @if(!in_array($field, $hiddenPart3))

                                    <div class="answer-item">

                                        <div class="answer-label">
                                            {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                        </div>

                                        <div class="answer-value">

                                            @if(is_array($value))

                                                {{ implode(', ', $value) }}

                                            @elseif(is_object($value))

                                                {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                            @else

                                                {{ $value !== null && $value !== '' ? $value : '-' }}

                                            @endif

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    @endforeach

                @else

                    <div class="empty-answer">
                        Tidak ada jawaban Part 3.
                    </div>

                @endif

            </div>


            {{-- =================================================
                PART 4
            ================================================= --}}
            <div class="part-content" id="part4">

                <div class="part-content-header">

                    <div>
                        <h3 class="part-content-title">
                            Part 4 — Aset Keluarga
                        </h3>

                        <p class="part-content-subtitle">
                            Data kepemilikan aset keluarga.
                        </p>
                    </div>

                </div>


                @if($dataPart4 && $dataPart4->count())

                    @php
                        $hiddenPart4 = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_periode_kode',
                            'created_by',
                            'updated_by'
                        ];
                    @endphp

                    @foreach($dataPart4 as $index => $record)

                        @php
                            $attributes = method_exists($record, 'getAttributes')
                                ? $record->getAttributes()
                                : (is_array($record)
                                    ? $record
                                    : get_object_vars($record));
                        @endphp

                        <div class="record-card">

                            <div class="record-card-title">
                                Data Aset {{ $index + 1 }}
                            </div>

                            <div class="record-card-body">

                                <div class="answers-grid">

                                    @foreach($attributes as $field => $value)

                                        @if(!in_array($field, $hiddenPart4))

                                            <div class="answer-item">

                                                <div class="answer-label">
                                                    {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                                </div>

                                                <div class="answer-value">

                                                    @if(is_array($value))

                                                        {{ implode(', ', $value) }}

                                                    @elseif(is_object($value))

                                                        {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                    @else

                                                        {{ $value !== null && $value !== '' ? $value : '-' }}

                                                    @endif

                                                </div>

                                            </div>

                                        @endif

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @endforeach

                @else

                    <div class="empty-answer">
                        Tidak ada data Part 4.
                    </div>

                @endif

            </div>


            {{-- =================================================
                PART 5
            ================================================= --}}
            <div class="part-content" id="part5">

                <div class="part-content-header">

                    <div>
                        <h3 class="part-content-title">
                            Part 5 — Anggota Keluarga
                        </h3>

                        <p class="part-content-subtitle">
                            Data anggota keluarga beserta jawaban kuisionernya.
                        </p>
                    </div>

                </div>


                @if($anggota && $anggota->count())

                    @php

                        $hiddenMember = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_kode',
                            'created_by',
                            'updated_by'
                        ];

                        $hiddenPart5 = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_periode_kode',
                            'keluarga_anggota_kode',
                            'created_by',
                            'updated_by'
                        ];

                    @endphp


                    @foreach($anggota as $index => $member)

                        @php

                            $memberCode =
                                $member->keluarga_anggota_kode
                                ?? $member->kode
                                ?? $member->id;

                            $memberAnswer = null;

                            if ($dataPart5 instanceof \Illuminate\Support\Collection) {

                                $memberAnswer = $dataPart5->get($memberCode);

                                if (!$memberAnswer) {
                                    $memberAnswer = $dataPart5->firstWhere(
                                        'keluarga_anggota_kode',
                                        $memberCode
                                    );
                                }

                                if (!$memberAnswer) {
                                    $memberAnswer = $dataPart5->firstWhere(
                                        'kode',
                                        $memberCode
                                    );
                                }

                            }

                        @endphp


                        <div class="member-card">

                            <div class="member-header">

                                <div class="member-number">
                                    {{ $index + 1 }}
                                </div>

                                <div class="member-name">
                                    {{ $member->nama
                                        ?? $member->nama_lengkap
                                        ?? $member->nama_anggota
                                        ?? 'Anggota Keluarga' }}
                                </div>

                            </div>


                            <div class="member-body">

                                @php
                                    $memberAttributes = method_exists($member, 'getAttributes')
                                        ? $member->getAttributes()
                                        : (is_array($member)
                                            ? $member
                                            : get_object_vars($member));
                                @endphp

                                <div class="answers-grid">

                                    @foreach($memberAttributes as $field => $value)

                                        @if(!in_array($field, $hiddenMember))

                                            <div class="answer-item">

                                                <div class="answer-label">
                                                    {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                                </div>

                                                <div class="answer-value">

                                                    @if(is_array($value))

                                                        {{ implode(', ', $value) }}

                                                    @elseif(is_object($value))

                                                        {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                    @else

                                                        {{ $value !== null && $value !== '' ? $value : '-' }}

                                                    @endif

                                                </div>

                                            </div>

                                        @endif

                                    @endforeach

                                </div>


                                @if($memberAnswer)

                                    @php

                                        $part5Attributes = method_exists($memberAnswer, 'getAttributes')
                                            ? $memberAnswer->getAttributes()
                                            : (is_array($memberAnswer)
                                                ? $memberAnswer
                                                : get_object_vars($memberAnswer));

                                    @endphp


                                    <div style="margin-top:10px;">

                                        <div class="record-card-title">
                                            Jawaban Kuisioner Anggota
                                        </div>

                                        <div style="margin-top:7px;">

                                            <div class="answers-grid">

                                                @foreach($part5Attributes as $field => $value)

                                                    @if(!in_array($field, $hiddenPart5))

                                                        <div class="answer-item">

                                                            <div class="answer-label">
                                                                {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                                            </div>

                                                            <div class="answer-value">

                                                                @if(is_array($value))

                                                                    {{ implode(', ', $value) }}

                                                                @elseif(is_object($value))

                                                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                                @else

                                                                    {{ $value !== null && $value !== '' ? $value : '-' }}

                                                                @endif

                                                            </div>

                                                        </div>

                                                    @endif

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <div class="empty-answer" style="margin-top:9px;">
                                        Belum ada jawaban kuisioner untuk anggota ini.
                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                @else

                    <div class="empty-answer">
                        Tidak ada data anggota keluarga.
                    </div>

                @endif

            </div>


            {{-- =================================================
                FOTO RUMAH
            ================================================= --}}
            <div class="part-content" id="foto">

                <div class="part-content-header">

                    <div>
                        <h3 class="part-content-title">
                            Foto Rumah
                        </h3>

                        <p class="part-content-subtitle">
                            Dokumentasi foto rumah keluarga.
                        </p>
                    </div>

                </div>


                @if($fotoRumah && $fotoRumah->count())

                    <div class="photo-grid">

                        @foreach($fotoRumah as $type => $foto)

                            @php

                                $fotoPath = null;

                                if (is_object($foto)) {

                                    $fotoPath = $foto->path_file ?? null;

                                    if (!$fotoPath && !empty($foto->nama_file)) {

                                        $fotoPath =
                                            'kuisioner/rumah/' .
                                            ltrim($foto->nama_file, '/');
                                    }

                                } elseif (is_array($foto)) {

                                    $fotoPath =
                                        $foto['path_file'] ?? null;

                                    if (!$fotoPath && !empty($foto['nama_file'])) {

                                        $fotoPath =
                                            'kuisioner/rumah/' .
                                            ltrim($foto['nama_file'], '/');
                                    }

                                }

                                $fotoPath = $fotoPath
                                    ? trim((string) $fotoPath, '/')
                                    : null;

                                $fotoUrl = null;

                                if ($fotoPath) {

                                    if (
                                        str_starts_with($fotoPath, 'http://') ||
                                        str_starts_with($fotoPath, 'https://')
                                    ) {

                                        $fotoUrl = $fotoPath;

                                    } elseif (
                                        str_starts_with($fotoPath, 'storage/')
                                    ) {

                                        $fotoUrl = asset($fotoPath);

                                    } elseif (
                                        str_starts_with($fotoPath, 'public/')
                                    ) {

                                        $fotoUrl = asset(
                                            str_replace(
                                                'public/',
                                                'storage/',
                                                $fotoPath
                                            )
                                        );

                                    } elseif (
                                        str_starts_with($fotoPath, '/storage/')
                                    ) {

                                        $fotoUrl = asset(
                                            ltrim($fotoPath, '/')
                                        );

                                    } else {

                                        $fotoUrl = asset(
                                            'storage/' . $fotoPath
                                        );
                                    }
                                }

                                $fotoTitle = ucwords(
                                    str_replace(
                                        ['_', '-'],
                                        ' ',
                                        $type
                                    )
                                );

                            @endphp


                            <div class="photo-card">

                                @if($fotoUrl)

                                    <div class="photo-image-wrapper">

                                        <img
                                            src="{{ $fotoUrl }}"
                                            alt="{{ $fotoTitle }}"
                                            class="photo-preview"
                                            loading="lazy"
                                            onclick="openImage(this.src)"
                                            onerror="handlePhotoError(this)"
                                        >

                                        <div
                                            class="photo-error"
                                            style="display:none;"
                                        >

                                            <div class="photo-error-icon">
                                                <i class="fas fa-camera"></i>
                                            </div>

                                            <div class="photo-error-text">
                                                Foto tidak tersedia
                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <div class="photo-image-wrapper no-photo">

                                        <div class="photo-empty-icon">
                                            <i class="fas fa-camera"></i>
                                        </div>

                                        <div class="photo-empty-text">
                                            Foto belum tersedia
                                        </div>

                                    </div>

                                @endif


                                <div class="photo-title">
                                    {{ $fotoTitle }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-photo">

                        <div class="empty-photo-icon">
                            <i class="fas fa-camera"></i>
                        </div>

                        <div>
                            <strong>Belum ada foto rumah</strong>

                            <span>
                                Dokumentasi foto belum tersedia.
                            </span>
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =============================================================
        MODAL FOTO
    ============================================================= --}}
    <div class="image-modal"
         id="imageModal"
         onclick="closeImage(event)">

        <button type="button"
                class="image-modal-close"
                onclick="closeImage(event)">
            &times;
        </button>

        <img id="modalImage"
             src=""
             alt="Preview Foto"
             onclick="event.stopPropagation()">

    </div>

</div>


<script>

    /* =========================================================
       ACCORDION INFORMASI
    ========================================================= */
    function toggleCard(header) {

        const card = header.closest('.detail-card');

        if (!card) return;

        card.classList.toggle('active');
    }


    /* =========================================================
       PILIH PART
    ========================================================= */
    function selectPart(partId, button) {

        document.querySelectorAll('.part-nav-item')
            .forEach(function(item) {
                item.classList.remove('active');
            });

        document.querySelectorAll('.part-content')
            .forEach(function(content) {
                content.classList.remove('active');
            });

        if (button) {
            button.classList.add('active');
        }

        const target = document.getElementById(partId);

        if (target) {
            target.classList.add('active');
        }
    }

    /* =========================================================
       BUKA FOTO
    ========================================================= */
    function openImage(url) {

        if (!url) return;

        const modal = document.getElementById('imageModal');
        const image = document.getElementById('modalImage');

        if (!modal || !image) return;

        image.src = url;

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';
    }


    /* =========================================================
       TUTUP FOTO
    ========================================================= */
    function closeImage(event) {

        if (event) {
            event.stopPropagation();
        }

        const modal = document.getElementById('imageModal');
        const image = document.getElementById('modalImage');

        if (!modal || !image) return;

        modal.classList.remove('show');

        image.src = '';

        document.body.style.overflow = '';
    }


    /* =========================================================
       JIKA FOTO GAGAL DIMUAT
    ========================================================= */
    function handlePhotoError(img) {

        img.style.display = 'none';

        const wrapper = img.closest('.photo-image-wrapper');

        if (!wrapper) return;

        const errorBox = wrapper.querySelector('.photo-error');

        if (errorBox) {
            errorBox.style.display = 'flex';
        }

        const viewIcon = wrapper.querySelector('.photo-view-icon');

        if (viewIcon) {
            viewIcon.style.display = 'none';
        }
    }


    /* =========================================================
       ESC UNTUK MODAL
    ========================================================= */
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {
            closeImage();
        }

    });

</script>

@endsection