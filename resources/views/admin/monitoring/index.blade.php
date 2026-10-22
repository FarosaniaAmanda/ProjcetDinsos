@extends('admin.layouts.app')

@section('title', 'Monitoring Pendataan')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

<style>
    /* =====================================================
       MONITORING PAGE
    ===================================================== */

    .monitoring-page {
        padding: 24px 32px 40px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        box-sizing: border-box;
    }

       .monitoring-main-card {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    background: #ffffff;
    border: 1px solid #e2e6f2;
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(37, 42, 134, 0.06);
    overflow: hidden;
    box-sizing: border-box;
}

    /* =====================================================
       HEADER
    ===================================================== */

    .monitoring-header {
        padding: 24px 26px 20px;
        border-bottom: 1px solid #e8ebf3;
    }

    .monitoring-header h1 {
        margin: 0;
        font-size: 25px;
        line-height: 1.3;
        font-weight: 700;
        color: #252A86;
    }

    .monitoring-header p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }


    /* =====================================================
       STATISTICS
    ===================================================== */

    .monitoring-stats-wrapper {
        padding: 20px 26px 22px;
        border-bottom: 1px solid #e8ebf3;
    }

    .monitoring-stats {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 14px;
    }

    .monitoring-stat-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 0;
        min-height: 125px;
        background: #ffffff;
        border: 1px solid #e6e8f1;
        border-radius: 16px;
        padding: 20px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(41, 45, 143, .045);
        transition: transform .2s ease, box-shadow .2s ease;
        box-sizing: border-box;
    }

    .monitoring-stat-card-link {
        display: flex;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
    }

    .monitoring-stat-card-link:hover {
        text-decoration: none;
    }

    .monitoring-stat-card-link.active {
        border-color: currentColor;
        box-shadow: 0 0 0 2px rgba(37, 42, 134, 0.08);
    }

    .monitoring-stat-card-link.active::after {
        content: '';
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: 0;
        height: 3px;
        background: currentColor;
        border-radius: 3px 3px 0 0;
    }

    .monitoring-stat-filter-hint {
        margin-top: 8px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.4;
    }

    .monitoring-stat-card-link:focus-visible {
        outline: 3px solid rgba(37, 42, 134, 0.15);
        outline-offset: 2px;
    }

    .monitoring-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: currentColor;
    }

    .monitoring-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(41, 45, 143, .07);
    }

    .monitoring-stat-card.total-card {
        color: #3155c6;
    }

    .monitoring-stat-card.not-processed-card {
        color: #7652c8;
    }

    .monitoring-stat-card.draft-card {
        color: #6b7280;
    }

    .monitoring-stat-card.pending-card {
        color: #bd8700;
    }

    .monitoring-stat-card.approved-card {
        color: #15916d;
    }

    .monitoring-stat-card.rejected-card {
        color: #d14b4b;
    }

    .monitoring-stat-label {
        color: #27305e;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
    }

    .monitoring-stat-value {
        margin-top: 8px;
        color: currentColor;
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1;
    }


    /* =====================================================
       TABLE SECTION
    ===================================================== */

    
.monitoring-table-header {
    padding: 20px 26px 16px;
    border-bottom: 1px solid #e8ebf3;
    display: flex;
    flex-direction: column;
    gap: 0;
}

/* Form pencarian dan filter */
.monitoring-filter-form {
    display: grid;
    grid-template-columns:
        minmax(0, 1.8fr)
        minmax(0, 1fr)
        minmax(0, 1fr)
        minmax(0, 1fr);
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    align-items: end;
    gap: 12px;
    box-sizing: border-box;
}

/* Informasi hasil pencarian */
.monitoring-search-info {
    margin: 6px 0 0;
    padding: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.monitoring-search-info strong {
    color: #252A86;
    font-weight: 700;
}

/* Judul tabel tidak memiliki jarak kosong tambahan */
.monitoring-table-title {
    margin: 0;
    font-size: 18px;
    line-height: 1.4;
    font-weight: 700;
    color: #252A86;
}

.monitoring-table-description {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

    /* =====================================================
   SEARCH MONITORING
===================================================== */

.monitoring-search-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 27px;
}

.monitoring-search-box {
    position: relative;
    width: 100%;
    max-width: none;
}

.monitoring-search-box input {
    width: 100%;
    height: 46px;
    padding: 0 45px 0 16px;
    border: 1px solid #d5d9e7;
    border-radius: 10px;
    outline: none;
    font-size: 14px;
    color: #1e293b;
    background: #ffffff;
    box-sizing: border-box;
    transition: 0.2s ease;
}

.monitoring-search-box input::placeholder {
    color: #94a3b8;
}

.monitoring-search-box input:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.09);
}

.monitoring-search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    pointer-events: none;
}

.monitoring-search-suggestions {
    display: none;
    position: absolute;
    top: 52px;
    left: 0;
    width: 100%;
    max-width: none;
    background: #ffffff;
    border: 1px solid #e2e6f2;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(15, 23, 42, 0.10);
    z-index: 50;
    overflow: hidden;
}

.monitoring-search-suggestions.show {
    display: block;
}

.monitoring-filter-form {
    display: grid;

    /* Search lebih panjang, 3 filter ikut bergeser */
    grid-template-columns:
        minmax(0, 1.8fr)
        minmax(0, 1fr)
        minmax(0, 1fr)
        minmax(0, 1fr);

    width: 100%;
    max-width: 1100px;
    margin: 0 auto;

    align-items: end;
    gap: 12px;

    box-sizing: border-box;
}

.monitoring-filter-group {
    width: 100%;
    min-width: 0;
    max-width: 100%;
    box-sizing: border-box;
}

.monitoring-filter-search {
    min-width: 0;
    width: 100%;
}

.monitoring-search-box {
    width: 100%;
    max-width: 100%;
}

