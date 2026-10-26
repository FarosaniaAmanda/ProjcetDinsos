@extends('admin.layouts.app') 
 
@section('title', 'Laporan Pendataan') 
 
@section('content')

@php
    $data = $laporan ?? collect();
@endphp
 
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

 <style>

/* Kartu alasan penolakan pada Detail Laporan */
.laporan-rejection-reason {
    display: none;
    margin: 0 0 18px;
    padding: 16px 18px;
    border: 1px solid #fecaca;
    border-left: 4px solid #dc2626;
    border-radius: 10px;
    background: #fff7f7;
}
.laporan-rejection-reason.show { display: block; }
.laporan-rejection-reason-title {
    margin: 0 0 7px;
    color: #b91c1c;
    font-size: 14px;
    font-weight: 700;
}
.laporan-rejection-reason-text {
    margin: 0;
    color: #7f1d1d;
    font-size: 13px;
    line-height: 1.65;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}


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
        minmax(0, 1.8fr)
        minmax(0, 1fr)
        minmax(0, 1fr)
        minmax(0, 1fr);

    width: 100%;
    max-width: 1100px;

    margin: 0 auto;

    gap: 12px;

    align-items: end;

    box-sizing: border-box;
}
.laporan-filter-reset-group {
    display: none !important;
}

.laporan-filter-reset-group {
    grid-column: span 2;
    display: flex;
    gap: 8px;
}

.laporan-filter-reset-group .laporan-reset-btn {
    flex: 1;
}

.laporan-apply-btn {
    border-color: #252A86;
    background: #252A86;
    color: #fff;
    font-family: inherit;
    cursor: pointer;
}


