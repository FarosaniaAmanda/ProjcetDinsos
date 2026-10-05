@extends('admin.layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .kuisioner-layout {
        --primary: #292D8F;
        --primary-dark: #25297F;
        --primary-soft: #F0F1FF;
        --border: #E2E4EF;
        --border-active: #C8CBF7;
        --text: #25283A;
        --muted: #777D91;
        --background: #F5F6FB;
        --white: #FFFFFF;

        width: 100%;
        font-family: 'Inter', sans-serif;
        color: var(--text);
    }

    .kuisioner-layout *,
    .kuisioner-layout *::before,
    .kuisioner-layout *::after {
        box-sizing: border-box;
    }


    /* =========================================================
       AREA UTAMA
       ========================================================= */

    .kuisioner-page {
        width: 100%;
        min-height: 100vh;
        background: var(--background);

        padding: 20px 18px 38px;
    }

    .kuisioner-container {
        width: 100%;
        max-width: 1345px;
        margin: 0 auto;
    }


    /* =========================================================
       HEADER KUISIIONER
       ========================================================= */

    .kuisioner-header {
        width: 100%;
        height: 170px;

        background: linear-gradient(
            135deg,
            #292D8F 0%,
            #2F3294 100%
        );

        border-radius: 20px;

        padding: 23px 32px;

        display: flex;
        flex-direction: column;
        justify-content: center;

        box-shadow:
            0 11px 26px rgba(41, 45, 143, 0.13);
    }


    /* =========================================================
       LABEL FORM KUISIIONER
       ========================================================= */

    .kuisioner-label {
        display: flex;
        align-items: center;
        justify-content: center;

        width: fit-content;
        height: 30px;

        padding: 0 13px;

        margin-bottom: 13px;

        border-radius: 8px;

        background: rgba(255, 255, 255, 0.13);

        border: 1px solid rgba(255, 255, 255, 0.20);

        color: #FFFFFF;

        font-size: 12px;
        font-weight: 700;

        line-height: 1;
    }


    /* =========================================================
       JUDUL
       ========================================================= */

    .kuisioner-title {
        margin: 0;

        color: #FFFFFF;

        font-size: 29px;
        font-weight: 800;

        line-height: 1.15;

        letter-spacing: -0.5px;
    }


    /* =========================================================
       SUBTITLE
       ========================================================= */

    .kuisioner-subtitle {
        margin: 11px 0 0;

        color: rgba(255, 255, 255, 0.80);

        font-size: 15px;
        font-weight: 400;

        line-height: 1.5;
    }


    /* =========================================================
       NAVIGASI PART
       ========================================================= */

    .part-navigation {
        width: 100%;

        display: grid;

        grid-template-columns:
            repeat(5, minmax(0, 1fr));

        gap: 11px;

        margin-top: 16px;
    }


    /* =========================================================
       CARD PART
       ========================================================= */

    .part-item {
        width: 100%;
        height: 68px;

        display: flex;
        align-items: center;

        gap: 11px;

        padding: 0 16px;

        background: #FFFFFF;

        border: 1px solid var(--border);

        border-radius: 13px;

        color: var(--muted);

        text-decoration: none;

        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease;
    }


    /* =========================================================
       HOVER
       ========================================================= */

    .part-item:hover {
        color: var(--primary);

        background: #FAFAFF;

        border-color: var(--border-active);

        text-decoration: none;
    }


    /* =========================================================
       ACTIVE
       ========================================================= */

    .part-item.active {
        color: var(--primary);

        background: var(--primary-soft);

        border-color: var(--border-active);
    }


    /* =========================================================
       NOMOR PART
       ========================================================= */

    .part-number {
        flex: 0 0 35px;

        width: 35px;
        height: 35px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #EEF0F4;

        color: #5F6577;

        font-size: 14px;
        font-weight: 600;

        line-height: 1;
    }


    /* NOMOR AKTIF */

    .part-item.active .part-number {
        background: var(--primary);
        color: #FFFFFF;
    }


    /* =========================================================
       TEKS PART
       ========================================================= */

    .part-text {
        min-width: 0;

        display: flex;
        align-items: center;
    }

    .part-text strong {
        display: block;

        margin: 0;
        padding: 0;

        color: inherit;

        font-size: 10px;
        font-weight: 500;

        line-height: 1.25;
    }


    /* =========================================================
       ISI PART
       ========================================================= */

    .kuisioner-content {
        width: 100%;
        margin-top: 22px;
    }


    /* =========================================================
       RESPONSIVE - LAPTOP
       ========================================================= */

    @media (max-width: 1250px) {

        .kuisioner-page {
            padding: 20px 17px 35px;
        }

        .kuisioner-header {
            height: 168px;
            padding: 23px 32px;
        }

        .kuisioner-title {
            font-size: 28px;
        }

        .kuisioner-subtitle {
            font-size: 14px;
        }

        .part-item {
            padding: 0 13px;
            gap: 9px;
        }

        .part-text strong {
            font-size: 10px;
        }
    }


    /* =========================================================
       RESPONSIVE - TABLET
       ========================================================= */

    @media (max-width: 1000px) {

        .kuisioner-page {
            padding: 20px 15px 32px;
        }

        .kuisioner-header {
            height: 155px;
            padding: 24px 26px;
        }

        .kuisioner-title {
            font-size: 26px;
        }

        .kuisioner-subtitle {
            font-size: 13px;
        }

        .part-navigation {
            gap: 9px;
        }

        .part-item {
            height: 62px;
            padding: 0 10px;
            gap: 8px;
        }

        .part-number {
            flex-basis: 32px;
            width: 32px;
            height: 32px;

            font-size: 13px;
        }

        .part-text strong {
            font-size: 9px;
        }
    }


    /* =========================================================
       RESPONSIVE - MOBILE
       ========================================================= */

    @media (max-width: 750px) {

        .kuisioner-page {
            padding: 20px 12px 28px;
        }

        .kuisioner-header {
            height: auto;
            min-height: 145px;

            padding: 21px 20px;

            border-radius: 17px;
        }

        .kuisioner-label {
            height: 28px;

            padding: 0 11px;

            margin-bottom: 11px;

            font-size: 11px;

            border-radius: 7px;
        }

        .kuisioner-title {
            font-size: 23px;
        }

        .kuisioner-subtitle {
            margin-top: 9px;
            font-size: 12px;
        }

        .part-navigation {
            gap: 6px;
            margin-top: 13px;
        }

        .part-item {
            height: 54px;

            padding: 0 7px;

            gap: 6px;

            border-radius: 11px;
        }

        .part-number {
            flex-basis: 29px;

            width: 29px;
            height: 29px;

            border-radius: 8px;

            font-size: 12px;
        }

        .part-text strong {
            font-size: 8px;
        }

        .kuisioner-content {
            margin-top: 14px;
        }
    }


    /* =========================================================
       RESPONSIVE - HP KECIL
       ========================================================= */

    @media (max-width: 560px) {

        .kuisioner-page {
            padding: 15px 8px 25px;
        }

        .kuisioner-header {
            min-height: 130px;

            padding: 18px 16px;

            border-radius: 14px;
        }

        .kuisioner-label {
            height: 25px;

            padding: 0 10px;

            margin-bottom: 9px;

            border-radius: 6px;

            font-size: 9px;
        }

        .kuisioner-title {
            font-size: 20px;
            letter-spacing: -0.3px;
        }

        .kuisioner-subtitle {
            margin-top: 7px;
            font-size: 10px;
        }

        .part-navigation {
            gap: 5px;
            margin-top: 10px;
        }

        .part-item {
            height: 45px;

            justify-content: center;

            padding: 0;

            border-radius: 9px;
        }

        .part-number {
            flex-basis: 27px;

            width: 27px;
            height: 27px;

            border-radius: 7px;

            font-size: 10px;
        }

        .part-text {
            display: none;
        }

        .kuisioner-content {
            margin-top: 11px;
        }
    }
