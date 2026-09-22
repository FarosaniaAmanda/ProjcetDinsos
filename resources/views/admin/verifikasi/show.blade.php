@extends('admin.layouts.app')

@section('title', 'Detail Verifikasi')

@push('styles')
<style>
    /* =====================================================
       DETAIL VERIFIKASI
    ====================================================== */

    .verification-container {
        width: 100%;
        max-width: 760px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e7e8ee;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        padding: 28px;
    }

    .verification-title {
        font-size: 28px;
        font-weight: 700;
        color: #222;
        margin-bottom: 8px;
    }

    .verification-subtitle {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 24px;
    }

    .verification-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .verification-field {
        background: #f9fafb;
        border: 1px solid #ececf1;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .verification-field label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #777;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .verification-field strong {
        font-size: 14px;
        color: #222;
        line-height: 1.5;
        word-break: break-word;
    }

    .verification-form-group {
        margin-top: 12px;
    }

    .verification-form-group > label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        color: #333;
        font-size: 14px;
    }

    .verification-form-group select {
        width: 100%;
        height: 44px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        padding: 0 12px;
        font-size: 14px;
        background: #fff;
        color: #333;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .verification-form-group select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, .10);
    }

    .verification-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .verification-btn {
        min-height: 44px;
        border: none;
        border-radius: 8px;
        padding: 12px 18px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .verification-btn-primary {
        background: #252A86;
        color: #ffffff;
    }

    .verification-btn-primary:hover {
        background: #1e236f;
        transform: translateY(-1px);
    }

    .verification-btn-secondary {
        background: #eef0f7;
        color: #333;
    }

    .verification-btn-secondary:hover {
        background: #e1e4ee;
        transform: translateY(-1px);
    }


    /* =====================================================
       TABLET
    ====================================================== */

    @media (max-width: 1100px) {
        .verification-container {
            max-width: 760px;
        }
    }


    /* =====================================================
       MOBILE
    ====================================================== */

    @media (max-width: 700px) {

        .verification-container {
            padding: 20px;
            border-radius: 12px;
        }

        .verification-title {
            font-size: 23px;
            line-height: 1.3;
        }

        .verification-subtitle {
            font-size: 13px;
            margin-bottom: 20px;
        }

        .verification-grid {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .verification-field {
            padding: 13px 14px;
        }

        .verification-field strong {
            font-size: 13px;
        }

        .verification-form-group {
            margin-top: 10px;
        }

        .verification-form-group select {
            height: 44px;
            font-size: 13px;
        }

        .verification-actions {
            gap: 10px;
        }

        .verification-btn {
            flex: 1;
            min-width: 120px;
            font-size: 13px;
            padding: 11px 14px;
        }
    }


    /* =====================================================
       SMALL MOBILE
    ====================================================== */

    @media (max-width: 430px) {

        .verification-container {
            padding: 16px;
        }

        .verification-title {
            font-size: 21px;
        }

        .verification-subtitle {
            font-size: 12px;
        }

        .verification-field {
            padding: 12px;
        }

        .verification-field label {
            font-size: 10px;
        }

        .verification-field strong {
            font-size: 12px;
        }

        .verification-actions {
            flex-direction: column;
        }

        .verification-btn {
            width: 100%;
            flex: none;
        }
    }
</style>
@endpush


@section('content')

    <div class="verification-container">

        <div class="verification-title">
            Detail Verifikasi
        </div>

        <div class="verification-subtitle">
            Periksa data dan ubah status responden.
        </div>


        {{-- =====================================================
             DATA RESPONDEN
        ====================================================== --}}

        <div class="verification-grid">

            <div class="verification-field">
                <label>No. KK</label>
                <strong>{{ $item['no_kk'] }}</strong>
            </div>

            <div class="verification-field">
                <label>NIK</label>
                <strong>{{ $item['nik'] }}</strong>
            </div>

            <div class="verification-field">
                <label>Nama</label>
                <strong>{{ $item['nama'] }}</strong>
            </div>

            <div class="verification-field">
                <label>Wilayah</label>
                <strong>{{ $item['wilayah'] }}</strong>
            </div>

            <div class="verification-field">
                <label>Petugas</label>
                <strong>{{ $item['petugas'] }}</strong>
            </div>

            <div class="verification-field">
                <label>Status Saat Ini</label>
                <strong>{{ $item['status_label'] }}</strong>
            </div>

        </div>


        {{-- =====================================================
             FORM UBAH STATUS
        ====================================================== --}}

        <form
            action="{{ route('verifikasi.update', $item['id'] ?? 0) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="verification-form-group">

                <label for="status">
                    Ubah Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                >

                    <option value="" disabled>
                        Pilih Status Verifikasi
                    </option>

                    <option
                        value="approved"
                        {{ ($item['status'] ?? '') === 'approved' ? 'selected' : '' }}
                    >
                        Approved
                    </option>

                    <option
                        value="rejected"
                        {{ ($item['status'] ?? '') === 'rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                </select>

            </div>


            {{-- =================================================
                 TOMBOL AKSI
            ================================================== --}}

            <div class="verification-actions">

                <button
                    type="submit"
                    class="verification-btn verification-btn-primary"
                >
                    Simpan
                </button>

                <a
                    href="{{ route('verifikasi.index') }}"
                    class="verification-btn verification-btn-secondary"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>

@endsection