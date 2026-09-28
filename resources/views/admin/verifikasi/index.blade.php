@extends('admin.layouts.app')

@section('title', 'Verifikasi Data')

@push('styles')
<style>
    /* =====================================================
       CARD BESAR VERIFIKASI
    ====================================================== */

    .verification-card {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    }

    /* =====================================================
       PAGE HEADER DI DALAM CARD BESAR
    ====================================================== */

    .verification-header {
        padding: 28px 28px 22px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: #252A86;
        line-height: 1.25;
        margin: 0 0 8px;
    }

    .page-description {
        font-size: 14px;
        color: #777;
        line-height: 1.6;
        max-width: 700px;
        margin: 0;
    }

    /* =====================================================
       ALERT SUCCESS
    ====================================================== */

    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 28px 20px;
        padding: 13px 16px;
        border-radius: 9px;
        background: #eaf8ef;
        border: 1px solid #bce5c9;
        color: #24723c;
        font-size: 13px;
        animation: alertFade .3s ease;
    }

    .alert-success svg {
        flex-shrink: 0;
    }

    @keyframes alertFade {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =====================================================
       STATISTICS
       CARD KECIL HANYA UNTUK STATISTIK
    ====================================================== */

    .stats-section {
        padding: 0 28px 26px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 12px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 11px;
        padding: 15px;
        min-width: 0;
        transition: all .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-label {
        font-size: 11px;
        color: #777;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .stat-value {
        font-size: 23px;
        font-weight: 700;
        color: #252A86;
        line-height: 1;
    }

    /* =====================================================
       SEARCH SECTION
       MASIH DI DALAM CARD BESAR
    ====================================================== */

    .search-section {
        padding: 0 28px 24px;
    }

    .search-title {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 9px;
    }

    .search-form {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-box {
        position: relative;
        width: 100%;
    }

    .search-box > svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
        z-index: 2;
    }

    .search-box input {
        width: 100%;
        height: 43px;
        padding: 0 13px 0 40px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        outline: none;
        font-size: 13px;
        color: #333;
        background: #ffffff;
        transition: all .2s ease;
        box-sizing: border-box;
    }

    .search-box input:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
    }

    .search-box input::placeholder {
        color: #a0a0a0;
    }

    /* =====================================================
       SEARCH SUGGESTIONS
    ====================================================== */

    .search-suggestions {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 5px);
        background: #ffffff;
        border: 1px solid #e1e3eb;
        border-radius: 9px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        z-index: 100;
        display: none;
        max-height: 250px;
        overflow-y: auto;
    }

    .search-suggestions.show {
        display: block;
    }

    .suggestion-item {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        padding: 9px 11px;
        border: none;
        border-bottom: 1px solid #f0f1f5;
        background: #ffffff;
        text-align: left;
        cursor: pointer;
        transition: background .15s ease;
        box-sizing: border-box;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background: #f6f7ff;
    }

    .suggestion-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #eef0ff;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .suggestion-content {
        min-width: 0;
        flex: 1;
    }

    .suggestion-name {
        font-size: 12px;
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-detail {
        font-size: 10.5px;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-empty {
        padding: 12px;
        text-align: center;
        font-size: 11px;
        color: #999;
    }

    .search-result {
        margin-top: 7px;
        padding-left: 2px;
        font-size: 11px;
        color: #777;
    }

    .search-result strong {
        color: #252A86;
        font-weight: 700;
    }

    /* =====================================================
       MONITORING SECTION
       BAGIAN INI BUKAN CARD TERPISAH
    ====================================================== */

    .monitoring-section {
        border-top: 1px solid #e8e9ef;
    }

    .monitoring-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 28px;
    }

    .monitoring-title {
        font-size: 17px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .monitoring-description {
        font-size: 12px;
        color: #888;
        line-height: 1.5;
    }

    /* =====================================================
       FILTER
    ====================================================== */

    .filter-wrapper {
        flex-shrink: 0;
    }

    .filter-select {
        height: 38px;
        min-width: 170px;
        padding: 0 12px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        background: #ffffff;
        color: #444;
        font-size: 12px;
        outline: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .filter-select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
    }

    /* =====================================================
       TABLE
    ====================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border-top: 1px solid #e8e9ef;
    }

    .data-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 14px;
        background: #f8f8fb;
        border-bottom: 1px solid #e8e9ef;
        color: #666;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .data-table td {
        padding: 14px;
        border-bottom: 1px solid #eeeef2;
        color: #444;
        font-size: 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .data-table tbody tr {
        transition: background .15s ease;
    }

    .data-table tbody tr:hover {
        background: #fafaff;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =====================================================
       STATUS BADGE
    ====================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff4db;
        color: #9a6a00;
    }

    .status-draft {
        background: #f0f0f2;
        color: #666;
    }

    .status-not-processed {
        background: #f1f2f8;
        color: #5f6380;
    }

    .status-approved {
        background: #eaf8ef;
        color: #24723c;
    }

    .status-rejected {
        background: #fdecec;
        color: #a53636;
    }

    /* =====================================================
       ACTION BUTTON
    ====================================================== */

    .action-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 11px;
        border: none;
        border-radius: 7px;
        background: #eef0ff;
        color: #252A86;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: all .2s ease;
        white-space: nowrap;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: #252A86;
        color: #ffffff;
    }

    /* =====================================================
       EMPTY STATE
    ====================================================== */

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #f1f2f8;
        color: #8b8fa8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-title {
        font-size: 14px;
        font-weight: 700;
        color: #555;
        margin-bottom: 5px;
    }

    .empty-state-description {
        font-size: 12px;
        color: #999;
    }

    /* =====================================================
       MODAL DETAIL
    ====================================================== */

    .verification-modal {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .verification-modal.show {
        display: flex;
    }

    .verification-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 18, 45, 0.55);
        backdrop-filter: blur(2px);
    }

    .verification-modal-box {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 620px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        animation: modalShow .2s ease;
    }

    @keyframes modalShow {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .verification-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #e8e9ef;
    }

    .verification-modal-header-content {
        min-width: 0;
    }

    .verification-modal-kicker {
        font-size: 10px;
        font-weight: 700;
        color: #252A86;
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 4px;
    }

    .verification-modal-title {
        font-size: 19px;
        font-weight: 700;
        color: #252A86;
        margin: 0;
    }

    .verification-modal-subtitle {
        font-size: 11px;
        color: #888;
        margin-top: 4px;
    }

    .verification-modal-close {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 7px;
        background: #f3f4f8;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all .2s ease;
    }

    .verification-modal-close:hover {
        background: #252A86;
        color: #ffffff;
    }

    .verification-modal-body {
        padding: 18px 20px 20px;
    }

    .verification-modal-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 18px;
    }

    .verification-field {
        background: #f8f9fb;
        border: 1px solid #ececf1;
        border-radius: 9px;
        padding: 11px 13px;
    }

    .verification-field label {
        display: block;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #888;
        margin-bottom: 4px;
        font-weight: 700;
    }

    .verification-field strong {
        display: block;
        font-size: 12px;
        color: #333;
        line-height: 1.4;
        word-break: break-word;
    }

    .verification-form-group {
        margin-top: 4px;
    }

    .verification-form-group > label {
        display: block;
        margin-bottom: 7px;
        font-weight: 700;
        color: #333;
        font-size: 12px;
    }

    .verification-form-group select {
        width: 100%;
        height: 40px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        padding: 0 11px;
        font-size: 12px;
        background: #fff;
        color: #333;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .verification-form-group select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, .10);
    }

    .verification-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    .verification-btn {
        min-height: 36px;
        border: none;
        border-radius: 7px;
        padding: 8px 14px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .verification-btn-primary {
        background: #252A86;
        color: #ffffff;
    }

    .verification-btn-primary:hover {
        background: #1e236f;
        transform: translateY(-1px);
    }

    .verification-btn-secondary {
        background: #eef0f7;
        color: #333;
    }

    .verification-btn-secondary:hover {
        background: #e1e4ee;
    }

    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .verification-header {
            padding: 24px 20px 20px;
        }

        .page-title {
            font-size: 23px;
        }

        .stats-section {
            padding: 0 20px 22px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .search-section {
            padding: 0 20px 22px;
        }

        .monitoring-header {
            padding: 20px;
            flex-direction: column;
            align-items: stretch;
        }

        .filter-wrapper {
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }

        .alert-success {
            margin-left: 20px;
            margin-right: 20px;
        }

        .verification-modal {
            padding: 15px;
        }

        .verification-modal-box {
            max-height: calc(100vh - 30px);
        }
    }

    @media (max-width: 600px) {
        .verification-header {
            padding: 20px 16px 18px;
        }

        .page-title {
            font-size: 21px;
        }

        .page-description {
            font-size: 13px;
        }

        .stats-section {
            padding: 0 16px 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .stat-card {
            padding: 13px;
        }

        .stat-value {
            font-size: 21px;
        }

        .search-section {
            padding: 0 16px 20px;
        }

        .search-box input {
            height: 40px;
            font-size: 12px;
        }

        .monitoring-header {
            padding: 17px 16px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .verification-modal {
            padding: 10px;
        }

        .verification-modal-box {
            border-radius: 12px;
            max-height: calc(100vh - 20px);
        }

        .verification-modal-header {
            padding: 15px 16px;
        }

        .verification-modal-body {
            padding: 15px 16px 16px;
        }

        .verification-modal-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }

    @media (max-width: 400px) {
        .stats-grid {
            gap: 8px;
        }

        .stat-card {
            padding: 11px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 19px;
        }

        .verification-actions {
            flex-direction: column-reverse;
        }

        .verification-btn {
            width: 100%;
        }
    }

/* =========================================================
   DETAIL VERIFIKASI - RESPONSIVE QUESTIONNAIRE
========================================================= */

.verification-modal-box {
    width: min(960px, 100%);
    max-width: 960px;
    max-height: calc(100vh - 40px);
    overflow: hidden;
}

.verification-modal-body {
    padding: 18px 20px 22px;
    overflow-y: auto;
    max-height: calc(100vh - 125px);
    scrollbar-width: thin;
}

.verification-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 16px;
}

.verification-summary-card {
    min-width: 0;
    padding: 13px 14px;
    border: 1px solid #e7e9f1;
    border-radius: 10px;
    background: #f8f9fc;
}

.verification-summary-label {
    display: block;
    margin-bottom: 5px;
    font-size: 10px;
    font-weight: 600;
    color: #7b8192;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.verification-summary-card strong {
    display: block;
    overflow: hidden;
    font-size: 13px;
    color: #252A86;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.verification-summary-status {
    color: #252A86 !important;
}

.verification-detail-section {
    margin-bottom: 16px;
    padding: 16px;
    border: 1px solid #e7e9f1;
    border-radius: 12px;
    background: #ffffff;
}

.verification-section-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 13px;
}

.verification-section-kicker {
    display: block;
    margin-bottom: 3px;
    font-size: 9px;
    font-weight: 700;
    color: #252A86;
    letter-spacing: .75px;
}

.verification-section-heading h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #252A86;
}