.laporan-filter-group {
    width: 100%;
    min-width: 0;
    max-width: 100%;
    box-sizing: border-box;
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

/* Keterangan jumlah hasil pencarian */
.laporan-search-result {
    margin: 4px 0 0 3px;
    padding: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}
.laporan-search-result strong {
    color: #252A86;
    font-weight: 600;
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
    color: #237D43;
}

.laporan-status.belum {
    background: #F0F0F0;
    color: #6B7280;
}

.laporan-status.diproses {
    background: #FFF3D6;
    color: #A87500;
}

.laporan-status.ditolak {
    background: #FDECEC;
    color: #C33333;
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

    .laporan-filter-reset-group {
        grid-column: 1 / -1;
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

    .laporan-filter-reset-group {
        grid-column: auto;
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
    grid-column: 1 / -1;
    display: block;
    width: 100%;
    max-width: none;
    min-width: 0;
    margin-top: 12px;
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e2e6f0;
    border-radius: 12px;
    box-sizing: border-box;
}

.laporan-part1-map-title {
    margin-bottom: 10px;
    color: #252a86;
    font-size: 14px;
    line-height: 1.4;
    font-weight: 700;
}

.laporan-part1-map-card {
    display: block;
    width: 100%;
    max-width: none;
    min-width: 0;
    padding: 10px;
    background: #f5f8fa;
    border: 1px solid #e2e6f0;
    border-radius: 10px;
    box-sizing: border-box;
}

.laporan-part1-map-container {
    display: block;
    width: 100%;
    height: 260px;
    min-width: 0;
    overflow: hidden;
    border: 1px solid #dfe3ee;
    border-radius: 10px;
    background: #eef2f7;
    box-sizing: border-box;
}

.laporan-part1-map-container .leaflet-container {
    width: 100%;
    height: 100%;
}

.laporan-part1-coordinate {
    width: 100%;
    margin-bottom: 10px;
    padding: 10px 12px;
    color: #64748b;
    background: #fff;
    border: 1px solid #dfe3ee;
    border-radius: 8px;
    font-size: 12px;
    line-height: 1.5;
    overflow-wrap: anywhere;
    box-sizing: border-box;
}

.laporan-part1-coordinate strong {
    color: #334155;
}

.laporan-part1-map-link {
    display: inline-block;
    margin-top: 10px;
    padding: 9px 12px;
    background: #eef0ff;
    border-radius: 8px;
    color: #252a86;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
}

@media (max-width: 768px) {
    .laporan-part1-map-container {
        height: 220px;
    }
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
.laporan-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 26px;
    border-top: 1px solid #E2E4EF;
    background: #FBFBFD;
    flex-wrap: wrap;
}

.pagination-info {
    color: #6B7280;
    font-size: 13px;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.page-btn {
    min-width: 35px;
    height: 35px;
    padding: 0 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #D9DCE8;
    border-radius: 8px;
    background: #fff;
    color: #252A86;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: .2s ease;
}

.page-btn:hover {
    background: #EEF1FF;
    border-color: #252A86;
}

.page-btn.active {
    background: #252A86;
    border-color: #252A86;
    color: #fff;
}

.page-btn.disabled {
    color: #B8BBC7;
    background: #F5F6FA;
    cursor: not-allowed;
}

@media (max-width: 700px) {
    .laporan-pagination {
        padding: 16px 18px;
        flex-direction: column;
        align-items: stretch;
    }

    .pagination {
        justify-content: center;
    }
}

/* Detail Laporan follows the verification modal's information and questionnaire layout. */
.laporan-modal {
    width: min(1250px, calc(100vw - 40px));
    flex: 0 0 min(1250px, calc(100vw - 40px));
    max-height: calc(100vh - 40px);
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
}

.laporan-modal-header {
    padding: 18px 20px;
    align-items: flex-start;
    border-bottom-color: #e8e9ef;
}

.laporan-modal-header h2 {
    margin: 0;
    color: #252a86;
    font-size: 19px;
    font-weight: 700;
}

.laporan-modal-header p {
    margin: 4px 0 0;
    color: #888;
    font-size: 11px;
}

.laporan-modal-close {
    display: flex;
    flex: 0 0 32px;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 7px;
    background: #f3f4f8;
    color: #666;
}

.laporan-modal-close:hover {
    background: #252a86;
    color: #fff;
}

.laporan-modal-body {
    padding: 18px 20px 22px;
    max-height: calc(100vh - 125px);
    scrollbar-width: thin;
}

.laporan-modal-summary {
    gap: 10px;
    margin-bottom: 16px;
}

.laporan-summary-box {
    padding: 13px 14px;
    border-color: #e7e9f1;
    border-radius: 10px;
    background: #f8f9fc;
}

.laporan-summary-label {
    display: block;
    margin-bottom: 5px;
    color: #7b8192;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.laporan-summary-value {
    display: block;
    margin: 4px 0 0;
    color: #263238;
    font-size: 14px;
    font-weight: 700;
}

.laporan-summary-value.status-approved {
    color: #24723c;
    background: #eaf8ef;
    border: 1px solid #bce5c9;
}

.laporan-summary-value.status-rejected {
    color: #a53636;
    background: #fdecec;
    border: 1px solid #f2c4c4;
}

.laporan-info-section,
.laporan-detail-data-section {
    margin: 0 0 16px;
    padding: 16px;
    border: 1px solid #e7e9f1;
    border-radius: 12px;
    background: #fff;
}

.laporan-info-title,
.laporan-detail-data-heading {
    margin: 0 0 13px;
    color: #252a86;
    font-size: 15px;
    font-weight: 700;
}

.laporan-info-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 9px;
}

.laporan-info-item {
    padding: 11px 12px;
    border-color: #edf0f5;
    border-radius: 9px;
    background: #fafbfc;
}

.laporan-info-label {
    display: block;
    margin-bottom: 5px;
    color: #6b7280;
    font-size: 10px;
    font-weight: 600;
}

.laporan-info-value {
    display: block;
    overflow: hidden;
    color: #263238;
    font-size: 13px;
    line-height: 1.4;
    font-weight: 700;
    text-overflow: ellipsis;
}

.laporan-info-value.laporan-detail-status {
    display: inline-flex;
    align-items: center;
    width: fit-content;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 11px;
    line-height: 1.2;
    overflow: visible;
}

.laporan-info-value.status-approved {
    color: #24723c;
    background: #eaf8ef;
    border: 1px solid #bce5c9;
}

.laporan-info-value.status-rejected {
    color: #a53636;
    background: #fdecec;
    border: 1px solid #f2c4c4;
}

.laporan-questionnaire-description {
    margin: -7px 0 13px;
    color: #7d8392;
    font-size: 11px;
    line-height: 1.5;
}

.laporan-detail-data-section:has(#laporanQuestionnaireTotal) {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
}

.laporan-detail-data-section:has(#laporanQuestionnaireTotal) > .laporan-detail-data-heading,
.laporan-detail-data-section:has(#laporanQuestionnaireTotal) > .laporan-questionnaire-description {
    grid-column: 1;
}

.laporan-detail-data-section:has(#laporanQuestionnaireTotal) > #laporanQuestionnaireTotal {
    grid-column: 2;
    grid-row: 1;
    align-self: start;
}

.laporan-detail-data-section:has(#laporanQuestionnaireTotal) > #modalQuestionnaireContent {
    grid-column: 1 / -1;
    min-width: 0;
}

.laporan-detail-data-heading {
    font-size: 15px;
}

.laporan-members-table-wrap {
    border-color: #e3e6ef;
    border-radius: 9px;
}

.laporan-members-table {
    min-width: 500px;
}

.laporan-members-table th {
    padding: 11px 13px;
    background: #f1f3ff;
    font-size: 11px;
}

.laporan-members-table td {
    padding: 11px 13px;
    font-size: 12px;
}

.questionnaire-total,
.questionnaire-content-count,
.questionnaire-part5-answer-count {
    flex: 0 0 auto;
    padding: 6px 10px;
    border-radius: 999px;
    background: #eef0ff;
    color: #252a86;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.questionnaire-parts {
    display: grid;
    grid-template-columns: 250px minmax(0, 1fr);
    gap: 16px;
    width: 100%;
    min-width: 0;
}

.questionnaire-sidebar {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 10px;
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    background: #f8f9fc;
}

.questionnaire-sidebar-title {
    padding: 6px 8px 9px;
    color: #5c6272;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .6px;
}

.questionnaire-part {
    width: 100%;
    overflow: hidden;
    border: 1px solid transparent;
    border-radius: 10px;
}

.questionnaire-part-header {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    min-height: 64px;
    padding: 10px 11px;
    border: 1px solid #e3e6ef;
    border-radius: 10px;
    background: #fff;
    color: #303545;
    text-align: left;
    cursor: pointer;
}

.questionnaire-part-header:hover,
.questionnaire-part.is-active .questionnaire-part-header {
    border-color: #252a86;
    background: #eef0ff;
}

.questionnaire-part-number {
    display: flex;
    flex: 0 0 34px;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eef0f5;
    color: #555b6b;
    font-size: 11px;
    font-weight: 800;
}

.questionnaire-part.is-active .questionnaire-part-number,
.questionnaire-part.is-active .questionnaire-part-chevron {
    background: #252a86;
    color: #fff;
}

.questionnaire-part-title {
    flex: 1;
    min-width: 0;
}

.questionnaire-part-title strong,
.questionnaire-part-title span {
    display: block;
}

.questionnaire-part-title strong {
    overflow: hidden;
    color: #303545;
    font-size: 12px;
    line-height: 1.35;
    text-overflow: ellipsis;
}

.questionnaire-part.is-active .questionnaire-part-title strong {
    color: #252a86;
}

.questionnaire-part-title span {
    margin-top: 3px;
    color: #858b99;
    font-size: 10px;
}

.questionnaire-part-chevron {
    display: flex;
    flex: 0 0 24px;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #f1f2f6;
    color: #777d8c;
}

.questionnaire-part.is-active .questionnaire-part-chevron {
    transform: rotate(180deg);
}

.questionnaire-part-body {
    display: none;
}

.questionnaire-content-panel {
    display: flex;
    flex-direction: column;
    min-width: 0;
    overflow: hidden;
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    background: #fff;
}

.questionnaire-content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 15px 17px;
    border-bottom: 1px solid #e7e9f0;
    background: #fafbfe;
}

.questionnaire-content-heading {
    min-width: 0;
}

.questionnaire-content-kicker {
    display: block;
    margin-bottom: 3px;
    color: #7c8291;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.questionnaire-content-title {
    margin: 0;
    color: #252a86;
    font-size: 15px;
    font-weight: 800;
    line-height: 1.35;
}

.questionnaire-content-body {
    flex: 1;
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    align-content: start;
    gap: 10px;
    padding: 15px;
    max-height: 520px;
    overflow-y: auto;
    background: #fff;
}

.questionnaire-question {
    min-width: 0;
    padding: 15px;
    border: 1px solid #e5e7ee;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 7px rgba(30, 35, 60, .03);
}


.questionnaire-question-number {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    margin-bottom: 8px;
    padding: 4px 8px;
    border-radius: 6px;
    background: #f0f1f5;
    color: #666c7a;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .35px;
}

.questionnaire-question-text {
    margin-bottom: 12px;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.65;
    overflow-wrap: anywhere;
}

.questionnaire-answer-label {
    margin-bottom: 5px;
    color: #656b79;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.questionnaire-answer {
    min-height: 42px;
    padding: 10px 12px;
    border-left: 3px solid #55b5d5;
    border-radius: 7px;
    background: #f5f8fa;
    color: #374151;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.6;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.questionnaire-answer.is-empty {
    border-left-color: #f5c928;
    background: #fff9e8;
    color: #8a6a20;
}

.questionnaire-content-body.is-photo-part {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.questionnaire-content-body.is-photo-part > .questionnaire-question {
    display: flex;
    flex-direction: column;
    align-self: start;
    min-width: 0;
    height: fit-content;
    padding: 10px;
    overflow: hidden;
}

.questionnaire-content-body.is-photo-part .questionnaire-question-text {
    margin-bottom: 8px;
    font-size: 13px;
}

.questionnaire-content-body.is-photo-part .questionnaire-answer-image {
    display: flex;
    flex-direction: column;
    flex: 0 0 auto;
    gap: 6px;
    min-height: 0;
    padding: 0;
    border: 0;
    background: transparent;
}

.questionnaire-content-body.is-photo-part .questionnaire-answer-image img {
    display: block;
    width: 100%;
    flex: 0 0 220px;
    height: 220px;
    max-height: 220px;
    margin: 0;
    border: 1px solid #e3e6ef;
    border-radius: 9px;
    background: #f5f7fb;
    object-fit: cover;
}

.questionnaire-image-caption {
    margin-top: 2px;
    color: #30364a;
    font-size: 12px;
    font-weight: 700;
}

.questionnaire-image-link,
.questionnaire-part5-image-link {
    align-self: flex-start;
    display: inline-flex;
    justify-content: center;
    margin-top: 2px;
    padding: 7px 10px;
    border-radius: 7px;
    background: #eef0ff;
    color: #252a86;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.questionnaire-image-link:hover,
.questionnaire-part5-image-link:hover {
    background: #252a86;
    color: #fff;
}

.questionnaire-part1-map-section {
    grid-column: 1 / -1;
    margin-top: 0;
    padding: 12px;
    border: 1px solid #e3e6ef;
    border-radius: 10px;
    background: #f8fafc;
}

.questionnaire-part1-map-title {
    margin-bottom: 10px;
    color: #252a86;
    font-size: 13px;
    font-weight: 700;
}

.questionnaire-part1-map-container {
    height: 230px;
    overflow: hidden;
    border: 1px solid #dfe3ee;
    border-radius: 9px;
}

.questionnaire-part1-map-container iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

.questionnaire-part1-coordinate {
    margin-top: 8px;
    color: #64748b;
    font-size: 11px;
}

.questionnaire-empty,
.laporan-detail-loading,
.laporan-detail-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 180px;
    padding: 25px;
    border: 1px dashed #dfe2eb;
    border-radius: 10px;
    background: #fafbfc;
    color: #7b8190;
    text-align: center;
}

.questionnaire-empty strong,
.laporan-detail-empty strong {
    color: #4a5060;
    font-size: 13px;
}

.questionnaire-empty span,
.laporan-detail-empty span {
    max-width: 400px;
    margin-top: 5px;
    color: #8a90a0;
    font-size: 11px;
    line-height: 1.5;
}



/* =====================================================
   PART 5 LAPORAN
   Card anggota di atas, jawaban di bawah
===================================================== */

.questionnaire-part5-layout {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    gap: 14px !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.questionnaire-part5-members,
.questionnaire-part5-answer {
    display: block !important;
    flex: 0 0 auto !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
    border: 1px solid #e0e5f1 !important;
    border-radius: 12px !important;
    background: #fff !important;
    box-sizing: border-box !important;
    overflow: hidden;
}

/* Header panel */
.questionnaire-part5-panel-header {
    display: block !important;
    padding: 13px 15px !important;
    border-bottom: 1px solid #e5e9f2 !important;
    background: #fafbfe !important;
}

.questionnaire-part5-panel-header strong {
    display: block !important;
    color: #252a86 !important;
    font-size: 15px !important;
    font-weight: 800 !important;
}

.questionnaire-part5-panel-header span {
    display: block !important;
    margin-top: 4px !important;
    color: #7b8395 !important;
    font-size: 12px !important;
    line-height: 1.5 !important;
}

/* Card anggota berjajar di bagian atas */
.questionnaire-part5-member-list {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    align-items: stretch !important;
    gap: 10px !important;
    width: 100% !important;
    max-height: none !important;
    overflow: visible !important;
    padding: 10px !important;
    box-sizing: border-box !important;
}

/* Bentuk dan ukuran setiap card */
.questionnaire-part5-member-card,
.questionnaire-part5-member-card:last-child {
    display: block !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    min-height: 84px !important;
    margin: 0 !important;
    padding: 11px !important;
    border: 1px solid #e0e5f1 !important;
    border-radius: 11px !important;
    text-align: left !important;
    white-space: normal !important;
    box-sizing: border-box !important;
    cursor: pointer;
    transition: border-color .18s ease, box-shadow .18s ease;
}

/* Warna berbeda untuk masing-masing anggota */
.questionnaire-part5-member-card:nth-child(5n + 1) {
    background: #fff0f5 !important;
    border-color: #f4c8d8 !important;
}

.questionnaire-part5-member-card:nth-child(5n + 2) {
    background: #fff8d9 !important;
    border-color: #f0df9b !important;
}

.questionnaire-part5-member-card:nth-child(5n + 3) {
    background: #f1eaff !important;
    border-color: #d7c6f4 !important;
}

.questionnaire-part5-member-card:nth-child(5n + 4) {
    background: #e8f4ff !important;
    border-color: #c4def5 !important;
}

.questionnaire-part5-member-card:nth-child(5n) {
    background: #e9f8ed !important;
    border-color: #c5e8ce !important;
}

/* Hover tidak menghapus warna dasar card */
.questionnaire-part5-member-card:hover {
    filter: brightness(.98);
}

/* Card anggota terpilih */
.questionnaire-part5-member-card.is-selected {
    outline: 2px solid #252a86 !important;
    outline-offset: 1px !important;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, .10) !important;
}

/* Informasi anggota di dalam card */
.questionnaire-part5-member-top {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.questionnaire-part5-member-avatar {
    display: flex !important;
    flex: 0 0 32px !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 9px !important;
    background: rgba(255, 255, 255, .8) !important;
    color: #252a86 !important;
    font-size: 12px !important;
    font-weight: 800 !important;
}

.questionnaire-part5-member-main {
    flex: 1 !important;
    min-width: 0 !important;
}

.questionnaire-part5-member-name {
    display: block !important;
    color: #303548 !important;
    font-size: 13px !important;
    font-weight: 750 !important;
    line-height: 1.4 !important;
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: clip !important;
    overflow-wrap: anywhere !important;
}

.questionnaire-part5-member-relation {
    display: block !important;
    margin-top: 3px !important;
    color: #626b7d !important;
    font-size: 11px !important;
}

.questionnaire-part5-member-nik {
    display: block !important;
    margin-top: 7px !important;
    color: #626b7d !important;
    font-size: 10px !important;
    line-height: 1.5 !important;
    overflow-wrap: anywhere !important;
}

/* Panel jawaban anggota di bawah */
.questionnaire-part5-answer-body {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    align-items: start !important;
    gap: 12px !important;
    width: 100% !important;
    max-height: none !important;
    overflow: visible !important;
    padding: 14px !important;
    box-sizing: border-box !important;
}

.questionnaire-part5-answer-member,
.questionnaire-part5-answer-empty {
    grid-column: 1 / -1 !important;
    min-width: 0 !important;
}

.questionnaire-part5-answer-member {
    display: flex !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    gap: 10px !important;
    padding-bottom: 12px !important;
    border-bottom: 1px solid #e5e9f2 !important;
}

.questionnaire-part5-answer-member h4 {
    margin: 0 !important;
    color: #303548 !important;
    font-size: 15px !important;
    font-weight: 800 !important;
}

.questionnaire-part5-answer-member p {
    margin: 5px 0 0 !important;
    color: #737c8f !important;
    font-size: 12px !important;
    line-height: 1.5 !important;
}

.questionnaire-part5-answer-count {
    flex-shrink: 0 !important;
    padding: 6px 10px !important;
    border-radius: 20px !important;
    background: #eef0ff !important;
    color: #252a86 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
}

/* Card pertanyaan dan jawaban */
.questionnaire-part5-question {
    min-width: 0 !important;
    padding: 14px !important;
    border: 1px solid #dfe5f1 !important;
    border-radius: 12px !important;
    background: #fff !important;
    box-sizing: border-box !important;
}

.questionnaire-part5-question-number {
    display: inline-block !important;
    margin-bottom: 10px !important;
    padding: 6px 9px !important;
    border-radius: 7px !important;
    background: #eef0f5 !important;
    color: #647087 !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    text-transform: uppercase;
}

.questionnaire-part5-question-text {
    margin-bottom: 12px !important;
    color: #303548 !important;
    font-size: 14px !important;
    font-weight: 650 !important;
    line-height: 1.5 !important;
    overflow-wrap: anywhere !important;
}

.questionnaire-part5-answer-label {
    margin-bottom: 7px !important;
    color: #647087 !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    text-transform: uppercase;
}

.questionnaire-part5-answer-value {
    min-width: 0 !important;
    padding: 11px 12px !important;
    border-left: 3px solid #55b5d5 !important;
    border-radius: 9px !important;
    background: #f2f6f9 !important;
    color: #354154 !important;
    font-size: 13px !important;
    line-height: 1.6 !important;
    overflow-wrap: anywhere !important;
    word-break: break-word !important;
}

.questionnaire-part5-answer-value.is-empty {
    color: #858b99 !important;
    font-style: italic;
}

/* Foto jawaban */
.questionnaire-part5-image-answer {
    display: flex !important;
    flex-direction: column !important;
    gap: 8px !important;
}

.questionnaire-part5-image {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    max-height: 320px !important;
    object-fit: contain !important;
    border-radius: 8px !important;
}

/* Tampilan tablet dan HP */
@media (max-width: 700px) {
    .questionnaire-part5-member-list {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .questionnaire-part5-answer-body {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    .questionnaire-part5-answer-member,
    .questionnaire-part5-answer-empty {
        grid-column: 1 !important;
    }
}

@media (max-width: 450px) {
    .questionnaire-part5-member-list {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    .questionnaire-part5-answer-body {
        padding: 10px !important;
    }
}


@media (max-width: 900px) {
    .laporan-info-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .questionnaire-parts {
        grid-template-columns: 190px minmax(0, 1fr);
        gap: 10px;
    }

    .questionnaire-part5-layout {
        grid-template-columns: minmax(0, 1fr);
        gap: 10px;
    }

    .questionnaire-content-body.is-photo-part {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .laporan-modal {
        width: calc(100vw - 20px);
        flex-basis: calc(100vw - 20px);
        max-height: calc(100vh - 20px);
        border-radius: 12px;
    }

    .laporan-modal-header {
        padding: 15px 16px;
    }

    .laporan-modal-body {
        padding: 15px;
        max-height: calc(100vh - 115px);
    }

    .laporan-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .laporan-detail-data-section {
        padding: 14px;
    }

    .laporan-detail-data-section:has(#laporanQuestionnaireTotal) {
        grid-template-columns: minmax(0, 1fr) auto;
    }

    .questionnaire-parts,
    .questionnaire-content-body.is-photo-part {
        grid-template-columns: minmax(0, 1fr);
    }

    .questionnaire-sidebar {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .questionnaire-sidebar-title {
        grid-column: 1 / -1;
    }

    .questionnaire-part-header {
        min-height: 56px;
        padding: 10px 12px;
    }

    .questionnaire-content-body {
        max-height: 65vh;
        padding: 12px;
    }

    .questionnaire-content-header {
        padding: 13px 14px;
    }

    .questionnaire-content-title {
        font-size: 14px;
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
    .laporan-info-grid,
    .questionnaire-sidebar {
        grid-template-columns: minmax(0, 1fr);
    }

    .questionnaire-sidebar-title {
        grid-column: 1;
    }

    .laporan-summary-box {
        padding: 13px 14px;
    }
}


/* =========================================================
   FINAL VISUAL ALIGNMENT: DETAIL LAPORAN = DETAIL VERIFIKASI
   Override terakhir agar aturan CSS sebelumnya tidak menimpa.
   Tidak mengubah data, ID, event handler, atau logika JavaScript.
========================================================= */
.laporan-modal {
    position: relative;
    width: min(1250px, calc(100vw - 40px));
    max-width: 1250px;
    max-height: calc(100vh - 40px);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
    overflow: hidden;
    box-sizing: border-box;
}

.laporan-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #e8e9ef;
    background: #fff;
    box-sizing: border-box;
}

.laporan-modal-header h2 {
    margin: 0;
    color: #252a86;
    font-size: 19px;
    line-height: 1.35;
    font-weight: 700;
}

.laporan-modal-header p {
    margin: 4px 0 0;
    color: #888;
    font-size: 11px;
    line-height: 1.5;
}

.laporan-modal-close {
    display: flex;
    flex: 0 0 32px;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 0;
    border-radius: 7px;
    background: #f3f4f8;
    color: #666;
    cursor: pointer;
}

.laporan-modal-close:hover {
    background: #252a86;
    color: #fff;
}

.laporan-modal-body {
    padding: 18px 20px 22px;
    max-height: calc(100vh - 125px);
    overflow-y: auto;
    scrollbar-width: thin;
    box-sizing: border-box;
}

/* Tiga kartu ringkasan utama */
.laporan-modal-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    margin: 0 0 16px;
}

.laporan-summary-box {
    min-width: 0;
    padding: 13px 14px;
    border: 1px solid #e7e9f1;
    border-radius: 10px;
    background: #f8f9fc;
    box-sizing: border-box;
}

.laporan-summary-label {
    display: block;
    margin: 0 0 5px;
    color: #7b8192;
    font-size: 10px;
    line-height: 1.4;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.laporan-summary-value {
    display: block;
    margin: 4px 0 0;
    color: #263238;
    font-size: 14px;
    line-height: 1.4;
    font-weight: 700;
    overflow-wrap: anywhere;
}

/* Badge status ringkasan dan status pada informasi responden */
.laporan-summary-value.laporan-detail-status,
.laporan-info-value.laporan-detail-status {
    display: inline-flex;
    align-items: center;
    width: fit-content;
    max-width: 100%;
    padding: 5px 9px;
    border: 1px solid transparent;
    border-radius: 999px;
    font-size: 11px;
    line-height: 1.2;
    font-weight: 700;
    white-space: normal;
    overflow: visible;
    text-overflow: clip;
    box-sizing: border-box;
}

.laporan-detail-status.status-approved {
    color: #24723c;
    background: #eaf8ef;
    border-color: #bce5c9;
}

.laporan-detail-status.status-rejected {
    color: #a53636;
    background: #fdecec;
    border-color: #f2c4c4;
}

.laporan-detail-status.status-pending {
    color: #9a6a00;
    background: #fff7df;
    border-color: #f0dfa5;
}

.laporan-detail-status.status-draft {
    color: #666b75;
    background: #f1f2f5;
    border-color: #dfe1e7;
}

/* Panel informasi dan kartu-kartu responden */
.laporan-info-section,
.laporan-detail-data-section {
    min-width: 0;
    margin: 0 0 16px;
    padding: 16px;
    border: 1px solid #e7e9f1;
    border-radius: 12px;
    background: #fff;
    box-sizing: border-box;
}

.laporan-info-title,
.laporan-detail-data-heading {
    margin: 0 0 13px;
    color: #252a86;
    font-size: 15px;
    line-height: 1.4;
    font-weight: 700;
}

.laporan-info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 9px;
    min-width: 0;
}

.laporan-info-item {
    min-width: 0;
    padding: 11px 12px;
    border: 1px solid #edf0f5;
    border-radius: 9px;
    background: #fafbfc;
    box-sizing: border-box;
}

.laporan-info-label {
    display: block;
    margin: 0 0 5px;
    color: #6b7280;
    font-size: 10px;
    line-height: 1.4;
    font-weight: 600;
    text-transform: none;
    letter-spacing: 0;
}

.laporan-info-value {
    display: block;
    min-width: 0;
    margin: 0;
    color: #263238;
    font-size: 13px;
    line-height: 1.4;
    font-weight: 700;
    overflow-wrap: anywhere;
    overflow: visible;
    text-overflow: clip;
}

/* Kuesioner Part 1–6: seragamkan panel, navigasi part, pertanyaan, dan jawaban */
.laporan-questionnaire-layout {
    display: grid;
    grid-template-columns: 250px minmax(0, 1fr);
    gap: 16px;
    width: 100%;
    min-width: 0;
    align-items: stretch;
}

.laporan-question-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.laporan-question-part-btn {
    width: 100%;
    min-height: 44px;
    padding: 10px 12px;
    border: 1px solid #e3e6ef;
    border-radius: 9px;
    background: #f8f9fc;
    color: #475569;
    font-size: 12px;
    line-height: 1.45;
    font-weight: 600;
    text-align: left;
    box-sizing: border-box;
}

.laporan-question-part-btn.active {
    border-color: #252a86;
    background: #252a86;
    color: #fff;
}

.laporan-question-answer {
    min-width: 0;
    padding: 16px;
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    background: #fff;
    box-sizing: border-box;
}

.laporan-question-answer-title {
    margin: 0 0 13px;
    padding: 0 0 10px;
    border-bottom: 1px solid #e8ebf3;
    color: #252a86;
    font-size: 15px;
    line-height: 1.5;
    font-weight: 700;
}

.laporan-question-answer-body {
    width: 100%;
    min-width: 0;
    color: #263238;
    font-size: 12px;
    line-height: 1.5;
}

.laporan-question-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.laporan-question-table th {
    padding: 11px 13px;
    border-bottom: 1px solid #e2e6f0;
    background: #f1f3ff;
    color: #4b5563;
    font-size: 11px;
    line-height: 1.45;
    font-weight: 700;
    text-align: left;
}

.laporan-question-table td {
    padding: 11px 13px;
    border-bottom: 1px solid #edf0f5;
    color: #263238;
    font-size: 12px;
    line-height: 1.5;
    vertical-align: top;
}

.laporan-questionnaire-description {
    margin: -7px 0 13px;
    color: #7d8392;
    font-size: 11px;
    line-height: 1.5;
}

.laporan-members-table-wrap {
    border: 1px solid #e3e6ef;
    border-radius: 9px;
    overflow-x: auto;
}

.laporan-members-table th {
    padding: 11px 13px;
    background: #f1f3ff;
    font-size: 11px;
}

.laporan-members-table td {
    padding: 11px 13px;
    font-size: 12px;
}

@media (max-width: 900px) {
    .laporan-questionnaire-layout {
        grid-template-columns: 200px minmax(0, 1fr);
        gap: 12px;
    }
    .laporan-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .laporan-modal {
        width: calc(100vw - 20px);
        max-height: calc(100vh - 20px);
        border-radius: 12px;
    }
    .laporan-modal-header {
        padding: 15px 16px;
    }
    .laporan-modal-header h2 {
        font-size: 18px;
    }
    .laporan-modal-body {
        max-height: calc(100vh - 105px);
        padding: 15px 16px 16px;
    }
    .laporan-modal-summary {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .laporan-info-section,
    .laporan-detail-data-section {
        padding: 12px;
        margin-bottom: 10px;
    }
    .laporan-info-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .laporan-questionnaire-layout {
        grid-template-columns: minmax(0, 1fr);
        gap: 10px;
    }
    .laporan-question-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .laporan-question-answer {
        padding: 11px;
    }
}

/* Header Anggota Kartu Keluarga - mengikuti Verifikasi */
.laporan-members-section {
    padding: 18px;
    border: 1px solid #e2e6f0;
    border-radius: 14px;
    background: #fff;
}

.laporan-members-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 16px;
    padding: 18px 20px;
    border: 1px solid #e0e5f1;
    border-radius: 13px;
    background: #f7f8fc;
}

.laporan-members-header h3 {
    margin: 0;
    color: #252a86;
    font-size: 19px;
    font-weight: 700;
    line-height: 1.4;
}

.laporan-head-family {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 3px;
    padding: 9px 13px;
    border: 1px solid #dfe4f4;
    border-radius: 10px;
    background: #f7f8ff;
    text-align: right;
}

.laporan-head-family span {
    color: #858b99;
    font-size: 13px;
    font-weight: 600;
}

.laporan-head-family strong {
    color: #252a86;
    font-size: 16px;
    font-weight: 700;
}

.laporan-members-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e0e5f1;
    border-radius: 12px;
}

.laporan-members-table {
    width: 100%;
    min-width: 500px;
    border-collapse: collapse;
    background: #fff;
}

.laporan-members-table th {
    padding: 12px 14px;
    background: #f1f3f9;
    color: #4b5563;
    font-size: 14px;
    font-weight: 700;
    text-align: left;
    border-bottom: 1px solid #e0e5f1;
}

.laporan-members-table td {
    padding: 13px 14px;
    color: #475569;
    font-size: 13px;
    border-bottom: 1px solid #e9edf5;
}

.laporan-members-table tbody tr:last-child td {
    border-bottom: none;
}

.laporan-members-table tbody tr:hover {
    background: #fafbff;
}

@media (max-width: 600px) {
    .laporan-members-header {
        align-items: stretch;
        flex-direction: column;
        padding: 15px;
    }

    .laporan-members-header h3 {
        font-size: 17px;
    }

    .laporan-head-family {
        align-items: flex-start;
        text-align: left;
    }
}

/* ==================================================
   HASIL KUISIONER LAPORAN: CARD 2 KOLOM
   Digunakan untuk Part 1 sampai Part 4
================================================== */

/* Susun card pertanyaan kiri-kanan */
#modalQuestionnaireContent .questionnaire-content-body {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    align-items: start;
}

/* Setiap pertanyaan menjadi card */
#modalQuestionnaireContent .questionnaire-question {
    width: 100%;
    min-width: 0;
    margin: 0;
    align-self: start;
    box-sizing: border-box;
}

/* Jawaban boleh memanjang, tetapi tidak merusak kolom */
#modalQuestionnaireContent .questionnaire-answer {
    min-width: 0;
    overflow-wrap: anywhere;
    word-break: break-word;
}

/* Foto dan pertanyaan lokasi memakai lebar penuh */
#modalQuestionnaireContent .questionnaire-question.questionnaire-photo-question,
#modalQuestionnaireContent .questionnaire-question.questionnaire-location-question {
    grid-column: 1 / -1;
}

#modalQuestionnaireContent .laporan-part1-map-section {
    grid-column: 1 / -1;
    width: 100%;
    min-width: 0;
}

/* Pesan kosong juga memenuhi area */
#modalQuestionnaireContent .questionnaire-content-body > .questionnaire-empty {
    grid-column: 1 / -1;
}

/* Layar kecil: kembali menjadi satu kolom */
@media (max-width: 700px) {
    #modalQuestionnaireContent .questionnaire-content-body {
        grid-template-columns: 1fr;
    }

    #modalQuestionnaireContent .questionnaire-question.questionnaire-photo-question,
    #modalQuestionnaireContent .questionnaire-question.questionnaire-location-question {
        grid-column: auto;
    }
}


