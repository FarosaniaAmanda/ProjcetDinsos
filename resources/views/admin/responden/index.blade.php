@extends('admin.layouts.app')

@section('title', 'Manajemen Responden')

@if (session('success'))
<div class="responden-notification success" id="respondenNotification"><span class="responden-notification-icon">✓</span><span>{{ session('success') }}</span></div>
@endif

@push('styles')

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<style>

/* =========================================================
   RESPONDEN PAGE
========================================================= */

.responden-page {
    width: 100%;
}

.responden-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 24px;
}

.responden-page-label {
    font-size: 11px;
    font-weight: 700;
    color: #252A86;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 7px;
}

.responden-page-title {
    font-size: 27px;
    font-weight: 700;
    color: #252A86;
    margin: 0 0 7px;
}

.responden-page-description {
    font-size: 13px;
    color: #777;
    line-height: 1.6;
    margin: 0;
}


/* =========================================================
   BUTTON
========================================================= */

.responden-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 40px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s ease;
    box-sizing: border-box;
}

.responden-btn-primary {
    background: #252A86;
    border: 1px solid #252A86;
    color: #fff;
}

.responden-btn-primary:hover {
    background: #1d226f;
    border-color: #1d226f;
    color: #fff;
}

.responden-btn-secondary {
    background: #fff;
    border: 1px solid #dcdfea;
    color: #555;
}

.responden-btn-secondary:hover {
    background: #f7f7fa;
    color: #252A86;
}


/* =========================================================
   TABLE PANEL
========================================================= */

.responden-panel {
    background: #fff;
    border: 1px solid #e7e8ee;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
}

.responden-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 20px;
    border-bottom: 1px solid #e8e9ef;
}

.responden-panel-title {
    font-size: 15px;
    font-weight: 700;
    color: #252A86;
}

.responden-panel-subtitle {
    margin-top: 4px;
    font-size: 11px;
    color: #888;
}


/* =========================================================
   SEARCH
========================================================= */

.responden-search {
    position: relative;
    width: 280px;
}

.responden-search input {
    width: 100%;
    height: 38px;
    padding: 0 42px 0 38px;
    border: 1px solid #dfe1e8;
    border-radius: 8px;
    outline: none;
    font-size: 12px;
    color: #333;
    box-sizing: border-box;
}

.responden-search input:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, .08);
}

.responden-search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 14px;
    pointer-events: none;
}

.responden-search-button {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 30px;
    height: 30px;
    border: 0;
    border-radius: 6px;
    background: transparent;
    color: #777;
    font-size: 17px;
    cursor: pointer;
}

.responden-search-button:hover {
    background: #f2f3f8;
    color: #252A86;
}


/* =========================================================
   TABLE
========================================================= */

.responden-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.responden-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 950px;
}

.responden-table th {
    background: #fafafd;
    color: #777;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8e9ef;
    text-align: left;
    white-space: nowrap;
}

.responden-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #eeeeF3;
    font-size: 12px;
    color: #555;
    vertical-align: middle;
}

.responden-table tbody tr:hover {
    background: #fafaff;
}

.responden-table tbody tr:last-child td {
    border-bottom: none;
}

.responden-number {
    width: 45px;
    color: #999;
}

.responden-pagination {
    display: flex;
    justify-content: flex-end;
    padding: 14px 20px;
    border-top: 1px solid #e8e9ef;
}

.responden-pagination-links {
    display: flex;
    align-items: center;
    gap: 5px;
}

.responden-pagination-link,
.responden-pagination-current,
.responden-pagination-disabled,
.responden-pagination-ellipsis {
    min-width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 10px;
    border: 1px solid #dfe1e8;
    border-radius: 6px;
    background: #fff;
    color: #555;
    font-size: 11px;
    text-decoration: none;
    box-sizing: border-box;
}

.responden-pagination-link:hover {
    border-color: #252A86;
    color: #252A86;
}

.responden-pagination-current {
    border-color: #252A86;
    background: #252A86;
    color: #fff;
    font-weight: 700;
}

.responden-pagination-disabled,
.responden-pagination-ellipsis {
    color: #aaa;
}

.responden-pagination-disabled {
    background: #fafafd;
}

.responden-pagination-direction {
    gap: 6px;
    padding: 0 11px;
}

@media (max-width: 500px) {
    .responden-pagination {
        justify-content: center;
        padding: 12px;
    }

    .responden-pagination-links {
        gap: 3px;
    }

    .responden-pagination-link,
    .responden-pagination-current,
    .responden-pagination-disabled,
    .responden-pagination-ellipsis {
        min-width: 30px;
        padding: 0 7px;
    }

    .responden-pagination-direction span:not([aria-hidden="true"]) {
        display: none;
    }
}


/* =========================================================
   NO KK - BISA DIKLIK
========================================================= */

.responden-kk-toggle {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    background: transparent;
    color: #252A86;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
}

.responden-kk-toggle:hover {
    color: #171b66;
}

.responden-kk-icon {
    width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    background: #f1f2ff;
    color: #252A86;
    font-size: 12px;
    transition: .2s ease;
}

.responden-kk-toggle.active .responden-kk-icon {
    background: #252A86;
    color: #fff;
    transform: rotate(180deg);
}

.responden-family-main {
    background: #fff;
}

.responden-family-main.expanded {
    background: #f8f9ff;
}


/* =========================================================
   JUMLAH ANGGOTA
========================================================= */

.responden-member-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 27px;
    padding: 0 10px;
    border-radius: 20px;
    background: #eef0ff;
    color: #252A86;
    font-size: 11px;
    font-weight: 700;
}

.responden-count-column {
    text-align: center !important;
    vertical-align: middle;
}

.responden-member-count-cell {
    text-align: center !important;
    vertical-align: middle;
    font-weight: 600;
    color: #555;
}

.responden-count-column .responden-member-count {
    margin: 0 auto;
}

/* =========================================================
   EDIT - TAMBAH ANGGOTA
========================================================= */

.anggota-edit-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 13px 14px;
    background: #f8f9ff;
    border: 1px solid #e4e6f2;
    border-radius: 9px;
    margin-top: 12px;
}

.anggota-edit-info {
    min-width: 0;
}

.anggota-edit-info-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    color: #252A86;
}

.anggota-edit-info-icon {
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: #eef0ff;
    color: #252A86;
    font-size: 12px;
    font-weight: 700;
}

.anggota-edit-info-text {
    margin-top: 4px;
    font-size: 10px;
    line-height: 1.5;
    color: #777;
}

.anggota-edit-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 7px;
    padding: 4px 8px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e0e3f0;
    color: #252A86;
    font-size: 9px;
    font-weight: 700;
}

.btn-tambah-anggota-edit {
    flex: 0 0 auto;
    height: 36px;
    padding: 0 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid #252A86;
    background: #252A86;
    color: #fff;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s ease;
}

.btn-tambah-anggota-edit:hover {
    background: #1d226f;
}

.anggota-card-new {
    border-color: #bfc5ef;
    background: #fbfbff;
}

.anggota-baru-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border-radius: 5px;
    background: #eef0ff;
    color: #252A86;
    font-size: 8px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.btn-hapus-anggota-baru {
    height: 29px;
    padding: 0 9px;
    border: 1px solid #edd5d5;
    background: #fff7f7;
    color: #b34a4a;
    border-radius: 6px;
    font-size: 9px;
    cursor: pointer;
}

.btn-hapus-anggota-baru:hover {
    background: #b34a4a;
    color: #fff;
}

@media (max-width: 700px) {
    .anggota-edit-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .btn-tambah-anggota-edit {
        width: 100%;
    }
}


/* =========================================================
   BARIS ANGGOTA
========================================================= */

.responden-member-row {
    display: none;
    background: #fafbff;
}

.responden-member-row.active {
    display: table-row;
}

.responden-member-row td {
    background: #fafbff;
    border-bottom: 1px solid #eeeeF3;
    color: #555;
    padding: 11px 16px;
}

.responden-member-row:hover td {
    background: #f4f6ff;
}

.responden-member-indent {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.responden-member-indent-line {
    width: 15px;
    height: 15px;
    border-left: 2px solid #dfe2f3;
    border-bottom: 2px solid #dfe2f3;
    border-radius: 0 0 0 5px;
}

.responden-member-name {
    font-weight: 600;
    color: #444;
}

.responden-member-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 6px;
    background: #eef7f3;
    color: #006b46;
    font-size: 10px;
    font-weight: 600;
}


/* =========================================================
   ACTION
========================================================= */

.responden-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    flex-wrap: nowrap;
    white-space: nowrap;
}

.responden-table td:last-child {
    white-space: nowrap;
    min-width: 185px;
}

.responden-edit-btn {
    height: 31px;
    padding: 0 10px;
    border: 1px solid #dfe1e8;
    background: #fff;
    color: #252A86;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.responden-edit-btn:hover {
    background: #252A86;
    border-color: #252A86;
    color: #fff;
}

.responden-delete-btn {
    height: 31px;
    padding: 0 10px;
    border: 1px solid #edd5d5;
    background: #fff7f7;
    color: #b34a4a;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.responden-delete-btn:hover {
    background: #b34a4a;
    border-color: #b34a4a;
    color: #fff;
}