.monitoring-search-box input {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.monitoring-filter-submit,
.monitoring-filter-reset {
    display: none !important;
}

.monitoring-filter-group {
    min-width: 0;
}

.monitoring-filter-label {
    display: block;
    margin-bottom: 7px;
    color: #555;
    font-size: 12px;
    font-weight: 700;
}

.monitoring-filter-select,
.monitoring-filter-submit,
.monitoring-filter-reset {
    width: 100%;
    height: 46px;
    box-sizing: border-box;
    border: 1px solid #d5d9e7;
    border-radius: 10px;
    background: #fff;
    color: #1e293b;
    font-size: 13px;
}

.monitoring-filter-select {
    padding: 0 12px;
}

.monitoring-filter-submit,
.monitoring-filter-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 13px;
    cursor: pointer;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.monitoring-filter-submit {
    border-color: #252a86;
    background: #252a86;
    color: #fff;
}

.monitoring-filter-reset {
    border-color: #d5d9e7;
    color: #475569;
}

.monitoring-filter-select:disabled {
    background: #f1f3f7;
    color: #94a3b8;
}

    .monitoring-suggestion-item {
        display: block;
        width: 100%;
        padding: 12px 15px;
        border: 0;
        background: #ffffff;
        text-align: left;
        font: inherit;
        cursor: pointer;
        border-bottom: 1px solid #eef1f6;
    }

    .monitoring-suggestion-item:last-child {
        border-bottom: none;
    }

    .monitoring-suggestion-item:hover {
        background: #f4f5ff;
    }

    .monitoring-suggestion-name {
        color: #252A86;
        font-size: 14px;
        font-weight: 600;
    }

    .monitoring-suggestion-detail {
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
    }


    /* =====================================================
       JUDUL TABLE
    ===================================================== */

    .monitoring-table-title {
        font-size: 18px;
        line-height: 1.4;

        font-weight: 700;

        color: #252A86;

        margin: 0;
    }


    /* =====================================================
       DESKRIPSI TABLE
    ===================================================== */

    .monitoring-table-description {
        margin: 7px 0 0;

        color: #64748b;

        font-size: 13px;

        line-height: 1.6;
    }


    /* =====================================================
       TABLE
    ===================================================== */

    .monitoring-table-wrapper {
        width: 100%;
        max-width: 1252px;
        margin: 0 auto;
        padding: 0 26px 24px;
        overflow-x: auto;
        box-sizing: border-box;
    }

    .monitoring-table {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        border-collapse: collapse;
        min-width: 1000px;
        background: #ffffff;
    }

    .monitoring-table th {
        background: #f1f3ff;

        color: #4b5563 !important;

        font-size: 12px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .02em;

        padding: 14px 16px;

        border-bottom: 1px solid #e2e6f2;

        text-align: left;

        white-space: nowrap;
    }

    .monitoring-table td {
        padding: 15px 16px;

        border-bottom: 1px solid #eef1f6;

        color: #4b5563 !important;

        font-size: 13px;

        vertical-align: middle;
    }

    .monitoring-table td strong {
        color: #4b5563 !important;
        font-weight: 600;
    }

    .monitoring-table tbody tr {
        transition: .15s ease;
    }

    .monitoring-table tbody tr:hover {
        background: #fafbff;
    }

    .monitoring-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =====================================================
       STATUS
    ===================================================== */

    .monitoring-status {
        display: inline-flex;

        align-items: center;

        padding: 5px 10px;

        border-radius: 999px;

        font-size: 11px;

        font-weight: 700;

        white-space: nowrap;
    }

    /* ================================
   STATUS MONITORING
================================ */


.monitoring-status.approved {
    background: #E8F7EE;
    color: #299447;
}


.monitoring-status.reject,
.monitoring-status.rejected {
    background: #FDECEC;
    color: #D9364F;
}


.monitoring-status.draft {
    background: #F0F0F0;
    color: #6B7280;
}


.monitoring-status.pending {
    background: #FFF7D6;
    color: #B88600;
}

.monitoring-status.not_processed {
background: #F0EAFE;
color: #7652C8;
}

    /* =====================================================
       DETAIL BUTTON
    ===================================================== */

    .monitoring-action-btn {
        border: 1px solid #d7daf2;

        background: #f1f3ff;

        color: #252A86;

        padding: 7px 13px;

        border-radius: 8px;

        font-size: 12px;

        font-weight: 600;

        cursor: pointer;

        transition: .2s ease;

        white-space: nowrap;
    }

    .monitoring-action-btn:hover {
        background: #252A86;

        color: #ffffff;

        border-color: #252A86;

        box-shadow: 0 3px 8px rgba(37, 42, 134, 0.18);
    }


    /* =====================================================
       EMPTY
    ===================================================== */

    .monitoring-empty {
        text-align: center;

        padding: 45px 20px;

        color: #64748b;
    }

    .monitoring-empty-icon {
        width: 54px;
        height: 54px;

        margin: 0 auto 13px;

        border-radius: 14px;

        background: #f1f3ff;

        color: #8b91bd;

        display: flex;

        align-items: center;
        justify-content: center;
    }

    .monitoring-empty-title {
        font-weight: 700;

        color: #252A86;

        margin-bottom: 5px;
    }

    .monitoring-empty-text {
        font-size: 13px;

        color: #64748b;
    }


    /* =====================================================
       MODAL DETAIL
    ===================================================== */

    .monitoring-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.52);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 10px;
        z-index: 3000;
        backdrop-filter: blur(3px);
    }

    .monitoring-modal-overlay.active {
        display: flex;
    }

    .monitoring-modal {
        position: relative;
        width: min(1250px, calc(100vw - 40px));
        max-height: calc(100vh - 40px);
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        overflow: hidden;
        animation: monitoringModalShow .2s ease;
    }

    @keyframes monitoringModalShow {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .monitoring-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        background: #ffffff;
    }

    .monitoring-modal-header-left h2 {
        margin: 0;
        font-size: 19px;
        line-height: 1.3;
        color: #252A86;
        font-weight: 700;
    }

    .monitoring-modal-header-left p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #7b7f87;
    }

    .monitoring-modal-close {
        width: 42px;
        height: 42px;
        border: none;
        border-radius: 11px;
        background: #f3f4f8;
        color: #666b73;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: .2s ease;
    }

    .monitoring-modal-close:hover {
        background: #252A86;
        color: #ffffff;
    }

    .monitoring-modal-body {
        position: relative;
        padding: 18px 20px 22px;
        overflow-y: auto;
        max-height: calc(100vh - 125px);
        scrollbar-width: thin;
    }

    /* =====================================================
       DETAIL SUMMARY
    ===================================================== */

    .monitoring-detail-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }

    .monitoring-detail-summary-card {
        display: flex;
        min-width: 0;
        min-height: 0;
        flex-direction: column;
        justify-content: center;
        padding: 13px 14px;
        border: 1px solid #e7e9f1;
        border-radius: 10px;
        background: #f8f9fc;
    }

    .monitoring-detail-summary-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .monitoring-detail-summary-value {
        color: #263238;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
        margin-top: 4px;
    }

    .monitoring-summary-status,
    .monitoring-detail-value.status-value {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        max-width: 100%;
        padding: 6px 10px;
        border: 1px solid #dcdde2;
        border-radius: 999px;
        background: #f0f0f2;
        color: #666b75;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.2;
        white-space: nowrap;
    }

    .monitoring-summary-status.status-approved,
    .monitoring-detail-value.status-value.status-approved {
        border-color: #bce5c9;
        background: #eaf8ef;
        color: #24723c;
    }

    .monitoring-summary-status.status-rejected,
    .monitoring-detail-value.status-value.status-rejected {
        border-color: #f2c4c4;
        background: #fdecec;
        color: #a53636;
    }

    .monitoring-summary-status.status-pending,
    .monitoring-detail-value.status-value.status-pending {
        border-color: #f1dfaa;
        background: #fff7d6;
        color: #9a6a00;
    }

    .monitoring-summary-status.status-not-processed,
    .monitoring-detail-value.status-value.status-not-processed {
        border-color: #d8c8fb;
        background: #f0eafe;
        color: #7652c8;
    }

    .monitoring-detail-period-meta {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 11px;
    }

    /* =====================================================
       DETAIL SECTION - GAYA VERIFIKASI
    ===================================================== */

    .monitoring-detail-section {
        border: 1px solid #e7e9f1;
        border-radius: 12px;
        overflow: visible;
        margin-bottom: 16px;
        padding: 16px;
        background: #ffffff;
    }

    .monitoring-detail-section:last-child {
        margin-bottom: 0;
    }

    .monitoring-detail-section-title {
        margin: 0 0 13px;
        background: #ffffff;
        color: #252A86;
        font-size: 15px;
        font-weight: 700;
    }

    .monitoring-detail-section-subtitle {
        margin: -8px 0 13px;
        color: #7b7f87;
        font-size: 11px;
        line-height: 1.55;
    }

    /* ALASAN PENOLAKAN DI MODAL DETAIL */
    .monitoring-rejection-reason {
        display: none;
        margin: 0 0 16px;
        padding: 15px 16px;
        border: 1px solid #f1c5c8;
        border-left: 4px solid #d9364f;
        border-radius: 12px;
        background: #fff8f8;
        box-sizing: border-box;
    }

    .monitoring-rejection-reason.show {
        display: block;
    }

    .monitoring-rejection-reason-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 7px;
        color: #b42336;
        font-size: 13px;
        font-weight: 800;
    }

    .monitoring-rejection-reason-text {
        margin: 0;
        color: #5f2930;
        font-size: 13px;
        line-height: 1.65;
        font-weight: 500;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .monitoring-detail-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 9px;
    }

    .monitoring-detail-item {
        position: relative;
        padding: 11px 12px;
        min-height: 0;
        border: 1px solid #edf0f5;
        border-radius: 9px;
        background: #fafbfc;
        box-sizing: border-box;
    }

    .monitoring-detail-label {
        color: #6b7280;
        font-size: 10px;
        line-height: 1.4;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .monitoring-detail-value {
        color: #263238;
        font-size: 13px;
        line-height: 1.4;
        font-weight: 700;
        word-break: break-word;
    }

    .monitoring-members-section {
        padding: 16px;
    }

    .monitoring-members-section .monitoring-members-family-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        min-width: 0;
        margin-bottom: 14px;
        padding: 10px 12px;
        border: 1px solid #e2e6f4;
        border-radius: 9px;
        background: #f7f8fd;
        text-align: left;
    }

    .monitoring-members-section .monitoring-members-family-summary-label {
        margin: 0;
        color: #73798a;
        font-size: 11px;
    }

    .monitoring-members-section .monitoring-members-family-summary-name {
        margin-top: 4px;
        color: #263238;
        font-size: 13px;
        font-weight: 700;
    }

    .monitoring-members-section .monitoring-members-family-summary-count {
        flex: 0 0 auto;
        color: #73798a;
        font-size: 11px;
    }

    /* =====================================================
       JUMLAH ANGGOTA + TOMBOL LIHAT ANGGOTA
    ===================================================== */

    .monitoring-member-item {
        z-index: 20;
    }

    .monitoring-member-count {
        display: block;
        margin-bottom: 9px;
    }

    .monitoring-member-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 8px 13px;
        border: 1px solid #cfd5f5;
        border-radius: 10px;
        background: #f2f4ff;
        color: #697080;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .monitoring-member-button:hover,
    .monitoring-member-button.active {
        border-color: #c7cdf6;
        background: #e9ecff;
        color: #252A86;
    }

    .monitoring-member-button svg {
        transition: transform .2s ease;
    }

    .monitoring-member-button.active svg {
        transform: rotate(180deg);
    }

    /* =====================================================
       FLOATING PANEL ANGGOTA KELUARGA
    ===================================================== */

    .monitoring-members-popover {
        position: absolute;
        top: 128px;
        right: 26px;
        width: min(720px, calc(100% - 52px));
        z-index: 100;
        display: none;
        background: #ffffff;
        border: 1px solid #dfe3ee;
        border-radius: 16px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
        overflow: hidden;
        animation: monitoringMemberShow .18s ease;
    }

    .monitoring-members-popover.active {
        display: block;
    }

    @keyframes monitoringMemberShow {
        from {
            opacity: 0;
            transform: translateY(-7px) scale(.99);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .monitoring-members-popover-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        padding: 22px 22px 18px;
        border-bottom: 1px solid #e8ebf2;
    }

    .monitoring-members-popover-title {
        margin: 0;
        color: #252A86;
        font-size: 22px;
        line-height: 1.25;
        font-weight: 700;
    }

    .monitoring-members-popover-description {
        margin: 7px 0 0;
        color: #7b8190;
        font-size: 13px;
        line-height: 1.5;
    }

    .monitoring-members-popover-close {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        border: 0;
        border-radius: 10px;
        background: #f3f4f8;
        color: #667085;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s ease;
    }

    .monitoring-members-popover-close:hover {
        background: #252A86;
        color: #ffffff;
    }


    .monitoring-members-family-summary {
        min-width: 178px;
        padding: 12px 15px;
        border: 1px solid #dfe3ee;
        border-radius: 12px;
        background: #f8f9ff;
        text-align: right;
    }

    .monitoring-members-family-summary-label {
        color: #6b7280;
        font-size: 11px;
        font-weight: 600;
    }

    .monitoring-members-family-summary-name {
        margin-top: 5px;
        color: #263238;
        font-size: 15px;
        font-weight: 700;
    }

    .monitoring-members-family-summary-count {
        margin-top: 3px;
        color: #7b8190;
        font-size: 12px;
    }

    .monitoring-members-popover-body {
        padding: 18px 22px 22px;
    }

    .monitoring-members-table-wrap {
        border: 1px solid #e0e4ec;
        border-radius: 12px;
        overflow: hidden;
        overflow-x: auto;
    }

    .monitoring-members-table {
        width: 100%;
        min-width: 560px;
        border-collapse: collapse;
        background: #ffffff;
    }

    .monitoring-members-table th {
        padding: 13px 15px;
        background: #f3f5f9;
        color: #5f6878;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
        border-bottom: 1px solid #dfe3eb;
    }

    .monitoring-members-table td {
        padding: 14px 15px;
        color: #374151;
        font-size: 13px;
        font-weight: 500;
        border-bottom: 1px solid #eef1f5;
        vertical-align: middle;
    }

    .monitoring-members-table tbody tr:last-child td {
        border-bottom: none;
    }

    .monitoring-members-empty {
        padding: 38px 20px;
        text-align: center;
        color: #64748b;
    }

    .monitoring-members-empty strong {
        display: block;
        margin-bottom: 6px;
        color: #263238;
        font-size: 14px;
    }

    .monitoring-members-empty span {
        color: #7b8190;
        font-size: 12px;
    }

    /* =====================================================
       HASIL KUISIONER 
    ===================================================== */

    .monitoring-questionnaire-wrapper {
        padding: 0 20px 20px;
    }

    .monitoring-questionnaire-master {
        display: grid;
        grid-template-columns: 250px minmax(0, 1fr);
        gap: 18px;
        align-items: stretch;
    }

    .monitoring-questionnaire-sidebar {
        border: 1px solid #e0e4ec;
        border-radius: 12px;
        background: #f8f9fd;
        padding: 14px;
        min-width: 0;
    }

    .monitoring-questionnaire-sidebar-title {
        margin: 2px 10px 12px;
        color: #667085;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .monitoring-questionnaire-part {
        margin-bottom: 9px;
        border: 1px solid #dfe3ec;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .monitoring-questionnaire-part:last-child {
        margin-bottom: 0;
    }

    .monitoring-questionnaire-part-header {
        width: 100%;
        border: none;
        background: #ffffff;
        color: #252A86;
        padding: 11px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 9px;
        cursor: pointer;
        text-align: left;
        transition: .18s ease;
    }

    .monitoring-questionnaire-part-header:hover,
    .monitoring-questionnaire-part-header.active {
        background: #eef0ff;
    }

    .monitoring-questionnaire-part-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .monitoring-questionnaire-part-number {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef0ff;
        color: #252A86;
        font-size: 14px;
        font-weight: 800;
    }

    .monitoring-questionnaire-part-header.active
    .monitoring-questionnaire-part-number {
        background: #252A86;
        color: #ffffff;
    }

    .monitoring-questionnaire-part-title {
        color: #263238;
        font-size: 12px;
        line-height: 1.35;
        font-weight: 700;
    }

    .monitoring-questionnaire-part-meta {
        margin-top: 3px;
        color: #8a93a3;
        font-size: 10px;
        line-height: 1.35;
    }

    
.monitoring-questionnaire-part-arrow {
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    border: 1px solid #dfe3ec;
    border-radius: 8px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #252A86;
    transition: background .18s ease, color .18s ease,
                border-color .18s ease, transform .2s ease;
}

.monitoring-questionnaire-part-header.active
.monitoring-questionnaire-part-arrow {
    background: #252A86;
    border-color: #252A86;
    color: #ffffff;
    transform: rotate(180deg);
}


    .monitoring-questionnaire-content-panel {
        min-width: 0;
        border: 1px solid #e0e4ec;
        border-radius: 12px;
        background: #ffffff;
        overflow: hidden;
    }

    .monitoring-questionnaire-content-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e7eaf0;
        background: #ffffff;
    }

    .monitoring-questionnaire-content-eyebrow {
        margin-bottom: 5px;
        color: #7b8190;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .monitoring-questionnaire-content-title {
        margin: 0;
        color: #252A86;
        font-size: 19px;
        line-height: 1.35;
        font-weight: 700;
    }

    .monitoring-questionnaire-content-body {
        padding: 15px;
        max-height: 480px;
        overflow-y: auto;
    }

    .monitoring-questionnaire-placeholder {
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 35px 25px;
        text-align: center;
        color: #7b8190;
    }

    .monitoring-questionnaire-placeholder strong {
        margin-bottom: 7px;
        color: #374151;
        font-size: 15px;
    }

    .monitoring-questionnaire-placeholder span {
        max-width: 360px;
        color: #8a93a3;
        font-size: 12px;
        line-height: 1.55;
    }
    
/* Ukuran foto pada detail Monitoring */
.monitoring-questionnaire-content-body.is-photo-part {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 12px !important;
    align-items: start !important;
}

.monitoring-questionnaire-content-body.is-photo-part img {
    display: block !important;
    width: 100% !important;
    height: 200px !important;
    max-height: 200px !important;
    object-fit: cover !important;
    border-radius: 9px !important;
}

