@extends('admin.kuisioner.layout')

@section('kuisioner-content')

<style>

/* =========================================================
   PART 3 CONTENT
   ========================================================= */

.part3-card {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #E2E4EF;
    border-radius: 17px;
    box-shadow: 0 5px 20px rgba(41, 45, 143, 0.05);
    overflow: hidden;
}


/* =========================================================
   HEADER
   ========================================================= */

.part3-card-header {
    padding: 25px 28px;
    border-bottom: 1px solid #E2E4EF;
    background: #FFFFFF;
}

.part3-card-header h2 {
    margin: 0;
    color: #292D8F;
    font-size: 20px;
    font-weight: 800;
    line-height: 1.3;
}

.part3-card-header p {
    margin: 7px 0 0;
    color: #777D91;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
}


/* =========================================================
   FORM
   ========================================================= */

.part3-form {
    padding: 28px;
}


/* =========================================================
   QUESTION
   ========================================================= */

.part3-question {
    padding: 22px 0;
    border-bottom: 1px solid #E9EAF2;
}

.part3-question:first-child {
    padding-top: 0;
}

.part3-question:last-child {
    border-bottom: none;
}


/* =========================================================
   QUESTION TITLE
   ========================================================= */

.part3-question-title {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
    color: #25283A;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.6;
}

.part3-number {
    flex: 0 0 30px;
    width: 30px;
    height: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;
    background: #F0F1FF;
    color: #292D8F;

    font-size: 11px;
    font-weight: 800;
    line-height: 1;
}


/* =========================================================
   INPUT WRAPPER
   ========================================================= */

.part3-input-wrap {
    padding-left: 42px;
}


/* =========================================================
   INPUT
   ========================================================= */

.part3-input {
    width: 100%;
    height: 43px;

    padding: 0 13px;

    border: 1px solid #D9DCE8;
    border-radius: 9px;

    background: #FFFFFF;
    color: #25283A;

    font-family: inherit;
    font-size: 14px;

    outline: none;
    transition: 0.2s ease;
}

.part3-input:focus {
    border-color: #292D8F;
    box-shadow: 0 0 0 3px rgba(41, 45, 143, 0.08);
}

.part3-input::placeholder {
    color: #A2A6B5;
}


/* =========================================================
   NOMINAL INPUT
   ========================================================= */

.part3-money-wrap {
    position: relative;
}

.part3-money-prefix {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);

    color: #555A6D;
    font-size: 14px;
    font-weight: 600;

    pointer-events: none;
}

.part3-money-input {
    padding-left: 38px;
}


/* =========================================================
   RADIO
   ========================================================= */

.part3-radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 22px;
    padding-left: 42px;
}

.part3-radio {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    color: #555A6D;
    font-size: 14px;
    font-weight: 500;

    cursor: pointer;
}

.part3-radio input {
    width: 16px;
    height: 16px;
    margin: 0;

    accent-color: #292D8F;
    cursor: pointer;
}


/* =========================================================
   CONDITIONAL
   ========================================================= */

.part3-conditional {
    display: none;

    margin-top: 15px;
    margin-left: 42px;

    padding: 17px;

    border: 1px solid #E2E4EF;
    border-radius: 11px;

    background: #FAFAFD;
}

.part3-conditional.show {
    display: block;
}

.part3-conditional-title {
    margin-bottom: 12px;

    color: #4D5265;
    font-size: 13px;
    font-weight: 700;
}


/* =========================================================
   VALIDATION ERROR
   ========================================================= */

.part3-error {
    margin-bottom: 18px;
    padding: 13px 16px;

    border: 1px solid #F2B8B5;
    border-radius: 10px;

    background: #FFF4F3;
    color: #A32924;

    font-size: 12px;
    line-height: 1.6;
}

.part3-error ul {
    margin: 0;
    padding-left: 18px;
}


/* =========================================================
   SUCCESS
   ========================================================= */

.part3-success {
    margin-bottom: 18px;
    padding: 13px 16px;

    border: 1px solid #B9DFC9;
    border-radius: 10px;

    background: #F1FBF5;
    color: #176B3A;

    font-size: 12px;
    line-height: 1.5;
}


/* =========================================================
   FOOTER
   ========================================================= */

.part3-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 12px;

    padding: 20px 28px;

    border-top: 1px solid #E2E4EF;
    background: #FBFBFD;
}

.part3-back,
.part3-next {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 43px;

    border-radius: 9px;

    font-family: inherit;
    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    transition: 0.2s ease;
}

.part3-back {
    padding: 0 19px;

    border: 1px solid #D9DCE8;

    background: #FFFFFF;
    color: #656A7C;
}

.part3-back:hover {
    background: #F5F6FB;
    border-color: #C8CBF7;
    color: #292D8F;
}

.part3-next {
    padding: 0 21px;

    border: none;

    background: #292D8F;
    color: #FFFFFF;

    cursor: pointer;
}

