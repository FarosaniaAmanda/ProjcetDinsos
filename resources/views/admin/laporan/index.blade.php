@extends('admin.layouts.app') 
 
@section('title', 'Laporan Pendataan') 
 
@section('content')

@php
    $data = $laporan ?? collect();
@endphp
 
 <style>

/* ============================================================
   LAPORAN
   ============================================================ */

.laporan-page {
    width: 100%;
    max-width: 100%;
    
    padding: 24px 32px 40px;

    background: #f5f7fb;

    min-height: calc(100vh - 70px);

    box-sizing: border-box;

    color: #222222;
}


/* ============================================================
   SATU CARD BESAR
   Header + Statistik + Filter + Tabel
   ============================================================ */

.laporan-page {
    position: relative;
}

.laporan-page::before {
    content: "";

    position: absolute;

    top: 24px;
    left: 32px;
    right: 32px;
    bottom: 40px;

    background: #ffffff;

    border: 1px solid #e2e6f2;

    border-radius: 18px;

    box-shadow:
        0 4px 18px rgba(37, 42, 134, 0.06);

    z-index: 0;

    pointer-events: none;
}


/*
|--------------------------------------------------------------------------
| Semua isi Laporan berada di atas card besar
|--------------------------------------------------------------------------
*/

.laporan-page > * {
    position: relative;
    z-index: 1;
}


/* ============================================================
   HEADER
   ============================================================ */

.laporan-header {
    width: 100%;

    margin: 0 auto;

    padding: 24px 26px 20px;

    border-bottom: 1px solid #e8ebf3;

    box-sizing: border-box;
}


.laporan-header-top {
    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 20px;
}


.laporan-header h1 {
    margin: 0;

    font-size: 25px;

    line-height: 1.3;

    font-weight: 700;

    color: #252A86;
}


.laporan-header p {
    margin: 8px 0 0;

    color: #64748b;

    font-size: 14px;

    line-height: 1.6;
}


/* ============================================================
   EXPORT
   ============================================================ */

.laporan-export-btn {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    min-height: 42px;

    padding: 0 17px;

    border: 1px solid #252A86;

    border-radius: 9px;

    background: #252A86;

    color: #ffffff;

    font-size: 13px;

    font-weight: 600;

    text-decoration: none;

    cursor: pointer;

    transition: .2s ease;

    white-space: nowrap;

    box-sizing: border-box;
}


.laporan-export-btn:hover {
    background: #1d216d;

    border-color: #1d216d;

    color: #ffffff;

    transform: translateY(-1px);

    box-shadow:
        0 4px 10px rgba(37, 42, 134, .15);
}


.laporan-export-btn svg {
    width: 17px;

    height: 17px;

    flex-shrink: 0;
}


/* ============================================================
   STATISTICS
   ============================================================ */

.laporan-stats {
    width: 100%;

    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));

    gap: 18px;

    padding: 20px 34px 22px;

    margin: 0;

    border-bottom: 1px solid #e8ebf3;

    box-sizing: border-box;
}


/* ============================================================
   STAT CARD
   ============================================================ */

.laporan-stat-card {
    position: relative;

    background: #ffffff;

    border: 1px solid #e2e6f2;

    border-radius: 16px;

    padding: 20px 18px;

    min-height: 118px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    overflow: hidden;

    box-sizing: border-box;

    box-shadow:
        0 2px 8px rgba(37, 42, 134, 0.04);

    transition: .2s ease;
}




.laporan-stat-card::before {
    content: "";

    position: absolute;

    top: 0;
    left: 0;
    right: 0;

    height: 5px;

    border-radius: 16px 16px 0 0;

    background: #3157D5;
}




.laporan-stat-card:nth-child(1)::before {
    background: #3157D5;
}




.laporan-stat-card:nth-child(2)::before {
    background: #159B72;
}




.laporan-stat-card:nth-child(3)::before {
    background: #D94A4A;
}




.laporan-stat-card:hover {
    transform: translateY(-1px);

    box-shadow:
        0 4px 12px rgba(37, 42, 134, 0.07);
}



.laporan-stat-info {
    width: 100%;
    min-width: 0;
}




.laporan-stat-label {
    margin: 0 0 7px;

    color: #172554;

    font-size: 16px;

    line-height: 1.35;

    font-weight: 700;
}




.laporan-stat-value {
    margin: 0;

    color: #3157D5;

    font-size: 32px;

    line-height: 1;

    font-weight: 700;
}




.laporan-stat-card:nth-child(2) .laporan-stat-value {
    color: #159B72;
}




.laporan-stat-card:nth-child(3) .laporan-stat-value {
    color: #D94A4A;
}


/* ============================================================
   MAIN LAPORAN CARD
   ============================================================

   PENTING:
   .laporan-card TIDAK BOLEH menjadi card kedua.
   Kita hilangkan border/shadow/radius karena card besarnya
   sudah dibuat oleh .laporan-page.
   ============================================================ */

.laporan-card {
    width: 100%;

    max-width: none;

    margin: 0;

    background: transparent;

    border: none;

    border-radius: 0;

    box-shadow: none;

    overflow: visible;

    box-sizing: border-box;
}


/* ============================================================
   CARD HEADER / FILTER
   ============================================================ */

.laporan-card-header {
    padding: 20px 26px 22px;

    background: transparent;

    border-bottom: 1px solid #e8ebf3;
}


.laporan-card-title {
    margin: 0;

    color: #252A86;

    font-size: 17px;

    line-height: 1.4;

    font-weight: 700;
}


.laporan-card-description {
    margin: 6px 0 0;

    color: #64748b;

    font-size: 13px;

    line-height: 1.6;
}


/* ============================================================
   FILTER
   ============================================================ */

.laporan-filter {
    display: grid;

    grid-template-columns:
        minmax(280px, 1.8fr)
        minmax(160px, .9fr)
        minmax(180px, 1fr)
        auto;

    gap: 12px;

    align-items: end;

    margin-top: 0;
}


.laporan-filter-group {
    min-width: 0;
}


.laporan-filter-label {
    display: block;

    margin: 0 0 7px;

    color: #555555;

    font-size: 13px;

    line-height: 1.4;

    font-weight: 700;
}


/* ============================================================
   SEARCH
   ============================================================ */

.laporan-search {
    position: relative;

    width: 100%;
}


.laporan-search input,
.laporan-filter select {
    width: 100%;

    height: 46px;

    border: 1px solid #d5d9e7;

    border-radius: 10px;

    background: #ffffff;

    color: #1e293b;

    font-size: 14px;

    outline: none;

    box-sizing: border-box;

    transition: .2s ease;
}


.laporan-search input {
    padding: 0 45px 0 16px;
}


.laporan-filter select {
    padding: 0 35px 0 14px;

    cursor: pointer;
}


.laporan-search input::placeholder {
    color: #94a3b8;
}


.laporan-search input:focus,
.laporan-filter select:focus {
    border-color: #252A86;

    box-shadow:
        0 0 0 3px rgba(37, 42, 134, .09);
}


.laporan-search-icon {
    position: absolute;

    right: 15px;

    top: 50%;

    transform: translateY(-50%);

    color: #64748b;

    pointer-events: none;
}


/* ============================================================
   RESET
   ============================================================ */

.laporan-reset-btn {
    min-width: 105px;

    height: 46px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0 15px;

    border: 1px solid #252A86;

    border-radius: 10px;

    background: #ffffff;

    color: #252A86;

    font-size: 13px;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;

    transition: .2s ease;

    box-sizing: border-box;

    white-space: nowrap;
}


.laporan-reset-btn:hover {
    background: #f1f3ff;

    color: #252A86;
}


/* ============================================================
   TABLE
   ============================================================ */

.laporan-table-wrapper {
    width: 100%;

    padding: 0 26px 24px;

    overflow-x: auto;

    box-sizing: border-box;
}


.laporan-table {
    width: 100%;

    min-width: 1050px;

    border-collapse: collapse;

    background: #ffffff;
}


.laporan-table th {
    padding: 14px 16px;

    background: #f1f3ff;

    color: #4b5563 !important;

    font-size: 12px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .02em;

    border-bottom: 1px solid #e2e6f2;

    text-align: left;

    white-space: nowrap;
}


