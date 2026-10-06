@extends('admin.layouts.app') 
 
@section('title', 'Laporan Pendataan') 
 
@section('content')

@php
    $data = $laporan ?? collect();
@endphp
 
<style>

 
<style> 
    /* ===================================================== 
       LAPORAN PAGE 
    ===================================================== */ 
 
    .laporan-page { 
        padding: 24px 32px 40px; 
        background: #ffffff; 
        min-height: calc(100vh - 70px); 
        box-sizing: border-box; 
        color: #222222; 
    } 
 
    /* ===================================================== 
       HEADER 
    ===================================================== */ 
 
    .laporan-header { 
        margin-bottom: 22px; 
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
        margin: 7px 0 0; 
        color: #666666; 
        font-size: 14px; 
        line-height: 1.6; 
    } 
 
    /* ===================================================== 
       EXPORT BUTTON 
    ===================================================== */ 
 
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
        box-shadow: 0 4px 10px rgba(37, 42, 134, .15); 
    } 
 
    .laporan-export-btn svg { 
        width: 17px; 
        height: 17px; 
        flex-shrink: 0; 
    } 
 
    /* ===================================================== 
       STATISTICS 
    ===================================================== */ 
 
    .laporan-stats { 
        display: grid; 
        grid-template-columns: repeat(4, minmax(0, 1fr)); 
        gap: 18px; 
        max-width: 1500px;
        margin: 0 auto 24px; 
    } 
 
    .laporan-stat-card { 
        background: #f5f8ff; 
        border: 1px solid #e0e7f5; 
        border-radius: 15px; 
        padding: 22px 20px; 
        min-height: 116px; 
        display: flex; 
        align-items: center; 
        gap: 14px; 
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04); 
        transition: .2s ease; 
        box-sizing: border-box; 
    } 
 
    .laporan-stat-card:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 6px 18px rgba(15, 23, 42, .07); 
    } 

    .laporan-stat-card:nth-child(2) {
        background: #f1faf3;
        border-color: #d9efdf;
    }

    .laporan-stat-card:nth-child(3) {
        background: #fff9e8;
        border-color: #f3e6bb;
    }

    .laporan-stat-card:nth-child(4) {
        background: #f5f4ff;
        border-color: #e3e0f8;
    }
 
    .laporan-stat-icon { 
        width: 44px; 
        height: 44px; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: #eef0ff; 
        color: #252A86; 
        flex-shrink: 0; 
    } 
 
    .laporan-stat-icon.blue-light { 
        background: #eaf7fb; 
        color: #2f9bbd; 
    } 
 
    .laporan-stat-icon.green { 
        background: #eaf7ee; 
        color: #299447; 
    } 
 
    .laporan-stat-icon.orange { 
        background: #fff7df; 
        color: #d69212; 
    } 
 
    .laporan-stat-info { 
        min-width: 0; 
    } 
 
    .laporan-stat-label { 
        margin-bottom: 4px; 
        color: #666666; 
        font-size: 12px; 
        line-height: 1.4; 
    } 
 
    .laporan-stat-value { 
        color: #252A86; 
        font-size: 25px; 
        line-height: 1.2; 
        font-weight: 700; 
    } 

    .laporan-stat-note {
        margin-top: 5px;
        color: #747b8b;
        font-size: 10px;
        line-height: 1.4;
    }
 
    /* ===================================================== 
       MAIN CARD 
    ===================================================== */ 
 
    .laporan-card { 
        background: #ffffff; 
        border: 1px solid #e3e5ea; 
        border-radius: 15px; 
        box-shadow: 0 3px 14px rgba(15, 23, 42, .045); 
        overflow: hidden; 
    } 
 
    .laporan-card-header { 
        padding: 20px 22px; 
        border-bottom: 1px solid #e7e8ec; 
    } 
 
    .laporan-card-title { 
        margin: 0 0 5px; 
        color: #252A86; 
        font-size: 17px; 
        line-height: 1.4; 
        font-weight: 700; 
    } 
 
    .laporan-card-description { 
        margin: 0; 
        color: #666666; 
        font-size: 13px; 
        line-height: 1.5; 
    } 
 
    /* ===================================================== 
       FILTER 
    ===================================================== */ 
 
    .laporan-filter { 
        display: grid; 
        grid-template-columns: minmax(280px, 1.8fr) minmax(160px, .9fr) minmax(180px, 1fr) auto; 
        gap: 12px; 
        margin-top: 17px; 
        align-items: end; 
    } 
 
    .laporan-filter-group { 
        min-width: 0; 
    } 
 
    .laporan-filter-label { 
        display: block; 
        margin: 0 0 7px; 
        color: #555555; 
        font-size: 12px; 
        line-height: 1.4; 
        font-weight: 600; 
    } 
 
    .laporan-filter-label-hidden { 
        visibility: hidden; 
    } 
 
    .laporan-search { 
        position: relative; 
        width: 100%; 
    } 
 
    .laporan-search input, 
    .laporan-filter select { 
        width: 100%; 
        height: 43px; 
        border: 1px solid #d9dce3; 
        border-radius: 9px; 
        background: #ffffff; 
        color: #222222; 
        font-size: 13px; 
        outline: none; 
        box-sizing: border-box; 
        transition: .2s ease; 
    } 
 
    .laporan-search input { 
        padding: 0 42px 0 14px; 
    } 
 
    .laporan-filter select { 
        padding: 0 35px 0 13px; 
        cursor: pointer; 
    } 
 
    .laporan-search input::placeholder { 
        color: #999999; 
    } 
 
    .laporan-search input:focus, 
    .laporan-filter select:focus { 
        border-color: #55B5D5; 
        box-shadow: 0 0 0 3px rgba(85, 181, 213, .12); 
    } 
 
    .laporan-search-icon { 
        position: absolute; 
        top: 50%; 
        right: 14px; 
        transform: translateY(-50%); 
        color: #777777; 
        pointer-events: none; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    } 
 
    .laporan-reset-btn { 
        width: 100%; 
        min-height: 43px; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        padding: 0 15px; 
        border: 1px solid #252A86; 
        border-radius: 9px; 
        background: #ffffff; 
        color: #252A86; 
        font-size: 13px; 
        font-weight: 600; 
        text-decoration: none; 
        box-sizing: border-box; 
        transition: .2s ease; 
        white-space: nowrap; 
    } 
 
    .laporan-reset-btn:hover { 
        background: #f1f3ff; 
        color: #252A86; 
    } 
 
    /* ===================================================== 
       TABLE 
    ===================================================== */ 
 
    .laporan-table-wrapper { 
        width: 100%; 
        overflow-x: auto; 
    } 
 
    .laporan-table { 
        width: 100%; 
        min-width: 1150px; 
        border-collapse: collapse; 
    } 
 
    .laporan-table th { 
        padding: 14px 15px; 
        background: #f7f8fc; 
        border-bottom: 1px solid #e3e5ea; 
        color: #4b5563 !important; 
        font-size: 12px; 
        font-weight: 700; 
        text-align: left; 
        white-space: nowrap; 
    } 
 
    .laporan-table td { 
        padding: 15px; 
        border-bottom: 1px solid #eef0f3; 
        color: #4b5563 !important; 
        font-size: 13px; 
        vertical-align: middle; 
        white-space: nowrap; 
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
 
    /* ===================================================== 
       STATUS 
    ===================================================== */ 
 
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
        background: #eaf7ee; 
        color: #299447; 
    } 
 
    .laporan-status.belum { 
        background: #f1f3f8; 
        color: #667085; 
    } 
 
    .laporan-status.diproses { 
        background: #fff7df; 
        color: #c17b00; 
    } 
 
    .laporan-status.ditolak { 
        background: #fff4cc; 
        color: #8a6200; 
        border: 1px solid #f0d77e;
    } 
 
    /* ===================================================== 
       CHECK COMPLETE 
    ===================================================== */ 
 
    .laporan-complete { 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        font-size: 12px; 
        font-weight: 600; 
    } 
 
    .laporan-complete.yes { 
        color: #299447; 
    } 
 
    .laporan-complete.no { 
        color: #888888; 
    } 
 
    .laporan-check { 
        width: 19px; 
        height: 19px; 
        border-radius: 5px; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 12px; 
        font-weight: 700; 
    } 
 
    .laporan-check.yes { 
        background: #eaf7ee; 
        color: #299447; 
    } 
 
    .laporan-check.no { 
        background: #f0f1f4; 
        color: #999999; 
    } 
 
    /* ===================================================== 
       DETAIL BUTTON 
    ===================================================== */ 
 
    .laporan-detail-btn { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        gap: 6px; 
        min-width: 70px; 
        height: 34px; 
        padding: 0 12px; 
        border: 1px solid #d5d8ed; 
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
    } 
 
    /* ===================================================== 
       EMPTY 
    ===================================================== */ 
 
    .laporan-empty { 
        padding: 45px 20px; 
        text-align: center; 
        color: #666666; 
    } 
 
    .laporan-empty-icon { 
        width: 52px; 
        height: 52px; 
        margin: 0 auto 12px; 
        border-radius: 13px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: #f1f3ff; 
        color: #252A86; 
    } 
 
    .laporan-empty-title { 
        margin-bottom: 5px; 
        color: #252A86; 
        font-size: 14px; 
        font-weight: 700; 
    } 
 
    .laporan-empty-text { 
        color: #777777; 
        font-size: 13px; 
    } 
 
    /* ===================================================== 
       MODAL 
    ===================================================== */ 
 
    .laporan-modal-overlay { 
        position: fixed; 
        inset: 0; 
        z-index: 5000; 
        display: none; 
        align-items: center; 
        justify-content: center; 
        padding: 20px; 
        background: rgba(15, 23, 42, .48); 
        backdrop-filter: blur(3px); 
        box-sizing: border-box; 
    } 
 
    .laporan-modal-overlay.active { 
        display: flex; 
    } 
 
    .laporan-modal { 
        width: min(850px, 100%); 
        max-height: calc(100vh - 40px); 
        background: #ffffff; 
        border-radius: 16px; 
        box-shadow: 0 25px 70px rgba(15, 23, 42, .22); 
        overflow: hidden; 
        animation: laporanModalShow .2s ease; 
    } 
 
    @keyframes laporanModalShow { 
        from { 
            opacity: 0; 
            transform: translateY(10px) scale(.98); 
        } 
 
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        } 
    } 
 
    /* ===================================================== 
       MODAL HEADER 
    ===================================================== */ 
 
    .laporan-modal-header { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        gap: 15px; 
        padding: 18px 21px; 
        border-bottom: 1px solid #e5e7eb; 
    } 
 
    .laporan-modal-header h2 { 
        margin: 0; 
        color: #252A86; 
        font-size: 18px; 
        font-weight: 700; 
    } 
 
    .laporan-modal-header p { 
        margin: 4px 0 0; 
        color: #777777; 
        font-size: 12px; 
    } 
 
    .laporan-modal-close { 
        width: 35px; 
        height: 35px; 
        border: none; 
        border-radius: 8px; 
        background: #f1f3ff; 
        color: #252A86; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        cursor: pointer; 
        transition: .2s ease; 
        flex-shrink: 0; 
    } 
 
    .laporan-modal-close:hover { 
        background: #252A86; 
        color: #ffffff; 
    } 
 
    /* ===================================================== 
       MODAL BODY 
    ===================================================== */ 
 
    .laporan-modal-body { 
        padding: 21px; 
        max-height: calc(100vh - 155px); 
        overflow-y: auto; 
    } 
 
    /* ===================================================== 
       MODAL SUMMARY 
    ===================================================== */ 
 
    .laporan-modal-summary { 
        display: grid; 
        grid-template-columns: repeat(3, minmax(0, 1fr)); 
        gap: 12px; 
        margin-bottom: 18px; 
    } 
 
    .laporan-summary-box { 
        padding: 14px; 
        border: 1px solid #e2e4ea; 
        border-radius: 10px; 
        background: #fafbff; 
    } 
 
    .laporan-summary-label { 
        margin-bottom: 5px; 
        color: #777777; 
        font-size: 11px; 
    } 
 
    .laporan-summary-value { 
        color: #252A86; 
        font-size: 14px; 
        font-weight: 700; 
        word-break: break-word; 
    } 

    .laporan-detail-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border: 1px solid transparent;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .laporan-detail-status.status-approved {
        background: #eaf7ee;
        border-color: #c8e8d0;
        color: #24723c;
    }

    .laporan-detail-status.status-rejected {
        background: #fff4cc;
        border-color: #f0d77e;
        color: #8a6200;
    }
 
    /* ===================================================== 
       MODAL INFORMATION 
    ===================================================== */ 
 
    .laporan-info-section { 
        margin-bottom: 18px; 
        border: 1px solid #e2e4ea; 
        border-radius: 11px; 
        overflow: hidden; 
    } 
 
    .laporan-info-title { 
        padding: 12px 15px; 
        background: #f7f8fc; 
        border-bottom: 1px solid #e2e4ea; 
        color: #252A86; 
        font-size: 13px; 
        font-weight: 700; 
    } 
 
    .laporan-info-grid { 
        display: grid; 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
    } 
 
    .laporan-info-item { 
        padding: 13px 15px; 
        border-bottom: 1px solid #eef0f3; 
    } 
 
    .laporan-info-item:nth-child(odd) { 
        border-right: 1px solid #eef0f3; 
    } 
 
    .laporan-info-label { 
        margin-bottom: 4px; 
        color: #777777; 
        font-size: 11px; 
    } 
 
    .laporan-info-value { 
        color: #4b5563; 
        font-size: 13px; 
        font-weight: 600; 
        word-break: break-word; 
    } 
 
    .laporan-detail-data-section { 
        margin-bottom: 18px; 
        border: 1px solid #e2e4ea; 
        border-radius: 11px; 
        overflow: hidden; 
    } 
 
    /* ===================================================== 
       MAP 
    ===================================================== */ 
 
    .laporan-map-container { 
        width: 100%; 
        height: 350px; 
        overflow: hidden; 
        background: #eef1f5; 
        position: relative; 
    } 
 
    .laporan-map-container iframe { 
        width: 100%; 
        height: 100%; 
        border: 0; 
        display: block; 
    } 
 
    .laporan-map-loading { 
        width: 100%; 
        height: 100%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: #777777; 
        font-size: 13px; 
        text-align: center; 
        padding: 20px; 
        box-sizing: border-box; 
    } 
 
    /* ===================================================== 
       MEMBERS + QUESTION TABLE 
    ===================================================== */ 
 
    .laporan-detail-data-heading { 
        padding: 12px 15px; 
        background: #f7f8fc; 
        border-bottom: 1px solid #e2e4ea; 
        color: #252A86; 
        font-size: 13px; 
        font-weight: 700; 
    } 
 
    .laporan-members-table-wrap { 
        overflow-x: auto; 
    } 
 
    .laporan-members-table, 
    .laporan-question-table { 
        width: 100%; 
        border-collapse: collapse; 
    } 
 
    .laporan-members-table th, 
    .laporan-members-table td, 
    .laporan-question-table th, 
    .laporan-question-table td { 
        padding: 10px 12px; 
        border-bottom: 1px solid #eef0f3; 
        color: #4b5563; 
        font-size: 12px; 
        text-align: left; 
        vertical-align: top; 
    } 
 
    .laporan-members-table th, 
    .laporan-question-table th { 
        background: #fbfcfe; 
        color: #374151; 
        font-weight: 700; 
    } 
 
    /* ===================================================== 
       QUESTIONNAIRE MONITORING STYLE 
    ===================================================== */ 
 
    .laporan-questionnaire-layout { 
        display: grid; 
        grid-template-columns: 220px minmax(0, 1fr); 
        gap: 14px; 
        padding: 14px; 
        min-height: 280px; 
    } 
 
    .laporan-question-list { 
        display: flex; 
        flex-direction: column; 
        gap: 7px; 
    } 
 
    .laporan-question-part-btn { 
        width: 100%; 
        padding: 11px 13px; 
        border: 1px solid #e1e4ec; 
        border-radius: 8px; 
        background: #ffffff; 
        color: #555; 
        font-size: 12px; 
        font-weight: 600; 
        text-align: left; 
        cursor: pointer; 
        transition: .2s ease; 
    } 
 
    .laporan-question-part-btn:hover { 
        background: #f1f3ff; 
        color: #252A86; 
    } 
 
    .laporan-question-part-btn.active { 
        background: #252A86; 
        border-color: #252A86; 
        color: #ffffff; 
    } 
 
    .laporan-question-answer { 
        min-width: 0; 
        border: 1px solid #e2e4ea; 
        border-radius: 10px; 
        overflow: hidden; 
        background: #ffffff; 
    } 
 
    .laporan-question-answer-title { 
        padding: 12px 15px; 
        background: #f7f8fc; 
        border-bottom: 1px solid #e2e4ea; 
        color: #252A86; 
        font-size: 13px; 
        font-weight: 700; 
    } 
 
    .laporan-question-answer-body { 
        padding: 0; 
    } 
 
    .laporan-question-answer-body .laporan-question-table { 
        margin: 0; 
    } 
 
    /* ===================================================== 
       FOTO / GAMBAR JAWABAN 
    ===================================================== */ 
 
    .laporan-answer-image { 
        display: block; 
        width: 150px; 
        max-width: 100%; 
        height: 110px; 
        object-fit: cover; 
        border-radius: 8px; 
        border: 1px solid #e1e4ec; 
        margin-bottom: 6px; 
        cursor: pointer; 
    } 
 
    .laporan-answer-image-link { 
        display: inline-block; 
        color: #252A86; 
        font-size: 11px; 
        font-weight: 600; 
        text-decoration: none; 
    } 
 
    .laporan-answer-image-link:hover { 
        text-decoration: underline; 
    } 
 
    .laporan-question-part summary { 
        padding: 11px 13px; 
        background: #fbfcfe; 
        color: #252A86; 
        font-size: 12px; 
        font-weight: 700; 
        cursor: pointer; 
    } 
 
    .laporan-question-table td { 
        overflow-wrap: anywhere; 
    } 
 
    .laporan-detail-loading, 
    .laporan-detail-error, 
    .laporan-detail-empty { 
        padding: 16px; 
        color: #64748b; 
        font-size: 13px; 
    } 
 
    .laporan-detail-error { 
        color: #a53636; 
    } 
 
    /* ===================================================== 
       MODAL FOOTER 
    ===================================================== */ 
 
    .laporan-modal-footer { 
        display: flex; 
        align-items: center; 
        justify-content: flex-end; 
        gap: 9px; 
        padding-top: 2px; 
    } 
 
    .laporan-modal-btn { 
        min-height: 38px; 
        padding: 0 14px; 
        border-radius: 8px; 
        font-size: 12px; 
        font-weight: 600; 
        cursor: pointer; 
        transition: .2s ease; 
    } 
 
    .laporan-modal-btn.close { 
        border: 1px solid #d8dbe2; 
        background: #ffffff; 
        color: #555555; 
    } 
 
    .laporan-modal-btn.close:hover { 
        background: #f5f6f8; 
    } 
 
    .laporan-modal-btn.pdf { 
        display: inline-flex; 
        align-items: center; 
        gap: 7px; 
        border: 1px solid #252A86; 
        background: #252A86; 
        color: #ffffff; 
        text-decoration: none; 
    } 
 
    .laporan-modal-btn.pdf:hover { 
        background: #1d216d; 
        border-color: #1d216d; 
    } 
 
    /* ===================================================== 
       RESPONSIVE 
    ===================================================== */ 
 
    @media (max-width: 1200px) { 
 
        .laporan-stats { 
            grid-template-columns: repeat(2, minmax(0, 1fr)); 
        } 
 
        .laporan-filter { 
            grid-template-columns: 1fr 1fr; 
        } 
 
        .laporan-filter-reset-group { 
            grid-column: 1 / -1; 
        } 
 
        .laporan-reset-btn { 
            width: 100%; 
        } 
    } 
 
    @media (max-width: 800px) { 
 
        .laporan-page { 
            padding: 20px 18px 30px; 
        } 
 
        .laporan-header-top { 
            flex-direction: column; 
            align-items: stretch; 
        } 
 
        .laporan-export-btn { 
            width: 100%; 
        } 
 
        .laporan-filter { 
            grid-template-columns: 1fr; 
        } 
 
        .laporan-modal-summary { 
            grid-template-columns: 1fr; 
        } 
 
        .laporan-info-grid { 
            grid-template-columns: 1fr; 
        } 
 
        .laporan-info-item:nth-child(odd) { 
            border-right: none; 
        } 
 
        .laporan-questionnaire-layout { 
            grid-template-columns: 1fr; 
        } 
 
        .laporan-question-list { 
            display: grid; 
            grid-template-columns: repeat(2, minmax(0, 1fr)); 
        } 
 
    } 
 
    @media (max-width: 600px) { 
 
        .laporan-page { 
            padding: 17px 13px 25px; 
        } 
 
        .laporan-header { 
            margin-bottom: 18px; 
        } 
 
        .laporan-header h1 { 
            font-size: 21px; 
        } 
 
        .laporan-header p { 
            font-size: 13px; 
        } 
 
        .laporan-stats { 
            grid-template-columns: repeat(2, minmax(0, 1fr)); 
            gap: 11px; 
        } 
 
        .laporan-stat-card { 
            min-height: 82px; 
            padding: 15px; 
        } 
 
        .laporan-card-header { 
            padding: 17px 15px; 
        } 
 
        .laporan-modal-overlay { 
            padding: 10px; 
        } 
 
        .laporan-modal { 
            max-height: calc(100vh - 20px); 
            border-radius: 13px; 
        } 
 
        .laporan-modal-header { 
            padding: 15px; 
        } 
 
        .laporan-modal-body { 
            padding: 15px; 
            max-height: calc(100vh - 115px); 
        } 
 
        .laporan-modal-footer { 
            flex-direction: column-reverse; 
        } 
 
        .laporan-modal-btn { 
            width: 100%; 
            justify-content: center; 
            text-align: center; 
        } 
 
        .laporan-question-list { 
            grid-template-columns: 1fr; 
        } 
 
        .laporan-map-container { 
            height: 300px; 
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
 
    {{-- ===================================================== 
         STATISTICS 
    ===================================================== --}} 
 
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

        <div class="laporan-stat-card">
            <div class="laporan-stat-info">
                <div class="laporan-stat-label">Petugas Aktif</div>
                <div class="laporan-stat-value">{{ $petugasAktif ?? 'Tidak tersedia' }}</div>
                @if(($petugasAktif ?? null) === 'Tidak tersedia')
                    <div class="laporan-stat-note">Status aktif belum tersedia di database</div>
                @endif
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
 
            {{-- ===================================================== 
                 LOKASI PENDATAAN 
            ===================================================== --}} 
 
            <section class="laporan-detail-data-section"> 
 
                <div class="laporan-detail-data-heading"> 
                    Lokasi Pendataan 
                </div> 
 
                <div 
                    id="laporanMap" 
                    class="laporan-map-container" 
                > 
                    <div class="laporan-map-loading"> 
                        Memuat peta lokasi... 
                    </div> 
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
 
    const laporanMap = document.getElementById( 
        'laporanMap' 
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
 
    function renderReportQuestionnaire(parts) { 
 
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
       MAP LOKASI 
    ===================================================== */ 
 
    function renderLaporanMap(item) { 
 
        if (!laporanMap) { 
            return; 
        } 
 
        /* ================================================= 
           AMBIL LATITUDE 
        ================================================= */ 
 
        const latitude = 
            item.latitude ?? 
            item.lat ?? 
            item.latitude_lokasi ?? 
            item.latitude_geotag ?? 
            item.lat_geotag ?? 
            item.lokasi_latitude ?? 
            item.koordinat_latitude ?? 
            item.koordinat_lat ?? 
            item.location_latitude ?? 
            null; 
 
        /* ================================================= 
           AMBIL LONGITUDE 
        ================================================= */ 
 
        const longitude = 
            item.longitude ?? 
            item.lng ?? 
            item.long ?? 
            item.longitude_lokasi ?? 
            item.longitude_geotag ?? 
            item.lng_geotag ?? 
            item.lokasi_longitude ?? 
            item.koordinat_longitude ?? 
            item.koordinat_lng ?? 
            item.location_longitude ?? 
            null; 
 
        const lat = 
            parseFloat(latitude); 
 
        const lng = 
            parseFloat(longitude); 
 
        /* ================================================= 
           CEK KOORDINAT 
        ================================================= */ 
 
        if ( 
            !Number.isFinite(lat) || 
            !Number.isFinite(lng) 
        ) { 
 
            laporanMap.innerHTML = ` 
                <div class="laporan-map-loading"> 
 
                    <div> 
                        <strong>Koordinat lokasi belum tersedia.</strong> 
                        <br> 
                        Data latitude dan longitude belum dikirim oleh server. 
                    </div> 
 
                </div> 
            `; 
 
            return; 
        } 
 
        /* ================================================= 
           GOOGLE MAPS EMBED 
        ================================================= */ 
 
        const mapUrl = 
            'https://www.google.com/maps?q=' + 
            encodeURIComponent(lat + ',' + lng) + 
            '&z=17&output=embed'; 
 
        laporanMap.innerHTML = ` 
 
            <iframe 
                src="${mapUrl}" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade" 
                allowfullscreen 
                title="Lokasi Pendataan" 
            ></iframe> 
 
        `; 
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
 
        /* ================================================= 
           RESET MAP SEBELUM DATA BARU 
        ================================================= */ 
 
        if (laporanMap) { 
 
            laporanMap.innerHTML = ` 
                <div class="laporan-map-loading"> 
                    Memuat peta lokasi... 
                </div> 
            `; 
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
               RENDER MAP 
            ================================================= */ 
 
            renderLaporanMap(item); 
 
            /* ================================================= 
               RENDER KUISIONER 
            ================================================= */ 
 
            renderReportQuestionnaire( 
                item.kuisioner || [] 
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
 
            if (laporanMap) { 
 
                laporanMap.innerHTML = ` 
                    <div class="laporan-map-loading"> 
                        Peta lokasi tidak dapat dimuat. 
                    </div> 
                `; 
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