</style>


<div class="kuisioner-layout">

    <div class="kuisioner-page">

        <div class="kuisioner-container">

            {{-- =====================================================
                 HEADER
                 ===================================================== --}}

            <div class="kuisioner-header">

                <div class="kuisioner-label">
                    FORM KUISIIONER
                </div>

                <h1 class="kuisioner-title">
                    Kuisioner Pendataan Keluarga
                </h1>

                <p class="kuisioner-subtitle">
                    Silakan lengkapi data keluarga dengan benar dan sesuai kondisi sebenarnya.
                </p>

            </div>


            {{-- =====================================================
                 NAVIGASI PART
                 ===================================================== --}}

            <nav class="part-navigation">

                {{-- PART 1 --}}
                <a
                    href="{{ route('kuisioner.part1') }}"
                    class="part-item {{ request()->routeIs('kuisioner.part1') ? 'active' : '' }}"
                >
                    <div class="part-number">
                        1
                    </div>

                    <div class="part-text">
                        <strong>
                            Keluarga
                        </strong>
                    </div>
                </a>


                {{-- PART 2 --}}
                <a
                    href="{{ route('kuisioner.part2') }}"
                    class="part-item {{ request()->routeIs('kuisioner.part2') ? 'active' : '' }}"
                >
                    <div class="part-number">
                        2
                    </div>

                    <div class="part-text">
                        <strong>
                            Kondisi Rumah
                        </strong>
                    </div>
                </a>


                {{-- PART 3 --}}
                <a
                    href="{{ route('kuisioner.part3') }}"
                    class="part-item {{ request()->routeIs('kuisioner.part3') ? 'active' : '' }}"
                >
                    <div class="part-number">
                        3
                    </div>

                    <div class="part-text">
                        <strong>
                            Keuangan Keluarga
                        </strong>
                    </div>
                </a>


                {{-- PART 4 --}}
                <a
                    href="{{ route('kuisioner.part4') }}"
                    class="part-item {{ request()->routeIs('kuisioner.part4') ? 'active' : '' }}"
                >
                    <div class="part-number">
                        4
                    </div>

                    <div class="part-text">
                        <strong>
                            Aset Keluarga
                        </strong>
                    </div>
                </a>


                {{-- PART 5 --}}
                <a
                    href="{{ route('kuisioner.part5') }}"
                    class="part-item {{ request()->routeIs('kuisioner.part5') ? 'active' : '' }}"
                >
                    <div class="part-number">
                        5
                    </div>

                    <div class="part-text">
                        <strong>
                            Anggota Keluarga
                        </strong>
                    </div>
                </a>

            </nav>


            {{-- =====================================================
                 ISI PART
                 ===================================================== --}}

            <div class="kuisioner-content">

                @yield('kuisioner-content')

            </div>

        </div>

    </div>

</div>

@endsection