.laporan-table td {
    padding: 15px 16px;

    border-bottom: 1px solid #eef1f6;

    color: #4b5563 !important;

    font-size: 13px;

    vertical-align: middle;
}


.laporan-table td strong {
    color: #4b5563 !important;

    font-weight: 600;
}


.laporan-table tbody tr {
    transition: .15s ease;
}


.laporan-table tbody tr:hover {
    background: #fafbff;
}


.laporan-table tbody tr:last-child td {
    border-bottom: none;
}


/* ============================================================
   STATUS
   ============================================================ */

.laporan-status {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 5px 10px;

    border-radius: 999px;

    font-size: 11px;

    font-weight: 700;

    white-space: nowrap;
}


.laporan-status.selesai,
.laporan-status.disetujui {
    background: #E8F7EE;

    color: #299447;
}


.laporan-status.belum {
    background: #F0F0F0;

    color: #6B7280;
}


.laporan-status.diproses {
    background: #FFF7D6;

    color: #B88600;
}


.laporan-status.ditolak {
    background: #FDECEC;

    color: #D9364F;

    border: 1px solid #f5b8b5;
}


/* ============================================================
   DETAIL BUTTON
   ============================================================ */

.laporan-detail-btn {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    min-width: 70px;

    height: 36px;

    padding: 0 13px;

    border: 1px solid #d7daf2;

    border-radius: 8px;

    background: #f1f3ff;

    color: #252A86;

    font-size: 12px;

    font-weight: 600;

    cursor: pointer;

    transition: .2s ease;
}


.laporan-detail-btn:hover {
    background: #252A86;

    border-color: #252A86;

    color: #ffffff;

    box-shadow:
        0 3px 8px rgba(37, 42, 134, .18);
}


/* ============================================================
   EMPTY
   ============================================================ */

.laporan-empty {
    padding: 45px 20px;

    text-align: center;

    color: #64748b;
}


/* ============================================================
   MODAL
   LOGIKA TIDAK DIUBAH
   ============================================================ */

.laporan-modal-overlay {
    position: fixed;

    inset: 0;

    z-index: 5000;

    display: none;

    align-items: center;

    justify-content: center;

    padding: 10px;

    background: rgba(15, 23, 42, .52);

    backdrop-filter: blur(3px);

    box-sizing: border-box;
}


.laporan-modal-overlay.active {
    display: flex;
}


.laporan-modal {
    position: relative;

    width: min(1250px, calc(100vw - 40px));

    max-height: calc(100vh - 20px);

    background: #ffffff;

    border-radius: 18px;

    box-shadow:
        0 25px 70px rgba(15, 23, 42, .25);

    overflow: hidden;
}


.laporan-modal-header {
    padding: 20px 26px;

    border-bottom: 1px solid #e5e7eb;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    background: #ffffff;
}


.laporan-modal-body {
    padding: 22px 26px 28px;

    overflow-y: auto;

    max-height: calc(100vh - 105px);
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 1200px) {

    .laporan-page {
        padding: 22px 24px 35px;
    }

    .laporan-page::before {
        top: 22px;
        left: 24px;
        right: 24px;
        bottom: 35px;
    }

    .laporan-stats {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .laporan-filter {
        grid-template-columns:
            1fr 1fr;
    }
}


@media (max-width: 700px) {

    .laporan-page {
        padding: 18px 14px 30px;
    }

    .laporan-page::before {
        top: 18px;
        left: 14px;
        right: 14px;
        bottom: 30px;

        border-radius: 14px;
    }

    .laporan-header {
        padding: 19px 17px 17px;
    }

    .laporan-header-top {
        flex-direction: column;
    }

    .laporan-export-btn {
        width: 100%;
    }

    .laporan-header h1 {
        font-size: 21px;
    }

    .laporan-header p {
        font-size: 13px;
    }

    .laporan-stats {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 10px;

        padding: 15px;
    }

    .laporan-stat-card {
        min-height: 76px;

        padding: 13px;

        border-radius: 12px;
    }

    .laporan-stat-label {
        font-size: 11px;
    }

    .laporan-stat-value {
        font-size: 19px;
    }

    .laporan-card-header {
        padding: 17px;
    }

    .laporan-filter {
        grid-template-columns: 1fr;

        gap: 12px;
    }

    .laporan-table-wrapper {
        padding: 0 17px 18px;
    }

    .laporan-modal-overlay {
        padding: 10px;
    }

    .laporan-modal {
        border-radius: 14px;

        max-height: calc(100vh - 20px);
    }

    .laporan-modal-header {
        padding: 15px 16px;
    }

    .laporan-modal-body {
        padding: 15px;

        max-height: calc(100vh - 115px);
    }
}


@media (max-width: 400px) {

    .laporan-page {
        padding: 12px 9px 25px;
    }

    .laporan-page::before {
        top: 12px;
        left: 9px;
        right: 9px;
        bottom: 25px;

        border-radius: 12px;
    }

    .laporan-stats {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 8px;
    }

    .laporan-stat-card {
        min-height: 70px;

        padding: 11px;

        border-radius: 11px;
    }

    .laporan-stat-label {
        font-size: 10px;
    }

    .laporan-stat-value {
        font-size: 18px;
    }
}

/* ============================================================
   DETAIL LAPORAN - SUMMARY
   ============================================================ */

.laporan-modal-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.laporan-summary-box {
    min-width: 0;
    padding: 16px 18px;
    background: #f8f9fd;
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    box-sizing: border-box;
}

.laporan-summary-label {
    margin-bottom: 7px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 600;
}

.laporan-summary-value {
    color: #1e293b;
    font-size: 15px;
    line-height: 1.5;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.laporan-detail-status {
    display: inline-flex;
    align-items: center;
    width: fit-content;
    padding: 5px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.laporan-detail-status.status-approved {
    background: #e8f7ee;
    color: #299447;
}

.laporan-detail-status.status-rejected {
    background: #fdecec;
    color: #d9364f;
}


/* ============================================================
   DETAIL INFORMASI PENDATAAN
   ============================================================ */

.laporan-info-section {
    margin-bottom: 24px;
}

.laporan-info-title,
.laporan-detail-data-heading {
    margin-bottom: 13px;
    color: #252a86;
    font-size: 16px;
    line-height: 1.4;
    font-weight: 700;
}

.laporan-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.laporan-info-item {
    min-width: 0;
    padding: 14px 16px;
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 11px;
    box-sizing: border-box;
}

.laporan-info-label {
    margin-bottom: 6px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 600;
}

.laporan-info-value {
    color: #334155;
    font-size: 14px;
    line-height: 1.55;
    font-weight: 600;
    overflow-wrap: anywhere;
}


/* ============================================================
   DETAIL SECTION
   ============================================================ */

.laporan-detail-data-section {
    margin-top: 22px;
    padding: 18px;
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 13px;
    box-sizing: border-box;
}

.laporan-detail-loading,
.laporan-detail-empty,
.laporan-detail-error {
    padding: 24px 18px;
    text-align: center;
    border-radius: 10px;
    font-size: 14px;
    line-height: 1.6;
}

.laporan-detail-loading {
    color: #64748b;
    background: #f8fafc;
}

.laporan-detail-empty {
    color: #64748b;
    background: #f8fafc;
}

.laporan-detail-error {
    color: #b42318;
    background: #fff5f5;
    border: 1px solid #f3c4c4;
}


/* ============================================================
   TABEL ANGGOTA KELUARGA
   ============================================================ */

.laporan-members-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e2e6f0;
    border-radius: 10px;
}

.laporan-members-table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
    background: #ffffff;
}

.laporan-members-table th {
    padding: 12px 14px;
    background: #f1f3ff;
    color: #4b5563;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 700;
    text-align: left;
    border-bottom: 1px solid #e2e6f0;
    white-space: nowrap;
}

.laporan-members-table td {
    padding: 13px 14px;
    color: #475569;
    font-size: 13px;
    line-height: 1.5;
    vertical-align: middle;
    border-bottom: 1px solid #eef1f6;
}

.laporan-members-table tbody tr:last-child td {
    border-bottom: none;
}

.laporan-members-table tbody tr:hover {
    background: #fafbff;
}


/* ============================================================
   LAYOUT PART KUISIONER
   ============================================================ */

.laporan-questionnaire-layout {
    display: grid;
    grid-template-columns: 240px minmax(0, 1fr);
    gap: 16px;
    width: 100%;
    min-width: 0;
}

.laporan-question-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.laporan-question-part-btn {
    width: 100%;
    min-height: 48px;
    padding: 11px 13px;
    border: 1px solid #dfe3ee;
    border-radius: 10px;
    background: #f8f9fd;
    color: #475569;
    font-size: 13px;
    line-height: 1.45;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
    transition: .2s ease;
    box-sizing: border-box;
}

.laporan-question-part-btn:hover {
    background: #f1f3ff;
    border-color: #cfd5ed;
    color: #252a86;
}

.laporan-question-part-btn.active {
    background: #252a86;
    border-color: #252a86;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(37, 42, 134, .12);
}

.laporan-question-answer {
    min-width: 0;
    padding: 16px;
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    box-sizing: border-box;
}

.laporan-question-answer-title {
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8ebf3;
    color: #252a86;
    font-size: 15px;
    line-height: 1.5;
    font-weight: 700;
}

.laporan-question-answer-body {
    width: 100%;
    min-width: 0;
}


/* ============================================================
   TABEL PERTANYAAN + JAWABAN
   ============================================================ */

.laporan-question-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
}

