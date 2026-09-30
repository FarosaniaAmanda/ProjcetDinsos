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
        font-size: 12px; 
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
        padding: 18px 26px 24px; 
        box-sizing: border-box; 
    } 
 
    .data-table { 
        width: 100%; 
        min-width: 1450px; 
        border-collapse: separate; 
        border-spacing: 0; 
        table-layout: fixed; 
        border: 1px solid #e8e9ef; 
        border-radius: 12px; 
        overflow: hidden; 
    } 
 
    .data-table th { 
        padding: 14px 18px; 
        background: #f8f8fb; 
        border-bottom: 1px solid #e8e9ef; 
        color: #666; 
        font-size: 11px; 
        font-weight: 700; 
        text-align: left; 
        white-space: nowrap; 
    } 
 
    .data-table td { 
        padding: 16px 18px; 
        border-bottom: 1px solid #eeeef2; 
        color: #444; 
        font-size: 13px; 
        vertical-align: middle; 
        white-space: nowrap; 
    } 
 
    .data-table th:nth-child(1), 
    .data-table td:nth-child(1) { 
        width: 55px; 
        text-align: center; 
    } 
 
    .data-table th:nth-child(2), 
    .data-table td:nth-child(2) { 
        width: 180px; 
    } 
 
    .data-table th:nth-child(3), 
    .data-table td:nth-child(3) { 
        width: 175px; 
    } 
 
    .data-table th:nth-child(4), 
    .data-table td:nth-child(4) { 
        width: 220px; 
    } 
 
    .data-table th:nth-child(5), 
    .data-table td:nth-child(5) { 
        width: 190px; 
    } 
 
    .data-table th:nth-child(6), 
    .data-table td:nth-child(6) { 
        width: 160px; 
    } 
 
    .data-table th:nth-child(7), 
    .data-table td:nth-child(7) { 
        width: 220px; 
    } 
 
    .data-table th:nth-child(8), 
    .data-table td:nth-child(8) { 
        width: 120px; 
    } 
 
    .data-table th:nth-child(9), 
    .data-table td:nth-child(9) { 
        width: 180px; 
    } 
 
    .data-table th:nth-child(10), 
    .data-table td:nth-child(10) { 
        width: 250px; 
    } 
 
    .data-table td:nth-child(4), 
    .data-table td:nth-child(7), 
    .data-table td:nth-child(9) { 
        white-space: normal; 
        line-height: 1.5; 
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
        flex-wrap: wrap; 
        gap: 7px; 
        min-width: 220px; 
    } 
 
    .action-form { 
        display: inline-flex; 
        margin: 0; 
    } 
 
    .btn-detail, 
    .btn-status { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        min-height: 34px; 
        padding: 0 12px; 
        border: none; 
        border-radius: 8px; 
        text-decoration: none; 
        font-size: 12px; 
        font-weight: 700; 
        transition: all .2s ease; 
        white-space: nowrap; 
        cursor: pointer; 
    } 
 
    .btn-detail { 
        background: #eef0ff; 
        color: #252A86; 
    } 
 
    .btn-detail:hover { 
        background: #252A86; 
        color: #ffffff; 
    } 
 
    .btn-status-approve { 
        background: #eaf8ef; 
        color: #24723c; 
    } 
 
    .btn-status-approve:hover { 
        background: #24723c; 
        color: #ffffff; 
    } 
 
    .btn-status-reject { 
        background: #fdecec; 
        color: #a53636; 
    } 
 
    .btn-status-reject:hover { 
        background: #a53636; 
        color: #ffffff; 
    } 
 
    .btn-member-detail { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        gap: 6px; 
        margin-top: 7px; 
        min-height: 31px; 
        padding: 0 10px; 
        border: 1px solid #d7daf2; 
        border-radius: 8px; 
        background: #f8f9ff; 
        color: #252A86; 
        font-size: 11px; 
        font-weight: 700; 
        cursor: pointer; 
        transition: .2s ease; 
    } 
 
    .btn-member-detail:hover { 
        background: #252A86; 
        color: #ffffff; 
        border-color: #252A86; 
    } 
 
    .member-count-cell { 
        white-space: normal !important; 
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
       MODAL DETAIL ANGGOTA KELUARGA - EXTRA LARGE 
    ====================================================== */ 
 
    .family-members-modal-box { 
        width: min(1180px, calc(100vw - 40px)); 
        max-width: 1180px; 
        max-height: calc(100vh - 40px); 
    } 
 
    .family-members-modal-body { 
        padding: 20px 22px 24px; 
        overflow-y: auto; 
        max-height: calc(100vh - 125px); 
    } 
 
    .family-members-summary { 
        display: grid; 
        grid-template-columns: repeat(4, minmax(0, 1fr)); 
        gap: 12px; 
        margin-bottom: 18px; 
    } 
 
    .family-members-summary-card { 
        padding: 13px 15px; 
        border: 1px solid #e7e9f1; 
        border-radius: 10px; 
        background: #f8f9fc; 
    } 
 
    .family-members-summary-card span { 
        display: block; 
        margin-bottom: 5px; 
        color: #7b8192; 
        font-size: 10px; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: .3px; 
    } 
 
    .family-members-summary-card strong { 
        display: block; 
        color: #252A86; 
        font-size: 14px; 
        line-height: 1.4; 
        word-break: break-word; 
    } 
 
    .family-members-table-wrapper { 
        width: 100%; 
        overflow-x: auto; 
        border: 1px solid #e7e9f1; 
        border-radius: 12px; 
    } 
 
    .family-members-table { 
        width: 100%; 
        min-width: 760px; 
        border-collapse: collapse; 
    } 
 
    .family-members-table th { 
        padding: 13px 15px; 
        background: #f1f3ff; 
        border-bottom: 1px solid #e1e4ef; 
        color: #4b5563; 
        font-size: 12px; 
        font-weight: 700; 
        text-align: left; 
        white-space: nowrap; 
    } 
 
    .family-members-table td { 
        padding: 14px 15px; 
        border-bottom: 1px solid #eef1f6; 
        color: #475569; 
        font-size: 13px; 
        vertical-align: middle; 
    } 
 
    .family-members-table tr:last-child td { 
        border-bottom: none; 
    } 
 
    .family-members-table tbody tr:hover { 
        background: #fafbff; 
    } 
 
    .family-member-number { 
        width: 55px; 
        text-align: center; 
        color: #252A86 !important; 
        font-weight: 700; 
    } 
 
    .family-member-name { 
        color: #252A86 !important; 
        font-weight: 700; 
    } 
 
    .family-member-status { 
        display: inline-flex; 
        padding: 5px 9px; 
        border-radius: 999px; 
        background: #eef0ff; 
        color: #252A86; 
        font-size: 11px; 
        font-weight: 700; 
    } 
 
    .family-members-empty { 
        padding: 45px 20px; 
        text-align: center; 
        color: #64748b; 
    } 
 
    .family-members-empty strong { 
        display: block; 
        margin-bottom: 5px; 
        color: #252A86; 
        font-size: 14px; 
    } 
 
    .family-members-empty span { 
        font-size: 12px; 
    } 
 
    /* ===================================================== 
       RESPONSIVE 
    ====================================================== */ 
 
    @media (max-width: 1200px) { 
 
    .verification-modal-box { 
        width: calc(100vw - 30px); 
        max-width: none; 
    } 
 
} 
 
@media (max-width: 700px) { 
 
    .verification-modal { 
        padding: 10px; 
    } 
 
    .verification-modal-box { 
        width: calc(100vw - 20px); 
        max-width: none; 
        max-height: calc(100vh - 20px); 
        border-radius: 12px; 
    } 
 
    .questionnaire-part-header { 
        min-height: 56px; 
        padding: 10px 12px; 
    } 
 
    .questionnaire-part-number { 
        flex-basis: 32px; 
        width: 32px; 
        height: 32px; 
    } 
 
    .questionnaire-part-title strong { 
        font-size: 12px; 
    } 
 
    .questionnaire-part-body { 
        padding: 10px; 
    } 
 
} 
 
        /* ===================================================== 
       DROPDOWN ANGGOTA DI DALAM KARTU JUMLAH ANGGOTA 
    ====================================================== */ 
 
    .verification-member-info { 
        position: relative; 
        z-index: 20; 
    } 
 
    .verification-member-info:has(.family-members-inline:not([hidden])) { 
        z-index: 100; 
    } 
 
  /* ===================================================== 
   DROPDOWN DETAIL ANGGOTA KELUARGA 
===================================================== */ 
 
.verification-member-info { 
    position: relative; 
    overflow: visible !important; 
    z-index: 20; 
} 
 
.verification-member-info .btn-member-detail { 
    position: relative; 
    z-index: 22; 
} 
 
.family-members-inline { 
    position: absolute; 
    top: calc(100% + 10px); 
    right: 0; 
 
    width: 560px; 
    max-width: min(560px, 75vw); 
 
    padding: 18px; 
 
    background: #ffffff; 
    border: 1px solid #e1e5ef; 
    border-radius: 14px; 
 
    box-shadow: 
        0 14px 35px rgba(31, 41, 55, 0.14), 
        0 4px 10px rgba(31, 41, 55, 0.06); 
 
    z-index: 100; 
 
    animation: familyDropdownOpen .18s ease; 
} 
 
.family-members-inline[hidden] { 
    display: none; 
} 
 
/* HEADER */ 
 
.family-members-inline-header { 
    display: flex; 
    align-items: flex-start; 
    justify-content: space-between; 
    gap: 16px; 
 
    padding-bottom: 14px; 
    margin-bottom: 14px; 
 
    border-bottom: 1px solid #edf0f5; 
} 
 
.family-members-inline-header h4 { 
    margin: 4px 0 5px; 
 
    color: #252A86; 
    font-size: 17px; 
    font-weight: 700; 
} 
 
.family-members-inline-header p { 
    margin: 0; 
 
    color: #73798a; 
    font-size: 12px; 
    line-height: 1.5; 
} 
 
/* RINGKASAN KEPALA KELUARGA */ 
 
.family-members-inline-summary { 
    flex-shrink: 0; 
 
    min-width: 135px; 
 
    padding: 9px 12px; 
 
    border: 1px solid #e2e6f4; 
    border-radius: 9px; 
 
    background: #f7f8fd; 
 
    text-align: right; 
} 
 
.family-members-inline-summary span { 
    display: block; 
 
    margin-bottom: 3px; 
 
    color: #858b99; 
    font-size: 10px; 
    font-weight: 600; 
} 
 
.family-members-inline-summary strong { 
    display: block; 
 
    color: #252A86; 
    font-size: 12px; 
    font-weight: 700; 
} 
 
.family-members-inline-summary small { 
    display: block; 
 
    margin-top: 2px; 
 
    color: #6b7280; 
    font-size: 10px; 
} 
 
/* TABLE */ 
 
.family-members-inline-table-wrapper { 
    width: 100%; 
 
    max-height: 280px; 
 
    overflow: auto; 
 
    border: 1px solid #e5e7eb; 
    border-radius: 10px; 
 
    background: #ffffff; 
} 
 
.family-members-inline-table { 
    width: 100%; 
 
    min-width: 500px; 
 
    border-collapse: collapse; 
} 
 
.family-members-inline-table th { 
    position: sticky; 
    top: 0; 
    z-index: 2; 
 
    padding: 11px 12px; 
 
    background: #f4f6fa; 
 
    color: #555d70; 
 
    font-size: 11px; 
    font-weight: 700; 
 
    text-align: left; 
 
    border-bottom: 1px solid #e3e6ed; 
 
    white-space: nowrap; 
} 
 
.family-members-inline-table td { 
    padding: 12px; 
 
    color: #454b59; 
 
    font-size: 12px; 
 
    border-bottom: 1px solid #edf0f4; 
 
    vertical-align: middle; 
} 
 
.family-members-inline-table tbody tr:last-child td { 
    border-bottom: none; 
} 
 
.family-members-inline-table tbody tr:hover { 
    background: #fafbfe; 
} 
 
.family-member-name { 
    color: #252A86 !important; 
    font-weight: 600; 
} 
 
.family-member-status { 
    display: inline-flex; 
    align-items: center; 
 
    padding: 4px 8px; 
 
    border-radius: 999px; 
 
    background: #eef0f7; 
 
    color: #626978; 
 
    font-size: 10px; 
    font-weight: 600; 
} 
 
/* BUTTON SAAT TERBUKA */ 
 
.btn-open-members.is-open { 
    background: #eef1ff; 
    border-color: #cfd5f5; 
    color: #252A86; 
} 
 
/* ICON */ 
 
#membersDropdownIcon { 
    transition: transform .2s ease; 
} 
 