.responden-detail-btn{height:31px;padding:0 10px;border:1px solid #dfe1e8;background:#f8f9ff;color:#555;border-radius:7px;font-size:10px;font-weight:600;cursor:pointer;transition:all .2s ease}.responden-detail-btn:hover{background:#eef0ff;border-color:#252A86;color:#252A86}
.responden-notification{position:fixed;top:24px;left:24px;right:auto;z-index:99999;min-width:330px;max-width:440px;padding:14px 18px;border-radius:10px;color:#fff;font-size:12px;font-weight:600;display:flex;gap:11px;align-items:center;box-shadow:0 10px 28px rgba(0,0,0,.20);animation:respondenNotifIn .25s ease-out}.responden-notification.success{background:#198754;border:1px solid #157347}.responden-notification.warning{background:#dc3545;border:1px solid #bb2d3b}.responden-notification-icon{width:23px;height:23px;flex:0 0 23px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.18);color:#fff;font-weight:800;font-size:13px}.responden-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.responden-detail-item{padding:13px 14px;background:#fff;border:1px solid #e5e6ed;border-radius:9px;box-shadow:0 2px 7px rgba(0,0,0,.035)}.responden-detail-item.full{grid-column:1/-1}.responden-detail-label{font-size:9px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.35px;margin-bottom:6px}.responden-detail-value{font-size:12px;color:#333;line-height:1.6;word-break:break-word}.responden-detail-member{padding:12px 14px;border:1px solid #e5e6ed;border-radius:9px;background:#fff;margin-bottom:9px;box-shadow:0 2px 7px rgba(0,0,0,.035)}.responden-detail-member-name{font-size:11px;font-weight:700;color:#252A86}.responden-detail-member-meta{font-size:10px;color:#777;margin-top:5px;line-height:1.5}.responden-detail-section-title{display:flex;align-items:center;gap:8px;margin-bottom:12px;font-size:11px;font-weight:700;color:#252A86}.responden-detail-section-title::before{content:'';width:4px;height:17px;border-radius:3px;background:#252A86}.responden-detail-summary{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}.responden-detail-summary-item{padding:12px 14px;background:#f7f8fc;border:1px solid #e5e6ed;border-radius:9px}.responden-detail-summary-label{font-size:9px;color:#888;font-weight:700;text-transform:uppercase;letter-spacing:.3px}.responden-detail-summary-value{margin-top:4px;font-size:13px;color:#252A86;font-weight:700;line-height:1.4}
.responden-detail-map-wrapper{margin-top:14px;padding:13px 14px;background:#fff;border:1px solid #e5e6ed;border-radius:9px;box-shadow:0 2px 7px rgba(0,0,0,.035)}
.responden-detail-map{width:100%;height:300px;border:1px solid #dfe1e8;border-radius:8px;overflow:hidden;margin-top:9px}
.responden-detail-map-empty{display:flex;align-items:center;justify-content:center;min-height:120px;padding:20px;text-align:center;background:#f7f8fc;border:1px dashed #dfe1e8;border-radius:8px;color:#888;font-size:11px;line-height:1.6}

@keyframes respondenNotifIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
@media(max-width:700px){.responden-detail-grid{grid-template-columns:1fr}.responden-detail-item.full{grid-column:auto}.responden-notification{left:15px;right:15px;min-width:0;max-width:none}.responden-detail-summary{grid-template-columns:1fr}}


/* =========================================================
   EMPTY
========================================================= */

.responden-empty {
    text-align: center;
    padding: 55px 20px;
    color: #999;
}

.responden-empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f1f2f8;
    color: #252A86;
    font-size: 20px;
}

.responden-empty-title {
    color: #555;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 5px;
}

.responden-empty-text {
    font-size: 11px;
}


/* =========================================================
   MODAL
========================================================= */

.responden-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 18, 48, .48);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 9999;
    backdrop-filter: blur(2px);
}

.responden-modal-overlay.active {
    display: flex;
}

.responden-modal {
    width: 100%;
    max-width: 850px;
    max-height: 90vh;
    background: #fff;
    border-radius: 13px;
    overflow: hidden;
    box-shadow: 0 15px 45px rgba(0, 0, 0, .18);
    display: flex;
    flex-direction: column;
}

.responden-modal form {
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: 100%;
}

.responden-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #e8e9ef;
    flex-shrink: 0;
}

.responden-modal-label {
    font-size: 10px;
    color: #252A86;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 5px;
}

.responden-modal-title {
    font-size: 17px;
    color: #252A86;
    font-weight: 700;
    margin: 0;
}

.responden-modal-close {
    width: 34px;
    height: 34px;
    border: none;
    background: #f4f4f8;
    border-radius: 7px;
    color: #777;
    font-size: 19px;
    cursor: pointer;
    flex-shrink: 0;
}

.responden-modal-close:hover {
    background: #252A86;
    color: #fff;
}


/* =========================================================
   MODAL BODY
========================================================= */

.responden-modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}


/* =========================================================
   MODAL FOOTER
========================================================= */

.responden-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 20px;
    background: #fafafd;
    border-top: 1px solid #e8e9ef;
    flex-shrink: 0;
}

.responden-modal-footer .responden-btn {
    width: auto;
    min-width: 110px;
}


/* =========================================================
   MODAL FORM
========================================================= */

.responden-section {
    background: #f8f8fb;
    border: 1px solid #e5e6ed;
    border-radius: 10px;
    padding: 17px;
    margin-bottom: 18px;
}

.responden-section:last-child {
    margin-bottom: 0;
}

.responden-section-heading {
    font-size: 13px;
    font-weight: 700;
    color: #252A86;
    margin-bottom: 4px;
}

.responden-section-description {
    font-size: 11px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 17px;
}

.responden-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.responden-form-group {
    margin-bottom: 0;
}

.responden-form-group.full {
    grid-column: 1 / -1;
}

.responden-form-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #555;
    margin-bottom: 6px;
}

.required {
    color: #d64545;
}

.responden-form-control {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #dfe1e8;
    border-radius: 7px;
    background: #fff;
    color: #333;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
}

textarea.responden-form-control {
    height: auto;
    min-height: 90px;
    padding: 11px 12px;
    resize: vertical;
}

.responden-form-control:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, .08);
}

.responden-form-control::placeholder {
    color: #aaa;
}

.responden-form-control[readonly] {
    background: #f3f4f8;
    color: #555;
    cursor: not-allowed;
}


/* =========================================================
   ANGGOTA FORM
========================================================= */

.anggota-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.anggota-header-text {
    font-size: 12px;
    font-weight: 700;
    color: #252A86;
}

.btn-tambah-anggota {
    height: 34px;
    padding: 0 11px;
    border: 1px solid #252A86;
    background: #fff;
    color: #252A86;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
}

.btn-tambah-anggota:hover {
    background: #252A86;
    color: #fff;
}

.anggota-card {
    background: #fff;
    border: 1px solid #e1e3eb;
    border-radius: 9px;
    padding: 15px;
    margin-bottom: 12px;
}

.anggota-card:last-child {
    margin-bottom: 0;
}

.anggota-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eeeeF3;
}

.anggota-card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #252A86;
    font-size: 11px;
    font-weight: 700;
}

.anggota-number {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef0ff;
    color: #252A86;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.btn-hapus-anggota {
    height: 29px;
    padding: 0 9px;
    border: 1px solid #edd5d5;
    background: #fff7f7;
    color: #b34a4a;
    border-radius: 6px;
    font-size: 9px;
    cursor: pointer;
}

.btn-hapus-anggota:hover {
    background: #b34a4a;
    color: #fff;
}


/* =========================================================
   LAYOUT RT/RW DAN KODE POS/ALAMAT
========================================================= */

.responden-wilayah-pair {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
    grid-column: 1 / -1;
    width: 100%;
}

.responden-wilayah-pair .responden-form-group {
    min-width: 0;
}

@media (max-width: 700px) {
    .responden-wilayah-pair {
        grid-template-columns: 1fr;
    }
}

/* =========================================================
   MAP GEOTAGGING
========================================================= */

.responden-map {
    width: 100%;
    height: 320px;
    border: 1px solid #dfe1e8;
    border-radius: 9px;
    overflow: hidden;
    margin-top: 10px;
    z-index: 1;
}

.geotagging-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 10px;
    padding: 10px 12px;
    border: 1px solid #e5e6ed;
    border-radius: 7px;
    background: #fff;
    font-size: 11px;
    color: #777;
}

.geotagging-coordinate {
    font-weight: 700;
    color: #252A86;
}

.btn-lokasi {
    height: 35px;
    padding: 0 12px;
    border: 1px solid #252A86;
    background: #252A86;
    color: #fff;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
}

.btn-lokasi:hover {
    background: #1d226f;
}