.part3-next:hover {
    background: #25297F;
    transform: translateY(-1px);
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 750px) {

    .part3-form {
        padding: 22px 20px;
    }

    .part3-radio-group,
    .part3-input-wrap {
        padding-left: 0;
    }

    .part3-conditional {
        margin-left: 0;
    }

    .part3-footer {
        padding: 18px 20px;
    }

}


@media (max-width: 560px) {

    .part3-card {
        border-radius: 13px;
    }

    .part3-card-header {
        padding: 20px;
    }

    .part3-card-header h2 {
        font-size: 18px;
    }

    .part3-form {
        padding: 20px;
    }

    .part3-question-title {
        font-size: 14px;
    }

    .part3-radio-group {
        flex-direction: column;
        gap: 10px;
    }

    .part3-footer {
        flex-direction: column;
    }

    .part3-back,
    .part3-next {
        width: 100%;
    }

}

</style>


{{-- =========================================================
     ERROR
     ========================================================= --}}

@if ($errors->any())

    <div class="part3-error">

        <strong>Terjadi kesalahan:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

@endif


{{-- =========================================================
     SUCCESS
     ========================================================= --}}

@if (session('success'))

    <div class="part3-success">
        {{ session('success') }}
    </div>

@endif


{{-- =========================================================
     PART 3 CARD
     ========================================================= --}}