/* FIX FINAL PART 5 LAPORAN */
#modalQuestionnaireContent .questionnaire-part5-layout {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    gap: 14px !important;
    box-sizing: border-box !important;
}

#modalQuestionnaireContent .questionnaire-part5-members,
#modalQuestionnaireContent .questionnaire-part5-answer {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
}

/* Panel daftar anggota */
#modalQuestionnaireContent .questionnaire-part5-member-list {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    align-items: stretch !important;
    gap: 10px !important;
    width: 100% !important;
    max-height: none !important;
    overflow: visible !important;
    padding: 12px !important;
    box-sizing: border-box !important;
}

/* Ukuran card anggota */
#modalQuestionnaireContent .questionnaire-part5-member-card {
    display: block !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    min-height: 96px !important;
    margin: 0 !important;
    padding: 12px !important;
    border-radius: 12px !important;
    box-sizing: border-box !important;
}

/* Nama anggota jangan terpotong */
#modalQuestionnaireContent .questionnaire-part5-member-name {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: clip !important;
    overflow-wrap: anywhere !important;
    font-size: 13px !important;
    line-height: 1.4 !important;
}

/* Panel jawaban memenuhi lebar yang sama */
#modalQuestionnaireContent .questionnaire-part5-answer-body {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    align-items: start !important;
    gap: 12px !important;
    width: 100% !important;
    max-width: 100% !important;
    max-height: none !important;
    overflow: visible !important;
    padding: 14px !important;
    box-sizing: border-box !important;
}

