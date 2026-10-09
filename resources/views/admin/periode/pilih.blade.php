@extends('admin.layouts.app')

@section('title', 'Pilih Periode')

@section('content')

<style>

    .periode-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 18, 35, 0.48);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .periode-modal {
        width: 100%;
        max-width: 560px;
        max-height: calc(100vh - 40px);
        background: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 22px;
        box-shadow:
            0 25px 70px rgba(0, 0, 0, 0.20),
            0 8px 25px rgba(41, 45, 143, 0.10);
        overflow: hidden;
        animation: periodeModalIn .25s ease-out;
    }

    @keyframes periodeModalIn {

        from {
            opacity: 0;
            transform: translateY(15px) scale(.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

    }

    .periode-modal-header {
        padding: 26px 26px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        border-bottom: 1px solid #EEF0F5;
    }

    .periode-close {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        border: 0;
        border-radius: 9px;
        background: #FDEAEA;
        color: #C0392B;
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        transition: .2s ease;
    }

    .periode-close:hover {
        background: #C0392B;
        color: #FFFFFF;
    }

    .periode-warning {
        position: fixed;
        top: 24px;
        left: 24px;
        z-index: 10000;
        display: flex;
        align-items: flex-start;
        gap: 11px;
        max-width: min(360px, calc(100vw - 48px));
        padding: 13px 16px;
        border: 1px solid #F1C885;
        border-radius: 10px;
        background: #FFFBF2;
        color: #72500B;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .16);
        font-size: 13px;
        line-height: 1.5;
    }

    .periode-warning[hidden] {
        display: none;
    }

    .periode-warning-icon {
        width: 22px;
        height: 22px;
        flex: 0 0 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
        border-radius: 50%;
        background: #FFF0CC;
        color: #9A6800;
        font-size: 13px;
        font-weight: 800;
    }

    .periode-warning-content {
        flex: 1;
    }

    .periode-warning-title {
        display: block;
        margin-bottom: 2px;
        color: #4D3A12;
        font-size: 13px;
        font-weight: 700;
    }

    .periode-warning-message {
        display: block;
        color: #72500B;
        font-size: 12px;
    }

    .periode-warning-selesai {
        border-color: #F0C1BE;
        background: #FFF8F7;
        color: #842D27;
    }

    .periode-warning-selesai .periode-warning-icon {
        background: #FDE7E5;
        color: #B43A31;
    }

    .periode-warning-selesai .periode-warning-title,
    .periode-warning-selesai .periode-warning-message {
        color: #842D27;
    }

    .periode-warning-close {
        flex: 0 0 auto;
        padding: 0;
        border: 0;
        background: transparent;
        color: #7A8297;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
    }

    .periode-header-text {
        min-width: 0;
    }

    .periode-header-text h2 {
        margin: 0;
        color: #222675;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .periode-header-text p {
        margin: 5px 0 0;
        color: #7A8297;
        font-size: 12.5px;
        line-height: 1.5;
    }

    .periode-modal-body {
        padding: 20px 26px 26px;
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }

    .periode-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .periode-form {
        margin: 0;
    }

    .periode-option {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px;
        background: #FFFFFF;
        border: 1px solid #E4E7EF;
        border-radius: 14px;
        cursor: pointer;
        text-align: left;
        font: inherit;
        transition:
            border-color .2s ease,
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .periode-option:hover {
        background: #FAFAFF;
        border-color: #E4E7EF;
        transform: none;
        box-shadow: none;
    }

    .periode-option:focus,
    .periode-option:focus-visible {
        outline: none;
        border-color: #E4E7EF;
        box-shadow: none;
    }

    .periode-option.disabled {
        background: #FFFFFF;
        border-color: #E4E7EF;
        cursor: pointer;
        opacity: 1;
    }

    .periode-option.disabled:hover {
        background: #FFFFFF;
        border-color: #E4E7EF;
        transform: none;
        box-shadow: none;
    }

    .periode-option.disabled:focus,
    .periode-option.disabled:focus-visible,
    .periode-option.disabled:active {
        outline: none;
        border-color: #E4E7EF;
        box-shadow: none;
    }

    .periode-info {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .periode-name {
        overflow: hidden;
        color: #252A86;
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .periode-date {
        color: #7A8297;
        font-size: 11.5px;
        font-weight: 500;
    }

    .periode-status {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        margin-top: 3px;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .periode-status-aktif {
        background: #E8F8EE;
        color: #21874B;
    }

    .periode-status-akan-datang {
        background: #FFF4DD;
        color: #A66B00;
    }

    .periode-status-selesai {
        background: #FDEAEA;
        color: #C0392B;
    }

    .periode-empty {
        padding: 35px 20px;
        text-align: center;
    }

    .periode-empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #F1F2F6;
        color: #8A91A3;
        font-size: 20px;
    }

    .periode-empty h3 {
        margin: 0 0 5px;
        color: #39415A;
        font-size: 15px;
        font-weight: 800;
    }

    .periode-empty p {
        margin: 0;
        color: #7B8498;
        font-size: 12px;
    }

    .periode-modal-body::-webkit-scrollbar {
        width: 5px;
    }

    .periode-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .periode-modal-body::-webkit-scrollbar-thumb {
        background: #D7DAE5;
        border-radius: 10px;
    }

    @media (max-width: 600px) {

        .periode-overlay {
            padding: 14px;
        }

        .periode-modal {
            max-width: 100%;
            border-radius: 18px;
        }

        .periode-modal-header {
            padding: 21px 20px 17px;
        }

        .periode-modal-body {
            padding: 16px 20px 20px;
        }

        .periode-header-text h2 {
            font-size: 19px;
        }

        .periode-option {
            padding: 12px;
        }

    }

</style>

<div class="periode-overlay">

    <div id="periodeWarning" class="periode-warning" role="alert" aria-live="assertive" hidden>
        <span class="periode-warning-icon" aria-hidden="true">!</span>
        <span class="periode-warning-content">
            <strong class="periode-warning-title">Periode tidak dapat dipilih</strong>
            <span class="periode-warning-message"></span>
        </span>
        <button type="button" class="periode-warning-close" aria-label="Tutup peringatan" onclick="tutupPeringatanPeriode()">&times;</button>
    </div>

    <div class="periode-modal">

        {{-- HEADER MODAL --}}
        <div class="periode-modal-header">

            <div class="periode-header-text">

                <h2>Pilih Periode</h2>

                <p>
                    Pilih periode pendataan yang sedang aktif.
                </p>

            </div>

            <button
                type="button"
                class="periode-close"
                aria-label="Kembali ke halaman sebelumnya"
                title="Kembali"
                onclick="window.history.back()"
            >
                &times;
            </button>

        </div>


        {{-- ISI MODAL --}}
        <div class="periode-modal-body">

            @if($periodes->count() > 0)

                <div class="periode-list">

                    @foreach($periodes as $periode)

                        @php
                            $status = strtolower(trim($periode->status ?? ''));
                            $isAktif = $status === 'aktif';
                        @endphp


                        @if($isAktif)

                            {{-- PERIODE AKTIF --}}
                            <form
                                action="{{ route('periode.set') }}"
                                method="POST"
                                class="periode-form"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="periode_id"
                                    value="{{ $periode->id }}"
                                >

                                <input
                                    type="hidden"
                                    name="tujuan"
                                    value="{{ $tujuan }}"
                                >

                                <button
                                    type="submit"
                                    class="periode-option"
                                >

                                    <div class="periode-option-left">

                                        <div class="periode-info">

                                            <div class="periode-name">
                                                {{ $periode->nama }}
                                            </div>

                                            <div class="periode-date">

                                                {{ $periode->tgl_awal?->format('d M Y') }}

                                                &nbsp;–&nbsp;

                                                {{ $periode->tgl_akhir?->format('d M Y') }}

                                            </div>

                                            <span class="periode-status periode-status-aktif">
                                                Aktif
                                            </span>

                                        </div>

                                    </div>

                                </button>

                            </form>

                        @else

                            {{-- PERIODE TIDAK AKTIF --}}
                            <button
                                type="button"
                                class="periode-option disabled"
                                title="Periode ini tidak dapat dipilih."
                                onclick="tampilkanPeringatanPeriode(this.dataset.status)"
                                data-status="{{ $status }}"
                            >

                                <div class="periode-option-left">

                                    <div class="periode-info">

                                        <div class="periode-name">
                                            {{ $periode->nama }}
                                        </div>

                                        <div class="periode-date">

                                            {{ $periode->tgl_awal?->format('d M Y') }}

                                            &nbsp;–&nbsp;

                                            {{ $periode->tgl_akhir?->format('d M Y') }}

                                        </div>

                                        @if($status === 'akan datang')

                                            <span class="periode-status periode-status-akan-datang">
                                                Akan Datang
                                            </span>

                                        @elseif($status === 'selesai')

                                            <span class="periode-status periode-status-selesai">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="periode-status periode-status-selesai">
                                                Tidak Aktif
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </button>

                        @endif

                    @endforeach

                </div>

            @else

                <div class="periode-empty">

                    <div class="periode-empty-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>

                    <h3>
                        Belum ada periode
                    </h3>

                    <p>
                        Silakan buat periode terlebih dahulu.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

<script>
    let periodeWarningTimer;

    function tampilkanPeringatanPeriode(status) {
        const warning = document.getElementById('periodeWarning');
        const message = warning.querySelector('.periode-warning-message');

        if (status === 'akan datang') {
            warning.classList.remove('periode-warning-selesai');
            message.textContent = 'Periode belum dimulai. Silakan pilih periode yang berstatus Aktif.';
        } else if (status === 'selesai') {
            warning.classList.add('periode-warning-selesai');
            message.textContent = 'Periode telah berakhir. Silakan pilih periode yang berstatus Aktif.';
        } else {
            warning.classList.remove('periode-warning-selesai');
            message.textContent = 'Periode ini tidak tersedia untuk dipilih.';
        }

        warning.hidden = false;
        window.clearTimeout(periodeWarningTimer);
        periodeWarningTimer = window.setTimeout(function () {
            tutupPeringatanPeriode();
        }, 5000);
    }

    function tutupPeringatanPeriode() {
        document.getElementById('periodeWarning').hidden = true;
        window.clearTimeout(periodeWarningTimer);
    }
</script>

@endsection