.laporan-question-table th {
    padding: 12px 14px;
    background: #f1f3ff;
    color: #4b5563;
    font-size: 12px;
    line-height: 1.45;
    font-weight: 700;
    text-align: left;
    border-bottom: 1px solid #e2e6f0;
}

.laporan-question-table td {
    padding: 13px 14px;
    color: #475569;
    font-size: 13px;
    line-height: 1.6;
    vertical-align: top;
    border-bottom: 1px solid #eef1f6;
    overflow-wrap: anywhere;
}

.laporan-question-table tbody tr:last-child td {
    border-bottom: none;
}

.laporan-question-table tbody tr:hover {
    background: #fafbff;
}


/* ============================================================
   FOTO JAWABAN
   ============================================================ */

.laporan-answer-image {
    display: block;
    width: 150px;
    height: 105px;
    margin-bottom: 7px;
    object-fit: cover;
    border: 1px solid #dfe3ee;
    border-radius: 8px;
    background: #f8fafc;
}

.laporan-answer-image-link {
    display: inline-block;
    color: #252a86;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 600;
    text-decoration: none;
}

.laporan-answer-image-link:hover {
    text-decoration: underline;
}


/* ============================================================
   PETA GEOTAGGING PART 1
   ============================================================ */

.laporan-part1-map-section {
    margin-top: 18px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e2e6f0;
    border-radius: 12px;
}

.laporan-part1-map-title {
    margin-bottom: 12px;
    color: #252a86;
    font-size: 14px;
    line-height: 1.4;
    font-weight: 700;
}

.laporan-part1-map-container {
    width: 100%;
    height: 320px;
    overflow: hidden;
    border: 1px solid #dfe3ee;
    border-radius: 10px;
    background: #eef2f7;
}

.laporan-part1-map-container iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: 0;
}

.laporan-part1-coordinate {
    margin-top: 10px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.laporan-part1-coordinate strong {
    color: #334155;
}


/* ============================================================
   FOOTER MODAL
   ============================================================ */

.laporan-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid #e5e7eb;
}

.laporan-modal-btn {
    min-height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 15px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    transition: .2s ease;
}

.laporan-modal-btn.close {
    border: 1px solid #d5d9e7;
    background: #ffffff;
    color: #475569;
}

.laporan-modal-btn.close:hover {
    background: #f8fafc;
}

.laporan-modal-btn.pdf {
    border: 1px solid #252a86;
    background: #252a86;
    color: #ffffff;
}

.laporan-modal-btn.pdf:hover {
    background: #1d216d;
    border-color: #1d216d;
}


/* ============================================================
   RESPONSIVE DETAIL
   ============================================================ */

@media (max-width: 900px) {

    .laporan-modal-summary {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .laporan-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .laporan-questionnaire-layout {
        grid-template-columns: 190px minmax(0, 1fr);
    }
}


@media (max-width: 700px) {

    .laporan-modal-summary {
        grid-template-columns: 1fr;
        gap: 9px;
    }

    .laporan-info-grid {
        grid-template-columns: 1fr;
    }

    .laporan-detail-data-section {
        padding: 14px;
    }

    .laporan-questionnaire-layout {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .laporan-question-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
    }

    .laporan-question-part-btn {
        min-height: 44px;
        font-size: 12px;
    }

    .laporan-question-answer {
        padding: 12px;
    }

    .laporan-question-table {
        min-width: 620px;
    }

    .laporan-question-answer-body {
        overflow-x: auto;
    }

    .laporan-part1-map-container {
        height: 250px;
    }

    .laporan-modal-footer {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .laporan-modal-btn {
        width: 100%;
    }
}


@media (max-width: 400px) {

    .laporan-question-list {
        grid-template-columns: 1fr;
    }

    .laporan-summary-box {
        padding: 13px 14px;
    }

    .laporan-info-item {
        padding: 12px 13px;
    }

    .laporan-detail-data-section {
        padding: 12px;
    }

    .laporan-question-answer {
        padding: 10px;
    }

    .laporan-part1-map-container {
        height: 220px;
    }

    .laporan-answer-image {
        width: 120px;
        height: 90px;
    }
}
</style>

<div class="laporan-page"> 
 
    {{-- ===================================================== 
         HEADER 
    ===================================================== --}} 
 
    <div class="laporan-header"> 
 
        <div class="laporan-header-top"> 
 
            <div> 
                <h1>Laporan Pendataan</h1> 
 
                <p> 
                    Menampilkan rekapitulasi pendataan keluarga berdasarkan 
                    periode, wilayah, petugas, dan status pengisian. 
                </p> 
            </div> 
 
            <a 
                href="{{ route('admin.laporan.export', array_filter([ 
                    'search' => $search, 
                    'periode' => $periode, 
                    'wilayah' => $wilayah, 
                ], fn ($value) => $value !== '')) }}" 
                class="laporan-export-btn" 
                id="laporanExportLink" 
            > 
 
                <svg 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                > 
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"></path> 
                    <polyline points="14 2 14 8 20 8"></polyline> 
                    <path d="M8 13h8"></path> 
                    <path d="M8 17h5"></path> 
                </svg> 
 
                Export Excel 
 
            </a> 
 
        </div> 
 
    </div> 
 
    <div class="laporan-stats">

    <div class="laporan-stat-card">

        <div class="laporan-stat-info">

            <div class="laporan-stat-label">
                Total Responden
            </div>

            <div class="laporan-stat-value">
                {{ $totalResponden ?? count($laporan ?? []) }}
            </div>

        </div>

    </div>


    <div class="laporan-stat-card">

        <div class="laporan-stat-info">

            <div class="laporan-stat-label">
                Kuisioner Selesai
            </div>

            <div class="laporan-stat-value">
                {{ $kuisionerSelesai ?? 0 }}
            </div>

        </div>

    </div>


    <div class="laporan-stat-card">

        <div class="laporan-stat-info">

            <div class="laporan-stat-label">
                Wilayah Terdata
            </div>

            <div class="laporan-stat-value">
                {{ $wilayahTerdata ?? 0 }}
            </div>

        </div>

    </div>