.verification-section-heading p {
    margin: 4px 0 0;
    font-size: 11px;
    line-height: 1.5;
    color: #7d8392;
}

.verification-info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 9px;
}

.verification-info-item {
    min-width: 0;
    padding: 11px 12px;
    border: 1px solid #edf0f5;
    border-radius: 9px;
    background: #fafbfc;
}

.verification-info-item span {
    display: block;
    margin-bottom: 5px;
    font-size: 10px;
    color: #858b99;
}

.verification-info-item strong {
    display: block;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.4;
    color: #252A86;
    text-overflow: ellipsis;
}

.questionnaire-heading {
    align-items: center;
}

.questionnaire-total {
    flex: 0 0 auto;
    padding: 6px 10px;
    border-radius: 999px;
    background: #eef0ff;
    color: #252A86;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.questionnaire-parts {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.questionnaire-part {
    overflow: hidden;
    border: 1px solid #e5e7ef;
    border-radius: 10px;
    background: #fff;
}

.questionnaire-part.is-open {
    border-color: #cfd3f4;
}

.questionnaire-part-header {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 13px;
    border: 0;
    background: #f8f9fc;
    color: #252A86;
    text-align: left;
    cursor: pointer;
    transition: background .18s ease;
}

.questionnaire-part-header:hover {
    background: #f1f3fb;
}

.questionnaire-part.is-open .questionnaire-part-header {
    background: #f0f2ff;
}

.questionnaire-part-number {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #252A86;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
}

.questionnaire-part-title {
    min-width: 0;
    flex: 1;
}

.questionnaire-part-title strong {
    display: block;
    font-size: 12px;
    color: #252A86;
}

.questionnaire-part-title span {
    display: block;
    margin-top: 2px;
    font-size: 10px;
    color: #818797;
}

.questionnaire-part-chevron {
    flex: 0 0 24px;
    transition: transform .2s ease;
}

.questionnaire-part.is-open .questionnaire-part-chevron {
    transform: rotate(180deg);
}

.questionnaire-part-body {
    display: none;
    padding: 10px;
    border-top: 1px solid #e8eaf1;
    background: #fff;
}

.questionnaire-part.is-open .questionnaire-part-body {
    display: block;
}

.questionnaire-question {
    padding: 11px 12px;
    border: 1px solid #eceef4;
    border-radius: 8px;
    background: #fff;
}

.questionnaire-question + .questionnaire-question {
    margin-top: 7px;
}

.questionnaire-question-number {
    margin-bottom: 4px;
    font-size: 9px;
    font-weight: 700;
    color: #8a90a0;
}