.geotagging-confirm {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.geotagging-save,
.geotagging-cancel {
    height: 35px;
    padding: 0 14px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
}

.geotagging-save {
    border: 1px solid #252A86;
    background: #252A86;
    color: #fff;
}

.geotagging-save:hover {
    background: #1d226f;
}

.geotagging-cancel {
    border: 1px solid #dfe1e8;
    background: #fff;
    color: #666;
}

.geotagging-cancel:hover {
    background: #f5f5f8;
}

.geotagging-help {
    margin-top: 6px;
    font-size: 10px;
    color: #888;
    line-height: 1.5;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 750px) {

    .responden-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .responden-page-header .responden-btn {
        width: 100%;
    }

    .responden-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .responden-search {
        width: 100%;
    }

    .responden-form-grid {
        grid-template-columns: 1fr;
    }

    .responden-form-group.full {
        grid-column: auto;
    }

    .responden-modal {
        max-height: 94vh;
    }

}

@media (max-width: 500px) {

    .responden-modal-overlay {
        padding: 10px;
    }

    .responden-modal-header {
        padding: 15px;
    }

    .responden-modal-body {
        padding: 14px;
    }

    .responden-modal-footer {
        padding: 12px 15px;
    }

    .responden-section {
        padding: 13px;
    }

    .anggota-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .btn-tambah-anggota {
        width: 100%;
    }

    .responden-modal-footer {
        justify-content: flex-end;
    }

    .responden-modal-footer .responden-btn {
        flex: 1;
        min-width: 0;
    }

}

</style>
@endpush


@section('content')

<div class="responden-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="responden-page-header">

        <div>

            <div class="responden-page-label">
                ADMIN
            </div>

            <h1 class="responden-page-title">
                Data Responden
            </h1>

            <p class="responden-page-description">
                Kelola data Kartu Keluarga dan anggota keluarga yang akan didata.
            </p>

        </div>


        <button
            type="button"
            class="responden-btn responden-btn-primary"
            onclick="bukaModalTambah()"
        >
            <span style="font-size: 16px;">+</span>
            Tambah Responden
        </button>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="responden-panel">

        <div class="responden-panel-header">

            <div>

                <div class="responden-panel-title">
                    Daftar Responden
                </div>

                <div class="responden-panel-subtitle">
                    Data KK yang telah terdaftar dalam sistem.
                </div>

            </div>


            <form
                class="responden-search"
                id="searchRespondenForm"
                method="GET"
                action="{{ route('responden.index') }}"
            >

                <span class="responden-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    id="searchResponden"
                    name="search"
                    placeholder="Cari No. KK atau nama..."
                    autocomplete="off"
                    value="{{ request('search') }}"
                >

                <button
                    type="submit"
                    class="responden-search-button"
                    aria-label="Cari responden"
                    title="Cari responden"
                >
                    ⌕
                </button>

            </form>

        </div>


        <div class="responden-table-wrapper">

            <table
                class="responden-table"
                id="tabelResponden"
            >

                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            No. KK
                        </th>

                        <th>
                            NIK Anggota
                        </th>

                        <th class="responden-count-column">
                            Jumlah Anggota
                        </th>

                        <th>
                            Nama Anggota
                        </th>

                        <th>
                            Status Keluarga
                        </th>

                        <th>
                            Alamat Lengkap
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody id="respondenTableBody">

                    @forelse ($keluargas as $index => $keluarga)

                        @php
                            $jumlahAnggota = (int) ($keluarga->jml_keluarga ?? $keluarga->anggota->count());
                        @endphp

                        {{-- BARIS UTAMA KEPALA KELUARGA --}}
                        <tr
                            class="responden-row responden-family-main"
                            id="keluarga-row-{{ $keluarga->id }}"
                        >

                            <td class="responden-number">
                                {{ $keluargas->firstItem() + $index }}
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="responden-kk-toggle"
                                    id="kkToggle-{{ $keluarga->id }}"
                                    onclick="toggleKeluarga({{ $keluarga->id }})"
                                >
                                    <span
                                        class="responden-kk-icon"
                                        id="kkIcon-{{ $keluarga->id }}"
                                    >
                                        ⌄
                                    </span>
                                    <span>
                                        {{ $keluarga->no_kk ?? '-' }}
                                    </span>
                                </button>
                            </td>

                            <td>
                                <strong>
                                    {{ $keluarga->nik ?? '-' }}
                                </strong>
                            </td>

                            <td class="responden-count-column">
                                <span class="responden-member-count">
                                    {{ $jumlahAnggota }}
                                </span>
                            </td>

                            <td>
                                <span class="responden-member-name">
                                    {{ $keluarga->nama_lengkap ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="responden-member-status">
                                    Kepala Keluarga
                                </span>
                            </td>

                            <td>
                                <div>
                                    {{ $keluarga->kelurahan ?? '-' }}
                                </div>
                                <div style="font-size:11px;color:#777;margin-top:3px;">
                                    {{ $keluarga->kecamatan ?? '-' }}
                                </div>
                                <div style="font-size:11px;color:#777;margin-top:3px;">
                                    <div>
                                        {{ $keluarga->alamat_tampil ?? $keluarga->alamat_lengkap ?? '-' }}
                                    </div>
                                    @if($keluarga->rt || $keluarga->rw)
                                        <div style="font-size:11px;color:#777;margin-top:3px;">
                                            RT {{ $keluarga->rt ?? '-' }}/RW {{ $keluarga->rw ?? '-' }}
                                        </div>
                                    @endif
                                    @if($keluarga->geotangging)
                                        <div style="font-size:11px;color:#777;margin-top:3px;">
                                            Koordinat: {{ $keluarga->geotangging }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="responden-action">
                                    <button type="button" class="responden-detail-btn" onclick="detailResponden({{ $keluarga->id }})">Detail</button>

                                    <button
                                        type="button"
                                        class="responden-edit-btn"
                                        onclick="editResponden({{ $keluarga->id }})"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        class="responden-delete-btn"
                                        onclick="hapusResponden({{ $keluarga->id }})"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>

                        </tr>

                        {{-- BARIS ANGGOTA LAIN --}}
                        @foreach ($keluarga->anggota as $anggota)

                            <tr
                                class="responden-member-row"
                                id="member-{{ $keluarga->id }}-{{ $anggota->id }}"
                                data-keluarga="{{ $keluarga->id }}"
                            >
                                <td></td>
                                <td>
                                    <div class="responden-member-indent">
                                        <span class="responden-member-indent-line"></span>
                                        <span style="font-size:10px;color:#999;">
                                            Anggota
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $anggota->nik ?? '-' }}</strong>
                                </td>
                                <td class="responden-member-count-cell">
                                    {{ $jumlahAnggota }}
                                </td>
                                <td>
                                    <span class="responden-member-name">
                                        {{ $anggota->nama_lengkap ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="responden-member-status">
                                        {{ $anggota->status_keluarga ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $keluarga->kelurahan ?? '-' }}</div>
                                    <div style="font-size:11px;color:#777;margin-top:3px;">
                                        {{ $keluarga->kecamatan ?? '-' }}
                                    </div>
                                    <div style="font-size:11px;color:#777;margin-top:3px;">
                                        <div>
                                            {{ $keluarga->alamat_tampil ?? $keluarga->alamat_lengkap ?? '-' }}
                                        </div>
                                        @if($keluarga->rt || $keluarga->rw)
                                            <div style="font-size:11px;color:#777;margin-top:3px;">
                                                RT {{ $keluarga->rt ?? '-' }}/RW {{ $keluarga->rw ?? '-' }}
                                            </div>
                                        @endif
                                        @if($keluarga->geotangging)
                                            <div style="font-size:11px;color:#777;margin-top:3px;">
                                                Koordinat: {{ $keluarga->geotangging }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:10px;color:#aaa;">—</span>
                                </td>
                            </tr>

                        @endforeach

                    @empty
                        <tr>
                            <td colspan="8" class="responden-empty">
                                <div class="responden-empty-icon"></div>
                                <div class="responden-empty-title">
                                    Belum ada data responden
                                </div>
                                <div class="responden-empty-text">
                                    Silakan tambahkan data responden terlebih dahulu.
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($keluargas->hasPages())
            <nav class="responden-pagination" aria-label="Navigasi halaman responden">
                <div class="responden-pagination-links">
                    @if ($keluargas->previousPageUrl())
                        <a
                            href="{{ $keluargas->previousPageUrl() }}"
                            class="responden-pagination-link responden-pagination-direction"
                            rel="prev"
                            aria-label="Halaman sebelumnya"
                        >
                            <span aria-hidden="true">&lsaquo;</span>
                            <span>Sebelumnya</span>
                        </a>
                    @else
                        <span class="responden-pagination-disabled responden-pagination-direction" aria-disabled="true">
                            <span aria-hidden="true">&lsaquo;</span>
                            <span>Sebelumnya</span>
                        </span>
                    @endif

                    @if ($keluargas->currentPage() > 2)
                        <a href="{{ $keluargas->url(1) }}" class="responden-pagination-link">1</a>
                        @if ($keluargas->currentPage() > 3)
                            <span class="responden-pagination-ellipsis" aria-hidden="true">&hellip;</span>
                        @endif
                    @endif

                    @foreach ($keluargas->getUrlRange(max(1, $keluargas->currentPage() - 1), min($keluargas->lastPage(), $keluargas->currentPage() + 1)) as $page => $url)
                        @if ($page === $keluargas->currentPage())
                            <span class="responden-pagination-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="responden-pagination-link" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($keluargas->currentPage() < $keluargas->lastPage() - 1)
                        @if ($keluargas->currentPage() < $keluargas->lastPage() - 2)
                            <span class="responden-pagination-ellipsis" aria-hidden="true">&hellip;</span>
                        @endif
                        <a href="{{ $keluargas->url($keluargas->lastPage()) }}" class="responden-pagination-link">{{ $keluargas->lastPage() }}</a>
                    @endif

                    @if ($keluargas->nextPageUrl())
                        <a
                            href="{{ $keluargas->nextPageUrl() }}"
                            class="responden-pagination-link responden-pagination-direction"
                            rel="next"
                            aria-label="Halaman berikutnya"
                        >
                            <span>Berikutnya</span>
                            <span aria-hidden="true">&rsaquo;</span>
                        </a>
                    @else
                        <span class="responden-pagination-disabled responden-pagination-direction" aria-disabled="true">
                            <span>Berikutnya</span>
                            <span aria-hidden="true">&rsaquo;</span>
                        </span>
                    @endif
                </div>
            </nav>
        @endif

    </div>

</div>



{{-- =========================================================
     MODAL TAMBAH
========================================================= --}}

<div
    class="responden-modal-overlay"
    id="modalTambahResponden"
>

    <div class="responden-modal">

        <form
            action="{{ route('responden.store') }}"
            method="POST"
        >

            @csrf


            <div class="responden-modal-header">

                <div>

                    <div class="responden-modal-label">
                        DATA RESPONDEN
                    </div>

                    <h2 class="responden-modal-title">
                        Tambah Responden
                    </h2>

                </div>


                <button
                    type="button"
                    class="responden-modal-close"
                    onclick="tutupModalTambah()"
                >
                    ×
                </button>

            </div>


            <div class="responden-modal-body">


                {{-- =================================================
                     DATA KK
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Data Kartu Keluarga
                    </div>

                    <div class="responden-section-description">
                        Masukkan data kepala keluarga 
                    </div>

                    <div class="responden-form-grid">


                        {{-- NOMOR KK --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                No. KK
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nomor_kk"
                                class="responden-form-control nomor-16-digit"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                placeholder="Masukkan 16 digit nomor KK"
                                required
                            >

                            <div
                                style="
                                    font-size:10px;
                                    color:#888;
                                    margin-top:5px;
                                "
                            >
                                Harus tepat 16 digit angka.
                            </div>

                        </div>


                        {{-- NIK KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                NIK Kepala Keluarga
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nik_kepala_keluarga"
                                class="responden-form-control nomor-16-digit"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                placeholder="Masukkan 16 digit NIK"
                                required
                            >

                        </div>


                        {{-- NAMA KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Nama Kepala Keluarga
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_kepala_keluarga"
                                class="responden-form-control"
                                maxlength="255"
                                placeholder="Masukkan nama kepala keluarga"
                                required
                            >

                        </div>


                        {{-- STATUS KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Status Keluarga
                                <span class="required">*</span>
                            </label>

                            <select
                                name="status_kepala_keluarga"
                                class="responden-form-control"
                                required
                            >
                                <option value="Kepala Keluarga" selected>
                                    Kepala Keluarga
                                </option>
                            </select>

                        </div>

                    </div>

                </div>



                


                {{-- =================================================
                     WILAYAH
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Wilayah / Alamat Keluarga
                    </div>

                    <div class="responden-section-description">
                        Masukkan wilayah dan alamat tempat tinggal keluarga yang akan didata.
                    </div>


                    <div class="responden-form-grid">


                        {{-- PROVINSI --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Provinsi
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="provinsi"
                                class="responden-form-control"
                                value="Jawa Timur"
                                readonly
                                required
                            >

                        </div>


                        {{-- KOTA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Daerah/Kota
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="daerah"
                                class="responden-form-control"
                                value="Kota Pasuruan"
                                readonly
                                required
                            >

                        </div>


                        {{-- KECAMATAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kecamatan
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kecamatan_id"
                                id="kecamatan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan
                                </option>

                                @foreach ($kecamatans as $kecamatan)

                                    <option
                                        value="{{ $kecamatan->kecamatan_id }}"
                                    >
                                        {{ $kecamatan->deskripsi }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- KELURAHAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kelurahan/Desa
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kelurahan_id"
                                id="kelurahan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan terlebih dahulu
                                </option>

                            </select>

                        </div>


                        <div class="responden-wilayah-pair">

{{-- RT --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                RT
                            </label>

                            <input
                                type="text"
                                name="rt"
                                class="responden-form-control"
                                placeholder="Contoh: 001"
                                maxlength="5"
                                inputmode="numeric"
                            >

                        </div>

{{-- RW --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                RW
                            </label>

                            <input
                                type="text"
                                name="rw"
                                class="responden-form-control"
                                placeholder="Contoh: 002"
                                maxlength="5"
                                inputmode="numeric"
                            >

                        </div>

</div>

<div class="responden-wilayah-pair">

{{-- KODE POS --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kode Pos
                            </label>

                            <input
                                type="text"
                                name="kode_pos"
                                class="responden-form-control"
                                placeholder="Masukkan kode pos"
                                maxlength="10"
                                inputmode="numeric"
                            >

                        </div>

{{-- ALAMAT --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Alamat Lengkap 
                                <span class="required">*</span>
                            </label>

                            <textarea
                                name="alamat_lengkap"
                                class="responden-form-control"
                                placeholder="Masukkan alamat lengkap"
                                rows="3"
                                required
                            ></textarea>

                        </div>

</div>

                    </div>

                </div>



                


                {{-- =================================================
                     ANGGOTA KELUARGA
                ================================================== --}}

                <div class="responden-section">

                    <div class="anggota-header">
                        <div>
                            <div class="anggota-header-text">
                                Anggota Keluarga
                            </div>
                            <div class="responden-section-description" style="margin-bottom:0;margin-top:4px;">
                                Tambahkan anggota selain kepala keluarga.
                            </div>
                        </div>
                    </div>

                    <div class="responden-form-grid">
                        <div class="responden-form-group">
                            <label class="responden-form-label">
                                Jumlah Anggota 
                                <span class="required">*</span>
                            </label>
                            <input
                                type="number"
                                name="jumlah_anggota"
                                id="jumlah_anggota"
                                class="responden-form-control"
                                min="0"
                                max="19"
                                value="0"
                                required
                            >
                            <div style="font-size:10px;color:#888;margin-top:5px;">
                                
                            </div>
                        </div>
                    </div>

                    <div id="anggotaContainer" style="margin-top:15px;"></div>

                </div>


                


                {{-- =================================================
                     GEOTAGGING
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Geotagging Lokasi Rumah
                    </div>

                    <div class="responden-section-description">
                        Lokasi rumah akan diambil secara otomatis berdasarkan lokasi perangkat. Pastikan GPS/lokasi perangkat aktif dan izinkan akses lokasi pada browser.
                    </div>

                    <button
                        type="button"
                        class="btn-lokasi"
                        onclick="ambilLokasi('tambah', this)"
                    >
                        📍 Ambil Lokasi Saya
                    </button>

                    <div id="mapTambah" class="responden-map"></div>

                    <div class="geotagging-info">
                        <span>Koordinat tersimpan:</span>
                        <span
                            id="koordinatTambahText"
                            class="geotagging-coordinate"
                        >
                            Belum dipilih
                        </span>
                    </div>

                    <div class="geotagging-help">
                        Pilih lokasi secara otomatis dengan tombol “Ambil Lokasi Saya”, atau klik langsung pada peta untuk mengganti titik lokasi.
                    </div>

                    <div
                        id="konfirmasiLokasiTambah"
                        class="geotagging-confirm"
                        style="display:none;"
                    >
                        <button
                            type="button"
                            class="geotagging-save"
                            onclick="simpanLokasi('tambah')"
                        >
                            Simpan Lokasi
                        </button>

                        <button
                            type="button"
                            class="geotagging-cancel"
                            onclick="batalLokasi('tambah')"
                        >
                            Batal
                        </button>
                    </div>

                    <input
                        type="hidden"
                        name="geotangging"
                        id="geotangging"
                    >

                </div>
            </div>


            <div class="responden-modal-footer">

                <button
                    type="button"
                    class="responden-btn responden-btn-secondary"
                    onclick="tutupModalTambah()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="responden-btn responden-btn-primary"
                >
                    Simpan Responden
                </button>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================
     MODAL EDIT
========================================================= --}}

<div
    class="responden-modal-overlay"
    id="modalEditResponden"
>

    <div class="responden-modal">

        <form
            id="formEditResponden"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="responden-modal-header">

                <div>

                    <div class="responden-modal-label">
                        DATA RESPONDEN
                    </div>

                    <h2 class="responden-modal-title">
                        Edit Responden
                    </h2>

                </div>


                <button
                    type="button"
                    class="responden-modal-close"
                    onclick="tutupModalEdit()"
                >
                    ×
                </button>

            </div>


            <div class="responden-modal-body">


                {{-- =================================================
                     DATA KK EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Data Kartu Keluarga
                    </div>

                    <div class="responden-section-description">
                        
                    </div>


                    <div class="responden-form-grid">


                        {{-- NO KK --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                No. KK
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nomor_kk"
                                id="edit_nomor_kk"
                                class="responden-form-control nomor-16-digit"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                placeholder="Masukkan 16 digit nomor KK"
                                required
                            >

                            <div
                                style="
                                    font-size:10px;
                                    color:#888;
                                    margin-top:5px;
                                "
                            >
                                Harus tepat 16 digit angka.
                            </div>

                        </div>


                        {{-- NIK KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                NIK Kepala Keluarga
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nik_kepala_keluarga"
                                id="edit_nik_kepala_keluarga"
                                class="responden-form-control nomor-16-digit"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                placeholder="Masukkan 16 digit NIK"
                                required
                            >

                        </div>


                        {{-- NAMA KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Nama Kepala Keluarga
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_kepala_keluarga"
                                id="edit_nama_kepala_keluarga"
                                class="responden-form-control"
                                maxlength="255"
                                required
                            >

                        </div>


                        {{-- STATUS KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Status Keluarga
                                <span class="required">*</span>
                            </label>

                            <select
                                name="status_kepala_keluarga"
                                id="edit_status_kepala_keluarga"
                                class="responden-form-control"
                                required
                            >
                                <option value="Kepala Keluarga" selected>
                                    Kepala Keluarga
                                </option>
                            </select>

                        </div>

                    </div>

                </div>





                {{-- =================================================
                     WILAYAH EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Wilayah / Alamat Keluarga
                    </div>

                    <div class="responden-section-description">
                        
                    </div>


                    <div class="responden-form-grid">


                        {{-- PROVINSI --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Provinsi
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="provinsi"
                                id="edit_provinsi"
                                class="responden-form-control"
                                value="Jawa Timur"
                                readonly
                                required
                            >

                        </div>


                        {{-- KOTA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Daerah/Kota
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="daerah"
                                id="edit_daerah"
                                class="responden-form-control"
                                value="Kota Pasuruan"
                                readonly
                                required
                            >

                        </div>


                        {{-- KECAMATAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kecamatan
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kecamatan_id"
                                id="edit_kecamatan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan
                                </option>

                                @foreach ($kecamatans as $kecamatan)

                                    <option
                                        value="{{ $kecamatan->kecamatan_id }}"
                                    >
                                        {{ $kecamatan->deskripsi }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- KELURAHAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kelurahan/Desa
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kelurahan_id"
                                id="edit_kelurahan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan terlebih dahulu
                                </option>

                            </select>

                        </div>


                        <div class="responden-wilayah-pair">

{{-- RT --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                RT
                            </label>

                            <input
                                type="text"
                                name="rt"
                                id="edit_rt"
                                class="responden-form-control"
                                maxlength="5"
                                inputmode="numeric"
                                placeholder="Contoh: 001"
                            >

                        </div>

{{-- RW --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                RW
                            </label>

                            <input
                                type="text"
                                name="rw"
                                id="edit_rw"
                                class="responden-form-control"
                                maxlength="5"
                                inputmode="numeric"
                                placeholder="Contoh: 002"
                            >

                        </div>

</div>

<div class="responden-wilayah-pair">

{{-- KODE POS --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kode Pos
                            </label>

                            <input
                                type="text"
                                name="kode_pos"
                                id="edit_kode_pos"
                                class="responden-form-control"
                                maxlength="10"
                                inputmode="numeric"
                                placeholder="Masukkan kode pos"
                            >

                        </div>

{{-- ALAMAT --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Alamat Lengkap
                                <span class="required">*</span>
                            </label>

                            <textarea
                                name="alamat_lengkap"
                                id="edit_alamat_lengkap"
                                class="responden-form-control"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                required
                            ></textarea>

                        </div>

</div>

                    </div>

                </div>



                {{-- =================================================
                     ANGGOTA EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="anggota-header">
                        <div>
                            <div class="anggota-header-text">
                                Anggota Keluarga
                            </div>
                            <div class="responden-section-description" style="margin-bottom:0;margin-top:4px;">
                                
                            </div>
                        </div>
                    </div>

                    <div class="anggota-edit-toolbar">
                        <div class="anggota-edit-info">
                            <div class="anggota-edit-info-title">
                                <span class="anggota-edit-info-icon"></span>
                                <span></span>
                            </div>
                            <div class="anggota-edit-info-text">
                            </div>
                        </div>

                        <button
                            type="button"
                            class="btn-tambah-anggota-edit"
                            onclick="tambahAnggotaEditBaru()"
                        >
                            <span style="font-size:15px;line-height:1;">+</span>
                            <span>Tambah Anggota</span>
                        </button>
                    </div>

                    <div id="anggotaEditContainer" style="margin-top:14px;"></div>

                </div>


                {{-- =================================================
                     GEOTAGGING EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Geotagging Lokasi Rumah
                    </div>

                    <div class="responden-section-description">
                        Lokasi tersimpan dapat dilihat pada peta. Titik dapat digeser untuk memperbarui lokasi, atau gunakan tombol Ambil Lokasi Saya.
                    </div>

                    <button
                        type="button"
                        class="btn-lokasi"
                        onclick="ambilLokasi('edit', this)"
                    >
                        📍 Ambil Lokasi Saya
                    </button>

                    <div id="mapEdit" class="responden-map"></div>

                    <div class="geotagging-info">
                        <span>Koordinat tersimpan:</span>
                        <span
                            id="koordinatEditText"
                            class="geotagging-coordinate"
                        >
                            Belum dipilih
                        </span>
                    </div>

                    <div class="geotagging-help">
                        Geser titik pada peta ke lokasi yang benar, lalu klik Simpan Lokasi.
                    </div>

                    <div
                        id="konfirmasiLokasiEdit"
                        class="geotagging-confirm"
                        style="display:none;"
                    >
                        <button
                            type="button"
                            class="geotagging-save"
                            onclick="simpanLokasi('edit')"
                        >
                            Simpan Lokasi
                        </button>

                        <button
                            type="button"
                            class="geotagging-cancel"
                            onclick="batalLokasi('edit')"
                        >
                            Batal
                        </button>
                    </div>

                    <input
                        type="hidden"
                        name="geotangging"
                        id="edit_geotangging"
                    >

                </div>
            </div>


            <div class="responden-modal-footer">

                <button
                    type="button"
                    class="responden-btn responden-btn-secondary"
                    onclick="tutupModalEdit()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="responden-btn responden-btn-primary"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection



<div id="modalDetailResponden" class="responden-modal-overlay">
<div class="responden-modal">
<div class="responden-modal-header"><div><div class="responden-modal-label">Informasi</div><h3 class="responden-modal-title">Detail Responden</h3></div><button type="button" class="responden-modal-close" onclick="tutupModalDetail()">&times;</button></div>
<div class="responden-modal-body" id="detailRespondenBody"></div>
<div class="responden-modal-footer"><button type="button" class="responden-btn responden-btn-secondary" onclick="tutupModalDetail()">Tutup</button></div>
</div></div>

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const n = document.getElementById('respondenNotification');
    if (n) {
        setTimeout(function () { n.remove(); }, 4000);
    }

    const m = document.getElementById('modalDetailResponden');
    if (m) {
        m.addEventListener('click', function (e) {
            if (e.target === m) tutupModalDetail();
        });
    }
});
</script>

<script>

/* =========================================================
   MODAL
========================================================= */

function bukaModalTambah() {

    const modal = document.getElementById('modalTambahResponden');
    const form = modal ? modal.querySelector('form') : null;

    if (form) {
        form.reset();
    }

    pendingTambah = null;
    savedTambah = null;

    setSavedLocation('tambah', '');
    tampilkanKonfirmasiLokasi('tambah', false);

    if (markerTambah) {
        markerTambah.remove();
        markerTambah = null;
    }

    const jumlah = document.getElementById('jumlah_anggota');

    if (jumlah) {
        jumlah.value = 0;
    }

    const container = document.getElementById('anggotaContainer');

    if (container) {
        container.innerHTML = '';
    }

    renderAnggotaTambah();

    setTimeout(function () {
        initMapTambah();
    }, 200);

    if (modal) {
        modal.classList.add('active');
    }

    document.body.style.overflow = 'hidden';
}


function tutupModalTambah() {

    const modal = document.getElementById('modalTambahResponden');

    if (modal) {
        modal.classList.remove('active');
    }

    document.body.style.overflow = '';
}


function tutupModalEdit() {

    const modal = document.getElementById('modalEditResponden');

    if (modal) {
        modal.classList.remove('active');
    }

    document.body.style.overflow = '';
}


/* =========================================================
   NOMOR HANYA ANGKA
========================================================= */

document.addEventListener('input', function (event) {

    if (event.target.classList.contains('nomor-16-digit')) {

        event.target.value = event.target.value
            .replace(/\D/g, '')
            .slice(0, 16);
    }
});


/* =========================================================
   FORM ANGGOTA TAMBAH
========================================================= */

let anggotaIndex = 0;


function renderAnggotaTambah() {

    const container = document.getElementById('anggotaContainer');
    const input = document.getElementById('jumlah_anggota');

    if (!container || !input) {
        return;
    }

    let jumlah = parseInt(input.value) || 0;

    if (jumlah < 0) {
        jumlah = 0;
        input.value = 0;
    }

    if (jumlah > 19) {
        jumlah = 19;
        input.value = 19;
    }

    container.innerHTML = '';
    anggotaIndex = 0;

    for (let i = 0; i < jumlah; i++) {
        tambahAnggota(false, null, false);
    }
}


function tambahAnggota(keepExisting = false, data = null, isKepala = false) {

    const container = document.getElementById('anggotaContainer');

    if (!container) {
        return;
    }

    const index = anggotaIndex;
    const nomor = index + 1;

    const nik = data?.nik ?? '';
    const nama = data?.nama_lengkap ?? '';
    const status = data?.status_keluarga ?? '';

    const card = document.createElement('div');
    card.className = 'anggota-card';
    card.dataset.index = index;

    card.innerHTML = `
        <div class="anggota-card-header">
            <div class="anggota-card-title">
                <div class="anggota-number">${nomor}</div>
                <span>Anggota Keluarga ${nomor}</span>
                ${!data?.id && !isKepala ? '<span class="anggota-baru-badge">Anggota Baru</span>' : ''}
            </div>
            ${!data?.id && !isKepala ? `
                <button
                    type="button"
                    class="btn-hapus-anggota-baru"
                    onclick="hapusAnggotaTambah(${index})"
                >
                    Hapus
                </button>
            ` : ''}
        </div>

        <div class="responden-form-grid">

            <div class="responden-form-group">
                <label class="responden-form-label">
                    NIK <span class="required">*</span>
                </label>
                <input
                    type="text"
                    name="anggota[${index}][nik]"
                    class="responden-form-control nomor-16-digit"
                    maxlength="16"
                    minlength="16"
                    pattern="[0-9]{16}"
                    inputmode="numeric"
                    placeholder="Masukkan 16 digit NIK"
                    value="${escapeHtml(nik)}"
                    required
                >
            </div>

            <div class="responden-form-group">
                <label class="responden-form-label">
                    Nama Lengkap <span class="required">*</span>
                </label>
                <input
                    type="text"
                    name="anggota[${index}][nama_lengkap]"
                    class="responden-form-control anggota-nama-input"
                    maxlength="255"
                    placeholder="Masukkan nama lengkap"
                    value="${escapeHtml(nama)}"
                    required
                >
            </div>

            <div class="responden-form-group full">
                <label class="responden-form-label">
                    Status Keluarga <span class="required">*</span>
                </label>

                <select
                    name="anggota[${index}][status_keluarga]"
                    class="responden-form-control status-keluarga"
                    required
                >
                    <option value="">Pilih Status Keluarga</option>
                    <option value="Istri">Istri</option>
                    <option value="Suami">Suami</option>
                    <option value="Anak">Anak</option>
                    <option value="Orang Tua">Orang Tua</option>
                    <option value="Saudara">Saudara</option>
                    <option value="Famili">Famili</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>
    `;

    container.appendChild(card);

    anggotaIndex++;
}


function hapusAnggotaTambah(index) {

    const container = document.getElementById('anggotaContainer');
    const jumlah = document.getElementById('jumlah_anggota');

    if (!container || !jumlah) {
        return;
    }

    const cards = Array.from(container.querySelectorAll('.anggota-card'));
    const cardToRemove = cards.find(function (card) {
        return Number(card.dataset.index) === index;
    });

    if (!cardToRemove) {
        return;
    }

    cardToRemove.remove();

    const remainingCards = container.querySelectorAll('.anggota-card');

    remainingCards.forEach(function (card, cardIndex) {
        card.dataset.index = cardIndex;

        const number = card.querySelector('.anggota-number');
        const title = card.querySelector('.anggota-card-title > span');

        if (number) {
            number.textContent = cardIndex + 1;
        }

        if (title) {
            title.textContent = 'Anggota Keluarga ' + (cardIndex + 1);
        }

        card.querySelectorAll('[name^="anggota["]').forEach(function (field) {
            field.name = field.name.replace(/^anggota\[\d+\]/, 'anggota[' + cardIndex + ']');
        });

        const removeButton = card.querySelector('.btn-hapus-anggota-baru');

        if (removeButton) {
            removeButton.setAttribute('onclick', 'hapusAnggotaTambah(' + cardIndex + ')');
        }
    });

    jumlah.value = remainingCards.length;
    anggotaIndex = remainingCards.length;
}


/* =========================================================
   JUMLAH ANGGOTA
========================================================= */

function syncJumlahAnggotaTambah() {

    const container = document.getElementById('anggotaContainer');
    const input = document.getElementById('jumlah_anggota');

    if (!container || !input) {
        return;
    }

    let jumlahBaru = parseInt(input.value) || 0;

    if (jumlahBaru < 0) {
        jumlahBaru = 0;
        input.value = 0;
    }

    if (jumlahBaru > 19) {
        jumlahBaru = 19;
        input.value = 19;
    }

    let jumlahSekarang = container.querySelectorAll('.anggota-card').length;

    while (jumlahSekarang < jumlahBaru) {
        tambahAnggota(false, null, false);
        jumlahSekarang++;
    }

    while (jumlahSekarang > jumlahBaru) {
        const cards = container.querySelectorAll('.anggota-card');
        const lastCard = cards[cards.length - 1];

        if (!lastCard) {
            break;
        }

        lastCard.remove();
        jumlahSekarang--;
    }

    const cards = container.querySelectorAll('.anggota-card');

    cards.forEach(function (card, cardIndex) {

        card.dataset.index = cardIndex;

        const number = card.querySelector('.anggota-number');
        const title = card.querySelector('.anggota-card-title > span');

        if (number) {
            number.textContent = cardIndex + 1;
        }

        if (title) {
            title.textContent = 'Anggota Keluarga ' + (cardIndex + 1);
        }

        card.querySelectorAll('[name^=\"anggota[\"]').forEach(function (field) {
            field.name = field.name.replace(/^anggota\[\d+\]/, 'anggota[' + cardIndex + ']');
        });

        const removeButton = card.querySelector('.btn-hapus-anggota-baru');

        if (removeButton) {
            removeButton.setAttribute('onclick', 'hapusAnggotaTambah(' + cardIndex + ')');
        }
    });

    anggotaIndex = cards.length;
}

document.addEventListener('DOMContentLoaded', function () {

    const jumlah = document.getElementById('jumlah_anggota');

    if (jumlah) {
        jumlah.addEventListener('input', syncJumlahAnggotaTambah);
        renderAnggotaTambah();
    }

});


/* =========================================================
   MAP GEOTAGGING
========================================================= */

let mapTambah = null;
let markerTambah = null;
let mapEdit = null;
let markerEdit = null;

let pendingTambah = null;
let pendingEdit = null;
let savedTambah = null;
let savedEdit = null;

const DEFAULT_LAT = -7.6453;
const DEFAULT_LNG = 112.9075;


function formatKoordinat(lat, lng) {
    return Number(lat).toFixed(7) + ', ' + Number(lng).toFixed(7);
}


function setMarkerOnly(type, lat, lng) {

    const isTambah = type === 'tambah';
    const map = isTambah ? mapTambah : mapEdit;

    if (!map) {
        return;
    }

    const latLng = [Number(lat), Number(lng)];

    if (isTambah) {

        if (markerTambah) {
            markerTambah.setLatLng(latLng);
        } else {
            markerTambah = L.marker(latLng, {
                draggable: false
            }).addTo(map);
        }

    } else {

        if (markerEdit) {
            markerEdit.setLatLng(latLng);
        } else {
            markerEdit = L.marker(latLng, {
                draggable: true
            }).addTo(map);

            markerEdit.on('dragend', function () {
                const position = markerEdit.getLatLng();
                setPendingLocation(
                    'edit',
                    position.lat,
                    position.lng
                );
            });
        }
    }

    map.setView(latLng, 17);
}


function setSavedLocation(type, value) {

    const isTambah = type === 'tambah';

    const inputId = isTambah
        ? 'geotangging'
        : 'edit_geotangging';

    const textId = isTambah
        ? 'koordinatTambahText'
        : 'koordinatEditText';

    const input = document.getElementById(inputId);
    const text = document.getElementById(textId);

    if (input) {
        input.value = value || '';
    }

    if (text) {
        text.textContent = value || 'Belum dipilih';
    }
}


function tampilkanKonfirmasiLokasi(type, tampil) {

    const id = type === 'tambah'
        ? 'konfirmasiLokasiTambah'
        : 'konfirmasiLokasiEdit';

    const element = document.getElementById(id);

    if (element) {
        element.style.display = tampil ? 'flex' : 'none';
    }
}


function setPendingLocation(type, lat, lng) {

    const value = formatKoordinat(lat, lng);

    const pending = {
        lat: Number(lat),
        lng: Number(lng),
        value: value
    };

    if (type === 'tambah') {
        pendingTambah = pending;
    } else {
        pendingEdit = pending;
    }

    setMarkerOnly(type, lat, lng);

    const textId = type === 'tambah'
        ? 'koordinatTambahText'
        : 'koordinatEditText';

    const text = document.getElementById(textId);

    if (text) {
        text.textContent = value + ' (belum disimpan)';
    }

    tampilkanKonfirmasiLokasi(type, true);
}


function simpanLokasi(type) {

    const pending = type === 'tambah'
        ? pendingTambah
        : pendingEdit;

    if (!pending) {
        alert('Ambil lokasi terlebih dahulu.');
        return;
    }

    setSavedLocation(type, pending.value);

    if (type === 'tambah') {
        savedTambah = pending;
        pendingTambah = null;
    } else {
        savedEdit = pending;
        pendingEdit = null;
    }

    tampilkanKonfirmasiLokasi(type, false);
}


function batalLokasi(type) {

    const saved = type === 'tambah'
        ? savedTambah
        : savedEdit;

    if (saved) {

        setMarkerOnly(
            type,
            saved.lat,
            saved.lng
        );

        setSavedLocation(
            type,
            saved.value
        );

    } else {

        const marker = type === 'tambah'
            ? markerTambah
            : markerEdit;

        if (marker) {
            marker.remove();

            if (type === 'tambah') {
                markerTambah = null;
            } else {
                markerEdit = null;
            }
        }

        setSavedLocation(type, '');
    }

    if (type === 'tambah') {
        pendingTambah = null;
    } else {
        pendingEdit = null;
    }

    tampilkanKonfirmasiLokasi(type, false);
}


function kunciInteraksiPeta(map) {

    if (!map) {
        return;
    }

    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();

    if (map.tap) {
        map.tap.disable();
    }

    if (map.zoomControl) {
        map.zoomControl.remove();
    }
}


function initMapTambah() {

    const element = document.getElementById('mapTambah');

    if (!element || typeof L === 'undefined') {
        return;
    }

    if (!mapTambah) {

        mapTambah = L.map('mapTambah', {
            zoomControl: false,
            dragging: false,
            touchZoom: false,
            doubleClickZoom: false,
            scrollWheelZoom: false,
            boxZoom: false,
            keyboard: false
        }).setView(
            [DEFAULT_LAT, DEFAULT_LNG],
            14
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(mapTambah);

        kunciInteraksiPeta(mapTambah);

        mapTambah.on('click', function (event) {
            setPendingLocation(
                'tambah',
                event.latlng.lat,
                event.latlng.lng
            );
        });
    }

    setTimeout(function () {
        mapTambah.invalidateSize();
    }, 100);
}


function initMapEdit(latitude = null, longitude = null) {

    const element = document.getElementById('mapEdit');

    if (!element || typeof L === 'undefined') {
        return;
    }

    if (!mapEdit) {

        mapEdit = L.map('mapEdit', {
            zoomControl: false,
            dragging: false,
            touchZoom: false,
            doubleClickZoom: false,
            scrollWheelZoom: false,
            boxZoom: false,
            keyboard: false
        }).setView(
            [DEFAULT_LAT, DEFAULT_LNG],
            14
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(mapEdit);

        kunciInteraksiPeta(mapEdit);

        mapEdit.on('click', function (event) {
            setPendingLocation(
                'edit',
                event.latlng.lat,
                event.latlng.lng
            );
        });
    }

    if (latitude !== null && longitude !== null) {
        setMarkerOnly('edit', latitude, longitude);
    }

    setTimeout(function () {
        mapEdit.invalidateSize();
    }, 100);
}


function ambilLokasi(type, button) {

    if (!navigator.geolocation) {
        alert('Browser ini tidak mendukung pengambilan lokasi perangkat.');
        return;
    }

    if (button) {
        button.disabled = true;
        button.textContent = '📍 Mengambil lokasi...';
    }

    navigator.geolocation.getCurrentPosition(

        function (position) {

            setPendingLocation(
                type,
                position.coords.latitude,
                position.coords.longitude
            );

            if (button) {
                button.disabled = false;
                button.textContent = '📍 Ambil Lokasi Saya';
            }
        },

        function (error) {

            console.error('Geolocation:', error);

            if (button) {
                button.disabled = false;
                button.textContent = '📍 Ambil Lokasi Saya';
            }

            let message = 'Lokasi tidak dapat diambil. Pastikan GPS/lokasi perangkat aktif dan coba lagi.';

            if (error.code === 1) {
                message = 'Izin lokasi ditolak. Izinkan akses lokasi pada browser kemudian coba lagi.';
            } else if (error.code === 2) {
                message = 'Lokasi perangkat tidak tersedia. Pastikan GPS/lokasi perangkat aktif kemudian coba lagi.';
            } else if (error.code === 3) {
                message = 'Pengambilan lokasi terlalu lama. Pastikan GPS/lokasi perangkat aktif kemudian coba lagi.';
            }

            alert(message);
        },

        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
}


/* =========================================================
   DETAIL RESPONDEN
========================================================= */
function tutupModalDetail() {
    const modal = document.getElementById('modalDetailResponden');

    if (window.detailRespondenMap) {
        window.detailRespondenMap.remove();
        window.detailRespondenMap = null;
    }

    if (modal) {
        modal.classList.remove('active');
    }

    document.body.style.overflow = '';
}


window.detailResponden = function (id) {

    const modal = document.getElementById('modalDetailResponden');
    const body = document.getElementById('detailRespondenBody');

    if (!modal || !body) {
        return;
    }

    const detailUrl = @json(route('responden.editData', ['id' => '__ID__']));

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    body.innerHTML = `
        <div class="responden-section">
            <div class="responden-section-heading">
                Memuat data...
            </div>
        </div>
    `;

    fetch(detailUrl.replace('__ID__', id), {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            return response.json();
        })
        .then(function (data) {

            const anggota = Array.isArray(data.anggota)
                ? data.anggota
                : [];

            const rt = data.rt ?? '-';
            const rw = data.rw ?? '-';

            body.innerHTML = `
                <div class="responden-detail-summary">
                    <div class="responden-detail-summary-item">
                        <div class="responden-detail-summary-label">Nomor KK</div>
                        <div class="responden-detail-summary-value">${escapeHtml(data.no_kk ?? '-')}</div>
                    </div>
                    <div class="responden-detail-summary-item">
                        <div class="responden-detail-summary-label">Kepala Keluarga</div>
                        <div class="responden-detail-summary-value">${escapeHtml(data.nama_kepala_keluarga ?? data.nama_lengkap ?? '-')}</div>
                    </div>
                </div>

                <div class="responden-section">
                    <div class="responden-detail-section-title">Identitas Keluarga</div>
                    <div class="responden-detail-grid">
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">NIK Kepala Keluarga</div>
                            <div class="responden-detail-value">${escapeHtml(data.nik_kepala_keluarga ?? data.nik ?? '-')}</div>
                        </div>
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">Jumlah Anggota</div>
                            <div class="responden-detail-value">${anggota.length} anggota</div>
                        </div>
                    </div>
                </div>

                <div class="responden-section">
                    <div class="responden-detail-section-title">Wilayah dan Alamat</div>
                    <div class="responden-detail-grid">
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">Kecamatan</div>
                            <div class="responden-detail-value">${escapeHtml(data.kecamatan ?? '-')}</div>
                        </div>
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">Kelurahan/Desa</div>
                            <div class="responden-detail-value">${escapeHtml(data.kelurahan ?? '-')}</div>
                        </div>
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">RT</div>
                            <div class="responden-detail-value">${escapeHtml(rt)}</div>
                        </div>
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">RW</div>
                            <div class="responden-detail-value">${escapeHtml(rw)}</div>
                        </div>
                        <div class="responden-detail-item">
                            <div class="responden-detail-label">Kode Pos</div>
                            <div class="responden-detail-value">${escapeHtml(data.kode_pos ?? '-')}</div>
                        </div>
                        <div class="responden-detail-item full">
                            <div class="responden-detail-label">Alamat Lengkap</div>
                            <div class="responden-detail-value">${escapeHtml(data.alamat_lengkap ?? '-').replace(/\n/g, '<br>')}</div>
                        </div>
                        <div class="responden-detail-item full">
                            <div class="responden-detail-label">Titik Koordinat</div>
                            <div class="responden-detail-value" id="detailKoordinatText">${escapeHtml(data.geotangging ?? '-')}</div>

                            <div class="responden-detail-map-wrapper">
                                <div class="responden-detail-label">Peta Lokasi Rumah</div>
                                <div id="detailRespondenMap" class="responden-detail-map"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="responden-section">
                    <div class="responden-detail-section-title">Anggota Keluarga</div>
                    ${anggota.length
                        ? anggota.map(function (item, index) {
                            return `
                                <div class="responden-detail-member">
                                    <div class="responden-detail-member-name">${index + 1}. ${escapeHtml(item.nama_lengkap ?? '-')}</div>
                                    <div class="responden-detail-member-meta">
                                        NIK: ${escapeHtml(item.nik ?? '-')} &nbsp;•&nbsp;
                                        Status: ${escapeHtml(item.status_keluarga ?? '-')}
                                    </div>
                                </div>
                            `;
                        }).join('')
                        : '<div class="responden-detail-value">Belum ada anggota.</div>'
                    }
                </div>
            `;

            // Tampilkan peta berdasarkan koordinat geotangging yang tersimpan.
            const detailMapElement = document.getElementById('detailRespondenMap');
            const detailGeo = parseGeotangging(data.geotangging);

            if (detailMapElement && detailGeo) {
                setTimeout(function () {
                    if (window.detailRespondenMap) {
                        window.detailRespondenMap.remove();
                        window.detailRespondenMap = null;
                    }

                    window.detailRespondenMap = L.map('detailRespondenMap', {
                        zoomControl: true
                    }).setView(
                        [detailGeo.lat, detailGeo.lng],
                        17
                    );

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(window.detailRespondenMap);

                    L.marker([detailGeo.lat, detailGeo.lng])
                        .addTo(window.detailRespondenMap)
                        .bindPopup(
                            '<strong>Lokasi Rumah</strong><br>' +
                            escapeHtml(data.geotangging ?? '')
                        )
                        .openPopup();

                    setTimeout(function () {
                        window.detailRespondenMap.invalidateSize();
                    }, 100);
                }, 100);
            } else if (detailMapElement) {
                detailMapElement.innerHTML = `
                    <div class="responden-detail-map-empty">
                        Titik koordinat belum tersedia, sehingga peta lokasi tidak dapat ditampilkan.
                    </div>
                `;
            }
        })
        .catch(function (error) {
            console.error('Detail responden:', error);

            body.innerHTML = `
                <div class="responden-section">
                    <div class="responden-section-heading">
                        Gagal memuat detail responden
                    </div>
                    <div class="responden-detail-value" style="margin-top:8px;">
                        Data tidak dapat dimuat. Silakan coba lagi.
                    </div>
                </div>
            `;
        });
};

/* =========================================================
   EDIT RESPONDEN
========================================================= */

let anggotaEditIndex = 0;
let anggotaEditData = [];


window.editResponden = function (id) {

    const editDataUrl = @json(route('responden.editData', ['id' => '__ID__']));
    const updateUrl = @json(route('responden.update', ['id' => '__ID__']));

    fetch(editDataUrl.replace('__ID__', id))
        .then(function (response) {

            if (!response.ok) {
                throw new Error('Gagal mengambil data.');
            }

            return response.json();
        })
        .then(function (data) {

            const modal = document.getElementById('modalEditResponden');
            const form = document.getElementById('formEditResponden');

            if (!modal || !form) {
                return;
            }

            form.action = updateUrl.replace('__ID__', id);

            document.getElementById('edit_provinsi').value = data.provinsi ?? 'Jawa Timur';
            document.getElementById('edit_daerah').value = data.daerah ?? 'Kota Pasuruan';
            document.getElementById('edit_kode_pos').value = data.kode_pos ?? '';

            // RT/RW disimpan langsung di tabel keluargas.
            // Gunakan data.rt dan data.rw; fallback ke rt_rw untuk kompatibilitas.
            const rtRwValue = String(data.rt_rw ?? '').trim();
            const rtRwParts = rtRwValue.split('/');

            document.getElementById('edit_rt').value =
                data.rt ?? (rtRwParts[0] ? rtRwParts[0].trim() : '');

            document.getElementById('edit_rw').value =
                data.rw ?? (rtRwParts[1] ? rtRwParts[1].trim() : '');

            document.getElementById('edit_alamat_lengkap').value = data.alamat_lengkap ?? '';
            document.getElementById('edit_nomor_kk').value = data.no_kk ?? '';
            document.getElementById('edit_nik_kepala_keluarga').value = data.nik_kepala_keluarga ?? data.nik ?? '';
            document.getElementById('edit_nama_kepala_keluarga').value = data.nama_kepala_keluarga ?? data.nama_lengkap ?? '';

            const kecamatan = document.getElementById('edit_kecamatan');

            if (kecamatan) {
                kecamatan.value = data.kecamatan_id ?? '';
            }

            if (data.kecamatan_id) {
                loadKelurahan(data.kecamatan_id, 'edit_kelurahan', data.kelurahan_id);
            }

            anggotaEditData = Array.isArray(data.anggota)
                ? data.anggota.map(function (item) {
                    return {
                        id: item.id ?? null,
                        nik: item.nik ?? '',
                        nama_lengkap: item.nama_lengkap ?? '',
                        status_keluarga: item.status_keluarga ?? ''
                    };
                })
                : [];

            const kepalaIndex = anggotaEditData.findIndex(function (item) {
                return String(item.status_keluarga ?? '').trim().toUpperCase() === 'KEPALA KELUARGA';
            });

            if (kepalaIndex > 0) {
                const kepala = anggotaEditData.splice(kepalaIndex, 1)[0];
                anggotaEditData.unshift(kepala);
            }

            renderAnggotaEdit();

            const geo = parseGeotangging(data.geotangging);

            pendingEdit = null;
            savedEdit = geo
                ? {
                    lat: geo.lat,
                    lng: geo.lng,
                    value: formatKoordinat(geo.lat, geo.lng)
                }
                : null;

            setSavedLocation(
                'edit',
                savedEdit ? savedEdit.value : ''
            );

            tampilkanKonfirmasiLokasi('edit', false);

            setTimeout(function () {
                initMapEdit(
                    geo ? geo.lat : null,
                    geo ? geo.lng : null
                );
            }, 200);

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        })
        .catch(function (error) {
            console.error(error);
            alert('Data responden gagal dimuat.');
        });
};


function renderAnggotaEdit() {

    const container = document.getElementById('anggotaEditContainer');

    if (!container) {
        return;
    }

    container.innerHTML = '';
    anggotaEditIndex = 0;

    const countElement = document.getElementById('anggotaEditCount');

    if (countElement) {
        countElement.textContent = anggotaEditData.length + ' anggota';
    }

    anggotaEditData.forEach(function (data, index) {
        tambahAnggotaEdit(
            data,
            false
        );
    });
}


function tambahAnggotaEditBaru() {

    anggotaEditData.push({
        id: null,
        nik: '',
        nama_lengkap: '',
        status_keluarga: ''
    });

    renderAnggotaEdit();

    const container = document.getElementById('anggotaEditContainer');

    if (container) {
        const cards = container.querySelectorAll('.anggota-card');
        const lastCard = cards[cards.length - 1];

        if (lastCard) {
            lastCard.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
    }
}


function hapusAnggotaEdit(index) {

    const item = anggotaEditData[index];

    if (!item) {
        return;
    }

    const nama = item.nama_lengkap
        ? ' ' + item.nama_lengkap
        : '';

    if (!confirm('Hapus anggota' + nama + ' dari KK ini?')) {
        return;
    }

    anggotaEditData.splice(index, 1);
    renderAnggotaEdit();
}


function tambahAnggotaEdit(data = null, isKepala = false) {

    const container = document.getElementById('anggotaEditContainer');

    if (!container) {
        return;
    }

    const index = anggotaEditIndex;
    const nomor = index + 1;

    const id = data?.id ?? '';
    const nik = data?.nik ?? '';
    const nama = data?.nama_lengkap ?? '';
    const status = data?.status_keluarga ?? (isKepala ? 'Kepala Keluarga' : '');

    const card = document.createElement('div');
    card.className = 'anggota-card' + (!id ? ' anggota-card-new' : '');

    card.innerHTML = `
        <input
            type="hidden"
            name="anggota[${index}][id]"
            value="${escapeHtml(id)}"
        >

        <div class="anggota-card-header">
            <div class="anggota-card-title">
                <div class="anggota-number">${nomor}</div>
                <span>${isKepala ? 'Kepala Keluarga' : 'Anggota Keluarga ' + nomor}</span>
                ${!id && !isKepala ? '<span class="anggota-baru-badge">Anggota Baru</span>' : ''}
            </div>
            ${!isKepala ? `
                <button
                    type="button"
                    class="btn-hapus-anggota-baru"
                    onclick="hapusAnggotaEdit(${index})"
                    title="Hapus anggota dari KK"
                >
                    Hapus
                </button>
            ` : ''}
        </div>

        <div class="responden-form-grid">

            <div class="responden-form-group">
                <label class="responden-form-label">
                    NIK <span class="required">*</span>
                </label>
                <input
                    type="text"
                    name="anggota[${index}][nik]"
                    class="responden-form-control nomor-16-digit"
                    maxlength="16"
                    minlength="16"
                    pattern="[0-9]{16}"
                    inputmode="numeric"
                    placeholder="Masukkan 16 digit NIK"
                    value="${escapeHtml(nik)}"
                    required
                >
            </div>

            <div class="responden-form-group">
                <label class="responden-form-label">
                    Nama Lengkap <span class="required">*</span>
                </label>
                <input
                    type="text"
                    name="anggota[${index}][nama_lengkap]"
                    class="responden-form-control anggota-nama-input"
                    maxlength="255"
                    placeholder="Masukkan nama lengkap"
                    value="${escapeHtml(nama)}"
                    required
                >
            </div>

            <div class="responden-form-group full">
                <label class="responden-form-label">
                    Status Keluarga <span class="required">*</span>
                </label>
                <select
                    name="anggota[${index}][status_keluarga]"
                    class="responden-form-control status-keluarga"
                    required
                >
                    ${isKepala
                        ? '<option value="Kepala Keluarga" selected>Kepala Keluarga</option>'
                        : `
                            <option value="">Pilih Status Keluarga</option>
                            <option value="Istri" ${status === 'Istri' ? 'selected' : ''}>Istri</option>
                            <option value="Suami" ${status === 'Suami' ? 'selected' : ''}>Suami</option>
                            <option value="Anak" ${status === 'Anak' ? 'selected' : ''}>Anak</option>
                            <option value="Orang Tua" ${status === 'Orang Tua' ? 'selected' : ''}>Orang Tua</option>
                            <option value="Saudara" ${status === 'Saudara' ? 'selected' : ''}>Saudara</option>
                            <option value="Famili" ${status === 'Famili' ? 'selected' : ''}>Famili</option>
                            <option value="Lainnya" ${status === 'Lainnya' ? 'selected' : ''}>Lainnya</option>
                        `
                    }
                </select>
            </div>
        </div>
    `;

    container.appendChild(card);

    const namaInput = card.querySelector('.anggota-nama-input');

    if (isKepala && namaInput) {
        namaInput.addEventListener('input', function () {
            const namaKepala = document.getElementById('edit_nama_kepala_keluarga');
            if (namaKepala) {
                namaKepala.value = this.value;
            }
        });
    }

    anggotaEditIndex++;
}


/* =========================================================
   TOGGLE NO KK
========================================================= */

function toggleKeluarga(id) {

    const button = document.getElementById('kkToggle-' + id);
    const mainRow = document.getElementById('keluarga-row-' + id);
    const memberRows = document.querySelectorAll(
        '.responden-member-row[data-keluarga="' + id + '"]'
    );

    if (!button) {
        return;
    }

    const sedangTerbuka = button.classList.contains('active');

    button.classList.toggle('active', !sedangTerbuka);

    if (mainRow) {
        mainRow.classList.toggle('expanded', !sedangTerbuka);
    }

    memberRows.forEach(function (row) {
        row.classList.toggle('active', !sedangTerbuka);
    });
}
/* =========================================================
   HAPUS RESPONDEN
========================================================= */

function hapusResponden(id) {

    if (!confirm('Apakah Anda yakin ingin menghapus data responden ini?')) {
        return;
    }

    const url = '{{ url('/responden/hapus') }}/' + id;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function (response) {
        return response.json().then(function (data) {
            return {
                ok: response.ok,
                data: data
            };
        });
    })
    .then(function (result) {
        if (result.data.blocked) {
            tampilkanNotifikasi(result.data.message, 'warning');
            return;
        }

        if (!result.ok || !result.data.success) {
            throw new Error(result.data.message || 'Data responden gagal dihapus.');
        }

        const row = document.getElementById('keluarga-row-' + id);
        if (row) {
            row.remove();
        }

        document
            .querySelectorAll('.responden-member-row[data-keluarga="' + id + '"]')
            .forEach(function (memberRow) {
                memberRow.remove();
            });

        tampilkanNotifikasi(result.data.message, 'success');
    })
    .catch(function (error) {
        tampilkanNotifikasi(
            error.message || 'Terjadi kesalahan saat menghapus data responden.',
            'warning'
        );
    });
}

function tampilkanNotifikasi(pesan, tipe = 'success') {
    const lama = document.getElementById('respondenNotificationDynamic');
    if (lama) lama.remove();

    const notifikasi = document.createElement('div');
    notifikasi.id = 'respondenNotificationDynamic';
    notifikasi.className = 'responden-notification ' + (tipe === 'warning' ? 'warning' : 'success');
    notifikasi.innerHTML = '<span class="responden-notification-icon">' + (tipe === 'warning' ? '!' : '✓') + '</span><span>' + escapeHtml(pesan) + '</span>';
    document.body.appendChild(notifikasi);

    setTimeout(function () {
        notifikasi.remove();
    }, 4000);
}



/* =========================================================
   KELURAHAN
========================================================= */

function loadKelurahan(kecamatanId, targetId, selectedId = null) {

    const select = document.getElementById(targetId);

    if (!select) {
        return;
    }

    if (!kecamatanId) {
        select.innerHTML = '<option value="">Pilih Kecamatan terlebih dahulu</option>';
        return;
    }

    select.innerHTML = '<option value="">Memuat Kelurahan/Desa...</option>';

    fetch('/responden/kelurahan/' + kecamatanId)
        .then(function (response) {

            if (!response.ok) {
                throw new Error('Gagal mengambil data kelurahan.');
            }

            return response.json();
        })
        .then(function (result) {

            const data = result.data ?? [];

            select.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';

            data.forEach(function (item) {

                const option = document.createElement('option');
                option.value = item.kelurahan_id;
                option.textContent = item.deskripsi;

                if (
                    selectedId !== null &&
                    String(item.kelurahan_id) === String(selectedId)
                ) {
                    option.selected = true;
                }

                select.appendChild(option);
            });

            if (data.length === 0) {
                select.innerHTML = '<option value="">Kelurahan/Desa tidak ditemukan</option>';
            }
        })
        .catch(function (error) {

            console.error(error);
            select.innerHTML = '<option value="">Kelurahan gagal dimuat</option>';
        });
}


/* =========================================================
   PARSE GEOTAGGING
========================================================= */

function parseGeotangging(value) {

    if (!value) {
        return null;
    }

    const parts = String(value)
        .split(',')
        .map(function (item) {
            return item.trim();
        });

    if (parts.length < 2) {
        return null;
    }

    const lat = parseFloat(parts[0]);
    const lng = parseFloat(parts[1]);

    if (Number.isNaN(lat) || Number.isNaN(lng)) {
        return null;
    }

    return {
        lat: lat,
        lng: lng
    };
}


/* =========================================================
   SEARCH
========================================================= */

/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value) {

    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}


/* =========================================================
   EVENT KECAMATAN
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const kecamatan = document.getElementById('kecamatan');

    if (kecamatan) {
        kecamatan.addEventListener('change', function () {
            loadKelurahan(this.value, 'kelurahan');
        });
    }

    const editKecamatan = document.getElementById('edit_kecamatan');

    if (editKecamatan) {
        editKecamatan.addEventListener('change', function () {
            loadKelurahan(this.value, 'edit_kelurahan');
        });
    }
});


/* =========================================================
   CLICK OUTSIDE + ESC
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const modalTambah = document.getElementById('modalTambahResponden');
    const modalEdit = document.getElementById('modalEditResponden');
    const modalDetail = document.getElementById('modalDetailResponden');

    if (modalDetail) {
        modalDetail.addEventListener('click', function (event) {
            if (event.target === this) {
                tutupModalDetail();
            }
        });
    }

    if (modalTambah) {
        modalTambah.addEventListener('click', function (event) {
            if (event.target === this) {
                tutupModalTambah();
            }
        });
    }

    if (modalEdit) {
        modalEdit.addEventListener('click', function (event) {
            if (event.target === this) {
                tutupModalEdit();
            }
        });
    }
});


document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {
        tutupModalTambah();
        tutupModalEdit();
        tutupModalDetail();
    }
});

</script>

@endpush