</div>



 
    {{-- ===================================================== 
         MAIN REPORT CARD 
    ===================================================== --}} 
 
    <div class="laporan-card"> 
 
        <div class="laporan-card-header"> 
 
            <form 
                method="GET" 
                action="{{ route('laporan.index') }}" 
                class="laporan-filter" 
                id="laporanFilterForm" 
            > 
 
                <div class="laporan-filter-group laporan-filter-search"> 
 
                    <label 
                        for="laporanSearch" 
                        class="laporan-filter-label" 
                    > 
                        Pencarian Data 
                    </label> 
 
                    <div class="laporan-search"> 
 
                        <input 
                            type="text" 
                            name="search" 
                            id="laporanSearch" 
                            value="{{ $search ?? request('search') }}" 
                            placeholder="Cari No. KK, NIK, atau Nama Kepala Keluarga..." 
                            autocomplete="off" 
                        > 
 
                        <span 
                            class="laporan-search-icon" 
                            aria-hidden="true" 
                        > 
                            <svg 
                                width="17" 
                                height="17" 
                                viewBox="0 0 24 24" 
                                fill="none" 
                                stroke="currentColor" 
                                stroke-width="2" 
                            > 
                                <circle cx="11" cy="11" r="7"></circle> 
                                <line 
                                    x1="16.5" 
                                    y1="16.5" 
                                    x2="21" 
                                    y2="21" 
                                ></line> 
                            </svg> 
                        </span> 
 
                    </div> 
 
                </div> 
 
                <div class="laporan-filter-group"> 
 
                    <label 
                        for="filterPeriode" 
                        class="laporan-filter-label" 
                    > 
                        Periode 
                    </label> 
 
                    <select 
                        name="periode" 
                        id="filterPeriode" 
                    > 
 
                        <option value=""> 
                            Semua Periode 
                        </option> 
 
                        @foreach(($periodeList ?? []) as $itemPeriode) 
 
                            <option 
                                value="{{ $itemPeriode['kode'] }}" 
                                {{ ($periode ?? request('periode')) == $itemPeriode['kode'] ? 'selected' : '' }} 
                            > 
                                {{ $itemPeriode['nama'] }} 
                            </option> 
 
                        @endforeach 
 
                    </select> 
 
                </div> 
 
                <div class="laporan-filter-group"> 
 
                    <label 
                        for="filterWilayah" 
                        class="laporan-filter-label" 
                    > 
                        Wilayah 
                    </label> 
 
                    <select 
                        name="wilayah" 
                        id="filterWilayah" 
                    > 
 
                        <option value=""> 
                            Semua Wilayah 
                        </option> 
 
                        @foreach(($wilayahList ?? []) as $itemWilayah) 
 
                            <option 
                                value="{{ $itemWilayah }}" 
                                {{ ($wilayah ?? request('wilayah')) == $itemWilayah ? 'selected' : '' }} 
                            > 
                                {{ $itemWilayah }} 
                            </option> 
 
                        @endforeach 
 
                    </select> 
 
                </div> 
 
                <div class="laporan-filter-group laporan-filter-reset-group"> 
 
                    <span 
                        class="laporan-filter-label laporan-filter-label-hidden" 
                        aria-hidden="true" 
                    > 
                        Filter 
                    </span> 
 
                    <a 
                        href="{{ route('laporan.index') }}" 
                        class="laporan-reset-btn" 
                        id="resetLaporanFilter" 
                    > 
                        Reset Filter 
                    </a> 
 
                </div> 
 
            </form> 
 
        </div> 
 
        <div class="laporan-table-wrapper"> 
 
            <table class="laporan-table"> 
 
                <thead> 
 
                    <tr> 
                        <th>No.</th> 
                        <th>No. KK</th> 
                        <th>NIK</th> 
                        <th>Nama Kepala Keluarga</th> 
                        <th>Jumlah Anggota</th> 
                        <th>Wilayah</th> 
                        <th>Periode</th> 
                        <th>Tanggal Pendataan</th> 
                        <th>Status</th> 
                        <th>Aksi</th> 
                    </tr> 
 
                </thead> 
 
                <tbody id="laporanTableBody"> 
 
                       @forelse (($laporan ?? []) as $index => $item)
 
                        @php 
 
                            $itemId = data_get( 
                                $item, 
                                'id', 
                                $index 
                            ); 
 
                            $noKk = data_get( 
                                $item, 
                                'no_kk' 
                            ) 
                            ?? data_get( 
                                $item, 
                                'kk' 
                            ) 
                            ?? '-'; 
 
                            $nik = data_get( 
                                $item, 
                                'nik' 
                            ) 
                            ?? '-'; 
 
                            $nama = data_get( 
                                $item, 
                                'nama_kepala_keluarga' 
                            ) 
                            ?? data_get( 
                                $item, 
                                'nama_lengkap' 
                            ) 
                            ?? data_get( 
                                $item, 
                                'nama' 
                            ) 
                            ?? '-'; 
 
                            $jumlahAnggota = data_get( 
                                $item, 
                                'jumlah_anggota' 
                            ) 
                            ?? data_get( 
                                $item, 
                                'jumlah_anggota_keluarga' 
                            ) 
                            ?? 0; 
 
                            $wilayah = data_get( 
                                $item, 
                                'wilayah' 
                            ) 
                            ?? '-'; 
 
                            $periode = data_get( 
                                $item, 
                                'periode' 
                            ) 
                            ?? data_get( 
                                $item, 
                                'nama_periode' 
                            ) 
                            ?? '-'; 
 
                            $tanggalPendataan = data_get( 
                                $item, 
                                'tanggal_pendataan' 
                            ) 
                            ?? data_get( 
                                $item, 
                                'created_at' 
                            ) 
                            ?? '-'; 
 
                            $statusValue = strtolower((string) data_get($item, 'status', ''));
                            $status = data_get($item, 'status_label') ?? match ($statusValue) {
                                'approved', 'disetujui' => 'Disetujui',
                                'rejected', 'reject', 'ditolak' => 'Ditolak',
                                default => $statusValue !== '' ? $statusValue : 'Belum Selesai',
                            };
 
                            $isComplete = data_get( 
                                $item, 
                                'is_complete' 
                            ); 
 
                            $statusLower = strtolower($status); 
 
                            $statusClass = match(true) { 
 
                                in_array( 
                                    $statusLower, 
                                    ['selesai', 'disetujui', 'approved'] 
                                ) 
                                    => 'selesai', 
 
                                in_array( 
                                    $statusLower, 
                                    ['ditolak', 'rejected', 'reject'] 
                                ) 
                                    => 'ditolak', 
 
                                in_array( 
                                    $statusLower, 
                                    ['diproses', 'menunggu', 'pending'] 
                                ) 
                                    => 'diproses', 
 
                                default 
                                    => 'belum', 
 
                            }; 
 
                            if (is_null($isComplete)) { 
 
                                $isComplete = in_array( 
                                    $statusLower, 
                                    ['selesai', 'disetujui', 'approved'] 
                                ); 
 
                            } 
 
                        @endphp 
 
                        <tr 
                            data-search="{{ strtolower( 
                                $noKk . ' ' . 
                                $nik . ' ' . 
                                $nama . ' ' . 
                                $wilayah 
                            ) }}" 
                            data-periode="{{ strtolower($periode) }}" 
                            data-periode-kode="{{ strtolower(data_get($item, 'periode_kode', '')) }}" 
                            data-wilayah="{{ strtolower($wilayah) }}" 
                        > 
 
                            <td> 
                                {{ $index + 1 }} 
                            </td> 
 
                            <td> 
                                <strong> 
                                    {{ $noKk }} 
                                </strong> 
                            </td> 
 
                            <td> 
                                {{ $nik }} 
                            </td> 
 
                            <td> 
                                {{ $nama }} 
                            </td> 
 
                            <td> 
                                {{ $jumlahAnggota }} Orang 
                            </td> 
 
                            <td> 
                                {{ $wilayah }} 
                            </td> 
 
                            <td> 
                                {{ $periode }} 
                            </td> 
 
                            <td> 
                                {{ $tanggalPendataan }} 
                            </td> 
 
                            <td> 
 
                                <span class="laporan-status {{ $statusClass }}"> 
                                    {{ $status }} 
                                </span> 
 
                            </td> 
 
                            <td> 
 
                                <button 
                                    type="button" 
                                    class="laporan-detail-btn btn-detail-laporan" 
                                    data-id="{{ $itemId }}" 
                                    data-no-kk="{{ e($noKk) }}" 
                                    data-nik="{{ e($nik) }}" 
                                    data-nama="{{ e($nama) }}" 
                                    data-jumlah-anggota="{{ e($jumlahAnggota) }}" 
                                    data-wilayah="{{ e($wilayah) }}" 
                                    data-periode="{{ e($periode) }}" 
                                    data-tanggal="{{ e($tanggalPendataan) }}" 
                                    data-status="{{ e($status) }}" 
                                    data-complete="{{ $isComplete ? '1' : '0' }}" 
                                    data-detail-url="{{ route('laporan.detail', ['id' => $itemId]) }}" 
                                    data-pdf-url="{{ route('laporan.pdf', ['id' => $itemId]) }}" 
                                > 
                                    Detail 
                                </button> 
 
                            </td> 
 
                        </tr> 
 
                    @empty 
 
                        <tr> 
 
                            <td colspan="10"> 
 
                                <div class="laporan-empty"> 
 
                                    <div class="laporan-empty-icon"> 
                                        <svg 
                                            width="25" 
                                            height="25" 
                                            viewBox="0 0 24 24" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            stroke-width="1.8" 
                                        > 
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2h12a2 2 0 0 0 2-2V8z"></path> 
                                            <polyline points="14 2 14 8 20 8"></polyline> 
                                        </svg> 
                                    </div> 
 
                                    <div class="laporan-empty-title"> 
                                        Belum Ada Data 
                                    </div> 
 
                                    <div class="laporan-empty-text"> 
                                        Belum terdapat data pendataan yang dapat ditampilkan. 
                                    </div> 
 
                                </div> 
 
                            </td> 
 
                        </tr> 
 
                    @endforelse 
 
                </tbody> 
 
            </table> 
 
        </div> 
 
    </div> 
 