@media (max-width: 700px) {
    .monitoring-questionnaire-content-body.is-photo-part {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}


    .monitoring-question-item {
        border: 1px solid #e7eaf0;
        border-radius: 11px;
        padding: 14px 15px;
        margin-bottom: 10px;
        background: #ffffff;
    }

    .monitoring-question-item:last-child {
        margin-bottom: 0;
    }

    .monitoring-questionnaire-members-layout {
        display: grid;
        grid-template-columns: minmax(210px, .8fr) minmax(0, 1.5fr);
        gap: 14px;
    }

    .monitoring-questionnaire-members-panel,
    .monitoring-questionnaire-member-answers {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #e7eaf0;
        border-radius: 11px;
        background: #fff;
    }

    .monitoring-questionnaire-members-heading {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 13px 15px;
        border-bottom: 1px solid #e7eaf0;
    }

    .monitoring-questionnaire-members-heading strong {
        color: #374151;
        font-size: 13px;
    }

    .monitoring-questionnaire-members-heading span {
        color: #7b8190;
        font-size: 11px;
        line-height: 1.45;
    }

    .monitoring-questionnaire-members-list {
        display: grid;
        gap: 8px;
        max-height: 390px;
        overflow-y: auto;
        padding: 10px;
    }

    .monitoring-questionnaire-member-card {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px;
        border: 1px solid #e7eaf0;
        border-radius: 9px;
        background: #fff;
        color: #374151;
        text-align: left;
        cursor: pointer;
    }

    .monitoring-questionnaire-member-card:hover,
    .monitoring-questionnaire-member-card.is-selected {
        border-color: #252A86;
        background: #f4f5ff;
    }

    .monitoring-questionnaire-member-avatar {
        display: grid;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        place-items: center;
        border-radius: 50%;
        background: #e9eaff;
        color: #252A86;
        font-weight: 700;
    }

    .monitoring-questionnaire-member-info {
        display: grid;
        min-width: 0;
        gap: 3px;
    }

    .monitoring-questionnaire-member-info strong {
        overflow: hidden;
        font-size: 12px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .monitoring-questionnaire-member-info span,
    .monitoring-questionnaire-member-info small {
        color: #7b8190;
        font-size: 10px;
    }

    .monitoring-questionnaire-member-answers-body {
        max-height: 390px;
        overflow-y: auto;
        padding: 10px;
    }

    .monitoring-questionnaire-member-summary {
        margin-bottom: 10px;
        padding: 11px 12px;
        border-radius: 8px;
        background: #f4f5ff;
        color: #252A86;
        font-size: 12px;
        font-weight: 700;
    }

    .monitoring-question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 25px;
        height: 25px;
        padding: 0 7px;
        border-radius: 7px;
        background: #f0f2f7;
        color: #697386;
        font-size: 10px;
        font-weight: 800;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .monitoring-question-text {
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
        margin-bottom: 11px;
    }

    .monitoring-answer-label {
        color: #7b8190;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 5px;
    }

    .monitoring-answer-value {
        padding: 10px 12px;
        border-left: 3px solid #252A86;
        border-radius: 0 8px 8px 0;
        background: #f7f8fc;
        color: #374151;
        font-size: 13px;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .monitoring-answer-value.is-empty {
        color: #94a3b8;
        font-style: italic;
    }

    .monitoring-answer-value.is-visual {
        padding: 0;
        border: 0;
        background: transparent;
    }

    .monitoring-map {
        width: 100%;
        height: 260px;
        border: 1px solid #e2e6f2;
        border-radius: 10px;
        background: #f1f5f9;
        z-index: 0;
    }

    .monitoring-map-coordinates {
        margin-top: 9px;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
    }

    .monitoring-map-link {
        display: inline-flex;
        margin-top: 7px;
        color: #252A86;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .monitoring-map-link:hover {
        text-decoration: underline;
    }

    .monitoring-questionnaire-content-body.monitoring-questionnaire-part1 {
        max-height: 520px;
    }

    .monitoring-questionnaire-part1 .monitoring-question-item {
        padding: 15px;
        border: 1px solid #e5e7ee;
        border-radius: 10px;
        box-shadow: 0 2px 7px rgba(30, 35, 60, .03);
    }

    .monitoring-questionnaire-part1 .monitoring-question-number {
        min-width: 0;
        height: auto;
        min-height: 24px;
        margin-bottom: 8px;
        padding: 4px 8px;
        border-radius: 6px;
        background: #f0f1f5;
        color: #666c7a;
        font-size: 9px;
        letter-spacing: .35px;
    }

    .monitoring-questionnaire-part1 .monitoring-question-text {
        margin-bottom: 12px;
        color: #374151;
        font-size: 13px;
        line-height: 1.65;
    }

    .monitoring-questionnaire-part1 .monitoring-answer-label {
        margin-bottom: 5px;
        color: #656b79;
        font-size: 10px;
        letter-spacing: .4px;
    }

    .monitoring-questionnaire-part1 .monitoring-answer-value {
        min-height: 42px;
        padding: 10px 12px;
        border-left: 3px solid #55B5D5;
        border-radius: 7px;
        background: #f5f8fa;
        font-size: 13px;
        font-weight: 500;
    }

    .monitoring-questionnaire-part1 .monitoring-answer-value.is-empty {
        border-left-color: #F5C928;
        background: #fff9e8;
        color: #8a6a20;
    }

    .monitoring-questionnaire-part1 .monitoring-address-reason-label {
        margin-top: 10px;
    }

    .monitoring-questionnaire-part1 .questionnaire-answer-map {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #e6e8ef;
        border-radius: 10px;
        background: #f8f9fc;
        overflow: hidden;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-coordinates {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        padding: 9px 11px;
        border: 1px solid #e2e5ec;
        border-radius: 8px;
        background: #ffffff;
        color: #4b5563;
        font-size: 12px;
        line-height: 1.5;
        word-break: break-word;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-coordinates strong {
        color: #333;
        font-weight: 700;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-container {
        width: 100%;
        height: 330px;
        min-height: 260px;
        border: 1px solid #dfe3eb;
        border-radius: 9px;
        overflow: hidden;
        background: #eef1f5;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-container .leaflet-container {
        width: 100%;
        height: 100%;
        font-family: inherit;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 20px;
        border: 1px dashed #d8dce6;
        border-radius: 9px;
        background: #f8f9fc;
        color: #777;
        font-size: 12px;
        line-height: 1.5;
        text-align: center;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 9px;
        padding: 7px 10px;
        border-radius: 7px;
        background: #eef0ff;
        color: #252A86;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
    }

    .monitoring-questionnaire-part1 .questionnaire-map-link:hover {
        background: #252A86;
        color: #ffffff;
    }

    @media (max-width: 850px) {
        .monitoring-questionnaire-content-body.monitoring-questionnaire-part1 {
            max-height: 450px;
        }
    }

    @media (max-width: 600px) {
        .monitoring-questionnaire-part1 .questionnaire-map-container {
            height: 260px;
            min-height: 220px;
        }

        .monitoring-questionnaire-part1 .questionnaire-map-coordinates {
            align-items: flex-start;
            flex-direction: column;
            gap: 2px;
        }
    }

    .monitoring-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }

    .monitoring-image-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #e2e6f2;
        border-radius: 10px;
        background: #ffffff;
    }

    .monitoring-image-link,
    .monitoring-image {
        display: block;
        width: 100%;
    }

    .monitoring-questionnaire-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 13px;
    }

    .monitoring-questionnaire-total {
        flex: 0 0 auto;
        padding: 6px 10px;
        border: 1px solid #e1e4f3;
        border-radius: 999px;
        background: #f4f5ff;
        color: #252A86;
        font-size: 11px;
        font-weight: 800;
    }

    .monitoring-image {
        height: 150px;
        object-fit: cover;
        background: #f1f5f9;
    }

    .monitoring-image-name {
        padding: 8px 10px;
        color: #475569;
        font-size: 11px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    /* =====================================================
       RESPONSIVE DETAIL
    ===================================================== */

    @media (max-width: 1000px) {
        .monitoring-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .monitoring-questionnaire-master {
            grid-template-columns: 1fr;
        }

        .monitoring-questionnaire-sidebar {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .monitoring-questionnaire-sidebar-title {
            grid-column: 1 / -1;
        }

        .monitoring-questionnaire-part {
            margin-bottom: 0;
        }

        .monitoring-members-popover {
            right: 18px;
            width: calc(100% - 36px);
        }
    }

    @media (max-width: 700px) {
        .monitoring-modal-overlay {
            padding: 8px;
        }

        .monitoring-modal {
            width: 100%;
            max-height: calc(100vh - 16px);
            border-radius: 14px;
        }

        .monitoring-modal-header {
            padding: 15px 16px;
        }

        .monitoring-modal-header-left h2 {
            font-size: 21px;
        }

        .monitoring-modal-header-left p {
            font-size: 12px;
        }

        .monitoring-modal-body {
            padding: 15px;
            max-height: calc(100vh - 90px);
        }

        .monitoring-detail-summary {
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .monitoring-detail-grid {
            grid-template-columns: 1fr;
            padding: 0 15px 15px;
        }

        .monitoring-detail-section-title {
            padding: 14px 15px 7px;
        }

        .monitoring-detail-section-subtitle {
            padding: 0 15px 12px;
        }

        .monitoring-questionnaire-wrapper {
            padding: 0 15px 15px;
        }

        .monitoring-questionnaire-sidebar {
            display: block;
        }

        .monitoring-questionnaire-part {
            margin-bottom: 8px;
        }

        .monitoring-questionnaire-content-body {
            max-height: 420px;
        }

        .monitoring-questionnaire-members-layout {
            grid-template-columns: 1fr;
        }

        .monitoring-questionnaire-members-list {
            max-height: 220px;
        }

        .monitoring-questionnaire-member-answers-body {
            max-height: 360px;
        }

        .monitoring-members-popover {
            position: fixed;
            top: 50%;
            left: 50%;
            right: auto;
            width: calc(100vw - 28px);
            max-height: calc(100vh - 28px);
            transform: translate(-50%, -50%);
            overflow-y: auto;
        }

        .monitoring-members-popover.active {
            animation: monitoringMemberMobileShow .18s ease;
        }

        @keyframes monitoringMemberMobileShow {
            from {
                opacity: 0;
                transform: translate(-50%, -47%) scale(.99);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        .monitoring-members-popover-header {
            flex-direction: column;
        }

        .monitoring-members-family-summary {
            width: 100%;
            box-sizing: border-box;
            text-align: left;
        }

        .monitoring-members-popover-body {
            padding: 14px 15px 16px;
        }
    }

    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1200px) {

                .monitoring-page {
            padding: 24px 40px 40px;
            background: #f5f7fb;
            min-height: calc(100vh - 70px);
            box-sizing: border-box;
        }
        .monitoring-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .monitoring-filter-form {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }


    @media (max-width: 700px) {

        .monitoring-page {
            padding: 16px 12px 30px;
        }

        .monitoring-header {
            padding: 19px 17px 17px;
        }

        .monitoring-header h1 {
            font-size: 21px;
        }

        .monitoring-stats-wrapper {
            padding: 15px;
        }

        .monitoring-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 10px;
        }

        .monitoring-filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .monitoring-filter-search {
            grid-column: 1 / -1;
        }

       .monitoring-table-header {
    padding: 16px 17px 14px;
}

        .monitoring-table-wrapper {
            padding: 0 17px 18px;
        }

        .monitoring-search-wrapper {
            margin-bottom: 24px;
        }

        .monitoring-search-box {
            max-width: 100%;
        }

        .monitoring-search-suggestions {
            max-width: 100%;
        }

        .monitoring-modal-overlay {
            padding: 10px;
        }

        .monitoring-modal {
            border-radius: 14px;

            max-height: calc(100vh - 20px);
        }

        .monitoring-modal-body {
            padding: 15px;
        }

        .monitoring-detail-grid {
            grid-template-columns: 1fr;
        }

        .monitoring-detail-item:nth-child(odd) {
            border-right: none;
        }

        .monitoring-detail-summary {
            grid-template-columns: 1fr;
        }
    }

/* =====================================================
   FINAL RESPONSIVE DETAIL MONITORING
===================================================== */
.monitoring-members-popover-close {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 0;
    border-radius: 10px;
    background: #f3f4f8;
    color: #667085;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: .2s ease;
}
.monitoring-members-popover-close:hover {
    background: #252A86;
    color: #ffffff;
}
.monitoring-members-popover-header > div:first-child {
    min-width: 0;
    flex: 1 1 auto;
}
.monitoring-members-popover-title,
.monitoring-members-popover-description {
    overflow-wrap: anywhere;
}
.monitoring-detail-value {
    overflow-wrap: anywhere;
}
@media (max-width: 700px) {
    .monitoring-modal-header {
        align-items: flex-start;
    }
    .monitoring-modal-header-left {
        min-width: 0;
        padding-right: 4px;
    }
    .monitoring-modal-header-left h2 {
        font-size: 20px;
    }
    .monitoring-modal-header-left p {
        line-height: 1.55;
    }
    .monitoring-detail-summary-card,
    .monitoring-detail-item {
        min-width: 0;
    }
    .monitoring-members-popover {
        width: calc(100vw - 24px);
        max-width: calc(100vw - 24px);
        max-height: calc(100vh - 24px);
        border-radius: 15px;
    }
    .monitoring-members-popover-header {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 38px;
        align-items: start;
        gap: 12px;
        padding: 17px 16px 15px;
    }
    .monitoring-members-popover-title {
        font-size: 19px;
        line-height: 1.3;
    }
    .monitoring-members-popover-description {
        font-size: 12px;
        line-height: 1.55;
        margin-top: 6px;
    }
    .monitoring-members-family-summary {
        grid-column: 1 / -1;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        text-align: left;
        padding: 11px 13px;
    }
    .monitoring-members-popover-body {
        padding: 13px 15px 16px;
    }
    .monitoring-members-table-wrap {
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .monitoring-members-table {
        min-width: 560px;
    }
    .monitoring-questionnaire-part-header {
        min-height: 54px;
    }
    .monitoring-questionnaire-content-title {
        font-size: 17px;
        overflow-wrap: anywhere;
    }
}
@media (max-width: 380px) {
    .monitoring-modal-overlay { padding: 6px; }
    .monitoring-modal { max-height: calc(100vh - 12px); }
    .monitoring-modal-body { padding: 12px; }
    .monitoring-members-popover {
        width: calc(100vw - 16px);
        max-width: calc(100vw - 16px);
    }
    .monitoring-members-popover-header {
        padding: 15px 13px 13px;
        gap: 9px;
    }
}

.monitoring-pagination {
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
    .monitoring-pagination {
        padding: 16px 18px;
        flex-direction: column;
        align-items: stretch;
    }

    .pagination {
        justify-content: center;
    }
}


/* =========================================================
   DETAIL MONITORING — 
========================================================= */

/* Kartu pertanyaan Part 1-4 dan Foto Rumah: grid dua kolom. */
.monitoring-questionnaire-content-body {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    align-content: start !important;
    align-items: start !important;
    gap: 10px !important;
}

.monitoring-questionnaire-content-body > .monitoring-question-item {
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
    padding: 13px 14px !important;
    border: 1px solid #e0e5ee !important;
    border-radius: 12px !important;
    background: #ffffff !important;
    box-shadow: 0 3px 10px rgba(31, 45, 80, .045) !important;
    box-sizing: border-box !important;
    overflow: hidden !important;
}

/* Label nomor pertanyaan. */
.monitoring-questionnaire-content-body > .monitoring-question-item .monitoring-question-number {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: fit-content !important;
    min-width: 0 !important;
    height: auto !important;
    min-height: 24px !important;
    margin: 0 0 8px !important;
    padding: 5px 9px !important;
    border: 0 !important;
    border-radius: 8px !important;
    background: #eef0f4 !important;
    color: #657084 !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    line-height: 1.35 !important;
    letter-spacing: .3px !important;
}

/* Judul pertanyaan. */
.monitoring-questionnaire-content-body > .monitoring-question-item .monitoring-question-text {
    margin: 0 0 10px !important;
    color: #263247 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    line-height: 1.5 !important;
    overflow-wrap: anywhere !important;
}

/* Label jawaban responden. */
.monitoring-questionnaire-content-body > .monitoring-question-item .monitoring-answer-label {
    margin: 8px 0 5px !important;
    color: #667085 !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    line-height: 1.4 !important;
    letter-spacing: .4px !important;
}

/* Jawaban menggunakan latar abu muda dan aksen sian seperti Verifikasi. */
.monitoring-questionnaire-content-body > .monitoring-question-item .monitoring-answer-value {
    display: block !important;
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
    padding: 10px 12px !important;
    border: 0 !important;
    border-left: 3px solid #48b8dc !important;
    border-radius: 9px !important;
    background: #f3f7f9 !important;
    color: #263247 !important;
    font-size: 12px !important;
    font-weight: 400 !important;
    line-height: 1.55 !important;
    overflow-wrap: anywhere !important;
    white-space: pre-wrap !important;
    box-sizing: border-box !important;
}

.monitoring-questionnaire-content-body > .monitoring-question-item .monitoring-answer-value.is-empty {
    color: #929baa !important;
    font-style: italic !important;
}

/* Peta dan jawaban geotagging menggunakan lebar penuh. */
.monitoring-questionnaire-content-body > .monitoring-question-item:has(.questionnaire-answer-map),
.monitoring-questionnaire-content-body > .monitoring-question-item:has(.questionnaire-map-container),
.monitoring-questionnaire-content-body > .monitoring-question-item:has(.monitoring-map) {
    grid-column: 1 / -1 !important;
}

/* Part 5: daftar anggota di atas, hasil jawaban di bawah. */
.monitoring-questionnaire-content-body > .monitoring-questionnaire-members-layout {
    grid-column: 1 / -1 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    gap: 12px !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-members-panel,
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;
    box-sizing: border-box !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-members-heading {
    padding: 11px 14px !important;
    gap: 3px !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-members-heading strong {
    color: #263247 !important;
    font-size: 13px !important;
    font-weight: 750 !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-members-heading span {
    color: #7b8190 !important;
    font-size: 11px !important;
    line-height: 1.45 !important;
}

/* Card anggota ringkas, tetap berwarna dan tidak terpotong. */
.monitoring-questionnaire-content-body .monitoring-questionnaire-members-list {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    align-items: stretch !important;
    gap: 8px !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    max-height: none !important;
    overflow: visible !important;
    padding: 9px !important;
    box-sizing: border-box !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card {
    display: flex !important;
    align-items: flex-start !important;
    gap: 8px !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    min-height: 76px !important;
    margin: 0 !important;
    padding: 9px 10px !important;
    border: 1px solid #e7eaf0 !important;
    border-radius: 11px !important;
    color: #263247 !important;
    text-align: left !important;
    box-sizing: border-box !important;
    white-space: normal !important;
    overflow-wrap: anywhere !important;
    transition: outline .15s ease, box-shadow .15s ease !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card:nth-child(5n + 1) {
    background: #fff0f5 !important;
    border-color: #f4c8d8 !important;
}
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card:nth-child(5n + 2) {
    background: #fff8d9 !important;
    border-color: #f0df9b !important;
}
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card:nth-child(5n + 3) {
    background: #f1eaff !important;
    border-color: #d7c6f4 !important;
}
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card:nth-child(5n + 4) {
    background: #e8f4ff !important;
    border-color: #c4def5 !important;
}
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card:nth-child(5n) {
    background: #e9f8ed !important;
    border-color: #c5e8ce !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card:hover,
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-card.is-selected {
    color: #263247 !important;
    outline: 2px solid #252a86 !important;
    outline-offset: 1px !important;
    box-shadow: 0 0 0 2px rgba(37, 42, 134, .08) !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-avatar {
    display: grid !important;
    flex: 0 0 30px !important;
    width: 30px !important;
    height: 30px !important;
    place-items: center !important;
    border-radius: 9px !important;
    background: rgba(255, 255, 255, .78) !important;
    color: #374151 !important;
    font-size: 11px !important;
    font-weight: 800 !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-info {
    display: grid !important;
    flex: 1 1 auto !important;
    min-width: 0 !important;
    gap: 4px !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-info strong {
    display: block !important;
    color: #263247 !important;
    font-size: 12px !important;
    font-weight: 750 !important;
    line-height: 1.4 !important;
    overflow: visible !important;
    white-space: normal !important;
    text-overflow: clip !important;
    overflow-wrap: anywhere !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-info span {
    color: #657084 !important;
    font-size: 11px !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-info small {
    color: #657084 !important;
    font-size: 10px !important;
    line-height: 1.4 !important;
    overflow-wrap: anywhere !important;
}

/* Hasil jawaban anggota dibuat dua kolom seperti Part 1-4. */
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    align-items: start !important;
    gap: 9px !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    max-height: 600px !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding: 10px !important;
    box-sizing: border-box !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-summary,
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body > .monitoring-questionnaire-placeholder {
    grid-column: 1 / -1 !important;
    width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-summary {
    margin: 0 0 2px !important;
    padding: 10px 12px !important;
    border: 1px solid #e1e4f3 !important;
    border-radius: 9px !important;
    background: #f4f5ff !important;
    color: #252a86 !important;
    font-size: 12px !important;
    font-weight: 750 !important;
    line-height: 1.5 !important;
    overflow-wrap: anywhere !important;
}

/* Card pertanyaan Part 5 memakai gaya visual yang sama dengan Part sebelumnya. */
.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body > .monitoring-question-item {
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
    padding: 12px !important;
    border: 1px solid #e0e5ee !important;
    border-radius: 12px !important;
    background: #ffffff !important;
    box-shadow: 0 3px 10px rgba(31, 45, 80, .045) !important;
    box-sizing: border-box !important;
    overflow: hidden !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body .monitoring-question-number {
    display: inline-flex !important;
    width: fit-content !important;
    min-width: 0 !important;
    height: auto !important;
    margin: 0 0 7px !important;
    padding: 5px 9px !important;
    border-radius: 8px !important;
    background: #eef0f4 !important;
    color: #657084 !important;
    font-size: 10px !important;
    line-height: 1.35 !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body .monitoring-question-text {
    margin: 0 0 9px !important;
    color: #263247 !important;
    font-size: 12px !important;
    font-weight: 750 !important;
    line-height: 1.5 !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body .monitoring-answer-label {
    margin: 8px 0 5px !important;
    color: #667085 !important;
    font-size: 10px !important;
    font-weight: 800 !important;
}

.monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body .monitoring-answer-value {
    padding: 9px 11px !important;
    border: 0 !important;
    border-left: 3px solid #48b8dc !important;
    border-radius: 9px !important;
    background: #f3f7f9 !important;
    color: #263247 !important;
    font-size: 12px !important;
    line-height: 1.5 !important;
    overflow-wrap: anywhere !important;
    box-sizing: border-box !important;
}

/* Foto rumah: gambar tetap dapat dibuka dan tidak meluber. */
.monitoring-questionnaire-content-body .monitoring-images {
    grid-column: 1 / -1 !important;
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 10px !important;
}

.monitoring-questionnaire-content-body .monitoring-image-card {
    min-width: 0 !important;
    border-radius: 10px !important;
    overflow: hidden !important;
}

.monitoring-questionnaire-content-body .monitoring-image {
    width: 100% !important;
    height: 150px !important;
    object-fit: cover !important;
}

/* Responsif */
@media (max-width: 900px) {
    .monitoring-questionnaire-content-body .monitoring-questionnaire-members-list {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .monitoring-questionnaire-content-body .monitoring-images {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

@media (max-width: 600px) {
    .monitoring-questionnaire-content-body {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 9px !important;
        max-height: 420px !important;
    }

    .monitoring-questionnaire-content-body > .monitoring-question-item,
    .monitoring-questionnaire-content-body > .monitoring-questionnaire-members-layout,
    .monitoring-questionnaire-content-body > .monitoring-question-item:has(.questionnaire-answer-map),
    .monitoring-questionnaire-content-body > .monitoring-question-item:has(.questionnaire-map-container),
    .monitoring-questionnaire-content-body > .monitoring-question-item:has(.monitoring-map) {
        grid-column: 1 !important;
    }

    .monitoring-questionnaire-content-body .monitoring-questionnaire-members-list {
        grid-template-columns: minmax(0, 1fr) !important;
        max-height: none !important;
    }

    .monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body {
        grid-template-columns: minmax(0, 1fr) !important;
        max-height: 500px !important;
    }

    .monitoring-questionnaire-content-body .monitoring-questionnaire-member-summary,
    .monitoring-questionnaire-content-body .monitoring-questionnaire-member-answers-body > .monitoring-questionnaire-placeholder {
        grid-column: 1 !important;
    }

    .monitoring-questionnaire-content-body .monitoring-images {
        grid-template-columns: minmax(0, 1fr) !important;
    }
}


</style>


<div class="monitoring-page">

    <div class="monitoring-main-card">

        {{-- HEADER --}}
        <div class="monitoring-header">

            <h1>Monitoring Pendataan</h1>

            <p>
                Memantau data responden yang telah dilakukan pendataan
                dan hasil verifikasi.
            </p>

        </div>


        {{-- =====================================================
             STATISTIK
        ====================================================== --}}

        <div class="monitoring-stats-wrapper">

           <div class="monitoring-stats">
    <a
        href="{{ route('monitoring.index', array_merge(request()->except('page'), ['status' => 'all'])) }}"
        class="monitoring-stat-card monitoring-stat-card-link total-card {{ ($statusFilter ?? 'all') === 'all' ? 'active' : '' }}"
    >
        <div class="monitoring-stat-label"> 
        Total Responden 
    </div> 
 
    <div class="monitoring-stat-value"> 
        {{ $totalResponden ?? 0 }} 
    </div> 
</a>

    <a 
    href="{{ route('monitoring.index', array_merge(request()->except('page'), ['status' => 'not_processed'])) }}" 
    class="monitoring-stat-card monitoring-stat-card-link not-processed-card {{ ($statusFilter ?? 'all') === 'not_processed' ? 'active' : '' }}" 
> 
    <div class="monitoring-stat-label">Belum Didata</div> 
    <div class="monitoring-stat-value">{{ $belumDidata ?? 0 }}</div> 
</a>

    <a 
    href="{{ route('monitoring.index', array_merge(request()->except('page'), ['status' => 'draft'])) }}" 
    class="monitoring-stat-card monitoring-stat-card-link draft-card {{ ($statusFilter ?? 'all') === 'draft' ? 'active' : '' }}" 
> 
    <div class="monitoring-stat-label">Draft</div> 
    <div class="monitoring-stat-value">{{ $draftCount ?? 0 }}</div> 
</a>

  <a 
    href="{{ route('monitoring.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
    class="monitoring-stat-card monitoring-stat-card-link pending-card {{ ($statusFilter ?? 'all') === 'pending' ? 'active' : '' }}" 
> 
    <div class="monitoring-stat-label">Menunggu Verifikasi</div> 
    <div class="monitoring-stat-value">{{ $menungguVerifikasi ?? 0 }}</div> 
</a>

   <a 
    href="{{ route('monitoring.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}" 
    class="monitoring-stat-card monitoring-stat-card-link approved-card {{ ($statusFilter ?? 'all') === 'approved' ? 'active' : '' }}" 
> 
    <div class="monitoring-stat-label"> 
        Disetujui 
    </div> 

    <div class="monitoring-stat-value"> 
        {{ $disetujui ?? 0 }} 
    </div> 
</a>

    <a 
    href="{{ route('monitoring.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" 
    class="monitoring-stat-card monitoring-stat-card-link rejected-card {{ ($statusFilter ?? 'all') === 'rejected' ? 'active' : '' }}" 
> 
    <div class="monitoring-stat-label"> 
        Ditolak 
    </div> 

    <div class="monitoring-stat-value"> 
        {{ $ditolak ?? 0 }} 
    </div> 
</a>
           </div>
        </div>

        {{-- =====================================================
             DATA RESPONDEN
        ====================================================== --}}

       <div class="monitoring-table-section">

    <div class="monitoring-table-header">

        {{-- SEARCH --}}
        <form method="GET" action="{{ route('monitoring.index') }}" class="monitoring-filter-form">
            <div class="monitoring-filter-group monitoring-filter-search">
                <label class="monitoring-filter-label" for="monitoringSearch">Cari No. KK, NIK, atau Nama Kepala Keluarga</label>
                <div class="monitoring-search-box">
                    <input
                        type="search"
                        id="monitoringSearch"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Masukkan No. KK, NIK, atau nama kepala keluarga"
                        autocomplete="off"
                    >
                    <div class="monitoring-search-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                        </svg>
                    </div>
                </div>
                <div class="monitoring-search-suggestions" id="monitoringSearchSuggestions"></div>
            </div>

            <div class="monitoring-filter-group">
                <label class="monitoring-filter-label" for="monitoringKecamatan">Kecamatan</label>
                <select
                    class="monitoring-filter-select"
                    id="monitoringKecamatan"
                    name="kecamatan"
                    onchange="this.form.elements.kelurahan.value=''; this.form.submit()"
                >
                    <option value="">Semua Kecamatan</option>
                    @foreach ($kecamatanList as $kecamatan)
                        <option value="{{ $kecamatan['id'] }}" @selected(($filters['kecamatan'] ?? '') === $kecamatan['id'])>
                            {{ $kecamatan['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="monitoring-filter-group">
                <label class="monitoring-filter-label" for="monitoringKelurahan">Kelurahan</label>
                <select
                    class="monitoring-filter-select"
                    id="monitoringKelurahan"
                    name="kelurahan"
                    @disabled(empty($filters['kecamatan']))
                    onchange="this.form.submit()"
                >
                    <option value="">Semua Kelurahan</option>
                    @foreach ($kelurahanList as $kelurahan)
                        <option value="{{ $kelurahan['id'] }}" @selected(($filters['kelurahan'] ?? '') === $kelurahan['id'])>
                            {{ $kelurahan['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="monitoring-filter-group">
                <label class="monitoring-filter-label" for="monitoringStatus">Status</label>
                <select class="monitoring-filter-select" id="monitoringStatus" name="status" onchange="this.form.submit()">
                    <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>Semua Status</option>
                    <option value="not_processed" @selected(($filters['status'] ?? '') === 'not_processed')>Belum Didata</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Menunggu Verifikasi</option>
                    <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Ditolak</option>
                </select>
            </div>

            <button type="submit" class="monitoring-filter-submit">Terapkan</button>
            <a href="{{ route('monitoring.index') }}" class="monitoring-filter-reset">Reset Filter</a>
              
        
<div
    class="monitoring-search-info"
    id="monitoringSearchInfo"
    aria-live="polite"
    style="display: none;"
>
    Menampilkan <strong id="monitoringSearchCount">0</strong>
    data yang cocok dengan
    "<strong id="monitoringSearchKeyword"></strong>"
</div>
        
        </form>


        {{-- JUDUL --}}
        <div class="monitoring-table-title">
            Data Monitoring Responden
        </div>

        {{-- DESKRIPSI --}}
        <p class="monitoring-table-description">
            Daftar responden dari seluruh status pendataan dan verifikasi.
        </p>

    </div>


            {{-- =================================================
                 TABLE
            ================================================== --}}

            <div class="monitoring-table-wrapper">

                <table class="monitoring-table">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. KK</th>
                            <th>Periode</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>Wilayah</th>
                            <th>Status</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody id="monitoringTableBody">

                        @forelse($data as $index => $item)

                            @php
                                $itemId = data_get($item, 'id', $index);

                                $noKk = data_get($item, 'no_kk', '-');
                                $nik = data_get($item, 'nik', '-');
                                $nama = data_get($item, 'nama', '-');
                                $periode = data_get($item, 'periode', '-');
                                $periodeKode = data_get($item, 'periode_kode', '-');
                                $periodeTanggal = data_get($item, 'periode_tanggal', '-');
                                $wilayah = data_get($item, 'wilayah', '-');
                                $petugas = data_get($item, 'petugas', '-');
                                $jumlahAnggota = data_get($item, 'anggota', 0);
                                $tanggal = data_get($item, 'tanggal', '-');

                                $status = strtolower(
                                    (string) data_get($item, 'status', 'pending')
                                );

                                /*
                                 * NORMALISASI STATUS
                                 */
                                $statusClass = $status;
                                $statusLabel = data_get($item, 'status_label', match ($status) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'pending' => 'Menunggu Verifikasi',
                                    'not_processed' => 'Belum Didata',
                                    default => 'Draft',
                                });

                                $alasanPenolakan = trim((string) data_get($item, 'alasan_penolakan', ''));

                                /*
                                 * KUISIONER PART 1
                                 */
                                $kuisioner = data_get(
                                    $item,
                                    'kuisioner',
                                    []
                                );

                                $anggotaDetail = data_get(
                                    $item,
                                    'anggota_detail',
                                    data_get($item, 'anggota_data', [])
                                );
                            @endphp


                            <tr
                                data-search="{{ strtolower(
                                    $noKk . ' ' .
                                    $periode . ' ' .
                                    $nik . ' ' .
                                    $nama
                                ) }}"

                                data-no-kk="{{ strtolower($noKk) }}"

                                data-nik="{{ strtolower($nik) }}"

                                data-nama="{{ strtolower($nama) }}"
                            >

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $noKk }}
                                    </strong>
                                </td>

                                <td>{{ $periode }}</td>

                                <td>
                                    {{ $nama }}
                                </td>


                                <td>
                                    {{ $wilayah }}
                                </td>


                                <td>
                                    <span class="monitoring-status {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>


                                <td>
                                    {{ $petugas }}
                                </td>


                                <td>

                                    {{-- 
                                        JSON KUISIONER DIKIRIM
                                        MELALUI DATA ATTRIBUTE
                                    --}}
                                    <button
                                        type="button"
                                        class="monitoring-action-btn btn-detail-monitoring"

                                        data-id="{{ $itemId }}"

                                        data-no-kk="{{ $noKk }}"

                                        data-periode="{{ $periode }}"

                                        data-periode-kode="{{ $periodeKode }}"

                                        data-periode-tanggal="{{ $periodeTanggal }}"

                                        data-nik="{{ $nik }}"

                                        data-nama="{{ $nama }}"

                                        data-jumlah-anggota="{{ $jumlahAnggota }}"

                                        data-wilayah="{{ $wilayah }}"

                                        data-petugas="{{ $petugas }}"

                                        data-tanggal="{{ $tanggal }}"

                                        data-status="{{ $statusLabel }}"

                                        data-alasan-penolakan="{{ $alasanPenolakan }}"

                                        data-anggota-detail='@json($anggotaDetail)'

                                        data-kuisioner='@json($kuisioner)'
                                    >
                                        Detail
                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">

                                    <div class="monitoring-empty">

                                        <div class="monitoring-empty-icon">

                                            <svg
                                                width="25"
                                                height="25"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>

                                        </div>

                                        <div class="monitoring-empty-title">
                                            Belum Ada Data
                                        </div>

                                        <div class="monitoring-empty-text">
                                            Belum terdapat data responden yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>
                            </tr>

                        @endforelse


                        {{-- EMPTY HASIL SEARCH --}}

                        <tr
                            id="monitoringSearchEmpty"
                            style="display:none;"
                        >
                            <td colspan="8">

                                <div class="monitoring-empty">

                                    <div class="monitoring-empty-icon">

                                        <svg
                                            width="25"
                                            height="25"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <circle cx="11" cy="11" r="7"></circle>
                                            <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                                        </svg>

                                    </div>

                                    <div class="monitoring-empty-title">
                                        Data Tidak Ditemukan
                                    </div>

                                    <div class="monitoring-empty-text">
                                        Tidak ada data yang sesuai dengan pencarian.
                                    </div>

                                </div>

                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            @if ($data->hasPages())
                <div class="monitoring-pagination">
                    <div class="pagination-info">
                        Menampilkan
                        <strong>{{ $data->firstItem() }}</strong>
                        sampai
                        <strong>{{ $data->lastItem() }}</strong>
                        dari
                        <strong>{{ $data->total() }}</strong>
                        data
                    </div>

                    <div class="pagination">
                        @if($data->onFirstPage())
                            <span class="page-btn disabled">
                                ‹
                            </span>
                        @else
                            <a href="{{ $data->previousPageUrl() }}" class="page-btn">
                                ‹
                            </a>
                        @endif

                        @for($page = 1; $page <= $data->lastPage(); $page++)
                            @if($page == $data->currentPage())
                                <span class="page-btn active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $data->url($page) }}" class="page-btn">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if($data->hasMorePages())
                            <a href="{{ $data->nextPageUrl() }}" class="page-btn">
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

</div>


{{-- =========================================================
     MODAL DETAIL - READ ONLY
========================================================= --}}

<div
    class="monitoring-modal-overlay"
    id="monitoringDetailModal"
    aria-hidden="true"
>
    <div
        class="monitoring-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="monitoringModalTitle"
    >

        {{-- HEADER --}}
        <div class="monitoring-modal-header">

            <div class="monitoring-modal-header-left">

                <h2 id="monitoringModalTitle">
                    Detail Data Pendataan
                </h2>

                <p>
                    Periksa informasi responden dan jawaban kuisioner.
                </p>

            </div>

            <button
                type="button"
                class="monitoring-modal-close"
                id="closeMonitoringModal"
                aria-label="Tutup detail"
            >
                <svg
                    width="20"
                    height="20"
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


        {{-- BODY --}}
        <div class="monitoring-modal-body" id="monitoringModalBody">

            {{-- SUMMARY --}}
            <div class="monitoring-detail-summary">

                <div class="monitoring-detail-summary-card">
                    <div class="monitoring-detail-summary-label">
                        Nama Responden
                    </div>

                    <div
                        class="monitoring-detail-summary-value"
                        id="detailNamaSummary"
                    >
                        -
                    </div>
                </div>

                <div class="monitoring-detail-summary-card">
                    <div class="monitoring-detail-summary-label">
                        Nomor KK
                    </div>

                    <div
                        class="monitoring-detail-summary-value"
                        id="detailKkSummary"
                    >
                        -
                    </div>
                </div>

                <div class="monitoring-detail-summary-card">
                    <div class="monitoring-detail-summary-label">
                        Status Pendataan
                    </div>

                    <div
                        class="monitoring-detail-summary-value monitoring-summary-status"
                        id="detailStatusSummary"
                    >
                        -
                    </div>
                </div>

            </div>

            {{-- ALASAN PENOLAKAN: HANYA DITAMPILKAN JIKA STATUS DITOLAK --}}
            <section class="monitoring-rejection-reason" id="detailAlasanPenolakanCard" aria-live="polite">
                <h3 class="monitoring-rejection-reason-title">
                    
                    Alasan Data Ditolak !
                </h3>
                <p class="monitoring-rejection-reason-text" id="detailAlasanPenolakan">-</p>
            </section>

            {{-- INFORMASI RESPONDEN --}}
            <section class="monitoring-detail-section">

                <div class="monitoring-detail-section-title">
                    Informasi Responden
                </div>

                <div class="monitoring-detail-grid">

                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            No. KK
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailNoKk"
                        >
                            -
                        </div>
                    </div>

                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            NIK
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailNik"
                        >
                            -
                        </div>
                    </div>


                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Nama Kepala Keluarga
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailNama"
                        >
                            -
                        </div>
                    </div>


                    <div class="monitoring-detail-item monitoring-member-item">

                        <div class="monitoring-detail-label">
                            Jumlah Anggota Keluarga
                        </div>

                        <span
                            class="monitoring-detail-value"
                            id="detailJumlahAnggota"
                        >
                            0 Orang
                        </span>

                    </div>


                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Wilayah
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailWilayah"
                        >
                            -
                        </div>
                    </div>


                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Petugas
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailPetugas"
                        >
                            -
                        </div>
                    </div>


                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Tanggal Pendataan
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailTanggal"
                        >
                            -
                        </div>
                    </div>


                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Status Saat Ini
                        </div>

                        <div
                            class="monitoring-detail-value status-value"
                            id="detailStatus"
                        >
                            -
                        </div>
                    </div>

                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Periode Pendataan
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailPeriode"
                        >
                            -
                        </div>
                    </div>

                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Kode Periode
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailPeriodeKode"
                        >
                            -
                        </div>
                    </div>

                    <div class="monitoring-detail-item">
                        <div class="monitoring-detail-label">
                            Tanggal Periode
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailPeriodeTanggal"
                        >
                            -
                        </div>
                    </div>

                </div>

            </section>

            {{-- DETAIL ANGGOTA KELUARGA --}}
            <section class="monitoring-detail-section monitoring-members-section">

                <div class="monitoring-detail-section-title">
                    Anggota dalam Kartu Keluarga
                </div>

                <div class="monitoring-members-family-summary">
                    <div
                        class="monitoring-members-family-summary-label"
                    >
                        Kepala Keluarga
                    </div>

                    <div
                        class="monitoring-members-family-summary-name"
                        id="membersFamilyHead"
                    >
                        -
                    </div>

                    <div
                        class="monitoring-members-family-summary-count"
                        id="membersFamilyCount"
                    >
                        0 orang (termasuk kepala keluarga)
                    </div>
                </div>

                <div class="monitoring-members-table-wrap">
                    <table class="monitoring-members-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Status Keluarga</th>
                            </tr>
                        </thead>

                        <tbody id="monitoringMembersTableBody">
                            <tr>
                                <td colspan="4">
                                    <div class="monitoring-members-empty">
                                        <strong>Detail anggota belum tersedia</strong>
                                        <span>
                                            Data anggota keluarga untuk KK ini belum ditemukan.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>

            {{-- HASIL KUISIONER --}}
            <section class="monitoring-detail-section questionnaire-section">

                <div class="monitoring-questionnaire-heading">
                    <div>
                        <div class="monitoring-detail-section-title">
                            Hasil Kuisioner
                        </div>

                        <div class="monitoring-detail-section-subtitle">
                            Pilih bagian kuisioner di sebelah kiri untuk melihat pertanyaan dan jawaban responden.
                        </div>
                    </div>

                    <div class="monitoring-questionnaire-total" id="monitoringQuestionnaireTotal">
                        0 Part
                    </div>
                </div>

                <div
                    class="monitoring-questionnaire-wrapper"
                    id="monitoringQuestionnaireContent"
                >
                    <div class="monitoring-questionnaire-empty">
                        <strong>Belum Ada Hasil Kuisioner</strong>
                        <span>
                            Hasil kuisioner untuk responden ini belum tersedia.
                        </span>
                    </div>
                </div>

            </section>


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
       ELEMENT
    ===================================================== */

    const searchInput =
        document.getElementById('monitoringSearch');

    const suggestionsBox =
        document.getElementById('monitoringSearchSuggestions');

    const tableBody =
        document.getElementById('monitoringTableBody');

    const searchEmpty =
        document.getElementById('monitoringSearchEmpty');

    const modal =
        document.getElementById('monitoringDetailModal');

    const closeModalButton =
        document.getElementById('closeMonitoringModal');

    const questionnaireContent =
        document.getElementById('monitoringQuestionnaireContent');

    const questionnaireTotal =
        document.getElementById('monitoringQuestionnaireTotal');


    /* =====================================================
       SEARCH
    ===================================================== */

    function getMonitoringRows() {

        if (!tableBody) {
            return [];
        }

        return Array.from(
            tableBody.querySelectorAll('tr[data-search]')
        );
    }

    const monitoringSearchData =
        getMonitoringRows().map(function (row) {
            return {
                row: row,
                noKK: row.dataset.noKk || '',
                nik: row.dataset.nik || '',
                nama: row.dataset.nama || ''
            };
        });


    

function filterTable(keyword) {
    const search = String(keyword || '').trim().toLowerCase();
    let visibleCount = 0;

    monitoringSearchData.forEach(function (item) {
        const noKK = String(item.noKK || '').toLowerCase();
        const nik = String(item.nik || '').toLowerCase();
        const nama = String(item.nama || '').toLowerCase();

        const matches =
            search === '' ||
            noKK.includes(search) ||
            nik.includes(search) ||
            nama.includes(search);

        item.row.style.display = matches ? '' : 'none';

        if (matches) {
            visibleCount++;
        }
    });

    const searchInfo = document.getElementById('monitoringSearchInfo');
    const searchCount = document.getElementById('monitoringSearchCount');
    const searchKeyword = document.getElementById('monitoringSearchKeyword');

    if (searchInfo && searchCount && searchKeyword) {
        searchInfo.style.display = search ? 'block' : 'none';
        searchCount.textContent = visibleCount;
        searchKeyword.textContent = search;
    }

    if (searchEmpty) {
        searchEmpty.style.display =
            search !== '' && visibleCount === 0
                ? 'table-row'
                : 'none';
    }
}


    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    function showSuggestions(keyword) {

        if (!suggestionsBox) {
            return;
        }

        const search =
            String(keyword || '').trim().toLowerCase();

        suggestionsBox.innerHTML = '';

        if (!search) {

            suggestionsBox.classList.remove('show');

            return;
        }

        const matches =
            monitoringSearchData
                .filter(function (item) {
                    return (
                        item.nama.toLowerCase().includes(search) ||
                        item.noKK.toLowerCase().includes(search) ||
                        item.nik.toLowerCase().includes(search)
                    );
                })
                .slice(0, 5);


        if (matches.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'monitoring-suggestion-item';
            empty.textContent = 'Tidak ada data yang cocok.';
            suggestionsBox.appendChild(empty);
            suggestionsBox.classList.add('show');
            return;
        }


        matches.forEach(function (item) {

            const suggestion = document.createElement('button');
            suggestion.type = 'button';

            suggestion.className =
                'monitoring-suggestion-item';

            suggestion.innerHTML = `
                <div class="monitoring-suggestion-name">
                    ${escapeHtml(item.nama || '-')}
                </div>

                <div class="monitoring-suggestion-detail">
                    No. KK: ${escapeHtml(item.noKK || '-')}
                    &nbsp; | &nbsp;
                    NIK: ${escapeHtml(item.nik || '-')}
                </div>
            `;

            suggestion.addEventListener('click', function () {
                searchInput.value = item.nama || item.noKK || item.nik;
                filterTable(searchInput.value);
                suggestionsBox.classList.remove('show');
            });

            suggestionsBox.appendChild(suggestion);
        });


        suggestionsBox.classList.add('show');
    }


if (searchInput) {
    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim();

        // Perbarui isi tabel dan jumlah hasil.
        filterTable(keyword);

        // Perbarui saran setiap kali pengguna mengetik.
        if (keyword) {
            showSuggestions(keyword);
        } else if (suggestionsBox) {
            suggestionsBox.classList.remove('show');
            suggestionsBox.innerHTML = '';
        }
    });

    searchInput.addEventListener('focus', function () {
        const keyword = this.value.trim();

        if (keyword) {
            showSuggestions(keyword);
        }
    });

    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && suggestionsBox) {
            suggestionsBox.classList.remove('show');
            suggestionsBox.innerHTML = '';
        }
    });
}



    document.addEventListener('click', function (event) {

        if (
            suggestionsBox &&
            searchInput &&
            !searchInput.contains(event.target) &&
            !suggestionsBox.contains(event.target)
        ) {

            suggestionsBox.classList.remove('show');

        }

    });


    /* =====================================================
       QUESTIONNAIRE
    ===================================================== */

    function extractCoordinates(value) {

        if (Array.isArray(value) && value.length >= 2) {
            return extractCoordinates({
                latitude: value[0],
                longitude: value[1]
            });
        }

        if (value && typeof value === 'object') {

            const latitude =
                value.latitude ??
                value.lat;

            const longitude =
                value.longitude ??
                value.lng ??
                value.lon;

            if (
                latitude !== undefined &&
                latitude !== null &&
                latitude !== '' &&
                longitude !== undefined &&
                longitude !== null &&
                longitude !== '' &&
                Number.isFinite(Number(latitude)) &&
                Number.isFinite(Number(longitude))
            ) {
                const lat = Number(latitude);
                const lng = Number(longitude);

                if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    return { latitude: lat, longitude: lng };
                }
            }

            for (const key of ['coordinates', 'location', 'value', 'answer']) {
                if (value[key] !== undefined && value[key] !== value) {
                    const coordinates = extractCoordinates(value[key]);

                    if (coordinates) {
                        return coordinates;
                    }
                }
            }

            return null;
        }

        const text = String(value ?? '').trim();

        if (!text) {
            return null;
        }

        if (
            (text.startsWith('{') && text.endsWith('}')) ||
            (text.startsWith('[') && text.endsWith(']'))
        ) {
            try {
                const coordinates = extractCoordinates(JSON.parse(text));

                if (coordinates) {
                    return coordinates;
                }
            } catch (error) {
                // Try the supported plain-text coordinate formats next.
            }
        }

        const patterns = [
            /(?:lat(?:itude)?)[\s:=]+(-?\d+(?:\.\d+)?).*?(?:lng|lon|longitude)[\s:=]+(-?\d+(?:\.\d+)?)/i,
            /@(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)/,
            /(?:[?&]|\b)m?(?:lat|latitude)=(-?\d+(?:\.\d+)?).*?(?:[?&]|\b)m?(?:lng|lon|longitude)=(-?\d+(?:\.\d+)?)/i,
            /(-?\d+(?:\.\d+)?)\s*[,;]\s*(-?\d+(?:\.\d+)?)/
        ];

        for (const pattern of patterns) {
            const match = text.match(pattern);

            if (match) {
                const coordinates = extractCoordinates({
                    latitude: match[1],
                    longitude: match[2]
                });

                if (coordinates) {
                    return coordinates;
                }
            }
        }

        return null;
    }


    function normalizeQuestionImages(item, answer, type, question) {

        const candidates = [];

        function addCandidate(value, fallbackName) {

            if (Array.isArray(value)) {
                value.forEach(function (entry) {
                    addCandidate(entry, fallbackName);
                });

                return;
            }

            if (value && typeof value === 'object') {
                const url =
                    value.imageUrl ??
                    value.image_url ??
                    value.url ??
                    value.src ??
                    value.path ??
                    value.answer;

                if (url) {
                    addCandidate(
                        url,
                        value.imageName ??
                        value.image_name ??
                        value.name ??
                        fallbackName
                    );
                }

                return;
            }

            if (typeof value === 'string' && value.trim() !== '') {
                candidates.push({
                    url: value.trim(),
                    name: fallbackName || ''
                });
            }
        }

        addCandidate(
            item.imageUrls ??
            item.image_urls ??
            item.photos ??
            item.images,
            item.imageName ?? item.image_name
        );

        addCandidate(
            item.imageUrl ??
            item.image_url ??
            item.photoUrl ??
            item.photo_url ??
            item.url,
            item.imageName ?? item.image_name
        );

        if (
            ['image', 'foto', 'photo', 'gambar'].includes(type) ||
            /foto|photo|gambar/i.test(String(question)) ||
            /\.(?:jpe?g|png|gif|webp|bmp)(?:[?#].*)?$/i.test(String(answer ?? '').trim()) ||
            /^data:image\//i.test(String(answer ?? '').trim())
        ) {
            addCandidate(answer, item.imageName ?? item.image_name);
        }

        const unique = new Map();

        candidates.forEach(function (image) {
            if (!unique.has(image.url)) {
                unique.set(image.url, image);
            }
        });

        return Array.from(unique.values());
    }


    function isSafeImageUrl(value) {

        const url = String(value ?? '').trim();

        return (
            /^(https?:\/\/|\/|storage\/|public\/)/i.test(url) ||
            /^data:image\/(?:png|jpe?g|gif|webp);base64,/i.test(url)
        );
    }


    function normalizeQuestionnaire(raw) {

        if (!raw) {
            return [];
        }

        let data = raw;


        if (typeof data === 'string') {

            try {

                data = JSON.parse(data);

            } catch (error) {

                return [];

            }
        }


        if (
            data &&
            !Array.isArray(data) &&
            typeof data === 'object'
        ) {

            if (Array.isArray(data.parts)) {

                data = data.parts;

            } else if (Array.isArray(data.bagian)) {

                data = data.bagian;

            } else if (Array.isArray(data.questions)) {

                data = data.questions;

            } else if (Array.isArray(data.data)) {

                data = data.data;

            } else {

                data = Object.values(data);

            }
        }


        if (!Array.isArray(data)) {

            return [];

        }


        const result = [];


        data.forEach(function (item, index) {

            if (
                !item ||
                typeof item !== 'object'
            ) {
                return;
            }


            const nested =
                item.questions ??
                item.pertanyaan ??
                item.pertanyaan_list ??
                item.items ??
                item.answers;


            if (Array.isArray(nested)) {

                const rawPart =
                    item.part ??
                    item.part_number ??
                    item.number ??
                    item.bagian ??
                    item.section ??
                    item.section_number ??
                    (index + 1);
                const partMatch =
                    String(rawPart).match(/(?:part|bagian)?\s*(\d+)/i);
                const partNumber =
                    partMatch
                        ? parseInt(partMatch[1], 10)
                        : index + 1;
                

                const rawPartTitle =
    item.title ||
    item.judul ||
    item.nama ||
    item.part_title ||
    item.section_title ||
    (partMatch
        ? String(rawPart).replace(
            /^(?:part|bagian)?\s*\d+\s*[-:–—]?\s*/i,
            ''
        ).trim()
        : '');

const partTitle = String(rawPartTitle)
    .replace(/^(?:part|bagian)?\s*\d+\s*[-:–—]?\s*/i, '')
    .replace(/^[\s\-:–—]+/, '')
    .trim();

const partName = `Part ${partNumber}${partTitle ? ' — ' + partTitle : ''}`;



                if (Array.isArray(item.members)) {
                    result.push({
                        part: partName,
                        question: '',
                        answer: '',
                        members: item.members
                    });
                }


                nested.forEach(function (question, qIndex) {

                    result.push(
                        normalizeQuestion(
                            question,
                            partName,
                            qIndex
                        )
                    );

                });

                return;
            }


            result.push(
                normalizeQuestion(
                    item,
                    item.part || 'Part 1',
                    index
                )
            );

        });


        return result;
    }


    function normalizeQuestion(
        item,
        inheritedPart,
        index
    ) {

        const question =
            item.question ||
            item.pertanyaan ||
            item.question_text ||
            item.nama_pertanyaan ||
            item.text ||
            item.judul ||
            item.label ||
            ('Pertanyaan ' + (index + 1));


        let answerValue =
            item.answer ??
            item.jawaban ??
            item.response ??
            item.nilai ??
            item.value ??
            item.hasil ??
            '';
        const rawAnswer = answerValue;

        const type =
            String(
                item.type ??
                item.answer_type ??
                item.input_type ??
                ''
            ).toLowerCase();

        if (Array.isArray(answerValue)) {

            answerValue =
                answerValue.join(', ');

        } else if (
            answerValue &&
            typeof answerValue === 'object'
        ) {

            answerValue =
                answerValue.label ??
                answerValue.nama ??
                answerValue.value ??
                JSON.stringify(answerValue);

        }

        const isMapQuestion =
            type === 'map' ||
            /geotagging|geotag|titik lokasi|lokasi tempat tinggal|koordinat|latitude|longitude/i.test(String(question));

        const directCoordinates = extractCoordinates({
            latitude: item.latitude ?? item.lat,
            longitude: item.longitude ?? item.lng ?? item.lon
        });

        const coordinates =
            directCoordinates ??
            extractCoordinates(item.coordinates ?? item.location) ??
            extractCoordinates(item.answer ?? item.jawaban ?? item.value);

        const part =
            item.part ||
            item.bagian ||
            item.section ||
            item.part_name ||
            inheritedPart ||
            'Part 1';


        return {
            part: String(part),
            number: Number(item.number ?? item.nomor ?? item.no ?? item.urutan ?? index + 1),
            question: String(question),
            answer: String(answerValue ?? ''),
            reason: String(item.reason ?? item.alasan ?? '').trim(),
            type: isMapQuestion ? 'map' : type,
            latitude: coordinates?.latitude ?? null,
            longitude: coordinates?.longitude ?? null,
            images: normalizeQuestionImages(
                item,
                rawAnswer,
                type,
                question
            )
        };
    }


    function renderPart1MapAnswer(item, answerElement) {
        const rawCoordinates = String(item.answer ?? '').trim();
        const coordinateValue =
            !rawCoordinates || rawCoordinates === '-' || rawCoordinates.toLowerCase() === 'null'
                ? 'Belum tersedia'
                : rawCoordinates;

        answerElement.classList.add('is-visual');

        const mapWrapper = document.createElement('div');
        mapWrapper.className = 'questionnaire-answer-map';

        const coordinateLabel = document.createElement('div');
        coordinateLabel.className = 'questionnaire-map-coordinates';

        const coordinateTitle = document.createElement('strong');
        coordinateTitle.textContent = 'Koordinat lokasi:';

        const coordinateText = document.createElement('span');
        coordinateText.textContent = coordinateValue;

        coordinateLabel.append(coordinateTitle, coordinateText);
        mapWrapper.appendChild(coordinateLabel);
        answerElement.appendChild(mapWrapper);

        const hasValidCoordinates =
            Number.isFinite(item.latitude) &&
            Number.isFinite(item.longitude) &&
            item.latitude >= -90 &&
            item.latitude <= 90 &&
            item.longitude >= -180 &&
            item.longitude <= 180;

        if (!hasValidCoordinates || !window.L) {
            const emptyMap = document.createElement('div');
            emptyMap.className = 'questionnaire-map-empty';
            emptyMap.textContent = hasValidCoordinates
                ? 'Peta tidak dapat dimuat. Pastikan koneksi internet tersedia.'
                : 'Titik lokasi belum tersedia atau koordinat tidak valid.';
            mapWrapper.appendChild(emptyMap);

            return;
        }

        const latitude = item.latitude;
        const longitude = item.longitude;
        const mapId =
            'monitoring-part1-map-' +
            Date.now() +
            '-' +
            Math.random().toString(36).slice(2, 8);

        const mapContainer = document.createElement('div');
        mapContainer.className = 'questionnaire-map-container';
        mapContainer.id = mapId;
        mapWrapper.appendChild(mapContainer);

        const mapLink = document.createElement('a');
        mapLink.className = 'questionnaire-map-link';
        mapLink.href =
            'https://www.openstreetmap.org/?mlat=' +
            encodeURIComponent(latitude) +
            '&mlon=' +
            encodeURIComponent(longitude) +
            '#map=18/' +
            encodeURIComponent(latitude) +
            '/' +
            encodeURIComponent(longitude);
        mapLink.target = '_blank';
        mapLink.rel = 'noopener noreferrer';
        mapLink.textContent = 'Buka lokasi di OpenStreetMap';
        mapWrapper.appendChild(mapLink);

        window.setTimeout(function () {
            if (!document.getElementById(mapId)) {
                return;
            }

            const map = window.L.map(mapContainer, {
                scrollWheelZoom: false,
                attributionControl: true
            }).setView([latitude, longitude], 16);

            mapContainer._leaflet_map = map;

            window.L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }
            ).addTo(map);

            window.L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup('Titik Lokasi Responden<br>' + escapeHtml(rawCoordinates))
                .openPopup();

            window.setTimeout(function () {
                map.invalidateSize();
            }, 100);
        }, 50);
    }


    function renderQuestionAnswer(item, answerElement, usePart1MapStyle = false) {
        if (item.type === 'map' && usePart1MapStyle) {
            renderPart1MapAnswer(item, answerElement);

            return;
        }

        if (
            item.type === 'map' &&
            Number.isFinite(item.latitude) &&
            Number.isFinite(item.longitude)
        ) {
            const latitude = item.latitude;
            const longitude = item.longitude;
            const coordinateText =
                latitude + ', ' + longitude;
            const mapId =
                'monitoring-map-' +
                Date.now() +
                '-' +
                Math.random().toString(36).slice(2, 8);
            const mapUrl =
                'https://www.openstreetmap.org/?mlat=' +
                encodeURIComponent(latitude) +
                '&mlon=' +
                encodeURIComponent(longitude) +
                '#map=17/' +
                encodeURIComponent(latitude) +
                '/' +
                encodeURIComponent(longitude);

            answerElement.classList.add('is-visual');
            answerElement.innerHTML = `
                <div class="monitoring-map" id="${mapId}"></div>
                <div class="monitoring-map-coordinates">
                    Koordinat: ${escapeHtml(coordinateText)}
                </div>
                <a
                    class="monitoring-map-link"
                    href="${escapeHtml(mapUrl)}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Buka lokasi di OpenStreetMap
                </a>
            `;

            if (window.L) {
                const mapElement =
                    document.getElementById(mapId);

                const map =
                    window.L.map(mapElement, {
                        scrollWheelZoom: false
                    }).setView([latitude, longitude], 17);

                window.L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }
                ).addTo(map);

                const marker =
                    window.L.marker([latitude, longitude]).addTo(map);

                const popup =
                    document.createElement('div');

                popup.textContent =
                    'Titik Lokasi Responden: ' + coordinateText;

                marker.bindPopup(popup);

                window.setTimeout(function () {
                    map.invalidateSize();
                }, 100);
            } else {
                answerElement.insertAdjacentHTML(
                    'afterbegin',
                    '<div class="monitoring-map-coordinates">Peta tidak dapat dimuat saat ini.</div>'
                );
            }

            return;
        }

        const images =
            item.images.filter(function (image) {
                return isSafeImageUrl(image.url);
            });

        if (images.length > 0) {
            answerElement.classList.add('is-visual');
            answerElement.innerHTML = `
                <div class="monitoring-images">
                    ${images.map(function (image, index) {
                        const imageName =
                            image.name ||
                            ('Foto ' + (index + 1));
                        const url =
                            escapeHtml(image.url);
                        const isDataImage =
                            /^data:image\//i.test(image.url);

                        return `
                            <div class="monitoring-image-card">
                                ${isDataImage
                                    ? `<img class="monitoring-image" src="${url}" alt="${escapeHtml(imageName)}" loading="lazy">`
                                    : `<a class="monitoring-image-link" href="${url}" target="_blank" rel="noopener noreferrer"><img class="monitoring-image" src="${url}" alt="${escapeHtml(imageName)}" loading="lazy"></a>`
                                }
                                <div class="monitoring-image-name">${escapeHtml(imageName)}</div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;

            return;
        }

        answerElement.textContent =
            item.answer.trim() === ''
                ? 'Belum diisi'
                : item.answer;

        if (item.answer.trim() === '') {
            answerElement.classList.add('is-empty');
        }
    }


    function getPartNumber(
        partName,
        fallbackIndex
    ) {

        const match =
            String(partName).match(
                /^(?:(?:part|bagian)\s*)?(\d+)\b/i
            );


        return match
            ? parseInt(match[1], 10)
            : fallbackIndex + 1;
    }

    function renderPart5Members(members, contentBody) {

        const layout = document.createElement('div');
        layout.className = 'monitoring-questionnaire-members-layout';

        const membersPanel = document.createElement('div');
        membersPanel.className = 'monitoring-questionnaire-members-panel';
        membersPanel.innerHTML = `
            <div class="monitoring-questionnaire-members-heading">
                <strong>Anggota Keluarga</strong>
                <span>Pilih anggota untuk melihat detail jawaban.</span>
            </div>
            <div class="monitoring-questionnaire-members-list"></div>
        `;

        const memberList =
            membersPanel.querySelector('.monitoring-questionnaire-members-list');

        const answersPanel = document.createElement('div');
        answersPanel.className = 'monitoring-questionnaire-member-answers';
        answersPanel.innerHTML = `
            <div class="monitoring-questionnaire-members-heading">
                <strong>Hasil Jawaban Anggota</strong>
                <span>Jawaban kuisioner Part 5.</span>
            </div>
            <div class="monitoring-questionnaire-member-answers-body">
                <div class="monitoring-questionnaire-placeholder">
                    <strong>Pilih Anggota</strong>
                    <span>Pilih anggota keluarga untuk melihat pertanyaan dan jawabannya.</span>
                </div>
            </div>
        `;

        const answersBody =
            answersPanel.querySelector('.monitoring-questionnaire-member-answers-body');

        members.forEach(function (member) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'monitoring-questionnaire-member-card';

            const name = String(member.nama || member.nama_lengkap || '-');
            const relation = String(member.status_keluarga || member.status || '-');
            const nik = String(member.nik || '-');
            const initial = name.trim() ? name.trim().charAt(0).toUpperCase() : '?';

            button.innerHTML = `
                <span class="monitoring-questionnaire-member-avatar">${escapeHtml(initial)}</span>
                <span class="monitoring-questionnaire-member-info">
                    <strong>${escapeHtml(name)}</strong>
                    <span>${escapeHtml(relation)}</span>
                    <small>NIK: ${escapeHtml(nik)}</small>
                </span>
            `;

            button.addEventListener('click', function () {
                memberList.querySelectorAll('.monitoring-questionnaire-member-card')
                    .forEach(function (otherButton) {
                        otherButton.classList.remove('is-selected');
                    });
                button.classList.add('is-selected');

                const questions = Array.isArray(member.questions)
                    ? member.questions
                    : [];
                const usableQuestions = questions.filter(function (question) {
                    const questionText = String(
                        question.question ||
                        question.pertanyaan ||
                        question.text ||
                        question.label ||
                        ''
                    ).trim().toLowerCase();

                    return questionText !== 'data part 5';
                });

                answersBody.innerHTML = '';

                const memberHeading = document.createElement('div');
                memberHeading.className = 'monitoring-questionnaire-member-summary';
                memberHeading.textContent = `${name} · NIK: ${nik} · ${relation}`;
                answersBody.appendChild(memberHeading);

                if (usableQuestions.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'monitoring-questionnaire-placeholder';
                    empty.innerHTML = '<strong>Jawaban Belum Tersedia</strong><span>Belum ada jawaban kuisioner untuk anggota ini.</span>';
                    answersBody.appendChild(empty);
                    return;
                }

                usableQuestions.forEach(function (question, questionIndex) {
                    const questionItem = normalizeQuestion(
                        question,
                        'Part 5',
                        questionIndex
                    );
                    const questionCard = document.createElement('div');
                    questionCard.className = 'monitoring-question-item';

                    const number = document.createElement('div');
                    number.className = 'monitoring-question-number';
                   number.textContent = `Pertanyaan ${questionIndex + 1}`;

                    const text = document.createElement('div');
                    text.className = 'monitoring-question-text';
                    text.textContent = cleanPart1ResultLabel(questionItem.question);

                    const label = document.createElement('div');
                    label.className = 'monitoring-answer-label';
                    label.textContent = 'Jawaban';

                    const answer = document.createElement('div');
                    answer.className = 'monitoring-answer-value';

                    questionCard.append(number, text, label, answer);
                    answersBody.appendChild(questionCard);
                    renderQuestionAnswer(questionItem, answer);
                });
            });

            memberList.appendChild(button);
        });

        if (members.length === 0) {
            memberList.innerHTML = `
                <div class="monitoring-questionnaire-placeholder">
                    <strong>Belum Ada Anggota Keluarga</strong>
                    <span>Data jawaban anggota keluarga belum tersedia.</span>
                </div>
            `;
        }

        layout.append(membersPanel, answersPanel);
        contentBody.appendChild(layout);
    }


    function renderQuestionnaire(raw) {

        if (!questionnaireContent) {
            return;
        }

        const questions = normalizeQuestionnaire(raw);

        if (questions.length === 0) {

            questionnaireContent.innerHTML = `
                <div class="monitoring-questionnaire-placeholder">
                    <strong>Belum Ada Hasil Kuisioner</strong>
                    <span>
                        Hasil kuisioner untuk responden ini belum tersedia.
                    </span>
                </div>
            `;

            if (questionnaireTotal) {
                questionnaireTotal.textContent = '0 Part';
            }

            return;
        }

        const grouped = {};

        questions.forEach(function (question) {

            if (!grouped[question.part]) {
                grouped[question.part] = [];
            }

            grouped[question.part].push(question);

        });

        const sortedParts =
            Object.keys(grouped).sort(function (a, b) {

                return (
                    getPartNumber(a, 0) -
                    getPartNumber(b, 0)
                );

            });

        if (questionnaireTotal) {
            questionnaireTotal.textContent = sortedParts.length + ' Part';
        }

        /*
         * MASTER DETAIL:
         * - KIRI = daftar part
         * - KANAN = isi part terpilih
         * - SAAT AWAL TIDAK ADA PART YANG TERBUKA
         */
        questionnaireContent.innerHTML = `
            <div class="monitoring-questionnaire-master">

                <div class="monitoring-questionnaire-sidebar">

                    <div class="monitoring-questionnaire-sidebar-title">
                        Bagian Kuisioner
                    </div>

                    ${
                        sortedParts.map(function (partName, partIndex) {

                            const items = grouped[partName];
                            const memberPart =
                                items.find(function (item) {
                                    return Array.isArray(item.members);
                                });

                            const answered =
                                items.filter(function (item) {
                                    return item.answer.trim() !== '';
                                }).length;

                            const partNumber =
                                getPartNumber(partName, partIndex);

                                                        
                                let cleanTitle = String(partName)
                                    .replace(
                                        /^part\s*\d+\s*[-:–]?\s*/i,
                                        ''
                                    )
                                    .replace(/^[\s\-:–—]+/, '')
                                    .trim();

                                if (
                                    cleanTitle.toLowerCase() ===
                                    ('part ' + partNumber).toLowerCase()
                                ) {
                                    cleanTitle = '';
                                }


                            return `
                                <div class="monitoring-questionnaire-part">

                                    <button
                                        type="button"
                                        class="monitoring-questionnaire-part-header"
                                        data-part-index="${partIndex}"
                                        aria-expanded="false"
                                    >

                                        <div class="monitoring-questionnaire-part-left">

                                            <span class="monitoring-questionnaire-part-number">
                                                ${partNumber}
                                            </span>

                                            <div>
                                                <div class="monitoring-questionnaire-part-title">
                                                    ${escapeHtml(
                                                        cleanTitle ||
                                                        ('Part ' + partNumber)
                                                    )}
                                                </div>

                                                <div class="monitoring-questionnaire-part-meta">
                                                    ${memberPart
                                                        ? memberPart.members.length + ' anggota keluarga'
                                                        : (partNumber === 1
                                                            ? items.filter(function (item) {
                                                                return item.type !== 'map';
                                                            }).length
                                                            : items.length) + ' pertanyaan'}
                                                </div>
                                            </div>

                                        </div>

                                        <span class="monitoring-questionnaire-part-arrow">
                                            <svg
                                                width="15"
                                                height="15"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>

                                    </button>

                                </div>
                            `;

                        }).join('')
                    }

                </div>


                <div class="monitoring-questionnaire-content-panel">

                    <div class="monitoring-questionnaire-content-header">

                        <div class="monitoring-questionnaire-content-eyebrow">
                            Bagian Terpilih
                        </div>

                        <h3
                            class="monitoring-questionnaire-content-title"
                            id="monitoringSelectedPartTitle"
                        >
                            Pilih Bagian Kuisioner
                        </h3>

                    </div>

                    <div
                        class="monitoring-questionnaire-content-body"
                        id="monitoringSelectedPartBody"
                    >

                        <div class="monitoring-questionnaire-placeholder">

                            <strong>
                                Belum Ada Bagian Dipilih
                            </strong>

                            <span>
                                Pilih salah satu bagian kuisioner di sebelah kiri
                                untuk melihat pertanyaan dan jawaban responden.
                            </span>

                        </div>

                    </div>

                </div>

            </div>
        `;

        const partButtons =
            questionnaireContent.querySelectorAll(
                '.monitoring-questionnaire-part-header'
            );

        const selectedTitle =
            questionnaireContent.querySelector(
                '#monitoringSelectedPartTitle'
            );

        const selectedBody =
            questionnaireContent.querySelector(
                '#monitoringSelectedPartBody'
            );

        partButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                const index =
                    Number(this.dataset.partIndex);

                const wasActive =
                    this.classList.contains('active');

                partButtons.forEach(function (other) {

                    other.classList.remove('active');

                    other.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                });

                if (wasActive) {

                    selectedTitle.textContent =
                        'Pilih Bagian Kuisioner';

                    selectedBody.classList.remove('monitoring-questionnaire-part1');

                    selectedBody.innerHTML = `
                        <div class="monitoring-questionnaire-placeholder">
                            <strong>
                                Belum Ada Bagian Dipilih
                            </strong>

                            <span>
                                Pilih salah satu bagian kuisioner di sebelah kiri
                                untuk melihat pertanyaan dan jawaban responden.
                            </span>
                        </div>
                    `;

                    return;
                }

                this.classList.add('active');

                this.setAttribute(
                    'aria-expanded',
                    'true'
                );

                const partName =
                    sortedParts[index];

                const items =
                    grouped[partName];
                const memberPart =
                    items.find(function (item) {
                        return Array.isArray(item.members);
                    });

                const partNumber =
                    getPartNumber(partName, index);

                selectedBody.classList.toggle(
                    'monitoring-questionnaire-part1',
                    partNumber === 1
                );

                let cleanTitle =
                    String(partName)
                        .replace(
                            /^part\s*\d+\s*[-:–]?\s*/i,
                            ''
                        );

                if (
                    cleanTitle.toLowerCase() ===
                    ('part ' + partNumber).toLowerCase()
                ) {
                    cleanTitle = '';
                }

                selectedTitle.textContent =
                    cleanTitle
                        ? 'Part ' +
                          partNumber +
                          ' — ' +
                          cleanTitle
                        : 'Part ' + partNumber;

                if (memberPart) {
                    selectedBody.innerHTML = '';
                    renderPart5Members(memberPart.members, selectedBody);
                    selectedBody.scrollTop = 0;
                    return;
                }

                selectedBody.innerHTML =
                    items.map(function (item, qIndex) {
                        const isLocation =
                            String(item.type || '').toLowerCase() === 'map';
                        const answerText =
                            String(item.answer || '').trim();
                        const showAddressReason =
                            partNumber === 1 &&
                            answerText.toLowerCase() === 'tidak' &&
                            String(item.reason || '').trim() !== '';

                        return `
                            <div class="monitoring-question-item">

                                <div class="monitoring-question-number">
                                    ${isLocation
                                        ? 'LOKASI RUMAH'
                                        : 'PERTANYAAN ' + (item.number || qIndex + 1)}
                                </div>

                                <div class="monitoring-question-text">
                                    ${escapeHtml(
                                        partNumber === 1
                                            ? cleanPart1ResultLabel(item.question)
                                            : item.question
                                    )}
                                </div>

                                <div class="monitoring-answer-label">
                                    ${isLocation ? 'Koordinat dan peta' : 'Jawaban Responden'}
                                </div>

                                <div
                                    class="monitoring-answer-value"
                                    data-answer-index="${qIndex}"
                                >
                                </div>

                                ${showAddressReason
                                    ? `
                                        <div class="monitoring-answer-label monitoring-address-reason-label">
                                            Alasan alamat tidak sesuai
                                        </div>
                                        <div class="monitoring-answer-value monitoring-address-reason">
                                            ${escapeHtml(item.reason)}
                                        </div>
                                    `
                                    : ''}

                            </div>
                        `;

                    }).join('');

                selectedBody
                    .querySelectorAll('[data-answer-index]')
                    .forEach(function (answerElement) {
                        const answerIndex =
                            Number(answerElement.dataset.answerIndex);

                        renderQuestionAnswer(
                            items[answerIndex],
                            answerElement,
                            partNumber === 1
                        );
                    });

                selectedBody.scrollTop = 0;

            });

        });

    }


    /* =====================================================
       OPEN DETAIL
    ===================================================== */

    function normalizeMembers(raw) {

        if (!raw) {
            return [];
        }

        let data = raw;

        if (typeof data === 'string') {

            try {
                data = JSON.parse(data);
            } catch (error) {
                return [];
            }

        }

        if (!Array.isArray(data) && data && typeof data === 'object') {

            if (Array.isArray(data.data)) {
                data = data.data;
            } else if (Array.isArray(data.anggota)) {
                data = data.anggota;
            } else if (Array.isArray(data.members)) {
                data = data.members;
            } else {
                data = Object.values(data);
            }

        }

        return Array.isArray(data) ? data : [];
    }


    function getMemberValue(member, keys, fallback = '-') {

        if (!member || typeof member !== 'object') {
            return fallback;
        }

        for (const key of keys) {

            if (
                member[key] !== undefined &&
                member[key] !== null &&
                String(member[key]).trim() !== ''
            ) {
                return member[key];
            }

        }

        return fallback;
    }


    function renderMembers(rawMembers, headName, count) {

        const tbody =
            document.getElementById(
                'monitoringMembersTableBody'
            );

        const familyHead =
            document.getElementById(
                'membersFamilyHead'
            );

        const familyCount =
            document.getElementById(
                'membersFamilyCount'
            );

        if (!tbody) {
            return;
        }

        const members =
            normalizeMembers(rawMembers);

        if (familyHead) {
            familyHead.textContent =
                headName || '-';
        }

        if (familyCount) {
            familyCount.textContent =
                (count || members.length || 0) +
                ' orang (termasuk kepala keluarga)';
        }

        if (members.length === 0) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="4">
                        <div class="monitoring-members-empty">
                            <strong>Detail anggota belum tersedia</strong>
                            <span>
                                Data anggota keluarga untuk KK ini belum ditemukan.
                            </span>
                        </div>
                    </td>
                </tr>
            `;

            return;
        }

        tbody.innerHTML =
            members.map(function (member, index) {

                const nik = getMemberValue(
                    member,
                    ['nik', 'NIK', 'no_nik']
                );
                const name = getMemberValue(
                    member,
                    ['nama_lengkap', 'nama', 'nama_anggota', 'name']
                );
                const status = getMemberValue(
                    member,
                    ['status_keluarga', 'status', 'hubungan', 'hubungan_keluarga']
                );

                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(nik)}</td>
                        <td>${escapeHtml(name)}</td>
                        <td>${escapeHtml(status)}</td>
                    </tr>
                `;

            }).join('');

    }


    function setMonitoringStatus(element, status) {

        const value =
            String(status || '').trim();

        const normalized =
            value.toLowerCase();

        const statusClasses = [
            'status-approved',
            'status-rejected',
            'status-pending',
            'status-draft',
            'status-not-processed'
        ];

        const statusClass =
            normalized === 'approved' ||
            normalized.includes('disetujui')
                ? 'status-approved'
                : normalized === 'rejected' ||
                    normalized === 'reject' ||
                    normalized.includes('ditolak')
                    ? 'status-rejected'
                    : normalized === 'pending' ||
                        normalized.includes('menunggu')
                        ? 'status-pending'
                        : normalized.includes('belum didata') ||
                            normalized.includes('belum diproses')
                            ? 'status-not-processed'
                        : normalized === 'draft'
                            ? 'status-draft'
                            : '';

        element.textContent =
            value || '-';

        element.classList.remove(...statusClasses);

        if (statusClass) {
            element.classList.add(statusClass);
        }
    }


    function openDetailModal(button) {

        document.getElementById(
            'detailNamaSummary'
        ).textContent =
            button.dataset.nama || '-';


        document.getElementById(
            'detailKkSummary'
        ).textContent =
            button.dataset.noKk || '-';


        setMonitoringStatus(
            document.getElementById('detailStatusSummary'),
            button.dataset.status
        );

        // Alasan penolakan hanya ditampilkan untuk responden berstatus Ditolak.
        const rejectionReasonCard = document.getElementById('detailAlasanPenolakanCard');
        const rejectionReasonText = document.getElementById('detailAlasanPenolakan');
        const currentStatus = String(button.dataset.status || '').trim().toLowerCase();
        const rejectionReason = String(button.dataset.alasanPenolakan || '').trim();
        const isRejected = currentStatus === 'rejected'
            || currentStatus === 'reject'
            || currentStatus.includes('ditolak');

        if (rejectionReasonCard && rejectionReasonText) {
            rejectionReasonText.textContent = rejectionReason || 'Alasan penolakan belum tersedia.';
            rejectionReasonCard.classList.toggle('show', isRejected);
        }

        document.getElementById(
            'detailNoKk'
        ).textContent =
            button.dataset.noKk || '-';

        document.getElementById(
            'detailPeriode'
        ).textContent =
            button.dataset.periode || '-';

        document.getElementById('detailPeriodeKode').textContent =
            button.dataset.periodeKode || '-';

        document.getElementById('detailPeriodeTanggal').textContent =
            button.dataset.periodeTanggal || '-';


        document.getElementById(
            'detailNik'
        ).textContent =
            button.dataset.nik || '-';


        document.getElementById(
            'detailNama'
        ).textContent =
            button.dataset.nama || '-';


        const jumlahAnggota =
            Number(button.dataset.jumlahAnggota || 0);

        document.getElementById(
            'detailJumlahAnggota'
        ).textContent =
            jumlahAnggota + ' Orang';


        document.getElementById(
            'detailWilayah'
        ).textContent =
            button.dataset.wilayah || '-';


        document.getElementById(
            'detailPetugas'
        ).textContent =
            button.dataset.petugas || '-';


        document.getElementById(
            'detailTanggal'
        ).textContent =
            button.dataset.tanggal || '-';


        setMonitoringStatus(
            document.getElementById('detailStatus'),
            button.dataset.status
        );


        /* DETAIL ANGGOTA */
        renderMembers(
            button.dataset.anggotaDetail || '[]',
            button.dataset.nama || '-',
            jumlahAnggota
        );


        /* KUISIONER */
        renderQuestionnaire(
            button.dataset.kuisioner || '[]'
        );


        modal.classList.add('active');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';

        if (!history.state || history.state.monitoringDetail !== true) {
            history.pushState({ monitoringDetail: true }, '', window.location.href);
        }

    }


    /* =====================================================
       TOMBOL DETAIL
    ===================================================== */

    document
        .querySelectorAll(
            '.btn-detail-monitoring'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    openDetailModal(this);

                }
            );

        });


    /* =====================================================
       CLOSE MODAL
    ===================================================== */

    function closeDetailModal(skipHistory) {

        if (!modal) {
            return;
        }

        modal.classList.remove('active');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow = '';

        if (!skipHistory && history.state && history.state.monitoringDetail === true) {
            history.back();
        }

    }


    if (closeModalButton) {

        closeModalButton.addEventListener(
            'click',
            function () { closeDetailModal(false); }
        );

    }


    if (modal) {

        modal.addEventListener(
            'click',
            function (event) {

                if (event.target === modal) {

                    closeDetailModal(false);

                }

            }
        );

    }

    window.addEventListener('popstate', function () {
        if (modal && modal.classList.contains('active')) {
            closeDetailModal(true);
        }
    });


    window.addEventListener('popstate', function () {
        if (modal && modal.classList.contains('active')) {
            closeDetailModal(true);
        }
    });

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                modal &&
                modal.classList.contains('active')
            ) {

                closeDetailModal(false);

            }

        }
    );

});

</script>

@endsection