.questionnaire-question-text {
    font-size: 11px;
    font-weight: 600;
    line-height: 1.5;
    color: #303545;
}

.questionnaire-answer-label {
    margin-top: 8px;
    margin-bottom: 3px;
    font-size: 9px;
    font-weight: 700;
    color: #252A86;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.questionnaire-answer {
    padding: 8px 9px;
    border-radius: 7px;
    background: #f7f8fb;
    color: #505668;
    font-size: 11px;
    line-height: 1.55;
    white-space: pre-wrap;
    word-break: break-word;
}

.questionnaire-answer.is-empty {
    color: #9a6a18;
    background: #fff8e7;
}

.questionnaire-empty {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    padding: 28px 18px;
    border: 1px dashed #dfe2eb;
    border-radius: 10px;
    background: #fafbfc;
    text-align: center;
}

.questionnaire-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    margin-bottom: 9px;
    border-radius: 50%;
    background: #eef0ff;
    color: #252A86;
}

.questionnaire-empty strong {
    font-size: 12px;
    color: #4a5060;
}

.questionnaire-empty span {
    max-width: 400px;
    margin-top: 4px;
    font-size: 10px;
    line-height: 1.5;
    color: #8a90a0;
}

.verification-action-section {
    margin-bottom: 0;
}

.verification-form-group {
    margin-bottom: 12px;
}

.verification-form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #424858;
}

.verification-form-group select {
    width: 100%;
    min-height: 40px;
    padding: 0 12px;
    border: 1px solid #dfe2ea;
    border-radius: 8px;
    outline: none;
    background: #fff;
    color: #343949;
    font-size: 12px;
}

.verification-form-group select:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, .08);
}

.verification-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
}

.verification-btn {
    min-height: 38px;
    padding: 0 15px;
    border: 0;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: .18s ease;
}

.verification-btn-secondary {
    border: 1px solid #dfe2ea;
    background: #fff;
    color: #606676;
}

.verification-btn-secondary:hover {
    background: #f6f7fa;
}

.verification-btn-danger {
    background: #fff0f0;
    color: #b33a3a;
}

.verification-btn-danger:hover {
    background: #fce1e1;
}

.verification-btn-success {
    background: #eef8f1;
    color: #277443;
}

.verification-btn-success:hover {
    background: #dff1e4;
}

.verification-btn-primary {
    background: #252A86;
    color: #fff;
}

.verification-btn-primary:hover {
    background: #1e236f;
}

@media (max-width: 900px) {
    .verification-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .verification-modal {
        padding: 12px;
    }

    .verification-modal-box {
        max-height: calc(100vh - 24px);
        border-radius: 12px;
    }

    .verification-modal-body {
        max-height: calc(100vh - 105px);
        padding: 14px;
    }

    .verification-summary-grid {
        grid-template-columns: 1fr;
    }

    .verification-info-grid {
        grid-template-columns: 1fr;
    }

    .verification-section-heading {
        align-items: flex-start;
    }

    .questionnaire-heading {
        flex-direction: column;
        gap: 8px;
    }

    .questionnaire-total {
        align-self: flex-start;
    }
}

@media (max-width: 480px) {
    .verification-modal {
        padding: 7px;
    }

    .verification-modal-box {
        max-height: calc(100vh - 14px);
        border-radius: 10px;
    }

    .verification-modal-header {
        padding: 13px 14px;
    }

    .verification-modal-body {
        max-height: calc(100vh - 94px);
        padding: 10px;
    }

    .verification-detail-section {
        padding: 11px;
        margin-bottom: 10px;
    }

    .verification-summary-card {
        padding: 11px 12px;
    }

    .questionnaire-part-header {
        padding: 10px;
    }

    .questionnaire-part-number {
        flex-basis: 30px;
        width: 30px;
        height: 30px;
    }

    .questionnaire-part-body {
        padding: 7px;
    }

    .questionnaire-question {
        padding: 9px;
    }

    .verification-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .verification-btn {
        width: 100%;
    }

    .verification-btn-secondary {
        grid-column: 1 / -1;
    }
}

</style>
@endpush