</div> 
 
{{-- ===================================================== 
     DETAIL MODAL 
===================================================== --}} 
 
<div 
    class="laporan-modal-overlay" 
    id="laporanDetailModal" 
    aria-hidden="true" 
> 
 
    <div 
        class="laporan-modal" 
        role="dialog" 
        aria-modal="true" 
        aria-labelledby="laporanDetailTitle" 
    > 
 
        <div class="laporan-modal-header"> 
 
            <div> 
 
                <h2 id="laporanDetailTitle"> 
                    Detail Data Keluarga 
                </h2> 
 
                <p> 
                    Informasi lengkap pendataan satu keluarga 
                </p> 
 
            </div> 
 
            <button 
                type="button" 
                class="laporan-modal-close" 
                id="closeLaporanModal" 
                aria-label="Tutup" 
            > 
 
                <svg 
                    width="19" 
                    height="19" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                > 
                    <line x1="18" y1="6" x2="6" y2="18"></line> 
                    <line x1="6" y1="6" x2="18" y2="18"></line> 
                </svg> 
 
            </button> 
 
        </div> 
 
        <div class="laporan-modal-body"> 
 
            <div class="laporan-modal-summary"> 
 
                <div class="laporan-summary-box"> 
 
                    <div class="laporan-summary-label"> 
                        Nama Kepala Keluarga 
                    </div> 
 
                    <div 
                        class="laporan-summary-value" 
                        id="modalNamaSummary" 
                    > 
                        - 
                    </div> 
 
                </div> 
 
                <div class="laporan-summary-box"> 
 
                    <div class="laporan-summary-label"> 
                        Nomor KK 
                    </div> 
 
                    <div 
                        class="laporan-summary-value" 
                        id="modalKkSummary" 
                    > 
                        - 
                    </div> 
 
                </div> 
 
                <div class="laporan-summary-box"> 
 
                    <div class="laporan-summary-label"> 
                        Status Pendataan 
                    </div> 
 
                    <div 
                        class="laporan-summary-value" 
                        id="modalStatusSummary" 
                    > 
                        - 
                    </div> 
 
                </div> 
 
            </div> 
 
            <div class="laporan-info-section"> 
 
                <div class="laporan-info-title"> 
                    Informasi Pendataan 
                </div> 
 
                <div class="laporan-info-grid"> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Nomor KK</div> 
                        <div class="laporan-info-value" id="modalNoKk">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">NIK</div> 
                        <div class="laporan-info-value" id="modalNik">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Nama Kepala Keluarga</div> 
                        <div class="laporan-info-value" id="modalNama">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Jumlah Anggota</div> 
                        <div class="laporan-info-value" id="modalJumlahAnggota">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Wilayah</div> 
                        <div class="laporan-info-value" id="modalWilayah">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Periode</div> 
                        <div class="laporan-info-value" id="modalPeriode">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Tanggal Pendataan</div> 
                        <div class="laporan-info-value" id="modalTanggal">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Status</div> 
                        <div class="laporan-info-value" id="modalStatus">-</div> 
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <div class="laporan-info-label">Kelengkapan Data</div> 
                        <div class="laporan-info-value" id="modalComplete">-</div> 
                    </div> 
 
                </div> 
 
            </div> 
 
            <section class="laporan-detail-data-section"> 
 
                <div class="laporan-detail-data-heading"> 
                    Anggota Keluarga 
                </div> 
 
                <div id="modalMembersContent" class="laporan-detail-loading"> 
                    Memuat data anggota keluarga... 
                </div> 
 
            </section> 
 
            <section class="laporan-detail-data-section"> 
 
                <div class="laporan-detail-data-heading"> 
                    Hasil Kuisioner 
                </div> 
 
                <div id="modalQuestionnaireContent" class="laporan-detail-loading"> 
                    Memuat hasil kuisioner... 
                </div> 
 
            </section> 
 
            <div class="laporan-modal-footer"> 
 
                <button 
                    type="button" 
                    class="laporan-modal-btn close" 
                    id="closeLaporanModalBottom" 
                > 
                    Tutup 
                </button> 
 
                <a 
                    href="#" 
                    class="laporan-modal-btn pdf" 
                    id="downloadLaporanPdf" 
                    target="_blank" 
                > 
 
                    <svg 
                        width="16" 
                        height="16" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                    > 
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"></path> 
                        <polyline points="14 2 14 8 20 8"></polyline> 
                        <path d="M12 12v6"></path> 
                        <path d="M9.5 15.5L12 18l2.5-2.5"></path> 
                    </svg> 
 
                    Download PDF 
 
                </a> 
 
            </div> 
 
        </div> 
 
    </div> 
 
</div> 
 
