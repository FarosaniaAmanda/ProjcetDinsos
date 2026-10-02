@extends('admin.layouts.app')

@section('title', 'Detail Kuisioner Selesai')

@push('styles')
<style>
    /* =========================================================
       PAGE
    ========================================================= */
    .detail-page {
        padding: 10px 0 30px;
    }

    .detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .detail-header-left h1 {
        margin: 0 0 5px;
        color: #252A86;
        font-size: 26px;
        font-weight: 800;
    }

    .detail-header-left p {
        margin: 0;
        color: #667085;
        font-size: 14px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 17px;
        border-radius: 9px;
        background: #ffffff;
        color: #344054;
        border: 1px solid #d0d5dd;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: .2s ease;
    }

    .btn-back:hover {
        background: #f8f9fc;
        color: #252A86;
        border-color: #252A86;
    }

    /* =========================================================
       CARD
    ========================================================= */
    .detail-card {
        background: #ffffff;
        border: 1px solid #e4e7ec;
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(16, 24, 40, .04);
    }

    .detail-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 17px 20px;
        background: #fafbff;
        border-bottom: 1px solid #eaecf0;
        cursor: pointer;
    }

    .detail-card-title {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .part-number {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 9px;
        background: #252A86;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
    }

    .detail-card-title h2 {
        margin: 0;
        color: #252A86;
        font-size: 17px;
        font-weight: 800;
    }

    .detail-card-title span {
        display: block;
        margin-top: 2px;
        color: #667085;
        font-size: 12px;
        font-weight: 500;
    }

    .toggle-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef0ff;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: transform .2s ease;
    }

    .detail-card.active .toggle-icon {
        transform: rotate(180deg);
    }

    .detail-card-body {
        display: none;
        padding: 20px;
    }

    .detail-card.active .detail-card-body {
        display: block;
    }

    /* =========================================================
       INFORMATION GRID
    ========================================================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .info-item {
        border: 1px solid #eaecf0;
        border-radius: 10px;
        padding: 14px 15px;
        background: #ffffff;
    }

    .info-label {
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .info-value {
        color: #344054;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.55;
        word-break: break-word;
    }

    .info-value.empty {
        color: #98a2b3;
        font-weight: 500;
    }

    /* =========================================================
       STATUS
    ========================================================= */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-selesai {
        color: #167647;
        background: #ecfdf3;
        border: 1px solid #abefc6;
    }

    /* =========================================================
       QUESTION / ANSWER
    ========================================================= */
    .question-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .question-item {
        border: 1px solid #eaecf0;
        border-radius: 10px;
        overflow: hidden;
        background: #ffffff;
    }

    .question-label {
        padding: 12px 15px;
        background: #f8f9fc;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
        border-bottom: 1px solid #eaecf0;
    }

    .question-answer {
        padding: 13px 15px;
        color: #475467;
        font-size: 14px;
        line-height: 1.65;
        white-space: pre-line;
        word-break: break-word;
    }

    .answer-empty {
        color: #98a2b3;
        font-style: italic;
    }

    /* =========================================================
       ANGGOTA KELUARGA
    ========================================================= */
    .member-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .member-card {
        border: 1px solid #dfe3ea;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .member-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 16px;
        background: #f8f9fc;
        border-bottom: 1px solid #eaecf0;
    }

    .member-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .member-number {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        background: #252A86;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .member-name {
        color: #344054;
        font-size: 15px;
        font-weight: 800;
    }

    .member-body {
        padding: 16px;
    }

    /* =========================================================
       FOTO
    ========================================================= */
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .photo-card {
        border: 1px solid #eaecf0;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .photo-title {
        padding: 11px 13px;
        color: #344054;
        font-size: 13px;
        font-weight: 800;
        border-bottom: 1px solid #eaecf0;
    }

    .photo-wrapper {
        height: 210px;
        background: #f8f9fc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .photo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        cursor: pointer;
        transition: transform .2s ease;
    }

    .photo-wrapper img:hover {
        transform: scale(1.03);
    }

    .photo-empty {
        color: #98a2b3;
        font-size: 13px;
        text-align: center;
        padding: 20px;
    }

    /* =========================================================
       EMPTY
    ========================================================= */
    .empty-data {
        padding: 28px 20px;
        text-align: center;
        border: 1px dashed #d0d5dd;
        border-radius: 10px;
        color: #667085;
        font-size: 14px;
        background: #fafafa;
    }

    /* =========================================================
       PHOTO MODAL
    ========================================================= */
    .image-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, .82);
        align-items: center;
        justify-content: center;
        padding: 25px;
    }

    .image-modal.show {
        display: flex;
    }

    .image-modal-content {
        position: relative;
        max-width: 1000px;
        max-height: 90vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-modal-content img {
        max-width: 100%;
        max-height: 85vh;
        border-radius: 10px;
        object-fit: contain;
        background: #ffffff;
    }

    .image-modal-close {
        position: absolute;
        top: -42px;
        right: 0;
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: #ffffff;
        color: #344054;
        font-size: 22px;
        cursor: pointer;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */
    @media (max-width: 900px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .photo-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .detail-page {
            padding-top: 5px;
        }

        .detail-header {
            align-items: stretch;
        }

        .detail-header-left h1 {
            font-size: 21px;
        }

        .detail-header-left p {
            font-size: 13px;
            line-height: 1.5;
        }

        .btn-back {
            justify-content: center;
            width: 100%;
        }

        .detail-card {
            border-radius: 11px;
            margin-bottom: 14px;
        }

        .detail-card-header {
            padding: 14px;
        }

        .detail-card-body {
            padding: 14px;
        }

        .detail-card-title h2 {
            font-size: 15px;
        }

        .detail-card-title span {
            font-size: 11px;
        }

        .part-number {
            width: 31px;
            height: 31px;
            min-width: 31px;
        }

        .info-grid {
            gap: 10px;
        }

        .info-item {
            padding: 12px;
        }

        .info-value {
            font-size: 13px;
        }

        .question-label {
            font-size: 12px;
            padding: 11px 12px;
        }

        .question-answer {
            font-size: 13px;
            padding: 12px;
        }

        .photo-grid {
            grid-template-columns: 1fr;
        }

        .photo-wrapper {
            height: 220px;
        }

        .member-header {
            padding: 13px;
        }

        .member-body {
            padding: 13px;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="detail-header">

        <div class="detail-header-left">
            <h1>Detail Kuisioner Selesai</h1>
            <p>
                Informasi lengkap hasil pendataan responden dan jawaban kuisioner Part 1 sampai Part 5.
            </p>
        </div>

        <a href="{{ route('kuisioner.selesai') }}" class="btn-back">
            <span>←</span>
            <span>Kembali ke Kuisioner Selesai</span>
        </a>

    </div>


    {{-- =====================================================
         INFORMASI RESPONDEN
    ====================================================== --}}
    <div class="detail-card active">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">
                <div class="part-number">1</div>

                <div>
                    <h2>Informasi Responden</h2>
                    <span>Identitas keluarga yang telah menyelesaikan kuisioner</span>
                </div>
            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Nomor KK</div>
                    <div class="info-value">
                        {{ $dataPart1->nomor_kk ?? $dataPart1->no_kk ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">NIK Kepala Keluarga</div>
                    <div class="info-value">
                        {{ $dataPart1->nik ?? $dataPart1->nik_kepala_keluarga ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nama Kepala Keluarga</div>
                    <div class="info-value">
                        {{ $dataPart1->nama_kepala_keluarga ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Kecamatan</div>
                    <div class="info-value">
                        {{ $dataPart1->kecamatan ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Kelurahan</div>
                    <div class="info-value">
                        {{ $dataPart1->kelurahan ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">
                        {{ $dataPart1->alamat ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">RT</div>
                    <div class="info-value">
                        {{ $dataPart1->rt ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">RW</div>
                    <div class="info-value">
                        {{ $dataPart1->rw ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Kode Pos</div>
                    <div class="info-value">
                        {{ $dataPart1->kode_pos ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Status Kuisioner</div>
                    <div class="info-value">
                        <span class="status-badge status-selesai">
                            ● Selesai
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Tanggal Selesai</div>
                    <div class="info-value">
                        {{ optional($dataPart1->updated_at)->format('d-m-Y H:i') ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Kode Keluarga</div>
                    <div class="info-value">
                        {{ $dataPart1->keluarga_periode_kode ?? '-' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- =====================================================
         PART 1
    ====================================================== --}}
    <div class="detail-card">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">
                <div class="part-number">1</div>

                <div>
                    <h2>Part 1</h2>
                    <span>Data dasar keluarga</span>
                </div>
            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            @if($dataPart1)

                @php
                    $part1Hidden = [
                        'id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'keluarga_periode_kode',
                        'status',
                        'current_part',
                        'created_by',
                        'updated_by',
                    ];

                    $part1Fields = collect($dataPart1->getAttributes())
                        ->except($part1Hidden);
                @endphp

                <div class="question-list">

                    @forelse($part1Fields as $field => $value)

                        <div class="question-item">

                            <div class="question-label">
                                {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                            </div>

                            <div class="question-answer">
                                @if(is_array($value))
                                    {{ implode(', ', $value) }}
                                @elseif(is_object($value))
                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                @elseif($value === null || $value === '')
                                    <span class="answer-empty">Belum diisi</span>
                                @else
                                    {{ $value }}
                                @endif
                            </div>

                        </div>

                    @empty

                        <div class="empty-data">
                            Data Part 1 belum tersedia.
                        </div>

                    @endforelse

                </div>

            @else

                <div class="empty-data">
                    Data Part 1 belum tersedia.
                </div>

            @endif

        </div>
    </div>


    {{-- =====================================================
         PART 2
    ====================================================== --}}
    <div class="detail-card">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">
                <div class="part-number">2</div>

                <div>
                    <h2>Part 2</h2>
                    <span>Jawaban kuisioner bagian kedua</span>
                </div>
            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            @if($dataPart2)

                @php
                    $part2Hidden = [
                        'id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'keluarga_periode_kode',
                    ];

                    $part2Fields = collect($dataPart2->getAttributes())
                        ->except($part2Hidden);
                @endphp

                <div class="question-list">

                    @forelse($part2Fields as $field => $value)

                        <div class="question-item">

                            <div class="question-label">
                                {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                            </div>

                            <div class="question-answer">

                                @if(is_array($value))
                                    {{ implode(', ', $value) }}

                                @elseif(is_object($value))
                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                @elseif($value === null || $value === '')
                                    <span class="answer-empty">
                                        Belum diisi
                                    </span>

                                @else
                                    {{ $value }}
                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="empty-data">
                            Data Part 2 belum tersedia.
                        </div>

                    @endforelse

                </div>

            @else

                <div class="empty-data">
                    Data Part 2 belum tersedia.
                </div>

            @endif

        </div>
    </div>


    {{-- =====================================================
         PART 3
    ====================================================== --}}
    <div class="detail-card">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">
                <div class="part-number">3</div>

                <div>
                    <h2>Part 3</h2>
                    <span>Jawaban kuisioner bagian ketiga</span>
                </div>
            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            @if($dataPart3)

                @php
                    $part3Hidden = [
                        'id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'keluarga_periode_kode',
                    ];

                    $part3Fields = collect($dataPart3->getAttributes())
                        ->except($part3Hidden);
                @endphp

                <div class="question-list">

                    @forelse($part3Fields as $field => $value)

                        <div class="question-item">

                            <div class="question-label">
                                {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                            </div>

                            <div class="question-answer">

                                @if(is_array($value))
                                    {{ implode(', ', $value) }}

                                @elseif(is_object($value))
                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                @elseif($value === null || $value === '')
                                    <span class="answer-empty">
                                        Belum diisi
                                    </span>

                                @else
                                    {{ $value }}
                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="empty-data">
                            Data Part 3 belum tersedia.
                        </div>

                    @endforelse

                </div>

            @else

                <div class="empty-data">
                    Data Part 3 belum tersedia.
                </div>

            @endif

        </div>
    </div>


    {{-- =====================================================
         PART 4
    ====================================================== --}}
    <div class="detail-card">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">
                <div class="part-number">4</div>

                <div>
                    <h2>Part 4</h2>
                    <span>Jawaban kuisioner bagian keempat</span>
                </div>
            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            @if(isset($dataPart4) && $dataPart4->count())

                @foreach($dataPart4 as $index => $part4)

                    @php
                        $part4Hidden = [
                            'id',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'keluarga_periode_kode',
                        ];

                        $part4Fields = collect($part4->getAttributes())
                            ->except($part4Hidden);
                    @endphp

                    <div class="member-card" style="margin-bottom: 14px;">

                        <div class="member-header">
                            <div class="member-title">

                                <div class="member-number">
                                    {{ $index + 1 }}
                                </div>

                                <div class="member-name">
                                    Data Part 4
                                    {{ $index + 1 }}
                                </div>

                            </div>
                        </div>

                        <div class="member-body">

                            <div class="question-list">

                                @foreach($part4Fields as $field => $value)

                                    <div class="question-item">

                                        <div class="question-label">
                                            {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                        </div>

                                        <div class="question-answer">

                                            @if(is_array($value))
                                                {{ implode(', ', $value) }}

                                            @elseif(is_object($value))
                                                {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                            @elseif($value === null || $value === '')
                                                <span class="answer-empty">
                                                    Belum diisi
                                                </span>

                                            @else
                                                {{ $value }}
                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endforeach

            @else

                <div class="empty-data">
                    Data Part 4 belum tersedia.
                </div>

            @endif

        </div>
    </div>


    {{-- =====================================================
         PART 5 - ANGGOTA KELUARGA
    ====================================================== --}}
    <div class="detail-card">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">

                <div class="part-number">5</div>

                <div>
                    <h2>Part 5 - Anggota Keluarga</h2>
                    <span>Data dan jawaban setiap anggota keluarga</span>
                </div>

            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            @if(isset($anggota) && $anggota->count())

                <div class="member-list">

                    @foreach($anggota as $index => $member)

                        @php
                            $memberCode =
                                $member->keluarga_anggota_kode
                                ?? $member->kode
                                ?? $member->id;

                            $memberPart5 = null;

                            if (isset($dataPart5)) {
                                $memberPart5 = $dataPart5->get($memberCode);
                            }

                            $memberFields = collect($member->getAttributes())
                                ->except([
                                    'id',
                                    'created_at',
                                    'updated_at',
                                    'deleted_at',
                                    'keluarga_kode',
                                ]);
                        @endphp

                        <div class="member-card">

                            <div class="member-header">

                                <div class="member-title">

                                    <div class="member-number">
                                        {{ $index + 1 }}
                                    </div>

                                    <div>
                                        <div class="member-name">
                                            {{ $member->nama_lengkap ?? $member->nama ?? 'Anggota Keluarga' }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="member-body">

                                {{-- DATA ANGGOTA --}}
                                <div class="question-list">

                                    @foreach($memberFields as $field => $value)

                                        <div class="question-item">

                                            <div class="question-label">
                                                {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                            </div>

                                            <div class="question-answer">

                                                @if(is_array($value))
                                                    {{ implode(', ', $value) }}

                                                @elseif(is_object($value))
                                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                @elseif($value === null || $value === '')
                                                    <span class="answer-empty">
                                                        Belum diisi
                                                    </span>

                                                @else
                                                    {{ $value }}
                                                @endif

                                            </div>

                                        </div>

                                    @endforeach

                                </div>


                                {{-- JAWABAN PART 5 --}}
                                @if($memberPart5)

                                    <div style="height:16px;"></div>

                                    <div class="question-list">

                                        @php
                                            $part5Fields = collect($memberPart5->getAttributes())
                                                ->except([
                                                    'id',
                                                    'created_at',
                                                    'updated_at',
                                                    'deleted_at',
                                                    'keluarga_periode_kode',
                                                    'keluarga_anggota_kode',
                                                ]);
                                        @endphp

                                        @foreach($part5Fields as $field => $value)

                                            <div class="question-item">

                                                <div class="question-label">
                                                    {{ ucwords(str_replace(['_', '-'], ' ', $field)) }}
                                                </div>

                                                <div class="question-answer">

                                                    @if(is_array($value))
                                                        {{ implode(', ', $value) }}

                                                    @elseif(is_object($value))
                                                        {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}

                                                    @elseif($value === null || $value === '')
                                                        <span class="answer-empty">
                                                            Belum diisi
                                                        </span>

                                                    @else
                                                        {{ $value }}
                                                    @endif

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                @else

                                    <div style="margin-top:14px;" class="empty-data">
                                        Jawaban Part 5 untuk anggota ini belum tersedia.
                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-data">
                    Data anggota keluarga belum tersedia.
                </div>

            @endif

        </div>
    </div>


    {{-- =====================================================
         FOTO RUMAH
    ====================================================== --}}
    <div class="detail-card">

        <div class="detail-card-header" onclick="toggleCard(this)">
            <div class="detail-card-title">

                <div class="part-number">+</div>

                <div>
                    <h2>Foto Rumah</h2>
                    <span>Dokumentasi rumah responden</span>
                </div>

            </div>

            <div class="toggle-icon">⌃</div>
        </div>

        <div class="detail-card-body">

            @if(isset($fotoRumah) && $fotoRumah->count())

                <div class="photo-grid">

                    @foreach($fotoRumah as $jenis => $foto)

                        @php
                            $fotoPath =
                                $foto->path
                                ?? $foto->foto
                                ?? $foto->file_path
                                ?? $foto->nama_file
                                ?? null;
                        @endphp

                        <div class="photo-card">

                            <div class="photo-title">
                                {{ ucwords(str_replace(['_', '-'], ' ', $jenis)) }}
                            </div>

                            <div class="photo-wrapper">

                                @if($fotoPath)

                                    <img
                                        src="{{ asset('storage/' . ltrim($fotoPath, '/')) }}"
                                        alt="Foto {{ $jenis }}"
                                        onclick="openImage(this.src)"
                                    >

                                @else

                                    <div class="photo-empty">
                                        Foto tidak tersedia.
                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-data">
                    Belum ada foto rumah yang tersimpan.
                </div>

            @endif

        </div>
    </div>

</div>


{{-- =========================================================
     IMAGE MODAL
========================================================= --}}
<div class="image-modal" id="imageModal" onclick="closeImage(event)">

    <div class="image-modal-content">

        <button
            type="button"
            class="image-modal-close"
            onclick="closeImage(event)"
        >
            ×
        </button>

        <img
            id="previewImage"
            src=""
            alt="Preview Foto"
        >

    </div>

</div>
@endsection


@push('scripts')
<script>
    /* =========================================================
       ACCORDION
    ========================================================= */
    function toggleCard(header) {

        const currentCard = header.closest('.detail-card');

        if (!currentCard) {
            return;
        }

        const allCards = document.querySelectorAll('.detail-card');

        allCards.forEach(function(card) {

            if (card !== currentCard) {
                card.classList.remove('active');
            }

        });

        currentCard.classList.toggle('active');
    }


    /* =========================================================
       IMAGE PREVIEW
    ========================================================= */
    function openImage(src) {

        const modal = document.getElementById('imageModal');
        const image = document.getElementById('previewImage');

        if (!modal || !image) {
            return;
        }

        image.src = src;
        modal.classList.add('show');

        document.body.style.overflow = 'hidden';
    }


    function closeImage(event) {

        if (event) {

            const clickedElement = event.target;

            if (
                clickedElement.id !== 'imageModal' &&
                !clickedElement.classList.contains('image-modal-close')
            ) {
                return;
            }
        }

        const modal = document.getElementById('imageModal');
        const image = document.getElementById('previewImage');

        if (!modal || !image) {
            return;
        }

        modal.classList.remove('show');
        image.src = '';

        document.body.style.overflow = '';
    }


    /* =========================================================
       ESCAPE CLOSE IMAGE
    ========================================================= */
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            const modal = document.getElementById('imageModal');

            if (modal && modal.classList.contains('show')) {
                closeImage({
                    target: modal
                });
            }

        }

    });
</script>
@endpush