/* Informasi anggota terpilih memenuhi satu baris */
#modalQuestionnaireContent .questionnaire-part5-answer-member,
#modalQuestionnaireContent .questionnaire-part5-answer-empty {
    grid-column: 1 / -1 !important;
}

/* Kartu pertanyaan dan jawaban */
#modalQuestionnaireContent .questionnaire-part5-question {
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
    box-sizing: border-box !important;
}

/* Tampilan tablet dan HP */
@media (max-width: 700px) {
    #modalQuestionnaireContent .questionnaire-part5-member-list {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    #modalQuestionnaireContent .questionnaire-part5-answer-body {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    #modalQuestionnaireContent .questionnaire-part5-answer-member,
    #modalQuestionnaireContent .questionnaire-part5-answer-empty {
        grid-column: 1 !important;
    }
}

@media (max-width: 450px) {
    #modalQuestionnaireContent .questionnaire-part5-member-list {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}

/* FIX UTAMA: PART 5 HARUS MEMAKAI SELURUH KOLOM KONTEN */
#modalQuestionnaireContent .questionnaire-content-body > .questionnaire-part5-layout {
    grid-column: 1 / -1 !important;
    justify-self: stretch !important;
    align-self: stretch !important;

    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;

    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;

    margin: 0 !important;
    gap: 14px !important;
    box-sizing: border-box !important;
}