<script> 
document.addEventListener('DOMContentLoaded', function () { 
 
    /* ===================================================== 
       ELEMENT FILTER 
    ===================================================== */ 
 
    const laporanFilterForm = document.getElementById('laporanFilterForm'); 
    const searchInput = document.getElementById('laporanSearch'); 
    const periodeFilter = document.getElementById('filterPeriode'); 
    const wilayahFilter = document.getElementById('filterWilayah'); 
    const tableBody = document.getElementById('laporanTableBody'); 
 
    /* ===================================================== 
       REALTIME SEARCH + FILTER 
    ===================================================== */ 
 
    function filterLaporanTable() { 
 
        if (!tableBody) return; 
 
        const rows = tableBody.querySelectorAll('tr[data-search]'); 
 
        const searchValue = searchInput 
            ? searchInput.value.trim().toLowerCase() 
            : ''; 
 
        const periodeValue = periodeFilter 
            ? periodeFilter.value.trim().toLowerCase() 
            : ''; 
 
        const wilayahValue = wilayahFilter 
            ? wilayahFilter.value.trim().toLowerCase() 
            : ''; 
 
        let visibleCount = 0; 
 
        rows.forEach(function (row) { 
 
            const searchData = ( 
                row.dataset.search || '' 
            ).toLowerCase(); 
 
            const rowPeriode = ( 
                row.dataset.periodeKode || '' 
            ).toLowerCase(); 
 
            const rowWilayah = ( 
                row.dataset.wilayah || '' 
            ).toLowerCase(); 
 
            const cocokSearch = 
                searchValue === '' || 
                searchData.includes(searchValue); 
 
            const cocokPeriode = 
                periodeValue === '' || 
                rowPeriode === periodeValue; 
 
            const cocokWilayah = 
                wilayahValue === '' || 
                rowWilayah === wilayahValue; 
 
            const cocok = 
                cocokSearch && 
                cocokPeriode && 
                cocokWilayah; 
 
            if (cocok) { 
                row.style.display = ''; 
                visibleCount++; 
            } else { 
                row.style.display = 'none'; 
            } 
        }); 
 
        let emptyRow = document.getElementById( 
            'laporanRealtimeEmpty' 
        ); 
 
        if (visibleCount === 0 && rows.length > 0) { 
 
            if (!emptyRow) { 
 
                emptyRow = document.createElement('tr'); 
 
                emptyRow.id = 'laporanRealtimeEmpty'; 
 
                emptyRow.innerHTML = ` 
                    <td colspan="10"> 
                        <div class="laporan-empty"> 
 
                            <div class="laporan-empty-icon"> 
                                <svg 
                                    width="25" 
                                    height="25" 
                                    viewBox="0 0 24 24" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    stroke-width="1.8" 
                                > 
                                    <circle 
                                        cx="11" 
                                        cy="11" 
                                        r="7" 
                                    ></circle> 
 
                                    <line 
                                        x1="16.5" 
                                        y1="16.5" 
                                        x2="21" 
                                        y2="21" 
                                    ></line> 
                                </svg> 
                            </div> 
 
                            <div class="laporan-empty-title"> 
                                Data Tidak Ditemukan 
                            </div> 
 
                            <div class="laporan-empty-text"> 
                                Tidak ada data yang sesuai dengan pencarian atau filter. 
                            </div> 
 
                        </div> 
                    </td> 
                `; 
 
                tableBody.appendChild(emptyRow); 
            } 
 
            emptyRow.style.display = ''; 
 
        } else if (emptyRow) { 
 
            emptyRow.style.display = 'none'; 
        } 
    } 
 
    function syncExportLink() { 
 
        const exportLink = 
            document.getElementById('laporanExportLink'); 
 
        if (!exportLink) { 
            return; 
        } 
 
        const params = new URLSearchParams(); 
 
        if (searchInput && searchInput.value.trim() !== '') { 
            params.set('search', searchInput.value.trim()); 
        } 
 
        if (periodeFilter && periodeFilter.value.trim() !== '') { 
            params.set('periode', periodeFilter.value.trim()); 
        } 
 
        if (wilayahFilter && wilayahFilter.value.trim() !== '') { 
            params.set('wilayah', wilayahFilter.value.trim()); 
        } 
 
        const query = params.toString(); 
 
        exportLink.href = 
            exportLink.pathname + 
            (query !== '' ? '?' + query : ''); 
    } 
 
    let searchSubmitTimer; 
 
    function submitReportFilters() { 
 
        if (laporanFilterForm) { 
            laporanFilterForm.requestSubmit(); 
        } 
    } 
 
    /* ===================================================== 
       SEARCH REALTIME 
    ===================================================== */ 
 
    if (searchInput) { 
 
        searchInput.addEventListener( 
            'input', 
            function () { 
 
                filterLaporanTable(); 
                syncExportLink(); 
 
                window.clearTimeout(searchSubmitTimer); 
 
                searchSubmitTimer = window.setTimeout( 
                    submitReportFilters, 
                    450 
                ); 
 
            } 
        ); 
    } 
 
    /* ===================================================== 
       FILTER PERIODE REALTIME 
    ===================================================== */ 
 
    if (periodeFilter) { 
 
        periodeFilter.addEventListener( 
            'change', 
            function () { 
 
                filterLaporanTable(); 
                syncExportLink(); 
                submitReportFilters(); 
 
            } 
        ); 
    } 
 
    /* ===================================================== 
       FILTER WILAYAH REALTIME 
    ===================================================== */ 
 
    if (wilayahFilter) { 
 
        wilayahFilter.addEventListener( 
            'change', 
            function () { 
 
                filterLaporanTable(); 
                syncExportLink(); 
                submitReportFilters(); 
 
            } 
        ); 
    } 
 
    /* ===================================================== 
       RESET FILTER 
    ===================================================== */ 
 
    const resetButton = document.getElementById( 
        'resetLaporanFilter' 
    ); 
 
    if (resetButton) { 
 
        resetButton.addEventListener( 
            'click', 
            function (event) { 
 
                event.preventDefault(); 
 
                if (searchInput) { 
                    searchInput.value = ''; 
                } 
 
                if (periodeFilter) { 
                    periodeFilter.value = ''; 
                } 
 
                if (wilayahFilter) { 
                    wilayahFilter.value = ''; 
                } 
 
                filterLaporanTable(); 
                syncExportLink(); 
                submitReportFilters(); 
 
            } 
        ); 
    } 
 
    /* ===================================================== 
       MODAL 
    ===================================================== */ 
 
    const modal = document.getElementById( 
        'laporanDetailModal' 
    ); 
 
    const closeModal = document.getElementById( 
        'closeLaporanModal' 
    ); 
 
    const closeModalBottom = document.getElementById( 
        'closeLaporanModalBottom' 
    ); 
 
    const downloadPdf = document.getElementById( 
        'downloadLaporanPdf' 
    ); 
 
    const modalNamaSummary = document.getElementById( 
        'modalNamaSummary' 
    ); 
 
    const modalKkSummary = document.getElementById( 
        'modalKkSummary' 
    ); 
 
    const modalStatusSummary = document.getElementById( 
        'modalStatusSummary' 
    ); 
 
    const modalNoKk = document.getElementById( 
        'modalNoKk' 
    ); 
 
    const modalNik = document.getElementById( 
        'modalNik' 
    ); 
 
    const modalNama = document.getElementById( 
        'modalNama' 
    ); 
 
    const modalJumlahAnggota = document.getElementById( 
        'modalJumlahAnggota' 
    ); 
 
    const modalWilayah = document.getElementById( 
        'modalWilayah' 
    ); 
 
    const modalPeriode = document.getElementById( 
        'modalPeriode' 
    ); 
 
    const modalTanggal = document.getElementById( 
        'modalTanggal' 
    ); 
 
    const modalStatus = document.getElementById( 
        'modalStatus' 
    ); 
 
    const modalComplete = document.getElementById( 
        'modalComplete' 
    ); 
 
    const modalMembersContent = document.getElementById( 
        'modalMembersContent' 
    ); 
 
    const modalQuestionnaireContent = document.getElementById( 
        'modalQuestionnaireContent' 
    ); 
 
    function escapeHtml(value) { 
 
        return String(value ?? '') 
            .replace(/&/g, '&amp;') 
            .replace(/</g, '&lt;') 
            .replace(/>/g, '&gt;') 
            .replace(/"/g, '&quot;') 
            .replace(/'/g, '&#039;'); 
    } 
 
    /* ===================================================== 
       RENDER MEMBERS 
    ===================================================== */ 
 
    function renderReportMembers(members) { 
 
        if (!modalMembersContent) { 
            return; 
        } 
 
        if (!Array.isArray(members) || members.length === 0) { 
 
            modalMembersContent.className = 'laporan-detail-empty'; 
 
            modalMembersContent.textContent = 
                'Data anggota keluarga belum tersedia.'; 
 
            return; 
        } 
 
        modalMembersContent.className = 'laporan-members-table-wrap'; 
 
        modalMembersContent.innerHTML = ` 
            <table class="laporan-members-table"> 
                <thead> 
                    <tr> 
                        <th>No.</th> 
                        <th>NIK</th> 
                        <th>Nama Lengkap</th> 
                        <th>Status Keluarga</th> 
                    </tr> 
                </thead> 
 
                <tbody> 
 
                    ${members.map(function (member, index) { 
 
                        return ` 
                            <tr> 
                                <td>${index + 1}</td> 
 
                                <td> 
                                    ${escapeHtml(member.nik || '-')} 
                                </td> 
 
                                <td> 
                                    ${escapeHtml(member.nama_lengkap || '-')} 
                                </td> 
 
                                <td> 
                                    ${escapeHtml(member.status_keluarga || '-')} 
                                </td> 
                            </tr> 
                        `; 
 
                    }).join('')} 
 
                </tbody> 
            </table> 
        `; 
    } 
 
    /* ===================================================== 
       ANSWER TEXT 
    ===================================================== */ 
 
    function reportAnswerText(question) { 
 
        const answer = 
            question.answer ?? 
            question.jawaban ?? 
            question.value ?? 
            ''; 
 
        if (Array.isArray(answer)) { 
            return answer.join(', '); 
        } 
 
        if (answer && typeof answer === 'object') { 
            return JSON.stringify(answer); 
        } 
 
        return String(answer || 'Belum diisi'); 
    } 
 
    /* ===================================================== 
       QUESTIONNAIRE 
       PART KIRI + JAWABAN KANAN 
    ===================================================== */ 
 
    function renderReportQuestionnaire(parts, item) { 
 
        if (!modalQuestionnaireContent) { 
            return; 
        } 
 
        if (!Array.isArray(parts) || parts.length === 0) { 
 
            modalQuestionnaireContent.className = 
                'laporan-detail-empty'; 
 
            modalQuestionnaireContent.textContent = 
                'Hasil kuisioner belum tersedia.'; 
 
            return; 
        } 
 
        modalQuestionnaireContent.className = ''; 
 
        const firstPart = parts[0]; 
 
        /* ===================================================== 
           RENDER PERTANYAAN 
        ===================================================== */ 
 
        function renderQuestions(part) { 
 
            const questions = Array.isArray(part.questions) 
                ? part.questions 
                : []; 
 
            if (questions.length === 0) { 
 
                return ` 
                    <div class="laporan-detail-empty"> 
                        Belum ada jawaban pada bagian ini. 
                    </div> 
                `; 
            } 
 
            return ` 
                <table class="laporan-question-table"> 
 
                    <thead> 
                        <tr> 
 
                            <th style="width:45%;"> 
                                Pertanyaan 
                            </th> 
 
                            <th> 
                                Jawaban 
                            </th> 
 
                        </tr> 
                    </thead> 
 
                    <tbody> 
 
                        ${questions.map(function (question) { 
 
                            const label = 
                                question.question || 
                                question.text || 
                                question.pertanyaan || 
                                'Pertanyaan'; 
 
                            const answer = 
                                reportAnswerText(question); 
 
                            const imageUrl = 
                                question.imageUrl || 
                                question.image_url || 
                                question.url || 
                                question.path || 
                                ( 
                                    question.type === 'image' 
                                        ? answer 
                                        : '' 
                                ); 
 
                            const safeImageUrl = 
                                /^(https?:\/\/|\/storage\/|storage\/|\/uploads\/|uploads\/)/i 
                                    .test(imageUrl); 
 
                            const isImage = 
                                safeImageUrl && 
                                ( 
                                    question.type === 'image' || 
                                    question.imageUrl || 
                                    question.image_url || 
                                    question.url || 
                                    question.path 
                                ); 
 
                            return ` 
                                <tr> 
 
                                    <td> 
                                        ${escapeHtml(label)} 
                                    </td> 
 
                                    <td> 
 
                                        ${ 
                                            isImage 
                                                ? ` 
                                                    <a 
                                                        href="${escapeHtml(imageUrl)}" 
                                                        target="_blank" 
                                                        rel="noopener noreferrer" 
                                                    > 
                                                        <img 
                                                            src="${escapeHtml(imageUrl)}" 
                                                            class="laporan-answer-image" 
                                                            alt="Foto jawaban" 
                                                            onerror="this.style.display='none';" 
                                                        > 
 
                                                        <span class="laporan-answer-image-link"> 
                                                            Lihat foto 
                                                        </span> 
                                                    </a> 
                                                  ` 
 
                                                : safeImageUrl 
                                                    ? ` 
                                                        <a 
                                                            href="${escapeHtml(imageUrl)}" 
                                                            target="_blank" 
                                                            rel="noopener noreferrer" 
                                                            class="laporan-answer-image-link" 
                                                        > 
                                                            Lihat file 
                                                        </a> 
                                                      ` 
 
                                                    : escapeHtml(answer) 
                                        } 
 
                                    </td> 
 
                                </tr> 
                            `; 
 
                        }).join('')} 
 
                    </tbody> 
 
                </table>

                ${
                    String(part.part || '').trim() === '1' ||
                    String(part.part || '').trim().toLowerCase() === 'part 1'
                        ? renderPart1GeotagMap()
                        : ''
                }
            `;
        } 
 
        /* =====================================================
           PETA GEOTAGGING KHUSUS PART 1
           Peta ditampilkan di dalam Hasil Kuisioner Part 1.
        ===================================================== */

        function renderPart1GeotagMap() {

            const source = item || {};

            const latitude =
                source.latitude ??
                source.lat ??
                source.latitude_lokasi ??
                source.latitude_geotag ??
                source.lat_geotag ??
                source.lokasi_latitude ??
                source.koordinat_latitude ??
                source.koordinat_lat ??
                source.location_latitude ??
                null;

            const longitude =
                source.longitude ??
                source.lng ??
                source.long ??
                source.longitude_lokasi ??
                source.longitude_geotag ??
                source.lng_geotag ??
                source.lokasi_longitude ??
                source.koordinat_longitude ??
                source.koordinat_lng ??
                source.location_longitude ??
                null;

            const lat = parseFloat(latitude);
            const lng = parseFloat(longitude);

            if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                return `
                    <div class="laporan-part1-map-section">
                        <div class="laporan-part1-map-title">
                            Lokasi Geotagging
                        </div>
                        <div class="laporan-map-loading">
                            <strong>Koordinat lokasi belum tersedia.</strong>
                            <br>
                            Data latitude dan longitude belum dikirim oleh server.
                        </div>
                    </div>
                `;
            }

            const mapUrl =
                'https://www.google.com/maps?q=' +
                encodeURIComponent(lat + ',' + lng) +
                '&z=17&output=embed';

            return `
                <div class="laporan-part1-map-section">
                    <div class="laporan-part1-map-title">
                        Lokasi Geotagging
                    </div>

                    <div class="laporan-part1-map-container">
                        <iframe
                            src="${mapUrl}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                            title="Peta lokasi geotagging Part 1"
                        ></iframe>
                    </div>

                    <div class="laporan-part1-coordinate">
                        Latitude: <strong>${escapeHtml(lat)}</strong>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        Longitude: <strong>${escapeHtml(lng)}</strong>
                    </div>
                </div>
            `;
        }

        /* =====================================================
           HTML PART KIRI + JAWABAN KANAN
        ===================================================== */ 
 
        modalQuestionnaireContent.innerHTML = ` 
 
            <div class="laporan-questionnaire-layout"> 
 
                <div class="laporan-question-list"> 
 
                    ${parts.map(function (part, index) { 
 
                        return ` 
                            <button 
                                type="button" 
                                class="laporan-question-part-btn ${index === 0 ? 'active' : ''}" 
                                data-part-index="${index}" 
                            > 
 
                                Part ${escapeHtml( 
                                    part.part || index + 1 
                                )} 
 
                                ${ 
                                    part.title 
                                        ? ` — ${escapeHtml(part.title)}` 
                                        : '' 
                                } 
 
                            </button> 
                        `; 
 
                    }).join('')} 
 
                </div> 
 
                <div class="laporan-question-answer"> 
 
                    <div 
                        class="laporan-question-answer-title" 
                        id="laporanQuestionAnswerTitle" 
                    > 
 
                        Part ${escapeHtml( 
                            firstPart.part || 1 
                        )} 
 
                        ${ 
                            firstPart.title 
                                ? ` — ${escapeHtml(firstPart.title)}` 
                                : '' 
                        } 
 
                    </div> 
 
                    <div 
                        class="laporan-question-answer-body" 
                        id="laporanQuestionAnswerBody" 
                    > 
 
                        ${renderQuestions(firstPart)} 
 
                    </div> 
 
                </div> 
 
            </div> 
        `; 
 
        const partButtons = 
            modalQuestionnaireContent.querySelectorAll( 
                '.laporan-question-part-btn' 
            ); 
 
        const answerTitle = 
            modalQuestionnaireContent.querySelector( 
                '#laporanQuestionAnswerTitle' 
            ); 
 
        const answerBody = 
            modalQuestionnaireContent.querySelector( 
                '#laporanQuestionAnswerBody' 
            ); 
 
        /* ===================================================== 
           KLIK PART 
        ===================================================== */ 
 
        partButtons.forEach(function (button) { 
 
            button.addEventListener('click', function () { 
 
                const index = 
                    Number(this.dataset.partIndex); 
 
                const selectedPart = 
                    parts[index]; 
 
                if (!selectedPart) { 
                    return; 
                } 
 
                partButtons.forEach(function (item) { 
                    item.classList.remove('active'); 
                }); 
 
                this.classList.add('active'); 
 
                if (answerTitle) { 
 
                    answerTitle.innerHTML = ` 
 
                        Part ${escapeHtml( 
                            selectedPart.part || index + 1 
                        )} 
 
                        ${ 
                            selectedPart.title 
                                ? ` — ${escapeHtml(selectedPart.title)}` 
                                : '' 
                        } 
 
                    `; 
                } 
 
                if (answerBody) { 
 
                    answerBody.innerHTML = 
                        renderQuestions(selectedPart); 
                } 
 
            }); 
 
        }); 
    } 
 
    /* ===================================================== 
       OPEN MODAL 
    ===================================================== */ 
 
    function applyDetailStatusTone(element, status) {
        if (!element) {
            return;
        }

        const normalizedStatus = String(status || '').trim().toLowerCase();
        element.classList.remove('laporan-detail-status', 'status-approved', 'status-rejected');

        if (['approved', 'disetujui'].includes(normalizedStatus)) {
            element.classList.add('laporan-detail-status', 'status-approved');
        } else if (['rejected', 'ditolak', 'reject'].includes(normalizedStatus)) {
            element.classList.add('laporan-detail-status', 'status-rejected');
        }
    }

    async function openLaporanModal(button) { 
 
        const data = button.dataset; 
 
        if (modalNamaSummary) { 
            modalNamaSummary.textContent = 
                data.nama || '-'; 
        } 
 
        if (modalKkSummary) { 
            modalKkSummary.textContent = 
                data.noKk || '-'; 
        } 
 
        if (modalStatusSummary) { 
            modalStatusSummary.textContent = 
                data.status || '-'; 
            applyDetailStatusTone(modalStatusSummary, data.status);
        } 
 
        if (modalNoKk) { 
            modalNoKk.textContent = 
                data.noKk || '-'; 
        } 
 
        if (modalNik) { 
            modalNik.textContent = 
                data.nik || '-'; 
        } 
 
        if (modalNama) { 
            modalNama.textContent = 
                data.nama || '-'; 
        } 
 
        if (modalJumlahAnggota) { 
            modalJumlahAnggota.textContent = 
                (data.jumlahAnggota || '0') + ' Orang'; 
        } 
 
        if (modalWilayah) { 
            modalWilayah.textContent = 
                data.wilayah || '-'; 
        } 
 
        if (modalPeriode) { 
            modalPeriode.textContent = 
                data.periode || '-'; 
        } 
 
        if (modalTanggal) { 
            modalTanggal.textContent = 
                data.tanggal || '-'; 
        } 
 
        if (modalStatus) { 
            modalStatus.textContent = 
                data.status || '-'; 
            applyDetailStatusTone(modalStatus, data.status);
        } 
 
        if (modalComplete) { 
            modalComplete.textContent = '-'; 
        } 
 
        if (modalMembersContent) { 
 
            modalMembersContent.className = 
                'laporan-detail-loading'; 
 
            modalMembersContent.textContent = 
                'Memuat data anggota keluarga...'; 
        } 
 
        if (modalQuestionnaireContent) { 
 
            modalQuestionnaireContent.className = 
                'laporan-detail-loading'; 
 
            modalQuestionnaireContent.textContent = 
                'Memuat hasil kuisioner...'; 
        } 
 
        if (downloadPdf) { 
            downloadPdf.href = data.pdfUrl || '#'; 
        } 
 
        if (modal) { 
 
            modal.classList.add('active'); 
 
            modal.setAttribute( 
                'aria-hidden', 
                'false' 
            ); 
 
            document.body.style.overflow = 'hidden'; 
        } 
 
        try { 
 
            const response = await fetch( 
                data.detailUrl, 
                { 
                    headers: { 
                        Accept: 'application/json' 
                    }, 
                    credentials: 'same-origin' 
                } 
            ); 
 
            if (!response.ok) { 
 
                throw new Error( 
                    'Gagal memuat detail laporan (' + 
                    response.status + 
                    ').' 
                ); 
            } 
 
            const payload = await response.json(); 
 
            const item = payload.data; 
 
            if ( 
                !item || 
                String(item.id) !== String(data.id) 
            ) { 
 
                throw new Error( 
                    'Data detail laporan tidak sesuai dengan KK yang dipilih.' 
                ); 
            } 
 
            if (modalStatusSummary) { 
  
                modalStatusSummary.textContent = 
                    item.status_label || '-'; 
                applyDetailStatusTone(modalStatusSummary, item.status_label);
            } 
  
            if (modalStatus) { 
  
                modalStatus.textContent = 
                    item.status_label || '-'; 
                applyDetailStatusTone(modalStatus, item.status_label);
            } 
 
            if (modalComplete) { 
 
                modalComplete.textContent = 
                    item.is_complete 
                        ? 'Data lengkap (Part 1–5)' 
                        : 'Belum lengkap'; 
            } 
 
            /* ================================================= 
               RENDER ANGGOTA 
            ================================================= */ 
 
            renderReportMembers( 
                item.anggota_detail || [] 
            ); 
 
            /* ================================================= 
               RENDER KUISIONER + GEOTAGGING DI PART 1 
            ================================================= */ 
 
            renderReportQuestionnaire( 
                item.kuisioner || [],
                item
            ); 
 
        } catch (error) { 
 
            if (modalMembersContent) { 
 
                modalMembersContent.className = 
                    'laporan-detail-error'; 
 
                modalMembersContent.textContent = 
                    error.message; 
            } 
 
            if (modalQuestionnaireContent) { 
 
                modalQuestionnaireContent.className = 
                    'laporan-detail-error'; 
 
                modalQuestionnaireContent.textContent = 
                    'Detail kuisioner tidak dapat dimuat.'; 
            } 
 
        } 
    } 
 
    /* ===================================================== 
       CLOSE MODAL 
    ===================================================== */ 
 
    function closeLaporanModal() { 
 
        if (!modal) return; 
 
        modal.classList.remove('active'); 
 
        modal.setAttribute( 
            'aria-hidden', 
            'true' 
        ); 
 
        document.body.style.overflow = ''; 
    } 
 
    /* ===================================================== 
       DETAIL BUTTON 
    ===================================================== */ 
 
    document 
        .querySelectorAll('.btn-detail-laporan') 
        .forEach(function (button) { 
 
            button.addEventListener( 
                'click', 
                function () { 
 
                    openLaporanModal(this); 
 
                } 
            ); 
 
        }); 
 
    /* ===================================================== 
       CLOSE BUTTON 
    ===================================================== */ 
 
    if (closeModal) { 
 
        closeModal.addEventListener( 
            'click', 
            closeLaporanModal 
        ); 
 
    } 
 
    if (closeModalBottom) { 
 
        closeModalBottom.addEventListener( 
            'click', 
            closeLaporanModal 
        ); 
 
    } 
 
    /* ===================================================== 
       CLICK OUTSIDE MODAL 
    ===================================================== */ 
 
    if (modal) { 
 
        modal.addEventListener( 
            'click', 
            function (event) { 
 
                if (event.target === modal) { 
 
                    closeLaporanModal(); 
 
                } 
 
            } 
        ); 
 
    } 
 
    /* ===================================================== 
       ESC 
    ===================================================== */ 
 
    document.addEventListener( 
        'keydown', 
        function (event) { 
 
            if ( 
                event.key === 'Escape' && 
                modal && 
                modal.classList.contains('active') 
            ) { 
 
                closeLaporanModal(); 
 
            } 
 
        } 
    ); 
 
    filterLaporanTable(); 
 
}); 
</script> 
 
@endsection