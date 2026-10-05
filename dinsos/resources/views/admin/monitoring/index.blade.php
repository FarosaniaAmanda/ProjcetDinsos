@extends('admin.layouts.app')

@section('title', 'Monitoring Pendataan')

@section('content')

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
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(37, 42, 134, 0.06);
        overflow: hidden;
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
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }

    .monitoring-stat-card {
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 14px;
        padding: 17px 18px;
        min-height: 86px;
        box-shadow: 0 2px 8px rgba(37, 42, 134, 0.04);
        transition: .2s ease;
        box-sizing: border-box;
    }

    .monitoring-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(37, 42, 134, 0.08);
    }

    .monitoring-stat-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .monitoring-stat-value {
        color: #252A86;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 700;
    }


    /* =====================================================
       TABLE SECTION
    ===================================================== */

    .monitoring-table-header {
        padding: 22px 26px 20px;
        border-bottom: 1px solid #e8ebf3;

        /* Search berada di atas judul */
        display: flex;
        flex-direction: column;
    }


    /* =====================================================
       SEARCH
       SEARCH DI ATAS JUDUL
    ===================================================== */

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

    .monitoring-suggestion-item {
        padding: 12px 15px;

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
        min-width: 850px;
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
        max-height: calc(100vh - 20px);
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 25px 70px rgba(15, 23, 42, 0.25);
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
        padding: 20px 26px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        background: #ffffff;
    }

    .monitoring-modal-header-left h2 {
        margin: 0;
        font-size: 25px;
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
        padding: 22px 26px 28px;
        overflow-y: auto;
        max-height: calc(100vh - 105px);
    }

    /* =====================================================
       DETAIL SUMMARY
    ===================================================== */

    .monitoring-detail-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .monitoring-detail-summary-card {
        border: 1px solid #e2e6f2;
        border-radius: 12px;
        padding: 15px;
        background: #f8f9ff;
    }

    .monitoring-detail-summary-label {
        color: #64748b;
        font-size: 11px;
        margin-bottom: 6px;
    }

    .monitoring-detail-summary-value {
        color: #263238;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }

    /* =====================================================
       DETAIL SECTION - GAYA VERIFIKASI
    ===================================================== */

    .monitoring-detail-section {
        border: 1px solid #e2e6f2;
        border-radius: 14px;
        overflow: visible;
        margin-bottom: 20px;
        background: #ffffff;
    }

    .monitoring-detail-section:last-child {
        margin-bottom: 0;
    }

    .monitoring-detail-section-title {
        padding: 16px 20px 8px;
        background: #ffffff;
        color: #252A86;
        font-size: 18px;
        font-weight: 700;
    }

    .monitoring-detail-section-subtitle {
        padding: 0 20px 14px;
        color: #7b7f87;
        font-size: 13px;
        line-height: 1.55;
    }

    .monitoring-detail-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        padding: 0 20px 20px;
    }

    .monitoring-detail-item {
        position: relative;
        padding: 14px 15px;
        min-height: 84px;
        border: 1px solid #e5e8ef;
        border-radius: 11px;
        background: #fbfcfe;
        box-sizing: border-box;
    }

    .monitoring-detail-label {
        color: #6b7280;
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 7px;
        font-weight: 600;
    }

    .monitoring-detail-value {
        color: #263238;
        font-size: 14px;
        line-height: 1.45;
        font-weight: 700;
        word-break: break-word;
    }

    .monitoring-detail-value.status-value {
        color: #b42318;
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
       MUNCUL MENGAMBANG DI ATAS DETAIL
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
       HASIL KUISIONER - MASTER DETAIL SEPERTI VERIFIKASI
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
        border-radius: 8px;
        background: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        transition: transform .2s ease;
    }

    .monitoring-questionnaire-part-header.active
    .monitoring-questionnaire-part-arrow {
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
            padding: 22px 24px 35px;
        }

        .monitoring-stats {
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

        .monitoring-table-header {
            padding: 17px;
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
   Rapikan mobile tanpa mengubah struktur desktop utama
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

                <div class="monitoring-stat-card">
                    <div class="monitoring-stat-label">
                        Total Responden
                    </div>

                    <div class="monitoring-stat-value">
                        {{ $totalResponden ?? 0 }}
                    </div>
                </div>


    

                <div class="monitoring-stat-card">
                    <div class="monitoring-stat-label">
                        Disetujui
                    </div>

                    <div class="monitoring-stat-value">
                        {{ $disetujui ?? 0 }}
                    </div>
                </div>


                <div class="monitoring-stat-card">
                    <div class="monitoring-stat-label">
                        Ditolak
                    </div>

                    <div class="monitoring-stat-value">
                        {{ $ditolak ?? 0 }}
                    </div>
                </div>

            </div>

        </div>


        {{-- =====================================================
             DATA RESPONDEN
        ====================================================== --}}

       <div class="monitoring-table-section">

    <div class="monitoring-table-header">

        {{-- SEARCH --}}
        <div class="monitoring-search-wrapper">

            <div class="monitoring-search-box">

                <input
                    type="text"
                    id="monitoringSearch"
                    placeholder="Cari No. KK, NIK, atau Nama Kepala Keluarga"
                    autocomplete="off"
                >

                <div class="monitoring-search-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                    </svg>

                </div>

            </div>


        </div>


        {{-- JUDUL --}}
        <div class="monitoring-table-title">
            Data Yang Sudah Terverifikasi
        </div>

        {{-- DESKRIPSI --}}
        <p class="monitoring-table-description">
            Daftar data responden dari Kuisioner yang telah
            diproses pada tahap verifikasi.
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
                                $statusClass = match ($status) {
                                    'approved',
                                    'disetujui' => 'approved',

                                    'rejected',
                                    'reject',
                                    'ditolak' => 'reject',

                                    'pending',
                                    'menunggu' => 'pending',

                                    default => 'draft',
                                };

                                $statusLabel = match ($status) {
                                    'approved',
                                    'disetujui' => 'Disetujui',

                                    'rejected',
                                    'reject',
                                    'ditolak' => 'Ditolak',

                                    'pending',
                                    'menunggu' => 'Menunggu',

                                    'draft' => 'Draft',

                                    'not_processed' => 'Belum Diproses',

                                    default => 'Menunggu',
                                };

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

                                        data-nik="{{ $nik }}"

                                        data-nama="{{ $nama }}"

                                        data-jumlah-anggota="{{ $jumlahAnggota }}"

                                        data-wilayah="{{ $wilayah }}"

                                        data-petugas="{{ $petugas }}"

                                        data-tanggal="{{ $tanggal }}"

                                        data-status="{{ $statusLabel }}"

                                        data-anggota-detail='@json($anggotaDetail)'

                                        data-kuisioner='@json($kuisioner)'
                                    >
                                        Detail
                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7">

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
                            <td colspan="7">

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
                        Nama Kepala Keluarga
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
                        class="monitoring-detail-summary-value"
                        id="detailStatusSummary"
                    >
                        -
                    </div>
                </div>

            </div>


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
                            class="monitoring-detail-value monitoring-member-count"
                            id="detailJumlahAnggota"
                        >
                            0 Orang
                        </span>

                        <button
                            type="button"
                            class="monitoring-member-button"
                            id="openMonitoringMembers"
                            aria-expanded="false"
                        >
                            <span>Lihat Anggota</span>

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
                        </button>

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

                </div>

            </section>


            {{-- HASIL KUISIONER --}}
            <section class="monitoring-detail-section">

                <div class="monitoring-detail-section-title">
                    Hasil Kuisioner
                </div>

                <div class="monitoring-detail-section-subtitle">
                    Pilih bagian kuisioner di sebelah kiri untuk melihat pertanyaan dan jawaban responden.
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


            {{-- FLOATING ANGGOTA KELUARGA --}}
            <div
                class="monitoring-members-popover"
                id="monitoringMembersPopover"
                aria-hidden="true"
            >

                <div class="monitoring-members-popover-header">

                    <div>
                        <h3 class="monitoring-members-popover-title">
                            Anggota Keluarga
                        </h3>

                        <p class="monitoring-members-popover-description">
                            Daftar anggota keluarga untuk No. KK yang sedang diperiksa.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="monitoring-members-popover-close"
                        id="closeMonitoringMembers"
                        aria-label="Tutup anggota keluarga"
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>

                    <div class="monitoring-members-family-summary">

                        <div class="monitoring-members-family-summary-label">
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
                            0 Orang
                        </div>

                    </div>

                </div>


                <div class="monitoring-members-popover-body">

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

                </div>

            </div>

        </div>

    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

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


    function filterTable(keyword) {

        const search =
            String(keyword || '').trim().toLowerCase();

        const rows =
            getMonitoringRows();

        let visibleCount = 0;

        rows.forEach(function (row) {

            const text =
                row.getAttribute('data-search') || '';

            const match =
                text.includes(search);

            row.style.display =
                match ? '' : 'none';

            if (match) {
                visibleCount++;
            }

        });

        if (searchEmpty) {

            searchEmpty.style.display =
                rows.length > 0 && visibleCount === 0
                    ? ''
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

        const rows =
            getMonitoringRows();

        const matchedRows =
            rows.filter(function (row) {

                return (
                    row.getAttribute('data-search') || ''
                ).includes(search);

            }).slice(0, 6);


        if (matchedRows.length === 0) {

            suggestionsBox.classList.remove('show');

            return;
        }


        matchedRows.forEach(function (row) {

            const nama =
                row.getAttribute('data-nama') || '-';

            const noKk =
                row.getAttribute('data-no-kk') || '-';

            const nik =
                row.getAttribute('data-nik') || '-';


            const suggestion =
                document.createElement('div');

            suggestion.className =
                'monitoring-suggestion-item';

            suggestion.innerHTML = `
                <div class="monitoring-suggestion-name">
                    ${escapeHtml(nama)}
                </div>

                <div class="monitoring-suggestion-detail">
                    No. KK: ${escapeHtml(noKk)}
                    &nbsp; | &nbsp;
                    NIK: ${escapeHtml(nik)}
                </div>
            `;


            suggestion.addEventListener('click', function () {

                searchInput.value = nama;

                filterTable(nama);

                suggestionsBox.classList.remove('show');

            });


            suggestionsBox.appendChild(suggestion);

        });


        suggestionsBox.classList.add('show');
    }


    if (searchInput) {

        searchInput.addEventListener('input', function () {

            filterTable(this.value);

            showSuggestions(this.value);

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
                item.questions ||
                item.pertanyaan_list ||
                item.items;


            if (Array.isArray(nested)) {

                const partName =
                    item.title ||
                    item.part ||
                    item.bagian ||
                    item.section ||
                    ('Part ' + (index + 1));


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
            item.label ||
            ('Pertanyaan ' + (index + 1));


        let answer =
            item.answer ??
            item.jawaban ??
            item.response ??
            item.nilai ??
            item.value ??
            '';


        if (Array.isArray(answer)) {

            answer =
                answer.join(', ');

        } else if (
            answer &&
            typeof answer === 'object'
        ) {

            answer =
                answer.label ??
                answer.nama ??
                answer.value ??
                JSON.stringify(answer);

        }


        const part =
            item.part ||
            item.bagian ||
            item.section ||
            item.part_name ||
            inheritedPart ||
            'Part 1';


        return {
            part: String(part),
            question: String(question),
            answer: String(answer ?? '')
        };
    }


    function getPartNumber(
        partName,
        fallbackIndex
    ) {

        const match =
            String(partName).match(
                /(?:part|bagian)\s*(\d+)/i
            );


        return match
            ? parseInt(match[1], 10)
            : fallbackIndex + 1;
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

                            const answered =
                                items.filter(function (item) {
                                    return item.answer.trim() !== '';
                                }).length;

                            const partNumber =
                                getPartNumber(partName, partIndex);

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
                                                    ${items.length} pertanyaan
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

                const partNumber =
                    getPartNumber(partName, index);

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

                selectedBody.innerHTML =
                    items.map(function (item, qIndex) {

                        const empty =
                            item.answer.trim() === '';

                        return `
                            <div class="monitoring-question-item">

                                <div class="monitoring-question-number">
                                    Pertanyaan ${qIndex + 1}
                                </div>

                                <div class="monitoring-question-text">
                                    ${escapeHtml(item.question)}
                                </div>

                                <div class="monitoring-answer-label">
                                    Jawaban Responden
                                </div>

                                <div class="monitoring-answer-value ${empty ? 'is-empty' : ''}">
                                    ${
                                        empty
                                            ? 'Belum diisi'
                                            : escapeHtml(item.answer)
                                    }
                                </div>

                            </div>
                        `;

                    }).join('');

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
                (count || members.length || 0) + ' Orang';
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

                const nik =
                    getMemberValue(
                        member,
                        ['nik', 'NIK', 'no_nik']
                    );

                const name =
                    getMemberValue(
                        member,
                        [
                            'nama_lengkap',
                            'nama',
                            'nama_anggota',
                            'name'
                        ]
                    );

                const status =
                    getMemberValue(
                        member,
                        [
                            'status_keluarga',
                            'status',
                            'hubungan',
                            'hubungan_keluarga'
                        ]
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


    function closeMembersPopover() {

        const popover =
            document.getElementById(
                'monitoringMembersPopover'
            );

        const button =
            document.getElementById(
                'openMonitoringMembers'
            );

        if (popover) {

            popover.classList.remove('active');

            popover.setAttribute(
                'aria-hidden',
                'true'
            );

        }

        if (button) {

            button.classList.remove('active');

            button.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    }


    function toggleMembersPopover() {

        const popover =
            document.getElementById(
                'monitoringMembersPopover'
            );

        const button =
            document.getElementById(
                'openMonitoringMembers'
            );

        if (!popover || !button) {
            return;
        }

        const isOpen =
            popover.classList.contains('active');

        if (isOpen) {

            closeMembersPopover();

            return;
        }

        popover.classList.add('active');

        popover.setAttribute(
            'aria-hidden',
            'false'
        );

        button.classList.add('active');

        button.setAttribute(
            'aria-expanded',
            'true'
        );

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


        document.getElementById(
            'detailStatusSummary'
        ).textContent =
            button.dataset.status || '-';


        document.getElementById(
            'detailNoKk'
        ).textContent =
            button.dataset.noKk || '-';


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


        document.getElementById(
            'detailStatus'
        ).textContent =
            button.dataset.status || '-';


        /* RESET ANGGOTA */
        closeMembersPopover();

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


    const membersButton =
        document.getElementById(
            'openMonitoringMembers'
        );

    if (membersButton) {

        membersButton.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();

                toggleMembersPopover();

            }
        );

    }

    const closeMembersButton = document.getElementById('closeMonitoringMembers');

    if (closeMembersButton) {
        closeMembersButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            closeMembersPopover();
        });
    }


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

        closeMembersPopover();

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


    document.addEventListener(
        'click',
        function (event) {

            const popover =
                document.getElementById(
                    'monitoringMembersPopover'
                );

            const button =
                document.getElementById(
                    'openMonitoringMembers'
                );

            if (
                popover &&
                popover.classList.contains('active') &&
                !popover.contains(event.target) &&
                button &&
                !button.contains(event.target)
            ) {
                closeMembersPopover();
            }

        }
    );


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