/* TOMBOL TUTUP PANEL ANGGOTA */
.family-members-inline-title { min-width: 0; flex: 1; }
.family-members-close { flex: 0 0 auto; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #e1e5ef; border-radius: 10px; background: #fff; color: #626978; cursor: pointer; transition: .18s ease; }
.family-members-close:hover { background: #f4f5fa; color: #252A86; border-color: #cfd5f5; }
.family-members-close:focus-visible { outline: 3px solid rgba(37,42,134,.15); outline-offset: 2px; }
@media (max-width: 600px) {
    .family-members-inline {
        position: fixed !important;
        top: 12px !important;
        right: 12px !important;
        bottom: 12px !important;
        left: 12px !important;
        width: auto !important;
        max-width: none !important;
        max-height: none !important;
        height: auto;
        box-sizing: border-box;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding: 16px;
        border-radius: 16px;
        z-index: 10000;
    }

    .family-members-inline[hidden] { display: none !important; }

    .family-members-inline-header {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) 42px;
        grid-template-areas: "title close" "summary summary";
        align-items: start !important;
        gap: 12px;
        padding-bottom: 14px;
        margin-bottom: 14px;
    }

    .family-members-inline-title {
        grid-area: title;
        min-width: 0;
    }

    .family-members-inline-title h4 {
        margin: 0 0 5px;
        font-size: 17px;
        line-height: 1.25;
    }

    .family-members-inline-title p {
        margin: 0;
        font-size: 12px;
        line-height: 1.5;
    }

    .family-members-close {
        grid-area: close;
        width: 42px;
        height: 42px;
        min-width: 42px;
        margin: 0;
    }

    .family-members-inline-summary {
        grid-area: summary;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        padding: 10px 12px;
        text-align: left;
    }

    .family-members-inline-table-wrapper {
        flex: 1 1 auto;
        min-height: 0;
        max-height: none !important;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
    }
}

/* EMPTY */ 
 
.family-members-empty { 
    padding: 25px 15px; 
 
    text-align: center; 
} 
 
.family-members-empty strong { 
    display: block; 
 
    margin-bottom: 5px; 
 
    color: #252A86; 
 
    font-size: 13px; 
} 
 
.family-members-empty span { 
    color: #73798a; 
 
    font-size: 11px; 
} 
 
/* ANIMASI */ 
 
@keyframes familyDropdownOpen { 
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
   RESPONSIVE 
===================================================== */ 
 
@media (max-width: 900px) { 
 
    .family-members-inline { 
        right: auto; 
        left: 0; 
 
        width: 520px; 
        max-width: calc(100vw - 60px); 
    } 
 
} 
 
@media (max-width: 600px) { 
 
    .family-members-inline { 
        position: absolute; 
 
        left: auto; 
        right: 0; 
 
        width: calc(100vw - 40px); 
        max-width: none; 
 
        padding: 13px; 
    } 
 
    .family-members-inline-header { 
        flex-direction: column; 
    } 
 
    .family-members-inline-summary { 
        width: 100%; 
        text-align: left; 
    } 
 
} 
 
    @media (max-width: 900px) { 
        .family-members-inline { 
            right: auto; 
            left: 0; 
            width: min(620px, calc(100vw - 60px)); 
        } 
 
        .family-members-inline::before { 
            left: 28px; 
            right: auto; 
        } 
    } 
 
    @media (max-width: 600px) { 
        .family-members-inline { 
            position: fixed; 
            top: auto; 
            right: 12px; 
            bottom: 12px; 
            left: 12px; 
            width: auto; 
            max-height: calc(100vh - 24px); 
            padding: 14px; 
            border-radius: 14px; 
            z-index: 9999; 
        } 
 
        .family-members-inline::before { 
            display: none; 
        } 
 
        .family-members-inline-header { 
            align-items: flex-start; 
            flex-direction: column; 
        } 
 
        .family-members-inline-summary { 
            width: 100%; 
            box-sizing: border-box; 
        } 
 
        .family-members-inline-table-wrapper { 
            max-height: 45vh; 
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
   MODAL DETAIL VERIFIKASI - EXTRA LARGE 
========================================================= */ 
 
.verification-modal-box { 
    position: relative; 
    z-index: 2; 
    width: min(1250px, calc(100vw - 40px)); 
    max-width: 1250px; 
    max-height: calc(100vh - 40px); 
    overflow: hidden; 
    background: #ffffff; 
    border-radius: 16px; 
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18); 
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
    margin-top: 4px;
    color: #263238;
    font-size: 14px;
    font-weight: 700;
}

.verification-summary-status {
    color: #B42318 !important;
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
    color: #6B7280;
    font-weight: 600;
}

.verification-info-item strong {
    display: block;
    overflow: hidden;
    font-size: 13px;
    line-height: 1.4;
    color: #263238;
    font-weight: 700;
    text-overflow: ellipsis;
}
.verification-info-item strong#modalStatusLabel {
    color: #B42318;
    font-weight: 700;
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
 
/* =========================================================
   HASIL KUISIONER - MASTER DETAIL
   PART DI KIRI | ISI PART DI KANAN
========================================================= */
.questionnaire-parts {
    display: grid;
    grid-template-columns: 250px minmax(0, 1fr);
    gap: 16px;
    width: 100%;
    min-height: 430px;
}
.questionnaire-sidebar {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 10px;
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    background: #f8f9fc;
    align-self: stretch;
}
.questionnaire-sidebar-title {
    padding: 6px 8px 9px;
    font-size: 11px;
    font-weight: 800;
    color: #5c6272;
    text-transform: uppercase;
    letter-spacing: .6px;
}
.questionnaire-part {
    width: 100%;
    overflow: hidden;
    border: 1px solid transparent;
    border-radius: 10px;
    background: transparent;
    transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
}
.questionnaire-part-header {
    width: 100%;
    min-height: 64px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 11px;
    border: 1px solid #e3e6ef;
    border-radius: 10px;
    background: #ffffff;
    color: #303545;
    text-align: left;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, transform .15s ease;
}
.questionnaire-part-header:hover {
    background: #f3f5ff;
    border-color: #cdd2f2;
}
.questionnaire-part-header:active { transform: scale(.99); }
.questionnaire-part.is-active .questionnaire-part-header {
    background: #eef0ff;
    border-color: #252A86;
    box-shadow: 0 3px 10px rgba(37, 42, 134, .08);
}
.questionnaire-part-number {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eef0f5;
    color: #555b6b;
    font-size: 11px;
    font-weight: 800;
}
.questionnaire-part.is-active .questionnaire-part-number {
    background: #252A86;
    color: #ffffff;
}
.questionnaire-part-title { min-width: 0; flex: 1; }
.questionnaire-part-title strong {
    display: block;
    overflow: hidden;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.35;
    color: #303545;
    text-overflow: ellipsis;
}
.questionnaire-part.is-active .questionnaire-part-title strong { color: #252A86; }
.questionnaire-part-title span {
    display: block;
    margin-top: 3px;
    font-size: 10px;
    line-height: 1.4;
    color: #858b99;
}
.questionnaire-part-chevron {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 24px;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #f1f2f6;
    color: #777d8c;
    transition: transform .2s ease, background .2s ease, color .2s ease;
}
.questionnaire-part.is-active .questionnaire-part-chevron {
    background: #252A86;
    color: #ffffff;
    transform: rotate(180deg);
}
.questionnaire-part-body { display: none !important; }
.questionnaire-content-panel {
    min-width: 0;
    display: flex;
    flex-direction: column;
    border: 1px solid #e3e6ef;
    border-radius: 12px;
    background: #ffffff;
    overflow: hidden;
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
.questionnaire-content-heading { min-width: 0; }
.questionnaire-content-kicker {
    display: block;
    margin-bottom: 3px;
    font-size: 9px;
    font-weight: 800;
    color: #7c8291;
    text-transform: uppercase;
    letter-spacing: .7px;
}
.questionnaire-content-title {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    line-height: 1.35;
    color: #252A86;
}
.questionnaire-content-count {
    flex: 0 0 auto;
    padding: 6px 10px;
    border-radius: 999px;
    background: #eef0ff;
    color: #252A86;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}
.questionnaire-content-body {
    flex: 1;
    padding: 15px;
    max-height: 520px;
    overflow-y: auto;
    background: #ffffff;
}
.questionnaire-content-body::-webkit-scrollbar { width: 7px; }
.questionnaire-content-body::-webkit-scrollbar-track { background: #f5f6fa; }
.questionnaire-content-body::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background: #cdd1dc;
}
.questionnaire-question {
    padding: 15px;
    border: 1px solid #e5e7ee;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 2px 7px rgba(30, 35, 60, .03);
}
.questionnaire-question + .questionnaire-question { margin-top: 10px; }
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
    font-size: 13px;
    font-weight: 600;
    line-height: 1.65;
    color: #374151;
}
.questionnaire-answer-label {
    margin-bottom: 5px;
    font-size: 10px;
    font-weight: 800;
    color: #656b79;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.questionnaire-answer {
    min-height: 42px;
    padding: 10px 12px;
    border-left: 3px solid #55B5D5;
    border-radius: 7px;
    background: #f5f8fa;
    color: #374151;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.6;
    white-space: pre-wrap;
    word-break: break-word;
}
.questionnaire-answer.is-empty {
    border-left-color: #F5C928;
    background: #fff9e8;
    color: #8a6a20;
}
.questionnaire-empty {
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 25px;
    border: 1px dashed #dfe2eb;
    border-radius: 10px;
    background: #fafbfc;
    text-align: center;
}
.questionnaire-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    margin-bottom: 10px;
    border-radius: 50%;
    background: #eef0ff;
    color: #252A86;
}
.questionnaire-empty strong { font-size: 13px; color: #4a5060; }
.questionnaire-empty span {
    max-width: 400px;
    margin-top: 5px;
    font-size: 11px;
    line-height: 1.5;
    color: #8a90a0;
}
@media (max-width: 850px) {
    .questionnaire-parts { grid-template-columns: 190px minmax(0, 1fr); gap: 10px; }
    .questionnaire-part-header { min-height: 58px; padding: 8px; gap: 7px; }
    .questionnaire-part-number { flex-basis: 30px; width: 30px; height: 30px; }
    .questionnaire-part-title strong { font-size: 11px; }
    .questionnaire-content-body { max-height: 480px; }
}
@media (max-width: 650px) {
    .questionnaire-parts { grid-template-columns: 1fr; }
    .questionnaire-sidebar { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .questionnaire-sidebar-title { grid-column: 1 / -1; }
    .questionnaire-content-body { max-height: 450px; }
    .questionnaire-content-title { font-size: 14px; }
    .questionnaire-question-text,
    .questionnaire-answer { font-size: 12px; }
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
 
    .verification-action-section.is-hidden { 
        display: none; 
    } 
 
    .verification-member-info { 
        display: flex; 
        flex-direction: column; 
        align-items: flex-start; 
    } 
 
    .verification-member-info .btn-member-detail { 
        margin-top: 9px; 
    } 
 
    .verification-select-wrapper { 
        position: relative; 
    } 
 
    .verification-select-wrapper select { 
        appearance: none; 
        -webkit-appearance: none; 
        padding-right: 40px; 
    } 
 
    .verification-select-wrapper svg { 
        position: absolute; 
        right: 13px; 
        top: 50%; 
        transform: translateY(-50%); 
        color: #73798a; 
        pointer-events: none; 
    } 
 
@media (max-width: 900px) { 
    .verification-info-grid { 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
    } 
} 
 
@media (max-width: 700px) { 
    .family-members-inline { 
        padding: 13px; 
    } 
 
    .family-members-inline-header { 
        align-items: flex-start; 
        flex-direction: column; 
    } 
 
 
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
 
    .family-members-modal-box { 
        width: calc(100vw - 24px); 
        max-height: calc(100vh - 24px); 
    } 
 
    .family-members-modal-body { 
        padding: 14px; 
        max-height: calc(100vh - 105px); 
    } 
 
    .family-members-summary { 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
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
 
    .family-members-summary { 
        grid-template-columns: 1fr; 
    } 
 
    .verification-btn { 
        width: 100%; 
    } 
 
    .verification-btn-secondary { 
        grid-column: 1 / -1; 
    } 
} 
 


/* =====================================================
   FINAL MOBILE - HEADER ANGGOTA KELUARGA
   Merapikan judul, deskripsi, dan tombol tutup
===================================================== */
@media (max-width: 600px) {
    .family-members-inline {
        padding: 16px !important;
    }

    .family-members-inline-header {
        display: block !important;
        position: relative !important;
        padding-bottom: 14px !important;
        margin-bottom: 14px !important;
    }

    /* Area judul */
    .family-members-inline-header > div:first-child {
        position: relative !important;
        width: 100% !important;
        min-width: 0 !important;
        padding-right: 52px !important;
        box-sizing: border-box !important;
    }

    .family-members-inline-title {
        width: 100% !important;
        min-width: 0 !important;
    }

    .family-members-inline-header h4 {
        margin: 0 0 6px !important;
        padding: 0 !important;
        font-size: 18px !important;
        line-height: 1.25 !important;
        white-space: normal !important;
        overflow-wrap: anywhere !important;
    }

    .family-members-inline-header p {
        margin: 0 !important;
        max-width: 100% !important;
        font-size: 12px !important;
        line-height: 1.55 !important;
        white-space: normal !important;
        overflow-wrap: break-word !important;
    }

    /* Tombol X tetap di kanan atas, tidak turun ke bawah teks */
    .family-members-inline-header .family-members-close {
        position: absolute !important;
        top: 0 !important;
        right: 0 !important;
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        margin: 0 !important;
        flex: none !important;
    }

    /* Ringkasan Kepala Keluarga berada di bawah deskripsi */
    .family-members-inline-summary {
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        margin-top: 14px !important;
        padding: 10px 12px !important;
        box-sizing: border-box !important;
        text-align: left !important;
    }

    .family-members-inline-summary span,
    .family-members-inline-summary strong,
    .family-members-inline-summary small {
        white-space: normal !important;
        overflow-wrap: anywhere !important;
    }
}

@media (max-width: 380px) {
    .family-members-inline {
        padding: 13px !important;
    }

    .family-members-inline-header h4 {
        font-size: 17px !important;
    }

    .family-members-inline-header p {
        font-size: 11.5px !important;
        line-height: 1.5 !important;
    }

    .family-members-inline-header > div:first-child {
        padding-right: 48px !important;
    }

    .family-members-inline-header .family-members-close {
        width: 38px !important;
        height: 38px !important;
        min-width: 38px !important;
    }
}

/* =========================================================
   MOBILE - STATUS ACTION TETAP TERLIHAT DAN RAPI
   Tidak menghapus aksi Setujui / Tolak.
========================================================= */
@media (max-width: 768px) {
    .verification-action-section {
        display: block !important;
        width: 100%;
        margin-top: 16px;
        padding: 16px;
        box-sizing: border-box;
    }

    .verification-action-section.is-hidden {
        display: none !important;
    }

    .verification-action-section .verification-section-heading h3 {
        font-size: 15px;
        line-height: 1.35;
        margin-bottom: 5px;
    }

    .verification-action-section .verification-section-heading p {
        font-size: 12px;
        line-height: 1.55;
        margin-bottom: 12px;
    }

    .verification-action-section .verification-form-group {
        width: 100%;
    }

    .verification-action-section .verification-select-wrapper,
    .verification-action-section .verification-select-wrapper select {
        width: 100%;
        box-sizing: border-box;
    }

    .verification-action-section .verification-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        width: 100%;
    }

    .verification-action-section .verification-btn {
        width: 100%;
        min-height: 42px;
    }
}

@media (max-width: 600px) {
    .verification-action-section { margin-top: 14px; }
    .verification-action-section .verification-section-heading h3 { font-size: 16px; }
    .verification-action-section .verification-section-heading p { font-size: 12px; line-height: 1.5; }
    .verification-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .verification-actions .verification-btn { flex: 1 1 140px; min-height: 42px; }
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
 
      {{-- ================================================= 
     STATISTIK 
     DATA LANGSUNG DARI DATABASE 
================================================== --}} 
 
<div class="stats-section"> 
 
    <div class="stats-grid"> 
 
        {{-- TOTAL RESPONDEN --}} 
        <div class="stat-card"> 
 
            <div class="stat-label"> 
                Total Responden 
            </div> 
 
            <div class="stat-value"> 
                {{ $totalResponden ?? 0 }} 
            </div> 
 
        </div> 
 
 
        {{--  Menunggu Verifikasi --}} 
        <div class="stat-card"> 
 
            <div class="stat-label"> 
                Menunggu Verifikasi 
            </div> 
 
            <div class="stat-value"> 
                {{ $belumDidata ?? 0 }} 
            </div> 
 
        </div> 
 
 
        {{-- Draft --}} 
        <div class="stat-card"> 
 
            <div class="stat-label"> 
                Draft 
            </div> 
 
            <div class="stat-value"> 
                {{ $menungguVerifikasi ?? 0 }} 
            </div> 
 
        </div> 
 
 
        {{-- DISETUJUI --}} 
        <div class="stat-card"> 
 
            <div class="stat-label"> 
                Disetujui 
            </div> 
 
            <div class="stat-value"> 
                {{ $disetujui ?? 0 }} 
            </div> 
 
        </div> 
 
 
        {{-- DITOLAK --}} 
        <div class="stat-card"> 
 
            <div class="stat-label"> 
                Ditolak 
            </div> 
 
            <div class="stat-value"> 
                {{ $ditolak ?? 0 }} 
            </div> 
 
        </div> 
 
    </div> 
 
</div> 
 
 
        {{-- ================================================= 
             SEARCH 
        ================================================== --}} 
 
        <div class="search-section"> 
 
 
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
                        Data Yang Akan di Verifikasi 
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
                                data-kuisioner='@json($item["kuisioner"] ?? [])'
                                data-anggota-detail="{{ e(json_encode($item['anggota_detail'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}"
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
 
                                <td class="member-count-cell">
                                    <strong>
                                        {{ $item['anggota'] ?? 0 }} Orang
                                    </strong>
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
 
                     <div class="verification-info-item verification-member-info"> 
 
    <span>Jumlah Anggota Keluarga</span> 
 
    <strong id="modalAnggota">-</strong> 
 
    <button 
        type="button" 
        class="btn-member-detail btn-open-members" 
        id="openMembersFromDetail" 
        aria-expanded="false" 
        title="Lihat detail anggota keluarga" 
    > 
        <span>Lihat Anggota</span> 
 
        <svg 
            id="membersDropdownIcon" 
            width="14" 
            height="14" 
            viewBox="0 0 24 24" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="2" 
        > 
            <polyline points="6 9 12 15 18 9"></polyline> 
        </svg> 
    </button> 
 
 
    {{-- ================================================ 
         DROPDOWN ANGGOTA KELUARGA 
    ================================================= --}} 
 
    <div 
        class="family-members-inline" 
        id="familyMembersInline" 
        hidden 
    > 
 
        <div class="family-members-inline-header">

            <div class="family-members-inline-title">
                <h4>Anggota Keluarga</h4>
                <p>Daftar anggota keluarga untuk No. KK yang sedang diperiksa.</p>
            </div>

            <button type="button" class="family-members-close" id="familyMembersClose" aria-label="Tutup daftar anggota keluarga" title="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="family-members-inline-summary"> 
 
                <span> 
                    Kepala Keluarga 
                </span> 
 
                <strong id="familyInlineHead"> 
                    - 
                </strong> 
 
                <small id="familyInlineCount"> 
                    0 Orang 
                </small> 
 
            </div> 
 
        </div> 
 
 
        <div class="family-members-inline-table-wrapper"> 
 
            <table class="family-members-inline-table"> 
 
                <thead> 
 
                    <tr> 
                        <th>No.</th> 
                        <th>NIK</th> 
                        <th>Nama Lengkap</th> 
                        <th>Status Keluarga</th> 
                    </tr> 
 
                </thead> 
 
                <tbody id="familyMembersInlineTableBody"> 
 
                    <tr> 
 
                        <td colspan="4"> 
 
                            <div class="family-members-empty"> 
 
                                <strong> 
                                    Belum ada detail anggota 
                                </strong> 
 
                                <span> 
                                    Data anggota keluarga belum tersedia. 
                                </span> 
 
                            </div> 
 
                        </td> 
 
                    </tr> 
 
                </tbody> 
 
            </table> 
 
        </div> 
 
    </div> 
 
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
                            
 
                            <h3> 
                                Hasil Kuisioner 
                            </h3> 
 
                            <p> 
                                Pilih bagian kuisioner di sebelah kiri untuk melihat pertanyaan dan jawaban responden. 
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
                <section 
                    class="verification-detail-section verification-action-section" 
                    id="verificationActionSection" 
                > 
 
                    <div class="verification-section-heading"> 
                        <div> 
                             
 
                            <h3> 
                                Ubah Status Data 
                            </h3> 
 
                            <p> 
                                Status dapat diubah kembali. Data yang sudah disetujui dapat ditolak, dan data yang sudah ditolak dapat disetujui kembali. 
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
                            <label for="modalStatus">Aksi</label> 
 
                            <div class="verification-select-wrapper"> 
                                <select 
                                    id="modalStatus" 
                                    name="status" 
                                    required 
                                > 
                                    <option value="" disabled>Pilih Aksi</option> 
                                    <option value="approved">Setujui</option> 
                                    <option value="rejected">Tolak</option> 
                                </select> 
 
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"> 
                                    <polyline points="6 9 12 15 18 9"></polyline> 
                                </svg> 
                            </div> 
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
                                id="saveVerificationStatus" 
                            > 
                                Simpan Perubahan 
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
 
    const verificationActionSection = 
        document.getElementById('verificationActionSection'); 
 
    const openMembersFromDetail = 
        document.getElementById('openMembersFromDetail'); 
 
    let currentVerificationRow = null; 
 
    /* ===================================================== 
       DETAIL ANGGOTA DI DALAM MODAL DETAIL 
    ====================================================== */ 
 
    const familyMembersInline = 
        document.getElementById('familyMembersInline'); 
 
    const familyMembersInlineTableBody = 
        document.getElementById('familyMembersInlineTableBody'); 
 
    const familyInlineCount = 
        document.getElementById('familyInlineCount'); 
 
    const familyInlineHead = 
        document.getElementById('familyInlineHead'); 
 
    const familyInlineStatus = 
        document.getElementById('familyInlineStatus'); 
 
    const membersDropdownIcon = 
        document.getElementById('membersDropdownIcon');

    const familyMembersClose =
        document.getElementById('familyMembersClose');

    let familyMembersHistoryOpen = false; 
 
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
 
 
    function parseFamilyMembers(raw) {

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
 
        if (Array.isArray(raw)) { 
            return raw; 
        } 
 
        if (raw && Array.isArray(raw.data)) { 
            return raw.data; 
        } 
 
        if (raw && Array.isArray(raw.anggota)) { 
            return raw.anggota; 
        } 
 
        return []; 
 
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

        const parts = normalizeParts(raw);

        questionnaireContent.innerHTML = '';

        if (!parts.length) {
            questionnaireContent.innerHTML = `
                <div class="questionnaire-empty">
                    <div class="questionnaire-empty-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"></path>
                            <path d="M4 5.5v16"></path>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h8"></path>
                        </svg>
                    </div>
                    <strong>Belum Ada Hasil Kuisioner</strong>
                    <span>Hasil kuisioner untuk responden ini belum tersedia.</span>
                </div>
            `;

            if (questionnaireTotal) {
                questionnaireTotal.textContent = '0 Part';
            }

            return;
        }

        if (questionnaireTotal) {
            questionnaireTotal.textContent = `${parts.length} Part`;
        }

        const sidebar = document.createElement('div');
        sidebar.className = 'questionnaire-sidebar';

        const sidebarTitle = document.createElement('div');
        sidebarTitle.className = 'questionnaire-sidebar-title';
        sidebarTitle.textContent = 'Bagian Kuisioner';
        sidebar.appendChild(sidebarTitle);

        const contentPanel = document.createElement('div');
        contentPanel.className = 'questionnaire-content-panel';

        const contentHeader = document.createElement('div');
        contentHeader.className = 'questionnaire-content-header';

        const contentHeading = document.createElement('div');
        contentHeading.className = 'questionnaire-content-heading';

        const contentKicker = document.createElement('span');
        contentKicker.className = 'questionnaire-content-kicker';
        contentKicker.textContent = 'Bagian Terpilih';

        const contentTitle = document.createElement('h4');
        contentTitle.className = 'questionnaire-content-title';
        contentTitle.textContent = 'Pilih bagian kuisioner';

        contentHeading.appendChild(contentKicker);
        contentHeading.appendChild(contentTitle);

        const contentCount = document.createElement('span');
        contentCount.className = 'questionnaire-content-count';
        contentCount.textContent = '0 Pertanyaan';

        contentHeader.appendChild(contentHeading);
        contentHeader.appendChild(contentCount);

        const contentBody = document.createElement('div');
        contentBody.className = 'questionnaire-content-body';

        contentBody.innerHTML = `
            <div class="questionnaire-empty">
                <div class="questionnaire-empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"></path>
                        <path d="M4 5.5v16"></path>
                        <path d="M8 7h8"></path>
                        <path d="M8 11h8"></path>
                    </svg>
                </div>
                <strong>Pilih Bagian Kuisioner</strong>
                <span>Pilih salah satu bagian di sebelah kiri untuk melihat pertanyaan dan jawaban responden.</span>
            </div>
        `;

        contentPanel.appendChild(contentHeader);
        contentPanel.appendChild(contentBody);

        questionnaireContent.appendChild(sidebar);
        questionnaireContent.appendChild(contentPanel);

        let activePartElement = null;

        function showEmptySelection() {
            if (activePartElement) {
                activePartElement.classList.remove('is-active');
                activePartElement = null;
            }

            contentTitle.textContent = 'Pilih bagian kuisioner';
            contentCount.textContent = '0 Pertanyaan';

            contentBody.innerHTML = `
                <div class="questionnaire-empty">
                    <div class="questionnaire-empty-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"></path>
                            <path d="M4 5.5v16"></path>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h8"></path>
                        </svg>
                    </div>
                    <strong>Belum Ada Bagian Dipilih</strong>
                    <span>Pilih bagian kuisioner di sebelah kiri untuk melihat pertanyaan dan jawaban responden.</span>
                </div>
            `;
        }

        function showPart(part, partElement) {

            if (partElement.classList.contains('is-active')) {
                showEmptySelection();
                return;
            }

            sidebar.querySelectorAll('.questionnaire-part').forEach(function (otherPart) {
                otherPart.classList.remove('is-active');
            });

            partElement.classList.add('is-active');

            const partNumber = String(part.number ?? '-');
            const partTitle = String(
                part.title || `Part ${part.number || ''}`
            );

            contentTitle.textContent =
                `Part ${partNumber} — ${partTitle}`;

            const questionCount =
                Array.isArray(part.questions)
                    ? part.questions.length
                    : 0;

            contentCount.textContent =
                `${questionCount} Pertanyaan`;

            contentBody.innerHTML = '';

            if (!questionCount) {
                contentBody.innerHTML = `
                    <div class="questionnaire-empty">
                        <div class="questionnaire-empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"></path>
                                <path d="M4 5.5v16"></path>
                                <path d="M8 7h8"></path>
                                <path d="M8 11h8"></path>
                            </svg>
                        </div>
                        <strong>Belum Ada Pertanyaan</strong>
                        <span>Pertanyaan untuk bagian ini belum tersedia.</span>
                    </div>
                `;

                activePartElement = partElement;
                return;
            }

            part.questions.forEach(function (question, questionIndex) {

                const questionBox = document.createElement('div');
                questionBox.className = 'questionnaire-question';

                const questionNumber =
                    String(question.number ?? questionIndex + 1);

                const questionText =
                    String(
                        question.text ||
                        `Pertanyaan ${questionIndex + 1}`
                    );

                const answerText =
                    String(question.answer ?? '').trim();

                const questionNumberElement =
                    document.createElement('div');

                questionNumberElement.className =
                    'questionnaire-question-number';

                questionNumberElement.textContent =
                    `PERTANYAAN ${questionNumber}`;

                const questionTextElement =
                    document.createElement('div');

                questionTextElement.className =
                    'questionnaire-question-text';

                questionTextElement.textContent =
                    questionText;

                const answerLabel =
                    document.createElement('div');

                answerLabel.className =
                    'questionnaire-answer-label';

                answerLabel.textContent =
                    'Jawaban Responden';

                const answerElement =
                    document.createElement('div');

                answerElement.className =
                    'questionnaire-answer';

                if (answerText === '') {
                    answerElement.classList.add('is-empty');
                    answerElement.textContent =
                        'Belum diisi';
                } else {
                    answerElement.textContent =
                        answerText;
                }

                questionBox.appendChild(questionNumberElement);
                questionBox.appendChild(questionTextElement);
                questionBox.appendChild(answerLabel);
                questionBox.appendChild(answerElement);

                contentBody.appendChild(questionBox);
            });

            activePartElement = partElement;
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
                String(part.number ?? partIndex + 1);

            const title =
                String(
                    part.title ||
                    `Part ${part.number || partIndex + 1}`
                );

            const questionCount =
                Array.isArray(part.questions)
                    ? part.questions.length
                    : 0;

            const numberElement =
                document.createElement('span');

            numberElement.className =
                'questionnaire-part-number';

            numberElement.textContent =
                number;

            const titleWrapper =
                document.createElement('span');

            titleWrapper.className =
                'questionnaire-part-title';

            const titleStrong =
                document.createElement('strong');

            titleStrong.textContent =
                title;

            const titleCount =
                document.createElement('span');

            titleCount.textContent =
                `${questionCount} pertanyaan`;

            titleWrapper.appendChild(titleStrong);
            titleWrapper.appendChild(titleCount);

            const chevron =
                document.createElement('span');

            chevron.className =
                'questionnaire-part-chevron';

            chevron.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            `;

            header.appendChild(numberElement);
            header.appendChild(titleWrapper);
            header.appendChild(chevron);

            const body =
                document.createElement('div');

            body.className =
                'questionnaire-part-body';

            partWrapper.appendChild(header);
            partWrapper.appendChild(body);
            sidebar.appendChild(partWrapper);

            header.addEventListener('click', function () {
                showPart(part, partWrapper);
            });
        });

        // Sengaja tidak membuka Part 1 secara otomatis.
        // Pengguna harus memilih Part terlebih dahulu.
    }

    /* =====================================================
       DETAIL ANGGOTA - DROPDOWN DI DALAM MODAL DETAIL
    ====================================================== */
 
 
    function renderFamilyMembersInline(row) { 
 
        if (!row || !familyMembersInlineTableBody) { 
            return; 
        } 
 
        const jumlah = row.dataset.anggota || '0'; 
        const namaKepala = row.dataset.nama || '-'; 
        const anggota = parseFamilyMembers( 
            row.dataset.anggotaDetail || '[]' 
        ); 
 
        if (familyInlineHead) { 
            familyInlineHead.textContent = namaKepala; 
        } 
 
        if (familyInlineCount) { 
            familyInlineCount.textContent = `${jumlah} Orang`; 
        } 
 
        if (familyInlineStatus) { 
            familyInlineStatus.textContent = row.dataset.statusLabel || '-'; 
        } 
 
        if (!anggota.length) { 
            familyMembersInlineTableBody.innerHTML = ` 
                <tr> 
                    <td colspan="4"> 
                        <div class="family-members-empty"> 
                            <strong>Detail anggota belum tersedia</strong> 
                            <span>Data anggota keluarga untuk KK ini belum ditemukan.</span> 
                        </div> 
                    </td> 
                </tr> 
            `; 
            return; 
        } 
 
        familyMembersInlineTableBody.innerHTML = anggota.map(function (member, index) { 
            const nik = member.nik ?? '-'; 
            const namaMember = member.nama_lengkap ?? member.nama ?? '-'; 
            const statusMember = member.status_keluarga ?? member.status ?? '-'; 
 
            return ` 
                <tr> 
                    <td>${index + 1}</td> 
                    <td>${escapeHtmlValue(nik)}</td> 
                    <td class="family-member-name">${escapeHtmlValue(namaMember)}</td> 
                    <td> 
                        <span class="family-member-status"> 
                            ${escapeHtmlValue(statusMember)} 
                        </span> 
                    </td> 
                </tr> 
            `; 
        }).join(''); 
    } 
 
 
    function closeFamilyMembersInline(options) {
        options = options || {};
        if (familyMembersInline) familyMembersInline.setAttribute('hidden', '');
        if (openMembersFromDetail) {
            openMembersFromDetail.setAttribute('aria-expanded', 'false');
            openMembersFromDetail.classList.remove('is-open');
        }
        if (membersDropdownIcon) membersDropdownIcon.style.transform = '';
        if (familyMembersHistoryOpen && !options.fromHistory && history.state && history.state.familyMembersOpen) {
            familyMembersHistoryOpen = false;
            history.back();
        } else if (options.fromHistory) {
            familyMembersHistoryOpen = false;
        }
    }

    function toggleFamilyMembersInline() {
        if (!familyMembersInline || !currentVerificationRow) return;
        const willOpen = familyMembersInline.hasAttribute('hidden');
        if (willOpen) {
            renderFamilyMembersInline(currentVerificationRow);
            familyMembersInline.removeAttribute('hidden');
            if (openMembersFromDetail) {
                openMembersFromDetail.setAttribute('aria-expanded', 'true');
                openMembersFromDetail.classList.add('is-open');
            }
            if (membersDropdownIcon) membersDropdownIcon.style.transform = 'rotate(180deg)';
            if (window.matchMedia('(max-width: 600px)').matches && !familyMembersHistoryOpen) {
                history.pushState({ familyMembersOpen: true }, '');
                familyMembersHistoryOpen = true;
            }
            if (familyMembersClose) window.setTimeout(function () { familyMembersClose.focus(); }, 50);
        } else {
            closeFamilyMembersInline();
        }
    }

    /* ===================================================== 
       OPEN MODAL 
    ====================================================== */ 
 
    function openVerificationModal(row) { 
 
        if (!verificationModal || !row) { 
            return; 
        } 
 
        currentVerificationRow = row; 
 
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
        // Ubah Status Data selalu ditampilkan agar aksi Setujui/Tolak tetap tersedia.
        if (verificationActionSection) {
            verificationActionSection.classList.remove('is-hidden');
        }

        if (modalStatus) { 
            if (normalizedStatus === 'approved' || normalizedStatus === 'rejected') { 
                modalStatus.value = normalizedStatus; 
            } else { 
                modalStatus.value = ''; 
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
       TOGGLE DETAIL ANGGOTA DI MODAL DETAIL 
    ====================================================== */ 
 
    if (openMembersFromDetail) { 
        openMembersFromDetail.addEventListener('click', function (event) { 
            event.preventDefault(); 
            event.stopPropagation(); 
            toggleFamilyMembersInline(); 
        }); 
    } 
 
 
    
    if (familyMembersClose) {
        familyMembersClose.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            closeFamilyMembersInline();
            if (openMembersFromDetail) openMembersFromDetail.focus();
        });
    }

    document.addEventListener('click', function (event) {
        if (!familyMembersInline || familyMembersInline.hasAttribute('hidden')) return;
        if (familyMembersInline.contains(event.target)) return;
        if (openMembersFromDetail && openMembersFromDetail.contains(event.target)) return;
        closeFamilyMembersInline();
    });

    window.addEventListener('popstate', function () {
        if (!familyMembersInline || familyMembersInline.hasAttribute('hidden')) {
            familyMembersHistoryOpen = false;
            return;
        }
        closeFamilyMembersInline({ fromHistory: true });
    });
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
 
        closeFamilyMembersInline({ fromHistory: true });

        if (familyInlineStatus) { 
            familyInlineStatus.textContent = '-'; 
        } 
 
        document.body.style.overflow = ''; 
 
    } 
 
 
    /* ===================================================== 
   BUTTON DETAIL 
====================================================== */ 
 
document.addEventListener('click', function (event) {

    const button = event.target.closest('.btn-open-detail');
 
    if (!button) { 
        return; 
    } 
 
    event.preventDefault(); 
 
    const row = button.closest('.data-row'); 
 
    if (!row) { 
        console.error('Baris data responden tidak ditemukan.'); 
        return; 
    } 
 
    openVerificationModal(row); 
 
}); 
 
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