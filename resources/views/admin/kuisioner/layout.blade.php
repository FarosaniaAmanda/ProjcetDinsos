@extends('admin.layouts.app')

@section('content')

<div class="kuisioner-page">

    {{-- =====================================================
         BANNER KUISIONER
    ====================================================== --}}

    <div class="kuisioner-hero">

        <div class="hero-badge">
            FORM KUISIONER
        </div>

        <h1>
            Kuisioner Pendataan Keluarga
        </h1>

        <p>
            Silakan lengkapi data keluarga dengan benar dan sesuai kondisi sebenarnya.
        </p>

    </div>


    {{-- =====================================================
         NAVIGASI PART 1 - 5
    ====================================================== --}}

    <div class="kuisioner-navigation">

        <a
            href="{{ route('kuisioner.part1') }}"
            class="kuisioner-nav-item {{ request()->routeIs('kuisioner.part1') ? 'active' : '' }}"
        >
            <span class="nav-number">1</span>
            <span class="nav-title">Keluarga</span>
        </a>


        <a
            href="{{ route('kuisioner.part2') }}"
            class="kuisioner-nav-item {{ request()->routeIs('kuisioner.part2') ? 'active' : '' }}"
        >
            <span class="nav-number">2</span>
            <span class="nav-title">Kondisi Rumah</span>
        </a>


        <a
            href="{{ route('kuisioner.part3') }}"
            class="kuisioner-nav-item {{ request()->routeIs('kuisioner.part3') ? 'active' : '' }}"
        >
            <span class="nav-number">3</span>
            <span class="nav-title">
                Keuangan<br>
                Keluarga
            </span>
        </a>


        <a
            href="{{ route('kuisioner.part4') }}"
            class="kuisioner-nav-item {{ request()->routeIs('kuisioner.part4') ? 'active' : '' }}"
        >
            <span class="nav-number">4</span>
            <span class="nav-title">Aset Keluarga</span>
        </a>


        <a
            href="{{ route('kuisioner.part5') }}"
            class="kuisioner-nav-item {{ request()->routeIs('kuisioner.part5') ? 'active' : '' }}"
        >
            <span class="nav-number">5</span>
            <span class="nav-title">
                Anggota<br>
                Keluarga
            </span>
        </a>

    </div>


    {{-- =====================================================
         ISI MASING-MASING PART
    ====================================================== --}}

    @yield('kuisioner-content')

</div>


{{-- =========================================================
     STYLE LAYOUT KUISIONER
========================================================= --}}

<style>

.kuisioner-page {
    width: 100%;
}


/* =========================================================
   BANNER BIRU
========================================================= */

.kuisioner-hero {
    background: #2d328f;
    border-radius: 26px;
    padding: 36px 42px;
    margin-bottom: 20px;
    color: white;
    box-shadow: 0 15px 35px rgba(45, 50, 143, 0.12);
}

.hero-badge {
    display: inline-block;
    padding: 9px 17px;
    border-radius: 10px;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.25);
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 16px;
}

.kuisioner-hero h1 {
    margin: 0;
    font-size: 36px;
    font-weight: 800;
}

.kuisioner-hero p {
    margin: 14px 0 0;
    font-size: 18px;
    color: #dfe2ff;
}


/* =========================================================
   NAVIGASI PART
========================================================= */

.kuisioner-navigation {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

.kuisioner-nav-item {
    min-height: 88px;
    padding: 16px;
    border: 1px solid #dfe2ee;
    border-radius: 17px;
    background: #ffffff;
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    color: #697086;
    transition: 0.2s ease;
}

.kuisioner-nav-item:hover {
    border-color: #c5c9ef;
}

.kuisioner-nav-item.active {
    background: #f0f1ff;
    border-color: #bfc4ff;
    color: #20278c;
}

.nav-number {
    width: 45px;
    height: 45px;
    min-width: 45px;
    border-radius: 13px;
    background: #eef0f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 18px;
}

.kuisioner-nav-item.active .nav-number {
    background: #2d328f;
    color: white;
}

.nav-title {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.3;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .kuisioner-navigation {
        grid-template-columns: 1fr 1fr;
    }

}

@media (max-width: 600px) {

    .kuisioner-hero {
        padding: 28px 24px;
    }

    .kuisioner-hero h1 {
        font-size: 28px;
    }

    .kuisioner-hero p {
        font-size: 15px;
    }

    .kuisioner-navigation {
        grid-template-columns: 1fr;
    }

}

</style>

@endsection