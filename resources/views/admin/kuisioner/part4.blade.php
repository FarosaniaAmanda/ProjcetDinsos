@extends('admin.kuisioner.layout')

@section('kuisioner-content')

@php
    $dataPart4Map = $dataPart4Map ?? collect();

    $getAset = function ($namaAset) use ($dataPart4Map) {
        $asetKey = md5($namaAset);
        $data = $dataPart4Map->get($namaAset);

        return [
            'key' => $asetKey,
            'punya' => (string) old(
                "aset.$asetKey.punya",
                $data ? $data->is_punya_aset : ''
            ),
            'jumlah' => old(
                "aset.$asetKey.jumlah",
                $data?->jml_aset ?? ''
            ),
        ];
    };

    $gas3kg = $getAset('Tabung Gas 3 KG');
    $gas55 = $getAset('Tabung Gas 5,5 KG atau Lebih');
    $kulkas = $getAset('Lemari Es/Kulkas');
    $ac = $getAset('AC (Air Conditioner)');
    $emas = $getAset('Emas/Perhiasan');
    $komputer = $getAset('Komputer/Laptop/Tablet');
    $motor = $getAset('Sepeda Motor');
    $mobil = $getAset('Mobil');
    $rumah = $getAset('Rumah/Bangunan (selain yang ditempati)');
    $lahan = $getAset('Lahan Lainnya');
@endphp

<style>
.part4-card {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #E2E4EF;
    border-radius: 17px;
    box-shadow: 0 5px 20px rgba(41, 45, 143, 0.05);
    overflow: hidden;
}

.part4-card-header {
    padding: 25px 28px;
    border-bottom: 1px solid #E2E4EF;
    background: #FFFFFF;
}

.part4-card-header h2 {
    margin: 0;
    color: #292D8F;
    font-size: 20px;
    font-weight: 800;
    line-height: 1.3;
}

.part4-card-header p {
    margin: 7px 0 0;
    color: #777D91;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
}

.part4-section {
    padding: 24px 28px 10px;
}

.part4-section-title {
    margin: 0;
    color: #292D8F;
    font-size: 16px;
    font-weight: 800;
}

.part4-section-guide {
    margin: 6px 0 0;
    color: #777D91;
    font-size: 12px;
    line-height: 1.5;
}

.part4-form {
    padding: 10px 28px 28px;
}

.part4-question {
    padding: 22px 0;
    border-bottom: 1px solid #E9EAF2;
}

.part4-question:first-child {
    padding-top: 15px;
}

.part4-question:last-child {
    border-bottom: none;
}

.part4-question-title {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
    color: #25283A;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.6;
}

.part4-number {
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

.part4-content {
    padding-left: 42px;
}

.part4-radio-group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px 22px;
}

.part4-radio {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #555A6D;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
}

.part4-radio input {
    width: 16px;
    height: 16px;
    margin: 0;
    accent-color: #292D8F;
    cursor: pointer;
}

.part4-quantity {
    display: none;
    align-items: center;
    gap: 10px;
    margin-left: 5px;
}

.part4-quantity.show {
    display: flex;
}

.part4-quantity-label {
    color: #555A6D;
    font-size: 13px;
    font-weight: 600;
}

.part4-input {
    width: 130px;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #D9DCE8;
    border-radius: 9px;
    background: #FFFFFF;
    color: #25283A;
    font-family: inherit;
    font-size: 14px;
    outline: none;
    transition: 0.2s ease;
}

.part4-input:focus {
    border-color: #292D8F;
    box-shadow: 0 0 0 3px rgba(41, 45, 143, 0.08);
}

.part4-input::placeholder {
    color: #A2A6B5;
}

.part4-error {
    margin-bottom: 18px;
    padding: 13px 16px;
    border: 1px solid #F2B8B5;
    border-radius: 10px;
    background: #FFF4F3;
    color: #A32924;
    font-size: 12px;
    line-height: 1.6;
}

.part4-error ul {
    margin: 0;
    padding-left: 18px;
}

.part4-success {
    margin-bottom: 18px;
    padding: 13px 16px;
    border: 1px solid #B9DFC9;
    border-radius: 10px;
    background: #F1FBF5;
    color: #176B3A;
    font-size: 12px;
    line-height: 1.5;
}

.part4-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 20px 28px;
    border-top: 1px solid #E2E4EF;
    background: #FBFBFD;
}