<div class="part3-card">


    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <div class="part3-card-header">

        <h2>
            Part 3 — Pengeluaran dan Pendapatan Keluarga
        </h2>

        <p>
            Silakan lengkapi data pengeluaran dan pendapatan seluruh anggota keluarga dengan benar.
        </p>

    </div>


    {{-- =====================================================
         FORM
         ===================================================== --}}

    <form
        action="{{ route('kuisioner.part3.store') }}"
        method="POST"
    >

        @csrf


        <div class="part3-form">


            {{-- =================================================
                 34
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        34
                    </div>

                    <div>
                        Berapa rata-rata pengeluaran listrik selama sebulan?
                    </div>

                </div>

                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengeluaran_listrik_bulanan"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('pengeluaran_listrik_bulanan', $dataPart3->pengeluaran_listrik_bulanan ?? '') }}"
                            placeholder="Masukkan pengeluaran listrik sebulan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 35
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        35
                    </div>

                    <div>
                        Berapa rata-rata pengeluaran pulsa untuk seluruh anggota keluarga selama sebulan?
                    </div>

                </div>

                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengeluaran_pulsa_bulanan"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('pengeluaran_pulsa_bulanan', $dataPart3->pengeluaran_pulsa_bulanan ?? '') }}"
                            placeholder="Masukkan total pengeluaran pulsa sebulan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 36
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        36
                    </div>

                    <div>
                        Berapa rata-rata pengeluaran internet untuk seluruh anggota keluarga selama sebulan?
                    </div>

                </div>

                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengeluaran_internet_bulanan"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('pengeluaran_internet_bulanan', $dataPart3->pengeluaran_internet_bulanan ?? '') }}"
                            placeholder="Masukkan total pengeluaran internet sebulan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 37
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        37
                    </div>

                    <div>
                        Berapa rata-rata pengeluaran makanan keluarga selama seminggu?
                    </div>

                </div>

                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengeluaran_makan_mingguan"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('pengeluaran_makan_mingguan', $dataPart3->pengeluaran_makan_mingguan ?? '') }}"
                            placeholder="Masukkan total pengeluaran makanan seminggu"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 38
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        38
                    </div>

                    <div>
                        Berapa rata-rata pengeluaran bukan makanan rutin keluarga selama sebulan?
                    </div>

                </div>

                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengeluaran_nonmakan_bulanan"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('pengeluaran_nonmakan_bulanan', $dataPart3->pengeluaran_nonmakan_bulanan ?? '') }}"
                            placeholder="Masukkan total pengeluaran bukan makanan bulanan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 39
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        39
                    </div>

                    <div>
                        Berapa rata-rata pengeluaran bukan makanan rutin keluarga selama setahun?
                    </div>

                </div>

                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="pengeluaran_nonmakan_tahunan"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('pengeluaran_nonmakan_tahunan', $dataPart3->pengeluaran_nonmakan_tahunan ?? '') }}"
                            placeholder="Masukkan total pengeluaran bukan makanan tahunan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 40
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        40
                    </div>

                    <div>
                        Total pendapatan seluruh anggota keluarga dari pekerjaan baik berupa uang maupun barang/jasa (Gaji, tunjangan, uang makan, honor, lembur, dll)
                    </div>

                </div>


                <div class="part3-radio-group">

                    <label class="part3-radio">

                        <input
                            type="radio"
                            name="pendapatan_pekerjaan"
                            value="Ada"
                            {{ old('pendapatan_pekerjaan', ($dataPart3->total_pendapatan_kerja ?? null) !== null ? 'Ada' : '') === 'Ada' ? 'checked' : '' }}
                            onchange="togglePendapatanPekerjaan(true)"
                        >

                        Ada

                    </label>


                    <label class="part3-radio">

                        <input
                            type="radio"
                            name="pendapatan_pekerjaan"
                            value="Tidak"
                            {{ old('pendapatan_pekerjaan', ($dataPart3->total_pendapatan_kerja ?? null) !== null ? 'Ada' : '') === 'Tidak' ? 'checked' : '' }}
                            onchange="togglePendapatanPekerjaan(false)"
                        >

                        Tidak

                    </label>

                </div>


                <div
                    id="pendapatanPekerjaanBox"
                    class="part3-conditional
                    {{ old('pendapatan_pekerjaan', ($dataPart3->total_pendapatan_kerja ?? null) !== null ? 'Ada' : '') === 'Ada' ? 'show' : '' }}"
                >

                    <div class="part3-conditional-title">
                        Total Pendapatan Bekerja Sebulan
                    </div>

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="total_pendapatan_kerja"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('total_pendapatan_kerja', $dataPart3->total_pendapatan_kerja ?? '') }}"
                            placeholder="Masukkan total pendapatan bekerja sebulan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 41
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        41
                    </div>

                    <div>
                        Total pendapatan seluruh anggota keluarga dari usaha, baik offline maupun online (Offline seperti warung, kosan, rentenir, dll. Online seperti affiliate, online shop, endorse, youtuber, dll)
                    </div>

                </div>


                <div class="part3-input-wrap">

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="total_pendapatan_usaha"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('total_pendapatan_usaha', $dataPart3->total_pendapatan_usaha ?? '') }}"
                            placeholder="Masukkan total pendapatan usaha sebulan"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 42
                 ================================================= --}}

            <div class="part3-question">

                <div class="part3-question-title">

                    <div class="part3-number">
                        42
                    </div>

                    <div>
                        Total pendapatan seluruh anggota keluarga dari penerimaan lain (Misalnya transfer, pemberian, passive income, pensiunan, kupon SBN, Obligasi, dll)
                    </div>

                </div>


                <div class="part3-radio-group">

                    <label class="part3-radio">

                        <input
                            type="radio"
                            name="pendapatan_lainnya"
                            value="Ada"
                            {{ old('pendapatan_lainnya', ($dataPart3->total_pendapatan_lainnya ?? null) !== null ? 'Ada' : '') === 'Ada' ? 'checked' : '' }}
                            onchange="togglePendapatanLainnya(true)"
                        >

                        Ada

                    </label>


                    <label class="part3-radio">

                        <input
                            type="radio"
                            name="pendapatan_lainnya"
                            value="Tidak"
                            {{ old('pendapatan_lainnya', ($dataPart3->total_pendapatan_lainnya ?? null) !== null ? 'Ada' : '') === 'Tidak' ? 'checked' : '' }}
                            onchange="togglePendapatanLainnya(false)"
                        >

                        Tidak

                    </label>

                </div>


                <div
                    id="pendapatanLainnyaBox"
                    class="part3-conditional
                    {{ old('pendapatan_lainnya', ($dataPart3->total_pendapatan_lainnya ?? null) !== null ? 'Ada' : '') === 'Ada' ? 'show' : '' }}"
                >

                    <div class="part3-conditional-title">
                        Total Pendapatan Lainnya Sebulan
                    </div>

                    <div class="part3-money-wrap">

                        <span class="part3-money-prefix">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="total_pendapatan_lainnya"
                            class="part3-input part3-money-input"
                            min="0"
                            value="{{ old('total_pendapatan_lainnya', $dataPart3->total_pendapatan_lainnya ?? '') }}"
                            placeholder="Masukkan total pendapatan lainnya sebulan"
                        >

                    </div>

                </div>

            </div>


        </div>


        {{-- =====================================================
             FOOTER
             ===================================================== --}}

        <div class="part3-footer">

            <a
                href="{{ route('kuisioner.part2') }}"
                class="part3-back"
            >
                ← Kembali
            </a>


            <button
                type="submit"
                class="part3-next"
            >
                Lanjut ke Part 4 →
            </button>

        </div>

    </form>

</div>


<script>

/* =========================================================
   PENDAPATAN PEKERJAAN
   ========================================================= */

function togglePendapatanPekerjaan(show)
{
    const box =
        document.getElementById('pendapatanPekerjaanBox');

    if (!box) {
        return;
    }

    if (show) {
        box.classList.add('show');
    } else {
        box.classList.remove('show');
    }
}


/* =========================================================
   PENDAPATAN LAINNYA
   ========================================================= */

function togglePendapatanLainnya(show)
{
    const box =
        document.getElementById('pendapatanLainnyaBox');

    if (!box) {
        return;
    }

    if (show) {
        box.classList.add('show');
    } else {
        box.classList.remove('show');
    }
}


/* =========================================================
   RESTORE CONDITIONAL
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const pekerjaan =
        document.querySelector(
            'input[name="pendapatan_pekerjaan"]:checked'
        );

    if (pekerjaan) {
        togglePendapatanPekerjaan(
            pekerjaan.value === 'Ada'
        );
    }


    const lainnya =
        document.querySelector(
            'input[name="pendapatan_lainnya"]:checked'
        );

    if (lainnya) {
        togglePendapatanLainnya(
            lainnya.value === 'Ada'
        );
    }

});

</script>

@endsection