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

        border-bottom: 1px solid #EEF0F5;
    }

    .periode-icon {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #EEF0FF;
        color: #292D8F;

        font-size: 20px;
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

        transition:
            border-color .2s ease,
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .periode-option:hover {
        background: #FAFAFF;
        border-color: #292D8F;

        transform: translateY(-1px);

        box-shadow:
            0 7px 18px rgba(41, 45, 143, .08);
    }

    .periode-option:active {
        transform: translateY(0);
    }

    .periode-option-left {
        min-width: 0;

        display: flex;
        align-items: center;

        gap: 13px;
    }

    .periode-calendar {
        width: 43px;
        height: 43px;
        flex: 0 0 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #EEF0FF;
        color: #292D8F;

        font-size: 17px;
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

    .periode-arrow {
        width: 31px;
        height: 31px;
        flex: 0 0 31px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #F3F4F8;
        color: #7A8297;

        font-size: 12px;

        transition: .2s ease;
    }

    .periode-option:hover .periode-arrow {
        background: #292D8F;
        color: #FFFFFF;
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

        .periode-icon {
            width: 43px;
            height: 43px;
            flex-basis: 43px;
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

```
<div class="periode-modal">

    {{-- HEADER MODAL --}}
    <div class="periode-modal-header">

        <div class="periode-icon">
            <i class="bi bi-calendar3"></i>
        </div>

        <div class="periode-header-text">

            <h2>Pilih Periode</h2>

            <p>
                Pilih periode pendataan yang ingin digunakan.
            </p>

        </div>

    </div>


    {{-- ISI MODAL --}}
    <div class="periode-modal-body">

        @if($periodes->count() > 0)

            <div class="periode-list">

                @foreach($periodes as $periode)

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

                                <div class="periode-calendar">
                                    <i class="bi bi-calendar-event"></i>
                                </div>

                                <div class="periode-info">

                                    <div class="periode-name">
                                        {{ $periode->nama }}
                                    </div>

                                    <div class="periode-date">

                                        {{ $periode->tgl_awal?->format('d M Y') }}

                                        &nbsp;–&nbsp;

                                        {{ $periode->tgl_akhir?->format('d M Y') }}

                                    </div>

                                </div>

                            </div>


                            <div class="periode-arrow">
                                <i class="bi bi-chevron-right"></i>
                            </div>

                        </button>

                    </form>

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
```

</div>

@endsection