.part4-footer-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.part4-back,
.part4-draft,
.part4-next {
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

.part4-back {
    padding: 0 19px;
    border: 1px solid #D9DCE8;
    background: #FFFFFF;
    color: #656A7C;
}

.part4-back:hover {
    background: #F5F6FB;
    border-color: #C8CBF7;
    color: #292D8F;
}

.part4-draft {
    padding: 0 18px;
    border: 1px solid #D9DCE8;
    background: #FFFFFF;
    color: #656A7C;
    cursor: pointer;
}

.part4-draft:hover {
    background: #F5F6FB;
    border-color: #C8CBF7;
    color: #292D8F;
}

.part4-next {
    padding: 0 21px;
    border: none;
    background: #292D8F;
    color: #FFFFFF;
    cursor: pointer;
}

.part4-next:hover {
    background: #25297F;
    transform: translateY(-1px);
}

@media (max-width: 750px) {
    .part4-form {
        padding: 10px 20px 22px;
    }

    .part4-section {
        padding-left: 20px;
        padding-right: 20px;
    }

    .part4-content {
        padding-left: 0;
    }

    .part4-footer {
        padding: 18px 20px;
    }
}

@media (max-width: 560px) {
    .part4-card {
        border-radius: 13px;
    }

    .part4-card-header {
        padding: 20px;
    }

    .part4-card-header h2 {
        font-size: 18px;
    }

    .part4-question-title {
        font-size: 14px;
    }

    .part4-radio-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .part4-quantity {
        margin-left: 0;
    }

    .part4-footer {
        flex-direction: column;
    }

    .part4-back,
    .part4-footer-actions {
        width: 100%;
    }

    .part4-footer-actions {
        flex-direction: column;
    }

    .part4-draft,
    .part4-next {
        width: 100%;
    }
}
</style>

@if ($errors->any())
    <div class="part4-error">
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="part4-success">
        {{ session('success') }}
    </div>
@endif

<div class="part4-card">

    <div class="part4-card-header">
        <h2>Part 4 — Kepemilikan Aset Keluarga</h2>
        <p>Lengkapi data kepemilikan aset bergerak dan tidak bergerak keluarga.</p>
    </div>

    <div class="part4-section">
        <h3 class="part4-section-title">Kepemilikan Aset Bergerak Keluarga</h3>
        <p class="part4-section-guide">
            <strong>Petunjuk:</strong>
            Pilih <strong>Ya</strong> dan isikan jumlahnya jika memiliki,
            atau pilih <strong>Tidak</strong> jika tidak memiliki.
        </p>
    </div>

    
    <form action="{{ route('kuisioner.part4.store') }}" method="POST">
        @csrf

        <input type="hidden" name="draft_id" value="{{ $draft->id }}">
        <input type="hidden" name="keluarga_periode_kode" value="{{ $draft->keluarga_periode_kode }}">


        <div class="part4-form">

            {{-- 43: Tabung Gas 3 KG --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">43</div>
                    <div>Tabung Gas 3 KG</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $gas3kg['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $gas3kg['key'] }}', true)"
                                {{ $gas3kg['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $gas3kg['key'] }}"
                            class="part4-quantity {{ $gas3kg['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $gas3kg['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $gas3kg['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $gas3kg['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $gas3kg['key'] }}', false)"
                                {{ $gas3kg['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 44: Tabung Gas 5,5 KG atau Lebih --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">44</div>
                    <div>Tabung Gas 5,5 KG atau Lebih</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $gas55['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $gas55['key'] }}', true)"
                                {{ $gas55['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $gas55['key'] }}"
                            class="part4-quantity {{ $gas55['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $gas55['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $gas55['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $gas55['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $gas55['key'] }}', false)"
                                {{ $gas55['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 45: Lemari Es/Kulkas --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">45</div>
                    <div>Lemari Es/Kulkas</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $kulkas['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $kulkas['key'] }}', true)"
                                {{ $kulkas['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $kulkas['key'] }}"
                            class="part4-quantity {{ $kulkas['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $kulkas['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $kulkas['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $kulkas['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $kulkas['key'] }}', false)"
                                {{ $kulkas['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 46: AC --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">46</div>
                    <div>AC (Air Conditioner)</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $ac['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $ac['key'] }}', true)"
                                {{ $ac['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $ac['key'] }}"
                            class="part4-quantity {{ $ac['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $ac['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $ac['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $ac['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $ac['key'] }}', false)"
                                {{ $ac['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 47: Emas/Perhiasan --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">47</div>
                    <div>Emas/Perhiasan</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $emas['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $emas['key'] }}', true)"
                                {{ $emas['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $emas['key'] }}"
                            class="part4-quantity {{ $emas['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $emas['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $emas['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $emas['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $emas['key'] }}', false)"
                                {{ $emas['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 48: Komputer/Laptop/Tablet --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">48</div>
                    <div>Komputer/Laptop/Tablet</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $komputer['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $komputer['key'] }}', true)"
                                {{ $komputer['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $komputer['key'] }}"
                            class="part4-quantity {{ $komputer['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $komputer['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $komputer['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $komputer['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $komputer['key'] }}', false)"
                                {{ $komputer['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 49: Sepeda Motor --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">49</div>
                    <div>Sepeda Motor</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $motor['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $motor['key'] }}', true)"
                                {{ $motor['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $motor['key'] }}"
                            class="part4-quantity {{ $motor['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $motor['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $motor['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $motor['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $motor['key'] }}', false)"
                                {{ $motor['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- 50: Mobil --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">50</div>
                    <div>Mobil</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $mobil['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $mobil['key'] }}', true)"
                                {{ $mobil['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $mobil['key'] }}"
                            class="part4-quantity {{ $mobil['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $mobil['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $mobil['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $mobil['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $mobil['key'] }}', false)"
                                {{ $mobil['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- ASET TIDAK BERGERAK --}}
            <div class="part4-section">
                <h3 class="part4-section-title">Kepemilikan Aset Tidak Bergerak Keluarga</h3>
                <p class="part4-section-guide">
                    <strong>Petunjuk:</strong>
                    Pilih <strong>Ya</strong> dan isikan jumlahnya jika memiliki,
                    atau pilih <strong>Tidak</strong> jika tidak memiliki.
                </p>
            </div>

            {{-- 51: Rumah/Bangunan --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">51</div>
                    <div>Rumah/Bangunan (selain yang ditempati)</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $rumah['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $rumah['key'] }}', true)"
                                {{ $rumah['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $rumah['key'] }}"
                            class="part4-quantity {{ $rumah['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $rumah['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $rumah['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $rumah['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $rumah['key'] }}', false)"
                                {{ $rumah['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

            {{-- Lahan Lainnya --}}
            <div class="part4-question">
                <div class="part4-question-title">
                    <div class="part4-number">—</div>
                    <div>Lahan Lainnya</div>
                </div>

                <div class="part4-content">
                    <div class="part4-radio-group">
                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $lahan['key'] }}][punya]"
                                value="1"
                                onchange="toggleJumlah('{{ $lahan['key'] }}', true)"
                                {{ $lahan['punya'] === '1' ? 'checked' : '' }}
                            >
                            Ya
                        </label>

                        <div
                            id="jumlah-{{ $lahan['key'] }}"
                            class="part4-quantity {{ $lahan['punya'] === '1' ? 'show' : '' }}"
                        >
                            <span class="part4-quantity-label">Jumlah:</span>
                            <input
                                type="number"
                                min="1"
                                name="aset[{{ $lahan['key'] }}][jumlah]"
                                class="part4-input"
                                value="{{ $lahan['jumlah'] }}"
                                placeholder="Jumlah"
                            >
                        </div>

                        <label class="part4-radio">
                            <input
                                type="radio"
                                name="aset[{{ $lahan['key'] }}][punya]"
                                value="0"
                                onchange="toggleJumlah('{{ $lahan['key'] }}', false)"
                                {{ $lahan['punya'] === '0' ? 'checked' : '' }}
                            >
                            Tidak
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div class="part4-footer">
            <a href="{{ route('kuisioner.part3') }}" class="part4-back">
                ← Kembali
            </a>

            <div class="part4-footer-actions">
                <button
                    type="submit"
                    name="action"
                    value="draft"
                    class="part4-draft"
                >
                    Simpan Draft
                </button>

                <button
                    type="submit"
                    name="action"
                    value="next"
                    class="part4-next"
                >
                    Lanjut ke Part 5 →
                </button>
            </div>
        </div>
    </form>
</div>

<script>

function toggleJumlah(key, show) {
    const box = document.getElementById('jumlah-' + key);

    if (!box) {
        return;
    }

    const input = box.querySelector('input');

    if (show) {
        box.classList.add('show');

        if (input) {
            input.disabled = false;
        }
    } else {
        box.classList.remove('show');

        if (input) {
            input.value = '';
            input.disabled = true;
        }
    }
}

// Menyesuaikan status input ketika halaman pertama kali dibuka
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.part4-quantity').forEach(function (box) {
        const input = box.querySelector('input');

        if (!input) {
            return;
        }

        const terlihat = box.classList.contains('show');

        input.disabled = !terlihat;
    });
});
</script>

@endsection