@section('content')

    {{-- =====================================================
         SATU CARD BESAR
    ====================================================== --}}

    <div class="verification-card">

        {{-- =================================================
             HEADER VERIFIKASI
        ================================================== --}}

        <div class="verification-header">
            <h1 class="page-title">
                Sistem Verifikasi
            </h1>

            <p class="page-description">
                Kelola, periksa, dan perbarui data hasil pendataan responden.
            </p>
        </div>


        {{-- =================================================
             SUCCESS ALERT
        ================================================== --}}

        @if (session('success'))
            <div
                class="alert-success"
                id="successAlert"
            >
                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>

                <span>
                    {{ session('success') }}
                </span>
            </div>
        @endif


        {{-- =================================================
             STATISTIK
        ================================================== --}}

        <div class="stats-section">
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-label">
                        Total Responden
                    </div>

                    <div class="stat-value">
                        100
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Sudah Didata
                    </div>

                    <div class="stat-value">
                        10
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Belum Didata
                    </div>

                    <div class="stat-value">
                        265
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Menunggu Verifikasi
                    </div>

                    <div class="stat-value">
                        118
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Disetujui
                    </div>

                    <div class="stat-value">
                        742
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Ditolak
                    </div>

                    <div class="stat-value">
                        83
                    </div>
                </div>

            </div>
        </div>


        {{-- =================================================
             SEARCH
        ================================================== --}}

        <div class="search-section">

            <div class="search-title">
                Cari Data Responden
            </div>

            <div class="search-form">

                <div class="search-box">

                    <svg
                        width="17"
                        height="17"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="11"
                            cy="11"
                            r="7"
                        ></circle>

                        <line
                            x1="16.65"
                            y1="16.65"
                            x2="21"
                            y2="21"
                        ></line>
                    </svg>

                    <input
                        type="text"
                        id="liveSearch"
                        placeholder="Cari No.KK, NIK, atau Nama Kepala Keluarga...."
                        autocomplete="off"
                    >

                    <div
                        class="search-suggestions"
                        id="searchSuggestions"
                    ></div>

                </div>

            </div>

            <div
                class="search-result"
                id="searchResult"
                style="display: none;"
            ></div>

        </div>


        {{-- =================================================
             MONITORING
             BUKAN CARD BARU
        ================================================== --}}

        <div class="monitoring-section">

            <div class="monitoring-header">

                <div>
                    <div class="monitoring-title">
                        Data Monitoring Pendataan
                    </div>

                    <div class="monitoring-description">
                        Daftar data responden yang telah masuk ke sistem.
                    </div>
                </div>


                {{-- FILTER STATUS --}}

                <div class="filter-wrapper">

                    <form
                        action="{{ route('verifikasi.index') }}"
                        method="GET"
                    >

                        <select
                            name="status"
                            class="filter-select"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="all"
                                {{ request('status', 'all') == 'all' ? 'selected' : '' }}
                            >
                                Semua Status
                            </option>

                            <option
                                value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}
                            >
                                Menunggu Verifikasi
                            </option>

                            <option
                                value="draft"
                                {{ request('status') == 'draft' ? 'selected' : '' }}
                            >
                                Draft
                            </option>

                            <option
                                value="not_processed"
                                {{ request('status') == 'not_processed' ? 'selected' : '' }}
                            >
                                Belum Didata
                            </option>

                            <option
                                value="approved"
                                {{ request('status') == 'approved' ? 'selected' : '' }}
                            >
                                Disetujui
                            </option>

                            <option
                                value="rejected"
                                {{ request('status') == 'rejected' ? 'selected' : '' }}
                            >
                                Ditolak
                            </option>

                        </select>

                    </form>

                </div>

            </div>


            {{-- =================================================
                 TABLE
            ================================================== --}}

            <div class="table-wrapper">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. KK</th>
                            <th>NIK</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>Jumlah Anggota</th>
                            <th>Status</th>
                            <th>Wilayah Pendataan</th>
                            <th>Petugas</th>
                            <th>Tanggal Pendataan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody id="dataTableBody">

                        @forelse ($data as $item)

                            @php
                                $status = strtolower(
                                    str_replace(
                                        [' ', '-'],
                                        '_',
                                        $item['status'] ?? ''
                                    )
                                );

                                if (
                                    $status === 'pending' ||
                                    $status === 'menunggu' ||
                                    $status === 'menunggu_verifikasi'
                                ) {
                                    $statusLabel = 'Menunggu Verifikasi';
                                } elseif ($status === 'draft') {
                                    $statusLabel = 'Draft';
                                } elseif (
                                    $status === 'not_processed' ||
                                    $status === 'belum_didata'
                                ) {
                                    $statusLabel = 'Belum Didata';
                                } elseif (
                                    $status === 'approved' ||
                                    $status === 'disetujui'
                                ) {
                                    $statusLabel = 'Disetujui';
                                } elseif (
                                    $status === 'rejected' ||
                                    $status === 'ditolak'
                                ) {
                                    $statusLabel = 'Ditolak';
                                } else {
                                    $statusLabel = $item['status'] ?? '-';
                                }
                            @endphp


                            <tr
                                class="data-row"
                                data-id="{{ $item['id'] ?? '' }}"
                                data-no-kk="{{ $item['no_kk'] ?? '' }}"
                                data-nik="{{ $item['nik'] ?? '' }}"
                                data-nama="{{ $item['nama'] ?? '' }}"
                                data-wilayah="{{ $item['wilayah'] ?? '' }}"
                                data-petugas="{{ $item['petugas'] ?? '' }}"
                                data-status="{{ $item['status'] ?? '' }}"
                                data-status-label="{{ $statusLabel }}"
                                data-anggota="{{ $item['anggota'] ?? 0 }}"
                                data-tanggal="{{ $item['tanggal'] ?? '' }}"
                                data-kuisioner="{{ e(json_encode($item['kuisioner'] ?? $item['questionnaire'] ?? $item['jawaban_kuisioner'] ?? $item['answers'] ?? [])) }}"
                            >

                                <td class="row-number">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $item['no_kk'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['nik'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['nama'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['anggota'] ?? 0 }} Orang
                                </td>

                                <td>

                                    @if (
                                        $status === 'pending' ||
                                        $status === 'menunggu' ||
                                        $status === 'menunggu_verifikasi'
                                    )
                                        <span class="status-badge status-pending">
                                            Menunggu Verifikasi
                                        </span>

                                    @elseif ($status === 'draft')

                                        <span class="status-badge status-draft">
                                            Draft
                                        </span>

                                    @elseif (
                                        $status === 'not_processed' ||
                                        $status === 'belum_didata'
                                    )

                                        <span class="status-badge status-not-processed">
                                            Belum Didata
                                        </span>

                                    @elseif (
                                        $status === 'approved' ||
                                        $status === 'disetujui'
                                    )

                                        <span class="status-badge status-approved">
                                            Disetujui
                                        </span>

                                    @elseif (
                                        $status === 'rejected' ||
                                        $status === 'ditolak'
                                    )

                                        <span class="status-badge status-rejected">
                                            Ditolak
                                        </span>

                                    @else

                                        <span class="status-badge status-draft">
                                            {{ $item['status'] ?? '-' }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $item['wilayah'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['petugas'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['tanggal'] ?? '-' }}
                                </td>

                                <td>

                                    <div class="action-wrapper">

                                        <button
                                            type="button"
                                            class="btn-detail btn-open-detail"
                                        >
                                            Detail
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr id="serverEmptyRow">
                                <td colspan="10">

                                    <div class="empty-state">

                                        <div class="empty-state-icon">

                                            <svg
                                                width="23"
                                                height="23"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <circle
                                                    cx="11"
                                                    cy="11"
                                                    r="7"
                                                ></circle>

                                                <line
                                                    x1="16.65"
                                                    y1="16.65"
                                                    x2="21"
                                                    y2="21"
                                                ></line>
                                            </svg>

                                        </div>

                                        <div class="empty-state-title">
                                            Data tidak ditemukan
                                        </div>

                                        <div class="empty-state-description">
                                            Belum terdapat data responden yang masuk ke sistem.
                                        </div>

                                    </div>

                                </td>
                            </tr>

                        @endforelse


                        {{-- EMPTY RESULT LIVE SEARCH --}}

                        <tr
                            id="liveEmptyRow"
                            style="display: none;"
                        >

                            <td colspan="10">

                                <div class="empty-state">

                                    <div class="empty-state-icon">

                                        <svg
                                            width="23"
                                            height="23"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <circle
                                                cx="11"
                                                cy="11"
                                                r="7"
                                            ></circle>

                                            <line
                                                x1="16.65"
                                                y1="16.65"
                                                x2="21"
                                                y2="21"
                                            ></line>
                                        </svg>

                                    </div>

                                    <div class="empty-state-title">
                                        Data tidak ditemukan
                                    </div>

                                    <div
                                        class="empty-state-description"
                                        id="liveEmptyText"
                                    >
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


    {{-- =====================================================
         MODAL DETAIL VERIFIKASI
    ====================================================== --}}

    <div
        class="verification-modal"
        id="verificationModal"
        aria-hidden="true"
    >

        <div
            class="verification-modal-overlay"
            id="verificationModalOverlay"
        ></div>

        <div
            class="verification-modal-box"
            role="dialog"
            aria-modal="true"
            aria-labelledby="verificationModalTitle"
        >

            <div class="verification-modal-header">

                <div class="verification-modal-header-content">
                    <div class="verification-modal-kicker">
                        VERIFIKASI DATA
                    </div>

                    <h2
                        class="verification-modal-title"
                        id="verificationModalTitle"
                    >
                        Detail Data Pendataan
                    </h2>

                    <div class="verification-modal-subtitle">
                        Periksa informasi responden dan jawaban kuisioner sebelum melakukan verifikasi.
                    </div>
                </div>

                <button
                    type="button"
                    class="verification-modal-close"
                    id="closeVerificationModal"
                    aria-label="Tutup detail"
                >
                    <svg
                        width="17"
                        height="17"
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


            <div class="verification-modal-body">

                {{-- RINGKASAN --}}
                <div class="verification-summary-grid">

                    <div class="verification-summary-card">
                        <span class="verification-summary-label">
                            Nama Responden
                        </span>
                        <strong id="modalSummaryNama">-</strong>
                    </div>

                    <div class="verification-summary-card">
                        <span class="verification-summary-label">
                            Nomor KK
                        </span>
                        <strong id="modalSummaryNoKK">-</strong>
                    </div>

                    <div class="verification-summary-card">
                        <span class="verification-summary-label">
                            Status Pendataan
                        </span>
                        <strong
                            id="modalSummaryStatus"
                            class="verification-summary-status"
                        >
                            -
                        </strong>
                    </div>

                </div>


                {{-- INFORMASI RESPONDEN --}}
                <section class="verification-detail-section">

                    <div class="verification-section-heading">
                        <div>
                            <span class="verification-section-kicker">
                                DATA RESPONDEN
                            </span>

                            <h3>
                                Informasi Responden
                            </h3>
                        </div>
                    </div>


                    <div class="verification-info-grid">

                        <div class="verification-info-item">
                            <span>No. KK</span>
                            <strong id="modalNoKK">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>NIK</span>
                            <strong id="modalNIK">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>Nama Kepala Keluarga</span>
                            <strong id="modalNama">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>Jumlah Anggota Keluarga</span>
                            <strong id="modalAnggota">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>Wilayah</span>
                            <strong id="modalWilayah">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>Petugas</span>
                            <strong id="modalPetugas">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>Tanggal Pendataan</span>
                            <strong id="modalTanggal">-</strong>
                        </div>

                        <div class="verification-info-item">
                            <span>Status Saat Ini</span>
                            <strong id="modalStatusLabel">-</strong>
                        </div>

                    </div>

                </section>


                {{-- HASIL KUISIONER --}}
                <section class="verification-detail-section questionnaire-section">

                    <div class="verification-section-heading questionnaire-heading">

                        <div>
                            <span class="verification-section-kicker">
                                HASIL PENDATAAN
                            </span>

                            <h3>
                                Hasil Kuisioner
                            </h3>

                            <p>
                                Buka setiap part untuk memeriksa pertanyaan dan jawaban responden.
                            </p>
                        </div>

                        <div
                            class="questionnaire-total"
                            id="questionnaireTotal"
                        >
                            0 Part
                        </div>

                    </div>


                    <div
                        class="questionnaire-parts"
                        id="verificationQuestionnaireContent"
                    >
                        <div class="questionnaire-empty">
                            <div class="questionnaire-empty-icon">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"></path>
                                    <path d="M4 5.5v16"></path>
                                    <path d="M8 7h8"></path>
                                    <path d="M8 11h8"></path>
                                </svg>
                            </div>

                            <strong>
                                Belum Ada Hasil Kuisioner
                            </strong>

                            <span>
                                Hasil kuisioner untuk responden ini belum tersedia.
                            </span>
                        </div>
                    </div>

                </section>


                {{-- VERIFIKASI --}}
                <section class="verification-detail-section verification-action-section">

                    <div class="verification-section-heading">
                        <div>
                            <span class="verification-section-kicker">
                                VERIFIKASI
                            </span>

                            <h3>
                                Verifikasi Data
                            </h3>

                            <p>
                                Periksa seluruh data sebelum menentukan status pendataan.
                            </p>
                        </div>
                    </div>


                    <form
                        id="verificationUpdateForm"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')


                        <div class="verification-form-group">

                            <label for="modalStatus">
                                Ubah Status
                            </label>

                            <select
                                id="modalStatus"
                                name="status"
                                required
                            >
                                <option
                                    value=""
                                    disabled
                                >
                                    Pilih Status Verifikasi
                                </option>

                                <option value="approved">
                                    Disetujui
                                </option>

                                <option value="rejected">
                                    Ditolak
                                </option>
                            </select>

                        </div>


                        <div class="verification-actions">

                            <button
                                type="button"
                                class="verification-btn verification-btn-secondary"
                                id="cancelVerificationModal"
                            >
                                Tutup
                            </button>

                            

                            <button
                                type="submit"
                                class="verification-btn verification-btn-primary"
                            >
                                Simpan
                            </button>

                        </div>

                    </form>

                </section>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       SUCCESS ALERT
    ====================================================== */

    const successAlert =
        document.getElementById('successAlert');

    if (successAlert) {
        setTimeout(function () {

            successAlert.style.opacity = '0';
            successAlert.style.transform =
                'translateY(-5px)';
            successAlert.style.transition =
                'all .3s ease';

            setTimeout(function () {
                successAlert.remove();
            }, 300);

        }, 3000);
    }


    /* =====================================================
       LIVE SEARCH
    ====================================================== */

    const searchInput =
        document.getElementById('liveSearch');

    const searchSuggestions =
        document.getElementById('searchSuggestions');

    const searchResult =
        document.getElementById('searchResult');

    const tableRows =
        Array.from(
            document.querySelectorAll('.data-row')
        );

    const liveEmptyRow =
        document.getElementById('liveEmptyRow');

    const liveEmptyText =
        document.getElementById('liveEmptyText');


    /* =====================================================
       DATA SEARCH
    ====================================================== */

    const searchData =
        tableRows.map(function (row) {

            return {
                row: row,
                noKK: row.dataset.noKk || '',
                nik: row.dataset.nik || '',
                nama: row.dataset.nama || ''
            };

        });


    /* =====================================================
       ESCAPE HTML
    ====================================================== */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;
    }


    /* =====================================================
       UPDATE SEARCH RESULT
    ====================================================== */

    function updateSearchResult(keyword, count) {

        if (!searchResult) {
            return;
        }

        if (keyword.length > 0) {

            searchResult.style.display =
                'block';

            searchResult.innerHTML =
                'Menampilkan ' +
                '<strong>' +
                count +
                '</strong>' +
                ' data yang cocok dengan ' +
                '<strong>"' +
                escapeHtml(keyword) +
                '"</strong>';

        } else {

            searchResult.style.display =
                'none';

            searchResult.innerHTML =
                '';

        }
    }


    /* =====================================================
       FILTER TABLE
    ====================================================== */

    function filterTable(keyword) {

        keyword =
            keyword
                .toLowerCase()
                .trim();


        if (keyword === '') {

            tableRows.forEach(
                function (row, index) {

                    row.style.display =
                        '';

                    const numberCell =
                        row.querySelector(
                            '.row-number'
                        );

                    if (numberCell) {
                        numberCell.textContent =
                            index + 1;
                    }

                }
            );

            if (liveEmptyRow) {
                liveEmptyRow.style.display =
                    'none';
            }

            updateSearchResult('', 0);

            return [];
        }


        const filteredData =
            searchData.filter(
                function (item) {

                    const noKK =
                        item.noKK.toLowerCase();

                    const nik =
                        item.nik.toLowerCase();

                    const nama =
                        item.nama.toLowerCase();

                    return (
                        noKK.includes(keyword) ||
                        nik.includes(keyword) ||
                        nama.includes(keyword)
                    );

                }
            );


        tableRows.forEach(
            function (row) {
                row.style.display =
                    'none';
            }
        );


        filteredData.forEach(
            function (item, index) {

                item.row.style.display =
                    '';

                const numberCell =
                    item.row.querySelector(
                        '.row-number'
                    );

                if (numberCell) {
                    numberCell.textContent =
                        index + 1;
                }

            }
        );


        updateSearchResult(
            keyword,
            filteredData.length
        );


        if (liveEmptyRow) {

            if (filteredData.length === 0) {

                liveEmptyRow.style.display =
                    '';

                if (liveEmptyText) {

                    liveEmptyText.textContent =
                        'Tidak ada data yang sesuai dengan pencarian "' +
                        keyword +
                        '".';

                }

            } else {

                liveEmptyRow.style.display =
                    'none';

            }
        }


        return filteredData;
    }


    /* =====================================================
       SEARCH SUGGESTIONS
    ====================================================== */

    function showSuggestions(keyword) {

        if (!searchSuggestions) {
            return;
        }

        keyword =
            keyword
                .toLowerCase()
                .trim();


        if (keyword.length < 2) {

            searchSuggestions.classList.remove(
                'show'
            );

            searchSuggestions.innerHTML =
                '';

            return;
        }


        const matches =
            searchData
                .filter(function (item) {

                    return (
                        item.nama
                            .toLowerCase()
                            .includes(keyword) ||

                        item.noKK
                            .toLowerCase()
                            .includes(keyword) ||

                        item.nik
                            .toLowerCase()
                            .includes(keyword)
                    );

                })
                .slice(0, 5);


        searchSuggestions.innerHTML =
            '';


        if (matches.length === 0) {

            const empty =
                document.createElement('div');

            empty.className =
                'suggestion-empty';

            empty.textContent =
                'Tidak ada data yang cocok.';

            searchSuggestions.appendChild(
                empty
            );

            searchSuggestions.classList.add(
                'show'
            );

            return;
        }


        matches.forEach(
            function (item) {

                const button =
                    document.createElement(
                        'button'
                    );

                button.type =
                    'button';

                button.className =
                    'suggestion-item';


                const icon =
                    document.createElement(
                        'div'
                    );

                icon.className =
                    'suggestion-icon';

                icon.innerHTML = `
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="11"
                            cy="11"
                            r="7"
                        ></circle>

                        <line
                            x1="16.65"
                            y1="16.65"
                            x2="21"
                            y2="21"
                        ></line>
                    </svg>
                `;


                const content =
                    document.createElement(
                        'div'
                    );

                content.className =
                    'suggestion-content';


                const name =
                    document.createElement(
                        'div'
                    );

                name.className =
                    'suggestion-name';

                name.textContent =
                    item.nama || '-';


                const detail =
                    document.createElement(
                        'div'
                    );

                detail.className =
                    'suggestion-detail';

                detail.textContent =
                    'No. KK: ' +
                    (item.noKK || '-') +
                    ' • NIK: ' +
                    (item.nik || '-');


                content.appendChild(name);
                content.appendChild(detail);

                button.appendChild(icon);
                button.appendChild(content);


                button.addEventListener(
                    'click',
                    function () {

                        searchInput.value =
                            item.nama ||
                            item.noKK ||
                            item.nik;

                        filterTable(
                            searchInput.value
                        );

                        searchSuggestions.classList.remove(
                            'show'
                        );

                    }
                );


                searchSuggestions.appendChild(
                    button
                );

            }
        );


        searchSuggestions.classList.add(
            'show'
        );
    }


    /* =====================================================
       INPUT SEARCH
    ====================================================== */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value.trim();

                filterTable(keyword);
                showSuggestions(keyword);

            }
        );


        searchInput.addEventListener(
            'focus',
            function () {

                const keyword =
                    this.value.trim();

                if (keyword.length >= 2) {
                    showSuggestions(keyword);
                }

            }
        );


        searchInput.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    searchSuggestions.classList.remove(
                        'show'
                    );

                }

            }
        );
    }


    /* =====================================================
       CLICK DI LUAR SEARCH
    ====================================================== */

    document.addEventListener(
        'click',
        function (event) {

            if (
                searchSuggestions &&
                searchInput &&
                !searchSuggestions.contains(
                    event.target
                ) &&
                !searchInput.contains(
                    event.target
                )
            ) {

                searchSuggestions.classList.remove(
                    'show'
                );

            }

        }
    );


    /* =====================================================
       MODAL DETAIL
    ====================================================== */

    const verificationModal =
        document.getElementById('verificationModal');

    const verificationModalOverlay =
        document.getElementById('verificationModalOverlay');

    const closeVerificationModal =
        document.getElementById('closeVerificationModal');

    const cancelVerificationModal =
        document.getElementById('cancelVerificationModal');

    const verificationUpdateForm =
        document.getElementById('verificationUpdateForm');

    const modalNoKK =
        document.getElementById('modalNoKK');

    const modalNIK =
        document.getElementById('modalNIK');

    const modalNama =
        document.getElementById('modalNama');

    const modalWilayah =
        document.getElementById('modalWilayah');

    const modalPetugas =
        document.getElementById('modalPetugas');

    const modalStatusLabel =
        document.getElementById('modalStatusLabel');

    const modalStatus =
        document.getElementById('modalStatus');

    const modalAnggota =
        document.getElementById('modalAnggota');

    const modalTanggal =
        document.getElementById('modalTanggal');

    const modalSummaryNama =
        document.getElementById('modalSummaryNama');

    const modalSummaryNoKK =
        document.getElementById('modalSummaryNoKK');

    const modalSummaryStatus =
        document.getElementById('modalSummaryStatus');

    const questionnaireContent =
        document.getElementById('verificationQuestionnaireContent');

    const questionnaireTotal =
        document.getElementById('questionnaireTotal');

    const approveVerificationButton =
        document.getElementById('approveVerificationButton');

    const rejectVerificationButton =
        document.getElementById('rejectVerificationButton');


    /* =====================================================
       ROUTE UPDATE
    ====================================================== */

    const updateRouteTemplate =
        "{{ route('verifikasi.update', '__ID__') }}";


    /* =====================================================
       HELPER
    ====================================================== */

    function escapeHtmlValue(value) {

        return String(value ?? '-')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    function parseQuestionnaire(raw) {

        if (!raw) {
            return [];
        }

        if (typeof raw === 'string') {

            try {
                raw = JSON.parse(raw);
            } catch (error) {
                return [];
            }

        }

        if (!raw) {
            return [];
        }

        /*
         * Bentuk yang didukung:
         * 1. { parts: [...] }
         * 2. { data: [...] }
         * 3. array part
         * 4. array pertanyaan yang mempunyai field part/bagian
         */

        if (!Array.isArray(raw) && typeof raw === 'object') {

            if (Array.isArray(raw.parts)) {
                return raw.parts;
            }

            if (Array.isArray(raw.data)) {
                return raw.data;
            }

            if (Array.isArray(raw.questionnaire)) {
                return raw.questionnaire;
            }

            if (Array.isArray(raw.questions)) {
                return raw.questions;
            }

        }

        if (Array.isArray(raw)) {
            return raw;
        }

        return [];
    }


    function getFirstValue(object, keys, fallback = '') {

        if (!object || typeof object !== 'object') {
            return fallback;
        }

        for (const key of keys) {

            if (
                Object.prototype.hasOwnProperty.call(object, key) &&
                object[key] !== null &&
                object[key] !== undefined &&
                String(object[key]).trim() !== ''
            ) {
                return object[key];
            }

        }

        return fallback;
    }


    function normalizeQuestionAnswer(value) {

        if (value === null || value === undefined) {
            return '';
        }

        if (Array.isArray(value)) {
            return value.join(', ');
        }

        if (typeof value === 'object') {

            const nested =
                getFirstValue(
                    value,
                    [
                        'label',
                        'name',
                        'nama',
                        'text',
                        'value',
                        'jawaban',
                        'answer'
                    ],
                    ''
                );

            if (nested !== '') {
                return normalizeQuestionAnswer(nested);
            }

            try {
                return JSON.stringify(value);
            } catch (error) {
                return '';
            }
        }

        return String(value);
    }


    function normalizeQuestion(question, index) {

        if (
            question === null ||
            question === undefined
        ) {
            return {
                number: index + 1,
                text: '',
                answer: ''
            };
        }

        if (typeof question !== 'object') {
            return {
                number: index + 1,
                text: String(question),
                answer: ''
            };
        }

        const text =
            getFirstValue(
                question,
                [
                    'pertanyaan',
                    'question',
                    'question_text',
                    'nama_pertanyaan',
                    'text',
                    'judul',
                    'label'
                ],
                `Pertanyaan ${index + 1}`
            );

        const answer =
            getFirstValue(
                question,
                [
                    'jawaban',
                    'answer',
                    'response',
                    'nilai',
                    'value',
                    'hasil'
                ],
                ''
            );

        return {
            number:
                getFirstValue(
                    question,
                    ['number', 'nomor', 'no', 'urutan'],
                    index + 1
                ),
            text: normalizeQuestionAnswer(text),
            answer: normalizeQuestionAnswer(answer)
        };
    }


    function normalizeParts(raw) {

        const source =
            parseQuestionnaire(raw);

        if (!source.length) {
            return [];
        }

        /*
         * Kalau data sudah berbentuk Part:
         * [
         *   {
         *      part: 1,
         *      title: "...",
         *      questions: [...]
         *   }
         * ]
         */
        const looksLikeParts =
            source.some(function (item) {

                if (!item || typeof item !== 'object') {
                    return false;
                }

                return (
                    Array.isArray(item.questions) ||
                    Array.isArray(item.pertanyaan) ||
                    Array.isArray(item.items) ||
                    Array.isArray(item.answers)
                );

            });

        if (looksLikeParts) {

            return source.map(function (part, index) {

                const questions =
                    Array.isArray(part.questions)
                        ? part.questions
                        : Array.isArray(part.pertanyaan)
                            ? part.pertanyaan
                            : Array.isArray(part.items)
                                ? part.items
                                : Array.isArray(part.answers)
                                    ? part.answers
                                    : [];

                const partNumber =
                    getFirstValue(
                        part,
                        ['part', 'part_number', 'bagian', 'section', 'section_number'],
                        index + 1
                    );

                const partTitle =
                    getFirstValue(
                        part,
                        ['title', 'judul', 'nama', 'part_title', 'section_title'],
                        `Part ${partNumber}`
                    );

                return {
                    number: partNumber,
                    title: partTitle,
                    questions: questions.map(normalizeQuestion)
                };

            });

        }


        /*
         * Kalau database mengirim daftar pertanyaan langsung,
         * kelompokkan berdasarkan part/bagian.
         */
        const grouped = {};

        source.forEach(function (question, index) {

            const partNumber =
                getFirstValue(
                    question,
                    [
                        'part',
                        'part_number',
                        'bagian',
                        'section',
                        'section_number'
                    ],
                    1
                );

            const key =
                String(partNumber);

            if (!grouped[key]) {

                grouped[key] = {
                    number: partNumber,
                    title:
                        getFirstValue(
                            question,
                            [
                                'part_title',
                                'section_title',
                                'nama_part',
                                'nama_bagian'
                            ],
                            `Part ${partNumber}`
                        ),
                    questions: []
                };

            }

            grouped[key].questions.push(
                normalizeQuestion(
                    question,
                    grouped[key].questions.length
                )
            );

        });

        return Object.values(grouped);
    }


    function renderQuestionnaire(raw) {

        if (!questionnaireContent) {
            return;
        }

        const parts =
            normalizeParts(raw);

        questionnaireContent.innerHTML = '';

        if (!parts.length) {

            questionnaireContent.innerHTML = `
                <div class="questionnaire-empty">
                    <div class="questionnaire-empty-icon">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"></path>
                            <path d="M4 5.5v16"></path>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h8"></path>
                        </svg>
                    </div>

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


        if (questionnaireTotal) {
            questionnaireTotal.textContent =
                `${parts.length} Part`;
        }


        parts.forEach(function (part, partIndex) {

            const partWrapper =
                document.createElement('div');

            partWrapper.className =
                'questionnaire-part';

            const header =
                document.createElement('button');

            header.type = 'button';
            header.className =
                'questionnaire-part-header';

            const number =
                escapeHtmlValue(
                    part.number || partIndex + 1
                );

            const title =
                escapeHtmlValue(
                    part.title || `Part ${part.number || partIndex + 1}`
                );

            const questionCount =
                part.questions.length;

            header.innerHTML = `
                <span class="questionnaire-part-number">
                    ${number}
                </span>

                <span class="questionnaire-part-title">
                    <strong>${title}</strong>
                    <span>
                        ${questionCount} pertanyaan
                    </span>
                </span>

                <span class="questionnaire-part-chevron">
                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </span>
            `;


            const body =
                document.createElement('div');

            body.className =
                'questionnaire-part-body';


            if (!questionCount) {

                body.innerHTML = `
                    <div class="questionnaire-empty">
                        <strong>Belum ada pertanyaan</strong>
                        <span>
                            Pertanyaan untuk part ini belum tersedia.
                        </span>
                    </div>
                `;

            } else {

                part.questions.forEach(
                    function (question, questionIndex) {

                        const questionBox =
                            document.createElement('div');

                        questionBox.className =
                            'questionnaire-question';

                        const questionNumber =
                            escapeHtmlValue(
                                question.number ||
                                questionIndex + 1
                            );

                        const questionText =
                            escapeHtmlValue(
                                question.text ||
                                `Pertanyaan ${questionIndex + 1}`
                            );

                        const answer =
                            question.answer || '';

                        const answerHtml =
                            answer !== ''
                                ? escapeHtmlValue(answer)
                                : 'Belum diisi';

                        questionBox.innerHTML = `
                            <div class="questionnaire-question-number">
                                PERTANYAAN ${questionNumber}
                            </div>

                            <div class="questionnaire-question-text">
                                ${questionText}
                            </div>

                            <div class="questionnaire-answer-label">
                                Jawaban Responden
                            </div>

                            <div class="questionnaire-answer ${answer === '' ? 'is-empty' : ''}">
                                ${answerHtml}
                            </div>
                        `;

                        body.appendChild(questionBox);

                    }
                );

            }


            partWrapper.appendChild(header);
            partWrapper.appendChild(body);
            questionnaireContent.appendChild(partWrapper);


            header.addEventListener(
                'click',
                function () {

                    const isOpen =
                        partWrapper.classList.contains(
                            'is-open'
                        );

                    /*
                     * Tutup semua part lain.
                     */
                    questionnaireContent
                        .querySelectorAll(
                            '.questionnaire-part'
                        )
                        .forEach(
                            function (otherPart) {

                                otherPart.classList.remove(
                                    'is-open'
                                );

                            }
                        );

                    /*
                     * Kalau sebelumnya tertutup,
                     * buka part yang baru diklik.
                     */
                    if (!isOpen) {

                        partWrapper.classList.add(
                            'is-open'
                        );

                    }

                }
            );

        });

    }


    /* =====================================================
       OPEN MODAL
    ====================================================== */

    function openVerificationModal(row) {

        if (!verificationModal || !row) {
            return;
        }

        const id =
            row.dataset.id || '';

        const noKK =
            row.dataset.noKk || '-';

        const nik =
            row.dataset.nik || '-';

        const nama =
            row.dataset.nama || '-';

        const wilayah =
            row.dataset.wilayah || '-';

        const petugas =
            row.dataset.petugas || '-';

        const anggota =
            row.dataset.anggota || '0';

        const tanggal =
            row.dataset.tanggal || '-';

        const status =
            row.dataset.status || '';

        const statusLabel =
            row.dataset.statusLabel || '-';


        if (modalNoKK) {
            modalNoKK.textContent = noKK;
        }

        if (modalNIK) {
            modalNIK.textContent = nik;
        }

        if (modalNama) {
            modalNama.textContent = nama;
        }

        if (modalWilayah) {
            modalWilayah.textContent = wilayah;
        }

        if (modalPetugas) {
            modalPetugas.textContent = petugas;
        }

        if (modalAnggota) {
            modalAnggota.textContent =
                anggota === '0'
                    ? '0 Orang'
                    : `${anggota} Orang`;
        }

        if (modalTanggal) {
            modalTanggal.textContent = tanggal;
        }

        if (modalStatusLabel) {
            modalStatusLabel.textContent = statusLabel;
        }

        if (modalSummaryNama) {
            modalSummaryNama.textContent = nama;
        }

        if (modalSummaryNoKK) {
            modalSummaryNoKK.textContent = noKK;
        }

        if (modalSummaryStatus) {
            modalSummaryStatus.textContent = statusLabel;
        }


        let normalizedStatus =
            status
                .toLowerCase()
                .replace(/[\s-]+/g, '_');


        if (normalizedStatus === 'disetujui') {
            normalizedStatus = 'approved';
        }

        if (normalizedStatus === 'ditolak') {
            normalizedStatus = 'rejected';
        }


        if (
            normalizedStatus !== 'approved' &&
            normalizedStatus !== 'rejected'
        ) {

            if (modalStatus) {
                modalStatus.value = '';
            }

        } else {

            if (modalStatus) {
                modalStatus.value =
                    normalizedStatus;
            }

        }


        if (
            verificationUpdateForm &&
            id
        ) {

            verificationUpdateForm.action =
                updateRouteTemplate.replace(
                    '__ID__',
                    encodeURIComponent(id)
                );

        }


        let rawQuestionnaire = [];

        try {

            rawQuestionnaire =
                row.dataset.kuisioner
                    ? JSON.parse(
                        row.dataset.kuisioner
                    )
                    : [];

        } catch (error) {

            rawQuestionnaire = [];

        }

        renderQuestionnaire(
            rawQuestionnaire
        );


        verificationModal.classList.add('show');

        verificationModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';

    }


    /* =====================================================
       QUICK ACTION SETUJUI / TOLAK
       Tetap menggunakan form + route yang sama.
    ====================================================== */

    function submitVerificationStatus(status) {

        if (!verificationUpdateForm) {
            return;
        }

        if (modalStatus) {
            modalStatus.value = status;
        }

        verificationUpdateForm.requestSubmit();

    }


    if (approveVerificationButton) {

        approveVerificationButton.addEventListener(
            'click',
            function () {

                submitVerificationStatus(
                    'approved'
                );

            }
        );

    }


    if (rejectVerificationButton) {

        rejectVerificationButton.addEventListener(
            'click',
            function () {

                submitVerificationStatus(
                    'rejected'
                );

            }
        );

    }


    /* =====================================================
       CLOSE MODAL
    ====================================================== */

    function closeModal() {

        if (!verificationModal) {
            return;
        }

        verificationModal.classList.remove(
            'show'
        );

        verificationModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow = '';

    }


    /* =====================================================
       BUTTON DETAIL
    ====================================================== */

    document
        .querySelectorAll('.btn-open-detail')
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const row =
                            this.closest(
                                '.data-row'
                            );

                        openVerificationModal(
                            row
                        );

                    }
                );

            }
        );


    /* =====================================================
       CLOSE BUTTON
    ====================================================== */

    if (closeVerificationModal) {

        closeVerificationModal.addEventListener(
            'click',
            closeModal
        );

    }


    if (cancelVerificationModal) {

        cancelVerificationModal.addEventListener(
            'click',
            closeModal
        );

    }


    /* =====================================================
       CLICK BACKDROP
    ====================================================== */

    if (verificationModalOverlay) {

        verificationModalOverlay.addEventListener(
            'click',
            closeModal
        );

    }


    /* =====================================================
       ESCAPE
    ====================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                verificationModal &&
                verificationModal.classList.contains('show')
            ) {

                closeModal();

            }

        }
    );

});

</script>
@endpush