/* Panel anggota dan jawaban sama-sama selebar konten */
#modalQuestionnaireContent .questionnaire-part5-layout > .questionnaire-part5-members,
#modalQuestionnaireContent .questionnaire-part5-layout > .questionnaire-part5-answer {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;
    box-sizing: border-box !important;
}

/* Tiga card anggota membagi lebar secara merata */
#modalQuestionnaireContent .questionnaire-part5-layout .questionnaire-part5-member-list {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    width: 100% !important;
    max-width: 100% !important;
    max-height: none !important;
    overflow: visible !important;
    gap: 10px !important;
    padding: 12px !important;
    box-sizing: border-box !important;
}

/* Jawaban menggunakan seluruh lebar panel */
#modalQuestionnaireContent .questionnaire-part5-layout .questionnaire-part5-answer-body {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    width: 100% !important;
    max-width: 100% !important;
    max-height: none !important;
    overflow: visible !important;
    gap: 12px !important;
    padding: 14px !important;
    box-sizing: border-box !important;
}

/* Identitas anggota terpilih memenuhi satu baris */
#modalQuestionnaireContent .questionnaire-part5-answer-member,
#modalQuestionnaireContent .questionnaire-part5-answer-empty {
    grid-column: 1 / -1 !important;
}

@media (max-width: 700px) {
    #modalQuestionnaireContent .questionnaire-part5-layout .questionnaire-part5-member-list {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    #modalQuestionnaireContent .questionnaire-part5-layout .questionnaire-part5-answer-body {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}

@media (max-width: 450px) {
    #modalQuestionnaireContent .questionnaire-part5-layout .questionnaire-part5-member-list {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}


/* ==================================================
   PERBAIKAN PART 6 FOTO RUMAH - HALAMAN LAPORAN
   3 CARD SEJAJAR SEPERTI HALAMAN VERIFIKASI
================================================== */

/* Paksa Part 6 menggunakan 3 kolom */
#modalQuestionnaireContent .questionnaire-content-body.is-photo-part {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    align-items: stretch !important;
    gap: 14px !important;
}

/* Card foto tidak boleh memenuhi satu baris */
#modalQuestionnaireContent .questionnaire-content-body.is-photo-part
.questionnaire-question.questionnaire-photo-question {
    grid-column: auto !important;
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    min-width: 0 !important;
    height: 100% !important;
    padding: 12px !important;
    border: 1px solid #e2e6f2 !important;
    border-radius: 12px !important;
    background: #fff !important;
    box-sizing: border-box !important;
}

/* Foto memiliki ukuran seragam */
#modalQuestionnaireContent .questionnaire-content-body.is-photo-part
.questionnaire-answer-image img {
    display: block !important;
    width: 100% !important;
    height: auto !important;
    aspect-ratio: 1 / 1 !important;
    max-height: none !important;
    object-fit: cover !important;
    border-radius: 9px !important;
}

/* Area foto mengikuti lebar card */
#modalQuestionnaireContent .questionnaire-content-body.is-photo-part
.questionnaire-answer-image {
    display: flex !important;
    flex-direction: column !important;
    flex: 1 1 auto !important;
    min-width: 0 !important;
    width: 100% !important;
}

/* Tablet: 2 card per baris */
@media (max-width: 900px) {
    #modalQuestionnaireContent .questionnaire-content-body.is-photo-part {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

/* HP: 1 card per baris */
@media (max-width: 600px) {
    #modalQuestionnaireContent .questionnaire-content-body.is-photo-part {
        grid-template-columns: minmax(0, 1fr) !important;
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
                    'search' => $filters['search'] ?? '',
                    'kecamatan' => $filters['kecamatan'] ?? '',
                    'kelurahan' => $filters['kelurahan'] ?? '',
                    'status' => $filters['status'] ?? 'all',
                ], fn ($value) => $value !== '' && $value !== 'all')) }}"
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

    {{-- TOTAL RESPONDEN --}}
    <div class="laporan-stat-card">
        <div class="laporan-stat-info">

            <div class="laporan-stat-label">
                Total Responden
            </div>

            <div class="laporan-stat-value">
                {{ $totalResponden ?? 0 }}
            </div>

        </div>
    </div>


    {{-- DISETUJUI --}}
    <div class="laporan-stat-card">
        <div class="laporan-stat-info">

            <div class="laporan-stat-label">
                Disetujui
            </div>

            <div class="laporan-stat-value">
                {{ $disetujui ?? 0 }}
            </div>

        </div>
    </div>


    {{-- DITOLAK --}}
    <div class="laporan-stat-card">
        <div class="laporan-stat-info">

            <div class="laporan-stat-label">
                Ditolak
            </div>

            <div class="laporan-stat-value">
                {{ $ditolak ?? 0 }}
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
                            value="{{ $filters['search'] ?? '' }}"
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

                    <div
                        class="laporan-search-result"
                        id="laporanSearchResult"
                        aria-live="polite"
                        style="display: none;"
                    ></div> 
 
                </div> 
 
                <div class="laporan-filter-group">
                    <label for="filterKecamatan" class="laporan-filter-label">Kecamatan</label>
                    <select name="kecamatan" id="filterKecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($kecamatanList as $kecamatan)
                            <option value="{{ $kecamatan['id'] }}" @selected(($filters['kecamatan'] ?? '') === $kecamatan['id'])>
                                {{ $kecamatan['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="laporan-filter-group">
                    <label for="filterKelurahan" class="laporan-filter-label">Kelurahan</label>
                    <select
                        name="kelurahan"
                        id="filterKelurahan"
                        @disabled(empty($filters['kecamatan']))
                    >
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahanList as $kelurahan)
                            <option value="{{ $kelurahan['id'] }}" @selected(($filters['kelurahan'] ?? '') === $kelurahan['id'])>
                                {{ $kelurahan['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="laporan-filter-group">
                    <label for="filterStatus" class="laporan-filter-label">Status</label>
                    <select name="status" id="filterStatus">
                        <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>Semua Status</option>
                        <option value="not_processed" @selected(($filters['status'] ?? '') === 'not_processed')>Belum Didata</option>
                        <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Menunggu Verifikasi</option>
                        <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Ditolak</option>
                    </select>
                </div>
 
                <div class="laporan-filter-group laporan-filter-reset-group"> 
 
                    <span 
                        class="laporan-filter-label laporan-filter-label-hidden" 
                        aria-hidden="true" 
                    > 
                        Filter 
                    </span> 
 
                    <button type="submit" class="laporan-reset-btn laporan-apply-btn">Terapkan</button>
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
                                'approved', 'disetujui', 'selesai' => 'Disetujui',
                                'pending', 'menunggu', 'diproses', 'waiting_verification' => 'Menunggu Verifikasi',
                                'rejected', 'reject', 'ditolak' => 'Ditolak',
                                default => $statusValue !== '' ? ucfirst($statusValue) : 'Belum Selesai',
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
                                    ['diproses', 'menunggu', 'pending', 'menunggu verifikasi', 'waiting_verification'] 
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

        @if ($laporan->hasPages())
            <div class="laporan-pagination">
                <div class="pagination-info">
                    Menampilkan
                    <strong>{{ $laporan->firstItem() }}</strong>
                    sampai
                    <strong>{{ $laporan->lastItem() }}</strong>
                    dari
                    <strong>{{ $laporan->total() }}</strong>
                    data
                </div>

                <div class="pagination">
                    @if($laporan->onFirstPage())
                        <span class="page-btn disabled">
                            ‹
                        </span>
                    @else
                        <a href="{{ $laporan->previousPageUrl() }}" class="page-btn">
                            ‹
                        </a>
                    @endif

                    @for($page = 1; $page <= $laporan->lastPage(); $page++)
                        @if($page == $laporan->currentPage())
                            <span class="page-btn active">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $laporan->url($page) }}" class="page-btn">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if($laporan->hasMorePages())
                        <a href="{{ $laporan->nextPageUrl() }}" class="page-btn">
                            ›
                        </a>
                    @else
                        <span class="page-btn disabled">
                            ›
                        </span>
                    @endif
                </div>
            </div>
        @endif

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
                    Detail Data Pendataan
                </h2> 
 
                <p>
                    Periksa informasi responden dan jawaban kuisioner.
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
 
                    <span class="laporan-summary-label">
                        Nama Responden
                    </span>
 
                    <strong class="laporan-summary-value" id="modalNamaSummary">-</strong>
 
                </div> 
 
                <div class="laporan-summary-box"> 
 
                    <span class="laporan-summary-label">
                        Nomor KK
                    </span>
 
                    <strong class="laporan-summary-value" id="modalKkSummary">-</strong>
 
                </div> 
 
                <div class="laporan-summary-box"> 
 
                    <span class="laporan-summary-label">
                        Status Pendataan
                    </span>
 
                    <strong class="laporan-summary-value" id="modalStatusSummary">-</strong>
 
                </div> 
 
            </div> 
 
            {{-- Alasan penolakan hanya muncul untuk data berstatus Ditolak --}}
            <section class="laporan-rejection-reason" id="laporanRejectionReasonCard" aria-live="polite">
                <h3 class="laporan-rejection-reason-title">Alasan Data Ditolak ! </h3>
                <p class="laporan-rejection-reason-text" id="laporanRejectionReasonText">-</p>
            </section>

            <section class="laporan-detail-data-section laporan-info-section">
 
                <div class="laporan-detail-data-heading">
                    Informasi Responden
                </div>
 
                <div class="laporan-info-grid"> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">No. KK</span>
                        <strong class="laporan-info-value" id="modalNoKk">-</strong>
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">NIK</span>
                        <strong class="laporan-info-value" id="modalNik">-</strong>
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Nama Kepala Keluarga</span>
                        <strong class="laporan-info-value" id="modalNama">-</strong>
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Jumlah Anggota Keluarga</span>
                        <strong class="laporan-info-value" id="modalJumlahAnggota">-</strong>
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Wilayah</span>
                        <strong class="laporan-info-value" id="modalWilayah">-</strong>
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Petugas</span>
                        <strong class="laporan-info-value" id="modalPetugasLaporan">-</strong>
                    </div>

                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Tanggal Pendataan</span>
                        <strong class="laporan-info-value" id="modalTanggal">-</strong>
                    </div> 
 
                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Status Saat Ini</span>
                        <strong class="laporan-info-value" id="modalStatus">-</strong>
                    </div>

                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Periode Pendataan</span>
                        <strong class="laporan-info-value" id="modalPeriode">-</strong>
                    </div>

                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Kode Periode</span>
                        <strong class="laporan-info-value" id="modalKodePeriodeLaporan">-</strong>
                    </div>

                    <div class="laporan-info-item"> 
                        <span class="laporan-info-label">Tanggal Periode</span>
                        <strong class="laporan-info-value" id="modalTanggalPeriodeLaporan">-</strong>
                    </div>

 
                </div> 
 
            </section>
 
            <section class="laporan-detail-data-section laporan-members-section">

    <div class="laporan-members-header">
        <h3>Anggota Dalam Kartu Keluarga</h3>

        <div class="laporan-head-family">
            <span>Kepala Keluarga</span>
            <strong id="modalFamilyHeadName">-</strong>
        </div>
    </div>

    <div id="modalMembersContent" class="laporan-detail-loading">
        Memuat data anggota keluarga...
    </div>

</section>
 
            <section class="laporan-detail-data-section"> 
 
                <div class="laporan-detail-data-heading">
                    Hasil Kuisioner
                </div> 

                <p class="laporan-questionnaire-description">
                    Pilih bagian kuisioner di sebelah kiri untuk melihat pertanyaan dan jawaban responden.
                </p>

                <div class="questionnaire-total" id="laporanQuestionnaireTotal">
                    0 Part
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
 
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script> 
document.addEventListener('DOMContentLoaded', function () {

    // Menghapus tanda bintang hanya pada label Part 1 di hasil kuesioner.
    // Form pengisian kuesioner tidak menggunakan helper ini.
    function cleanPart1ResultLabel(value) {
        const label = String(value ?? '').trim();
        const allowedLabels = [
            'NIK',
            'Nama Kepala Keluarga',
            'Status dalam Keluarga',
            'Nomor KK',
            'Jumlah Anggota Keluarga',
            'Provinsi',
            'Kabupaten / Kota',
            'Kecamatan',
            'Kelurahan',
            'Kode Pos',
            'RT / RW',
            'Alamat Lengkap'
        ];

        const withoutAsterisk = label.replace(/\s*\*\s*$/, '');
        return allowedLabels.includes(withoutAsterisk) ? withoutAsterisk : label;
    }

 
 
    /* ===================================================== 
       ELEMENT FILTER 
    ===================================================== */ 
 
    const laporanFilterForm = document.getElementById('laporanFilterForm'); 
    const searchInput = document.getElementById('laporanSearch'); 
    const kecamatanFilter = document.getElementById('filterKecamatan');
    const kelurahanFilter = document.getElementById('filterKelurahan');
    const statusFilter = document.getElementById('filterStatus');
    const tableBody = document.getElementById('laporanTableBody'); 
 
    /* =====================================================
       REALTIME SEARCH - MENYARING TABEL LANGSUNG
    ===================================================== */

    const searchResult = document.getElementById('laporanSearchResult');

    function filterLaporanTable() {
        if (!tableBody) return 0;

        const rows = Array.from(tableBody.querySelectorAll('tr[data-search]'));
        const originalKeyword = searchInput ? searchInput.value.trim() : '';
        const searchValue = originalKeyword.toLowerCase();
        let visibleCount = 0;

        rows.forEach(function (row) {
            const searchData = (row.dataset.search || '').toLowerCase();
            const matches = searchValue === '' || searchData.includes(searchValue);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        let emptyRow = document.getElementById('laporanRealtimeEmpty');
        if (searchValue !== '' && visibleCount === 0 && rows.length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.id = 'laporanRealtimeEmpty';
                emptyRow.innerHTML = `
                    <td colspan="10">
                        <div class="laporan-empty">
                            <div class="laporan-empty-icon">
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                                </svg>
                            </div>
                            <div class="laporan-empty-title">Data Tidak Ditemukan</div>
                            <div class="laporan-empty-text">Tidak ada data yang sesuai dengan pencarian.</div>
                        </div>
                    </td>
                `;
                tableBody.appendChild(emptyRow);
            }
            emptyRow.style.display = '';
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }

        if (searchResult) {
            if (searchValue === '') {
                searchResult.style.display = 'none';
                searchResult.replaceChildren();
            } else {
                searchResult.style.display = 'block';
                const count = document.createElement('strong');
                count.textContent = String(visibleCount);
                const keyword = document.createElement('strong');
                keyword.textContent = '"' + originalKeyword + '"';
                searchResult.replaceChildren(
                    document.createTextNode('Menampilkan '),
                    count,
                    document.createTextNode(' data yang cocok dengan '),
                    keyword
                );
            }
        }

        return visibleCount;
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
 
        if (kecamatanFilter && kecamatanFilter.value.trim() !== '') {
            params.set('kecamatan', kecamatanFilter.value.trim());
        } 

        if (kelurahanFilter && kelurahanFilter.value.trim() !== '') {
            params.set('kelurahan', kelurahanFilter.value.trim());
        }

        if (statusFilter && statusFilter.value !== '' && statusFilter.value !== 'all') {
            params.set('status', statusFilter.value);
        } 
 
        const query = params.toString(); 
 
        exportLink.href = 
            exportLink.pathname + 
            (query !== '' ? '?' + query : ''); 
    } 
 
    function submitReportFilters() {
        if (laporanFilterForm) {
            laporanFilterForm.requestSubmit();
        }
    }

    /* =====================================================
       PENCARIAN LANGSUNG TANPA RELOAD
    ===================================================== */

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            filterLaporanTable();
            syncExportLink();
        });

        // Tampilkan hasil sesuai kata kunci awal jika halaman dibuka dengan filter.
        filterLaporanTable();
    }

    if (kecamatanFilter) {
        kecamatanFilter.addEventListener('change', function () {
            if (kelurahanFilter) {
                kelurahanFilter.value = '';
            }
            syncExportLink();
            submitReportFilters();
        });
    }

    [kelurahanFilter, statusFilter].forEach(function (filter) {
        if (filter) {
            filter.addEventListener('change', function () {
                syncExportLink();
                submitReportFilters();
            });
        }
    });
 
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

                filterLaporanTable();
 
                if (kecamatanFilter) {
                    kecamatanFilter.value = '';
                } 

                if (kelurahanFilter) {
                    kelurahanFilter.value = '';
                } 

                if (statusFilter) {
                    statusFilter.value = 'all';
                }

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
 
    
    const laporanRejectionReasonCard = document.getElementById('laporanRejectionReasonCard');
    const laporanRejectionReasonText = document.getElementById('laporanRejectionReasonText');
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

    const modalPetugasLaporan = document.getElementById('modalPetugasLaporan');
    const modalKodePeriodeLaporan = document.getElementById('modalKodePeriodeLaporan');
    const modalTanggalPeriodeLaporan = document.getElementById('modalTanggalPeriodeLaporan');
 
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
        const familyHeadName = document.getElementById('modalFamilyHeadName');
const modalHeadName = document.getElementById('modalNama');

if (familyHeadName && modalHeadName) {
    familyHeadName.textContent = modalHeadName.textContent.trim() || '-';
}
 
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

    function formatRupiahPart3(value) {
    const original = String(value ?? '').trim();

    if (!original) return original;

    const angka = original
        .replace(/^rp\.?\s*/i, '')
        .replace(/\s/g, '')
        .replace(/\./g, '')
        .replace(/,/g, '');

    if (!/^\d+$/.test(angka)) return original;

    return 'Rp.' + new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0
    }).format(Number(angka));
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
                    <div class="questionnaire-empty">
                        <strong>Belum Ada Pertanyaan</strong>
                        <span>Pertanyaan untuk bagian ini belum tersedia.</span>
                    </div>
                `;
            }

            return `
                ${questions.map(function (question, index) {
                    const label = cleanPart1ResultLabel(
                        question.text ||
                        question.question ||
                        question.pertanyaan ||
                        `Pertanyaan ${index + 1}`
                    );
                    let answer = reportAnswerText(question);

                    const partNumber = String(part.number || part.part || '')
                        .replace(/part/ig, '')
                        .trim();

                    if (partNumber === '3') {
                        answer = formatRupiahPart3(answer);
}
                    const type = String(question.type || '').toLowerCase();
                    const imageUrl = String(question.imageUrl || '').trim();
                    const safeImageUrl =
                        /^(https?:\/\/|\/storage\/|storage\/|\/uploads\/|uploads\/)/i.test(imageUrl);
                    const isImage = type === 'image' && safeImageUrl;
                    const isLocation = type === 'map';

                    return `
                        <div class="questionnaire-question${isImage ? ' questionnaire-photo-question' : ''}${isLocation ? ' questionnaire-location-question' : ''}">
                            <div class="questionnaire-question-number">${isLocation ? 'LOKASI RUMAH' : `PERTANYAAN ${escapeHtml(question.number || index + 1)}`}</div>
                            <div class="questionnaire-question-text">${escapeHtml(label)}</div>
                            <div class="questionnaire-answer-label">${isLocation ? 'Koordinat dan peta' : 'Jawaban Responden'}</div>
                            ${isImage
                                ? `
                                    <div class="questionnaire-answer questionnaire-answer-image">
                                        <img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(question.imageName || label)}" loading="lazy" onerror="this.closest('.questionnaire-answer').textContent='Gambar tidak dapat ditampilkan.'">
                                        <a class="questionnaire-image-link" href="${escapeHtml(imageUrl)}" target="_blank" rel="noopener noreferrer">Buka gambar</a>
                                    </div>
                                `
                                : `<div class="questionnaire-answer${answer.trim() ? '' : ' is-empty'}">${escapeHtml(answer.trim() ? answer : 'Belum diisi')}</div>`
                            }
                        </div>
                    `;
                }).join('')}
                ${
                    String(part.number || part.part || '').trim() === '1' ||
                    String(part.number || part.part || '').trim().toLowerCase() === 'part 1'
                        ? renderPart1GeotagMap()
                        : ''
                }
            `;
        } 

        function renderPart5(part, contentBody) {
            const members = Array.isArray(part.members) ? part.members : [];
            contentBody.classList.remove('is-photo-part', 'is-map-part');
            contentBody.innerHTML = `

                <div class="questionnaire-part5-layout">
                    <section class="questionnaire-part5-members">
                        <div class="questionnaire-part5-panel-header">
                            <strong>Anggota Keluarga</strong>
                            <span>Pilih anggota untuk melihat hasil jawaban di sebelah kanan.</span>
                        </div>
                        <div class="questionnaire-part5-member-list"></div>
                    </section>
                    <section class="questionnaire-part5-answer">
                        <div class="questionnaire-part5-panel-header">
                            <strong>Hasil Jawaban Anggota</strong>
                            <span>Detail jawaban tampil di halaman yang sama.</span>
                        </div>
                        <div class="questionnaire-part5-answer-body">
                            <div class="questionnaire-part5-answer-empty">
                                <strong>Pilih Anggota</strong>
                                <span>Klik salah satu anggota di sebelah kiri untuk menampilkan seluruh jawaban.</span>
                            </div>
                        </div>
                    </section>
                </div>
            `;

            const memberList = contentBody.querySelector('.questionnaire-part5-member-list');
            const answerBody = contentBody.querySelector('.questionnaire-part5-answer-body');

            if (!members.length) {
                memberList.innerHTML = `
                    <div class="questionnaire-part5-answer-empty">
                        <strong>Belum Ada Anggota Keluarga</strong>
                        <span>Data jawaban anggota keluarga belum tersedia.</span>
                    </div>
                `;
                return;
            }

            members.forEach(function (member) {
                const name = String(member.nama || member.nama_lengkap || '-');
                const relation = String(member.status_keluarga || member.status || '-');
                const nik = String(member.nik || '-');
                const initial = name.trim() ? name.trim().charAt(0).toUpperCase() : '?';
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'questionnaire-part5-member-card';
                button.innerHTML = `
                    <span class="questionnaire-part5-member-top">
                        <span class="questionnaire-part5-member-avatar">${escapeHtml(initial)}</span>
                        <span class="questionnaire-part5-member-main">
                            <span class="questionnaire-part5-member-name">${escapeHtml(name)}</span>
                            <span class="questionnaire-part5-member-relation">${escapeHtml(relation)}</span>
                        </span>
                    </span>
                    <span class="questionnaire-part5-member-nik">NIK: ${escapeHtml(nik)}</span>
                `;

                button.addEventListener('click', function () {
                    memberList.querySelectorAll('.questionnaire-part5-member-card').forEach(function (other) {
                        other.classList.remove('is-selected');
                    });
                    button.classList.add('is-selected');

                   
const questions = Array.isArray(member.questions)
    ? member.questions
    : [];

const usableQuestions = questions
    .map(function (question, index) {
        const item = question && typeof question === 'object'
            ? question
            : {};

        return {
            number: item.number ?? index + 1,

            text: String(
                item.text ??
                item.question ??
                item.pertanyaan ??
                item.label ??
                `Pertanyaan ${index + 1}`
            ).trim(),

            answer: String(
                item.answer ??
                item.jawaban ??
                item.value ??
                ''
            ).trim(),

            type: String(
                item.type ?? 'text'
            ).toLowerCase(),

            imageUrl: String(
                item.imageUrl ??
                item.image_url ??
                ''
            ).trim(),

            imageName: String(
                item.imageName ??
                item.image_name ??
                ''
            ).trim()
        };
    })
    .filter(function (question) {
        return question.text.toLowerCase() !== 'data part 5';
    });


                    answerBody.innerHTML = `
                        <div class="questionnaire-part5-answer-member">
                            <div>
                                <h4>${escapeHtml(name)}</h4>
                                <p>NIK: ${escapeHtml(nik)} &nbsp;•&nbsp; ${escapeHtml(relation)}</p>
                            </div>
                            <span class="questionnaire-part5-answer-count">${usableQuestions.length} jawaban</span>
                        </div>
                    `;

                    if (!usableQuestions.length) {
                        answerBody.insertAdjacentHTML('beforeend', `
                            <div class="questionnaire-part5-answer-empty">
                                <strong>Jawaban Belum Tersedia</strong>
                                <span>Belum ada pertanyaan atau jawaban yang tersimpan untuk anggota ini.</span>
                            </div>
                        `);
                        return;
                    }

                    usableQuestions.forEach(function (question, index) {
                        const answer = question.answer.trim();
                        const imageUrl = String(question.imageUrl || '').trim();
                        const safeImageUrl =
                            /^(https?:\/\/|\/storage\/|storage\/|\/uploads\/|uploads\/)/i.test(imageUrl);
                        const isImage = question.type === 'image' && safeImageUrl;
                        answerBody.insertAdjacentHTML('beforeend', `
                            <div class="questionnaire-part5-question">
                                <div class="questionnaire-part5-question-number">Pertanyaan ${index + 1}</div>
                                <div class="questionnaire-part5-question-text">${escapeHtml(question.text || `Pertanyaan ${index + 1}`)}</div>
                                <div class="questionnaire-part5-answer-label">Jawaban</div>
                                ${isImage
                                    ? `
                                        <div class="questionnaire-part5-answer-value questionnaire-part5-image-answer">
                                            <img class="questionnaire-part5-image" src="${escapeHtml(imageUrl)}" alt="${escapeHtml(question.imageName || question.text)}" loading="lazy">
                                            ${question.imageName ? `<div class="questionnaire-image-caption">${escapeHtml(question.imageName)}</div>` : ''}
                                            <a class="questionnaire-part5-image-link" href="${escapeHtml(imageUrl)}" target="_blank" rel="noopener noreferrer">Buka gambar</a>
                                        </div>
                                    `
                                    : `<div class="questionnaire-part5-answer-value${answer ? '' : ' is-empty'}">${escapeHtml(answer || 'Belum diisi')}</div>`
                                }
                            </div>
                        `);
                    });
                });

                memberList.appendChild(button);
            });
        }
 
        /* =====================================================
           PETA GEOTAGGING KHUSUS PART 1
           Peta ditampilkan di dalam Hasil Kuisioner Part 1.
        ===================================================== */

        function initLaporanPart1Maps(container) {
            if (typeof L === 'undefined') {
                console.error('Leaflet belum dimuat.');
                return;
            }

            container.querySelectorAll('.laporan-part1-map-container').forEach(function (element) {
                if (element.dataset.mapInitialized === 'true') {
                    return;
                }

                const lat = Number(element.dataset.lat);
                const lng = Number(element.dataset.lng);

                if (!Number.isFinite(lat) || !Number.isFinite(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                    element.textContent = 'Koordinat lokasi tidak valid.';
                    return;
                }

                const map = L.map(element, { scrollWheelZoom: false }).setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);

                L.marker([lat, lng]).addTo(map).bindPopup('Titik Lokasi Responden');
                element.dataset.mapInitialized = 'true';

                requestAnimationFrame(function () {
                    map.invalidateSize();
                });
            });
        }

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

           
const mapId = 'laporan-part1-map-' +
    Date.now() + '-' +
    Math.random().toString(36).slice(2, 8);

const mapUrl =
    'https://www.openstreetmap.org/?mlat=' +
    encodeURIComponent(lat) +
    '&mlon=' +
    encodeURIComponent(lng) +
    '#map=17/' + lat + '/' + lng;

return `
    <div class="laporan-part1-map-section">
        <div class="laporan-part1-map-title">
            Lokasi Rumah / Geotagging
        </div>

        <div class="laporan-part1-map-card">
            <div class="laporan-part1-coordinate">
                Koordinat lokasi:
                <strong>${escapeHtml(lat)}</strong>,
                <strong>${escapeHtml(lng)}</strong>
            </div>

            <div
                class="laporan-part1-map-container"
                id="${mapId}"
                data-lat="${escapeHtml(lat)}"
                data-lng="${escapeHtml(lng)}"
            ></div>

            <a
                class="laporan-part1-map-link"
                href="${mapUrl}"
                target="_blank"
                rel="noopener noreferrer"
            >
                Buka lokasi di OpenStreetMap
            </a>
        </div>
    </div>
`;

        }

        /* =====================================================
           HTML PART KIRI + JAWABAN KANAN
        ===================================================== */ 
 
        const questionnaireTotal = document.getElementById('laporanQuestionnaireTotal');
        if (questionnaireTotal) {
            questionnaireTotal.textContent = `${parts.length} Part`;
        }

        modalQuestionnaireContent.innerHTML = `
            <div class="questionnaire-parts">
                <div class="questionnaire-sidebar">
                    <div class="questionnaire-sidebar-title">Bagian Kuisioner</div>
                    ${parts.map(function (part, index) {
                        const questionCount = Array.isArray(part.questions)
                            ? part.questions.filter(function (question) {
                                return String(question.type || '').toLowerCase() !== 'map';
                            }).length
                            : 0;
                        const countText = Array.isArray(part.members)
                            ? `${part.members.length} anggota`
                            : `${questionCount} pertanyaan`;

                        return `
                            <div class="questionnaire-part${index === 0 ? ' is-active' : ''}">
                                <button type="button" class="questionnaire-part-header" aria-expanded="${index === 0 ? 'true' : 'false'}">
                                    <span class="questionnaire-part-number">${escapeHtml(part.number || index + 1)}</span>
                                    <span class="questionnaire-part-title">
                                        <strong>${escapeHtml(part.title || `Part ${part.number || index + 1}`)}</strong>
                                        <span>${escapeHtml(countText)}</span>
                                    </span>
                                    <span class="questionnaire-part-chevron">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </button>
                                <div class="questionnaire-part-body"></div>
                            </div>
                        `;
                    }).join('')}
                </div>
                <div class="questionnaire-content-panel">
                    <div class="questionnaire-content-header">
                        <div class="questionnaire-content-heading">
                            <span class="questionnaire-content-kicker">Bagian Terpilih</span>
                            <h4 class="questionnaire-content-title">Pilih bagian kuisioner</h4>
                        </div>
                        <span class="questionnaire-content-count">0 Pertanyaan</span>
                    </div>
                    <div class="questionnaire-content-body">
                        <div class="questionnaire-empty">
                            <strong>Pilih Bagian Kuisioner</strong>
                            <span>Pilih salah satu bagian di sebelah kiri untuk melihat pertanyaan dan jawaban responden.</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const sidebar = modalQuestionnaireContent.querySelector('.questionnaire-sidebar');
        const contentTitle = modalQuestionnaireContent.querySelector('.questionnaire-content-title');
        const contentCount = modalQuestionnaireContent.querySelector('.questionnaire-content-count');
        const contentBody = modalQuestionnaireContent.querySelector('.questionnaire-content-body');

        modalQuestionnaireContent.querySelectorAll('.questionnaire-part-header').forEach(function (button, index) {
            button.addEventListener('click', function () {
                const partWrapper = button.closest('.questionnaire-part');
                const selectedPart = parts[index];

                if (partWrapper.classList.contains('is-active')) {
                    partWrapper.classList.remove('is-active');
                    button.setAttribute('aria-expanded', 'false');
                    contentTitle.textContent = 'Pilih bagian kuisioner';
                    contentCount.textContent = '0 Pertanyaan';
                    contentBody.classList.remove('is-photo-part', 'is-map-part');
                    contentBody.innerHTML = `
                        <div class="questionnaire-empty">
                            <strong>Belum Ada Bagian Dipilih</strong>
                            <span>Pilih salah satu bagian kuisioner di sebelah kiri untuk melihat pertanyaan dan jawaban responden.</span>
                        </div>
                    `;
                    return;
                }

                sidebar.querySelectorAll('.questionnaire-part').forEach(function (partElement) {
                    partElement.classList.remove('is-active');
                    partElement.querySelector('.questionnaire-part-header').setAttribute('aria-expanded', 'false');
                });

                partWrapper.classList.add('is-active');
                button.setAttribute('aria-expanded', 'true');
                contentTitle.textContent = `Part ${selectedPart.number || index + 1} — ${selectedPart.title || ''}`.trim();

                if (Array.isArray(selectedPart.members)) {
                    contentCount.textContent = `${selectedPart.members.length} anggota keluarga`;
                    renderPart5(selectedPart, contentBody);
                    return;
                }

                const questions = Array.isArray(selectedPart.questions) ? selectedPart.questions : [];
                const photoPart = questions.some(function (question) {
                    return question.type === 'image' && Boolean(String(question.imageUrl || '').trim());
                });
                const mapPart = questions.some(function (question) {
                    return question.type === 'map';
                });
                const questionCount = questions.filter(function (question) {
                    return question.type !== 'map';
                }).length;

                contentCount.textContent = `${questionCount} Pertanyaan`;
                contentBody.classList.toggle('is-photo-part', photoPart);
                contentBody.classList.toggle('is-map-part', mapPart);
                contentBody.innerHTML = renderQuestions(selectedPart);

                // Inisialisasi Leaflet setelah HTML Part 1 masuk ke halaman.
                requestAnimationFrame(function () {
                    initLaporanPart1Maps(contentBody);
                });
            });
        });

        modalQuestionnaireContent.querySelector('.questionnaire-part-header')?.click();
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

        if (laporanRejectionReasonCard && laporanRejectionReasonText) {
            laporanRejectionReasonText.textContent = '';
            laporanRejectionReasonCard.classList.remove('show');
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

        if (modalPetugasLaporan) {
            modalPetugasLaporan.textContent = data.petugas || '-';
        }
        if (modalKodePeriodeLaporan) {
            modalKodePeriodeLaporan.textContent = data.kodePeriode || data.periodeKode || '-';
        }
        if (modalTanggalPeriodeLaporan) {
            modalTanggalPeriodeLaporan.textContent = data.tanggalPeriode || data.periodeTanggal || '-';
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

            if (modalPetugasLaporan) {
                modalPetugasLaporan.textContent = item.petugas || item.nama_petugas || item.user_name || item.created_by_name || '-';
            }
            if (modalKodePeriodeLaporan) {
                modalKodePeriodeLaporan.textContent = item.kode_periode || item.periode_kode || item.kodePeriode || '-';
            }
            if (modalTanggalPeriodeLaporan) {
                const mulai = item.tanggal_mulai_periode || item.periode_mulai || item.tanggal_mulai;
                const selesai = item.tanggal_selesai_periode || item.periode_selesai || item.tanggal_selesai;
                modalTanggalPeriodeLaporan.textContent = item.tanggal_periode || item.periode_tanggal || item.tanggalPeriode || (mulai && selesai ? `${mulai} - ${selesai}` : '-');
            }
 
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

            // Gunakan alasan yang dikirim dari data Part 1 di database.
            const normalizedDetailStatus = String(
                item.status || item.status_label || ''
            ).trim().toLowerCase();
            const detailIsRejected = ['rejected', 'reject', 'ditolak'].includes(normalizedDetailStatus);
            const savedRejectionReason = String(
                item.alasan_penolakan || item.alasanPenolakan || ''
            ).trim();

            if (laporanRejectionReasonCard && laporanRejectionReasonText) {
                laporanRejectionReasonText.textContent =
                    savedRejectionReason || 'Alasan penolakan belum tersedia.';
                laporanRejectionReasonCard.classList.toggle('show', detailIsRejected);
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
 
}); 
</script> 
 
@endsection