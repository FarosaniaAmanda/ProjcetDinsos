@extends('admin.layouts.app')

@section('title', 'Pengguna')

@section('content')

<style>
    .pengguna-content {
        width: 100%;
    }

    .page-kicker {
        font-size: 11px;
        font-weight: 700;
        color: #252A86;
        letter-spacing: 1.2px;
        margin-bottom: 7px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 8px;
    }

    .page-description {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    /* =====================================================
       TABS
    ====================================================== */

    .tabs-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .tabs {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        padding: 0 20px;
        background: #fff;
    }

    .tab-button {
        position: relative;
        border: none;
        background: transparent;
        padding: 15px 18px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: .2s;
    }

    .tab-button:hover {
        color: #252A86;
    }

    .tab-button.active {
        color: #252A86;
        font-weight: 700;
    }

    .tab-button.active::after {
        content: "";
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: -1px;
        height: 2px;
        background: #252A86;
    }

    .tab-content {
        display: none;
        padding: 20px;
    }

    .tab-content.active {
        display: block;
    }

    /* =====================================================
       HEADER
    ====================================================== */

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 18px;
    }

    .table-header-left {
        min-width: 0;
    }

    .table-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .table-description {
        font-size: 11px;
        color: #9ca3af;
        line-height: 1.5;
    }

    .table-header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-shrink: 0;
    }

    /* =====================================================
       SEARCH
    ====================================================== */

    .search-box {
        width: 220px;
        height: 38px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        padding: 0 12px;
        font-family: inherit;
        font-size: 12px;
        color: #1f2937;
        outline: none;
    }

    .search-box:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37,42,134,.08);
    }

    .search-box::placeholder {
        color: #9ca3af;
    }

    /* =====================================================
       BUTTON
    ====================================================== */

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 38px;
        padding: 0 15px;
        background: #252A86;
        color: #fff;
        border: 1px solid #252A86;
        border-radius: 7px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: .2s;
    }

    .btn-primary:hover {
        background: #1d216d;
        border-color: #1d216d;
    }

    .btn-plus {
        font-size: 16px;
        line-height: 1;
    }

    /* =====================================================
       TABLE
    ====================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
    }

    table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        background: #fff;
    }

    th {
        background: #f8f9fc;
        color: #374151;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        padding: 13px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    td {
        color: #4b5563;
        font-size: 11px;
        padding: 13px 12px;
        border-bottom: 1px solid #eef0f4;
        vertical-align: middle;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #fafbff;
    }

    /* =====================================================
       ACTION
    ====================================================== */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit,
    .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 30px;
        padding: 0 10px;
        border-radius: 5px;
        font-family: inherit;
        font-size: 10px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: .2s;
    }

    .btn-edit {
        background: #fff;
        color: #252A86;
        border: 1px solid #252A86;
    }

    .btn-edit:hover {
        background: #252A86;
        color: #fff;
    }

    .btn-delete {
        background: #fff;
        color: #dc2626;
        border: 1px solid #dc2626;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: #fff;
    }

    /* =====================================================
       MODAL
    ====================================================== */

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,.48);
        z-index: 2000;
        padding: 25px;
        overflow-y: auto;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal {
        width: 100%;
        max-width: 560px;
        max-height: calc(100vh - 50px);
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0,0,0,.20);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: modalShow .18s ease;
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

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .modal-kicker {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #252A86;
        margin-bottom: 5px;
    }

    .modal-title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .modal-close {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 20px;
        cursor: pointer;
    }

    .modal-close:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .modal-body {
        padding: 22px;
        overflow-y: auto;
    }

    .modal-description {
        font-size: 11px;
        color: #9ca3af;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    /* =====================================================
       FORM
    ====================================================== */

    .form-group {
        margin-bottom: 17px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: 700;
        color: #374151;
    }

    .required {
        color: #dc2626;
    }

    .form-input,
    .form-select {
        width: 100%;
        height: 41px;
        padding: 0 12px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        outline: none;
        background: #fff;
        font-family: inherit;
        font-size: 12px;
        color: #1f2937;
        transition: .2s;
        box-sizing: border-box;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37,42,134,.08);
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .form-help {
        margin-top: 5px;
        font-size: 10px;
        color: #9ca3af;
        line-height: 1.5;
    }

    /* =====================================================
       PASSWORD
    ====================================================== */

    .password-reset-box {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .password-reset-box .form-input {
        flex: 1;
    }

    .password-default {
        background: #f8f9fc;
        color: #6b7280;
        cursor: default;
    }

    .btn-reset-password {
        height: 41px;
        padding: 0 13px;
        border-radius: 7px;
        border: 1px solid #252A86;
        background: #fff;
        color: #252A86;
        font-family: inherit;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: .2s;
    }

    .btn-reset-password:hover {
        background: #252A86;
        color: #fff;
    }

    .btn-reset-password:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    /* =====================================================
       FOOTER
    ====================================================== */

    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        padding: 16px 22px;
        border-top: 1px solid #e5e7eb;
        background: #fafbfc;
        flex-shrink: 0;
    }

    .btn-modal {
        height: 37px;
        padding: 0 15px;
        border-radius: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-modal-cancel {
        background: #fff;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-modal-cancel:hover {
        background: #f3f4f6;
    }

    .btn-modal-save {
        background: #252A86;
        color: #fff;
        border: 1px solid #252A86;
    }

    .btn-modal-save:hover {
        background: #1d216d;
    }

    .btn-modal-save:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    @media (max-width: 900px) {

        .table-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .table-header-actions {
            width: 100%;
            justify-content: flex-start;
        }

    }

    @media (max-width: 600px) {

        .tabs {
            padding: 0 10px;
            overflow-x: auto;
        }

        .tab-button {
            padding: 14px 12px;
            white-space: nowrap;
        }

        .tab-content {
            padding: 15px;
        }

        .table-header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box,
        .btn-primary {
            width: 100%;
        }

        .modal-overlay {
            padding: 12px;
            align-items: flex-start;
        }

        .modal {
            max-height: calc(100vh - 24px);
            margin-top: 12px;
        }

        .modal-header,
        .modal-body {
            padding: 17px;
        }

        .modal-footer {
            padding: 14px 17px;
        }

        .password-reset-box {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-reset-password {
            width: 100%;
        }

    }

    @media (max-width: 400px) {

        .modal-footer {
            flex-direction: column-reverse;
        }

        .btn-modal {
            width: 100%;
        }

    }
</style>


<div class="pengguna-content">

    <div class="page-kicker">MASTER</div>

    <h1 class="page-title">Pengguna</h1>

    <p class="page-description">
        Kelola data pengguna yang memiliki akses dalam sistem
        pendataan perlindungan sosial.
    </p>


    <div class="tabs-card">

        {{-- =====================================================
             TABS
        ====================================================== --}}

        <div class="tabs">

            <button
                type="button"
                class="tab-button active"
                data-tab="operator">
                Operator
            </button>

            <button
                type="button"
                class="tab-button"
                data-tab="verifikator">
                Verifikator
            </button>

            <button
                type="button"
                class="tab-button"
                data-tab="petugas">
                Petugas
            </button>

        </div>


        {{-- =====================================================
             OPERATOR
        ====================================================== --}}

        <div
            class="tab-content active"
            id="operator">

            <div class="table-header">

                <div class="table-header-left">

                    <div class="table-title">
                        Data Operator
                    </div>

                    <div class="table-description">
                        Daftar pengguna dengan hak akses sebagai operator.
                    </div>

                </div>

                <div class="table-header-actions">

                    <input
                        type="text"
                        class="search-box"
                        id="searchOperator"
                        placeholder="Cari operator..."
                        onkeyup="searchTable('searchOperator','operatorTable')">

                    <button
                        type="button"
                        class="btn-primary"
                        onclick="openAddModal('operator')">

                        <span class="btn-plus">+</span>
                        Tambah Operator

                    </button>

                </div>

            </div>


            <div class="table-wrapper">

                <table id="operatorTable">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nomor Identitas</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($operators as $index => $operator)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $operator->nomor_identitas }}
                                </td>

                                <td>
                                    {{ $operator->name }}
                                </td>

                                <td>
                                    {{ $operator->email }}
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        @php
                                            $operatorData = [
                                                'id' => $operator->id,
                                                'role' => 'operator',
                                                'nomor_identitas' => $operator->nomor_identitas,
                                                'name' => $operator->name,
                                                'email' => $operator->email,
                                            ];
                                        @endphp

                                        <button
                                            type="button"
                                            class="btn-edit"
                                            data-user='{{ json_encode($operatorData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}'
                                            onclick="editFromButton(this)">
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-delete"
                                            data-id="{{ $operator->id }}"
                                            data-name="{{ $operator->name }}"
                                            data-role="operator"
                                            onclick="hapusData(this)">
                                            Hapus
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align:center;color:#9ca3af;">

                                    Belum ada data operator.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             VERIFIKATOR
        ====================================================== --}}

        <div
            class="tab-content"
            id="verifikator">

            <div class="table-header">

                <div class="table-header-left">

                    <div class="table-title">
                        Data Verifikator
                    </div>

                    <div class="table-description">
                        Daftar pengguna dengan hak akses sebagai verifikator.
                    </div>

                </div>

                <div class="table-header-actions">

                    <input
                        type="text"
                        class="search-box"
                        id="searchVerifikator"
                        placeholder="Cari verifikator..."
                        onkeyup="searchTable('searchVerifikator','verifikatorTable')">

                    <button
                        type="button"
                        class="btn-primary"
                        onclick="openAddModal('verifikator')">

                        <span class="btn-plus">+</span>
                        Tambah Verifikator

                    </button>

                </div>

            </div>


            <div class="table-wrapper">

                <table id="verifikatorTable">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nomor Identitas</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($verifikators as $index => $verifikator)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $verifikator->nomor_identitas }}
                                </td>

                                <td>
                                    {{ $verifikator->name }}
                                </td>

                                <td>
                                    {{ $verifikator->email }}
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        @php
                                            $verifikatorData = [
                                                'id' => $verifikator->id,
                                                'role' => 'verifikator',
                                                'nomor_identitas' => $verifikator->nomor_identitas,
                                                'name' => $verifikator->name,
                                                'email' => $verifikator->email,
                                            ];
                                        @endphp

                                        <button
                                            type="button"
                                            class="btn-edit"
                                            data-user='{{ json_encode($verifikatorData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}'
                                            onclick="editFromButton(this)">
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-delete"
                                            data-id="{{ $verifikator->id }}"
                                            data-name="{{ $verifikator->name }}"
                                            data-role="verifikator"
                                            onclick="hapusData(this)">
                                            Hapus
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align:center;color:#9ca3af;">

                                    Belum ada data verifikator.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             PETUGAS
        ====================================================== --}}

        <div
            class="tab-content"
            id="petugas">

            <div class="table-header">

                <div class="table-header-left">

                    <div class="table-title">
                        Data Petugas
                    </div>

                    <div class="table-description">
                        Daftar petugas yang bertugas dalam proses pendataan.
                    </div>

                </div>

                <div class="table-header-actions">

                    <input
                        type="text"
                        class="search-box"
                        id="searchPetugas"
                        placeholder="Cari petugas..."
                        onkeyup="searchTable('searchPetugas','petugasTable')">

                    <button
                        type="button"
                        class="btn-primary"
                        onclick="openAddModal('petugas')">

                        <span class="btn-plus">+</span>
                        Tambah Petugas

                    </button>

                </div>

            </div>


            <div class="table-wrapper">

                <table id="petugasTable">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nomor Identitas</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Kelurahan</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($petugas as $index => $user)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $user->nomor_identitas }}
                                </td>

                                <td>
                                    {{ $user->name }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>
                                    {{ $user->kelurahan ?? '-' }}
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        @php
                                            $petugasData = [
                                                'id' => $user->id,
                                                'role' => 'petugas',
                                                'nomor_identitas' => $user->nomor_identitas,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                                'kelurahan' => $user->kelurahan,
                                            ];
                                        @endphp

                                        <button
                                            type="button"
                                            class="btn-edit"
                                            data-user='{{ json_encode($petugasData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}'
                                            onclick="editFromButton(this)">
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-delete"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-role="petugas"
                                            onclick="hapusData(this)">
                                            Hapus
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    style="text-align:center;color:#9ca3af;">

                                    Belum ada data petugas.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     MODAL
===================================================== --}}

<div
    class="modal-overlay"
    id="userModal">

    <div
        class="modal"
        role="dialog"
        aria-modal="true">

        <div class="modal-header">

            <div>

                <div
                    class="modal-kicker"
                    id="modalKicker">

                    PENGGUNA

                </div>

                <div
                    class="modal-title"
                    id="modalTitle">

                    Tambah Operator

                </div>

            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeModal()">

                ×

            </button>

        </div>


        <div class="modal-body">

            <div
                class="modal-description"
                id="modalDescription">

                Masukkan data pengguna baru.

            </div>


            <form id="userForm">

                <input
                    type="hidden"
                    id="userId">

                <input
                    type="hidden"
                    id="userRole">


                {{-- NOMOR IDENTITAS --}}

                <div class="form-group">

                    <label
                        for="nomorIdentitas"
                        class="form-label">

                        Nomor Identitas
                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        id="nomorIdentitas"
                        class="form-input"
                        placeholder="Masukkan nomor identitas"
                        maxlength="50"
                        required>

                </div>


                {{-- NAMA --}}

                <div class="form-group">

                    <label
                        for="namaLengkap"
                        class="form-label">

                        Nama Lengkap
                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        id="namaLengkap"
                        class="form-input"
                        placeholder="Masukkan nama lengkap"
                        required>

                </div>


                {{-- EMAIL --}}

                <div class="form-group">

                    <label
                        for="email"
                        class="form-label">

                        Email
                        <span class="required">*</span>

                    </label>

                    <input
                        type="email"
                        id="email"
                        class="form-input"
                        placeholder="Masukkan email"
                        required>

                </div>


                {{-- =================================================
                     PASSWORD
                ================================================== --}}

                <div
                    class="form-group"
                    id="passwordGroup">

                    <label
                        for="passwordDisplay"
                        class="form-label">

                        Password

                    </label>


                    <div
                        class="password-reset-box"
                        id="passwordAddBox">

                        <input
                            type="text"
                            id="passwordDisplay"
                            class="form-input password-default"
                            value="perlinsos123"
                            readonly>

                    </div>


                    <div
                        class="password-reset-box"
                        id="passwordEditBox"
                        style="display:none;">

                        <input
                            type="text"
                            class="form-input password-default"
                            value="Password tersimpan"
                            readonly>

                        <button
                            type="button"
                            class="btn-reset-password"
                            id="resetPasswordButton"
                            onclick="resetPassword()">

                            Reset Password

                        </button>

                    </div>


                    <div
                        class="form-help"
                        id="passwordHelp">

                        Password awal pengguna adalah
                        <strong>perlinsos123</strong>.
                        Pengguna dapat mengganti password melalui Profile.

                    </div>

                </div>


                {{-- =================================================
                     WILAYAH TUGAS
                ================================================== --}}

                <div
                    class="form-group"
                    id="wilayahGroup"
                    style="display:none;">

                    <label
                        for="wilayah"
                        class="form-label">

                        Wilayah Tugas
                        <span class="required">*</span>

                    </label>

                    <select
                        id="wilayah"
                        class="form-select">

                        <option value="">
                            Pilih Kelurahan
                        </option>

                        @foreach($kelurahans as $kelurahan)

                            <option
                                value="{{ $kelurahan->kelurahan_id }}">

                                {{ $kelurahan->deskripsi }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </form>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-modal btn-modal-cancel"
                onclick="closeModal()">

                Batal

            </button>

            <button
                type="button"
                class="btn-modal btn-modal-save"
                id="saveButton"
                onclick="saveUser()">

                Simpan

            </button>

        </div>

    </div>

</div>


<script>

    /* =====================================================
       URL
    ===================================================== */

    const csrfToken =
        @json(csrf_token());


    const storeUrl =
        @json(route('master.pengguna.store'));


    const updateUrlTemplate =
        @json(route(
            'master.pengguna.update',
            ['user' => '__USER_ID__']
        ));


    const deleteUrlTemplate =
        @json(route(
            'master.pengguna.destroy',
            ['user' => '__USER_ID__']
        ));


    const resetPasswordUrlTemplate =
        @json(route(
            'master.pengguna.reset-password',
            ['user' => '__USER_ID__']
        ));


    /* =====================================================
       ELEMENT
    ===================================================== */

    const userModal =
        document.getElementById('userModal');

    const userForm =
        document.getElementById('userForm');

    const userId =
        document.getElementById('userId');

    const userRole =
        document.getElementById('userRole');

    const modalTitle =
        document.getElementById('modalTitle');

    const modalDescription =
        document.getElementById('modalDescription');

    const modalKicker =
        document.getElementById('modalKicker');

    const saveButton =
        document.getElementById('saveButton');

    const nomorIdentitas =
        document.getElementById('nomorIdentitas');

    const namaLengkap =
        document.getElementById('namaLengkap');

    const email =
        document.getElementById('email');

    const wilayah =
        document.getElementById('wilayah');

    const wilayahGroup =
        document.getElementById('wilayahGroup');

    const passwordAddBox =
        document.getElementById('passwordAddBox');

    const passwordEditBox =
        document.getElementById('passwordEditBox');

    const passwordHelp =
        document.getElementById('passwordHelp');

    const resetPasswordButton =
        document.getElementById('resetPasswordButton');


    let currentMode = 'add';


    /* =====================================================
       SEARCH
    ===================================================== */

    function searchTable(inputId, tableId) {

        const input =
            document.getElementById(inputId);

        const table =
            document.getElementById(tableId);

        if (!input || !table) {
            return;
        }

        const filter =
            input.value.toLowerCase().trim();

        const rows =
            table.querySelectorAll('tbody tr');

        rows.forEach(function(row) {

            const text =
                row.textContent.toLowerCase();

            row.style.display =
                text.includes(filter)
                    ? ''
                    : 'none';

        });

    }


    /* =====================================================
       TAB
    ===================================================== */

    const tabButtons =
        document.querySelectorAll('.tab-button');

    const tabContents =
        document.querySelectorAll('.tab-content');


    function showTab(tabName) {

        tabButtons.forEach(function(button) {

            button.classList.remove('active');

        });


        tabContents.forEach(function(content) {

            content.classList.remove('active');

        });


        const selectedButton =
            document.querySelector(
                '.tab-button[data-tab="' +
                tabName +
                '"]'
            );


        const selectedContent =
            document.getElementById(tabName);


        if (
            selectedButton &&
            selectedContent
        ) {

            selectedButton.classList.add('active');

            selectedContent.classList.add('active');

        }

    }


    tabButtons.forEach(function(button) {

        button.addEventListener(
            'click',
            function() {

                showTab(
                    this.getAttribute('data-tab')
                );

            }
        );

    });


    /* =====================================================
       ADD MODAL
    ===================================================== */

    function openAddModal(role) {

        currentMode = 'add';

        userForm.reset();

        userId.value = '';

        userRole.value = role;


        modalKicker.textContent =
            'TAMBAH PENGGUNA';


        if (role === 'operator') {

            modalTitle.textContent =
                'Tambah Operator';

            modalDescription.textContent =
                'Masukkan data operator baru.';

            saveButton.textContent =
                'Simpan Operator';

        }


        if (role === 'verifikator') {

            modalTitle.textContent =
                'Tambah Verifikator';

            modalDescription.textContent =
                'Masukkan data verifikator baru.';

            saveButton.textContent =
                'Simpan Verifikator';

        }


        if (role === 'petugas') {

            modalTitle.textContent =
                'Tambah Petugas';

            modalDescription.textContent =
                'Masukkan data petugas baru beserta wilayah tugasnya.';

            saveButton.textContent =
                'Simpan Petugas';

        }


        const isPetugas =
            role === 'petugas';


        wilayahGroup.style.display =
            isPetugas
                ? 'block'
                : 'none';


        wilayah.required =
            isPetugas;


        passwordAddBox.style.display =
            'flex';


        passwordEditBox.style.display =
            'none';


        passwordHelp.innerHTML =
            'Password awal pengguna adalah ' +
            '<strong>perlinsos123</strong>. ' +
            'Pengguna dapat mengganti password melalui Profile.';


        userModal.classList.add('show');


        setTimeout(function() {

            nomorIdentitas.focus();

        }, 100);

    }


    /* =====================================================
       EDIT
    ===================================================== */

    function editFromButton(button) {

        let data = {};


        try {

            data =
                JSON.parse(
                    button.getAttribute('data-user')
                );

        } catch(error) {

            console.error(error);

            alert(
                'Data pengguna tidak dapat dibaca.'
            );

            return;

        }


        openEditModal(data);

    }


    function openEditModal(data) {

        currentMode = 'edit';


        userId.value =
            data.id || '';


        userRole.value =
            data.role || '';


        nomorIdentitas.value =
            data.nomor_identitas || '';


        namaLengkap.value =
            data.name || '';


        email.value =
            data.email || '';


        modalKicker.textContent =
            'EDIT PENGGUNA';


        if (data.role === 'operator') {

            modalTitle.textContent =
                'Edit Operator';

            modalDescription.textContent =
                'Perbarui data operator.';

        }


        if (data.role === 'verifikator') {

            modalTitle.textContent =
                'Edit Verifikator';

            modalDescription.textContent =
                'Perbarui data verifikator.';

        }


        if (data.role === 'petugas') {

            modalTitle.textContent =
                'Edit Petugas';

            modalDescription.textContent =
                'Perbarui data petugas dan wilayah tugasnya.';

        }


        saveButton.textContent =
            'Simpan Perubahan';


        const isPetugas =
            data.role === 'petugas';


        wilayahGroup.style.display =
            isPetugas
                ? 'block'
                : 'none';


        wilayah.required =
            isPetugas;


        /*
         * Password pada mode Edit
         */
        passwordAddBox.style.display =
            'none';


        passwordEditBox.style.display =
            'flex';


        passwordHelp.innerHTML =
            'Jika pengguna lupa password, klik ' +
            '<strong>Reset Password</strong> ' +
            'untuk mengembalikannya ke ' +
            '<strong>perlinsos123</strong>.';


        /*
         * Set Kelurahan Petugas
         */
        if (isPetugas) {

            wilayah.value = '';


            const options =
                wilayah.options;


            for (
                let i = 0;
                i < options.length;
                i++
            ) {

                if (
                    options[i].textContent.trim() ===
                    String(data.kelurahan || '').trim()
                ) {

                    wilayah.value =
                        options[i].value;

                    break;

                }

            }

        } else {

            wilayah.value = '';

        }


        userModal.classList.add('show');


        setTimeout(function() {

            nomorIdentitas.focus();

        }, 100);

    }


    /* =====================================================
       RESET PASSWORD
    ===================================================== */

    async function resetPassword() {

        const id =
            userId.value;


        if (!id) {

            alert(
                'ID pengguna tidak ditemukan.'
            );

            return;

        }


        const yakin =
            confirm(
                'Yakin ingin mereset password pengguna ini?\n\n' +
                'Password akan dikembalikan menjadi:\n' +
                'perlinsos123'
            );


        if (!yakin) {
            return;
        }


        const url =
            resetPasswordUrlTemplate.replace(
                '__USER_ID__',
                id
            );


        resetPasswordButton.disabled =
            true;


        resetPasswordButton.textContent =
            'Mereset...';


        try {

            const response =
                await fetch(url, {

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }

                });


            const result =
                await response.json();


            if (!response.ok) {

                alert(
                    result.message ||
                    'Password gagal direset.'
                );

                return;

            }


            alert(
                result.message ||
                'Password berhasil direset.'
            );


        } catch(error) {

            console.error(error);

            alert(
                'Terjadi kesalahan koneksi ke server.'
            );

        } finally {

            resetPasswordButton.disabled =
                false;

            resetPasswordButton.textContent =
                'Reset Password';

        }

    }


    /* =====================================================
       CLOSE
    ===================================================== */

    function closeModal() {

        userModal.classList.remove('show');

        userForm.reset();

        userId.value = '';

        userRole.value = '';

        wilayahGroup.style.display =
            'none';

        passwordAddBox.style.display =
            'flex';

        passwordEditBox.style.display =
            'none';

    }


    /* =====================================================
       SAVE
    ===================================================== */

    async function saveUser() {

        if (!userForm.checkValidity()) {

            userForm.reportValidity();

            return;

        }


        const role =
            userRole.value;


        if (!role) {

            alert(
                'Role pengguna tidak ditemukan.'
            );

            return;

        }


        const formData =
            new FormData();


        formData.append(
            'role',
            role
        );


        formData.append(
            'nomor_identitas',
            nomorIdentitas.value.trim()
        );


        formData.append(
            'name',
            namaLengkap.value.trim()
        );


        formData.append(
            'email',
            email.value.trim()
        );


        /*
         * Password TIDAK dikirim dari form.
         *
         * Saat tambah:
         * Controller otomatis menggunakan
         * perlinsos123.
         *
         * Saat edit:
         * Password hanya bisa direset
         * menggunakan tombol Reset Password.
         */


        /*
         * Kelurahan hanya untuk Petugas.
         */

        if (role === 'petugas') {

            formData.append(
                'kelurahan_id',
                wilayah.value
            );

        }


        let url =
            storeUrl;


        if (currentMode === 'edit') {

            url =
                updateUrlTemplate.replace(
                    '__USER_ID__',
                    userId.value
                );


            formData.append(
                '_method',
                'PUT'
            );

        }


        saveButton.disabled =
            true;


        saveButton.textContent =
            'Menyimpan...';


        try {

            const response =
                await fetch(url, {

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },

                    body: formData

                });


            const result =
                await response.json();


            if (!response.ok) {

                if (
                    response.status === 422 &&
                    result.errors
                ) {

                    const messages =
                        Object.values(
                            result.errors
                        ).flat();


                    alert(
                        messages.join('\n')
                    );

                } else {

                    alert(
                        result.message ||
                        'Terjadi kesalahan saat menyimpan data.'
                    );

                }

                return;

            }


            sessionStorage.setItem(
                'pengguna_active_tab',
                role
            );


            alert(
                result.message ||
                'Data berhasil disimpan.'
            );


            window.location.href =
                @json(route('master.pengguna.index'));


        } catch(error) {

            console.error(error);

            alert(
                'Terjadi kesalahan koneksi ke server.'
            );

        } finally {

            saveButton.disabled =
                false;


            saveButton.textContent =
                currentMode === 'edit'
                    ? 'Simpan Perubahan'
                    : 'Simpan';

        }

    }


    /* =====================================================
       DELETE
    ===================================================== */

    async function hapusData(button) {

        const id =
            button.dataset.id;


        const name =
            button.dataset.name ||
            'data ini';


        const role =
            button.dataset.role ||
            'pengguna';


        if (!id) {

            alert(
                'ID pengguna tidak ditemukan.'
            );

            return;

        }


        const yakin =
            confirm(
                'Apakah kamu yakin ingin menghapus ' +
                name +
                ' sebagai ' +
                role +
                '?'
            );


        if (!yakin) {
            return;
        }


        const url =
            deleteUrlTemplate.replace(
                '__USER_ID__',
                id
            );


        button.disabled =
            true;


        button.textContent =
            'Menghapus...';


        try {

            const response =
                await fetch(url, {

                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },

                    body:
                        '_method=DELETE'

                });


            const result =
                await response.json();


            if (!response.ok) {

                alert(
                    result.message ||
                    'Data gagal dihapus.'
                );


                button.disabled =
                    false;


                button.textContent =
                    'Hapus';


                return;

            }


            sessionStorage.setItem(
                'pengguna_active_tab',
                role
            );


            alert(
                result.message ||
                'Data berhasil dihapus.'
            );


            window.location.href =
                @json(route('master.pengguna.index'));


        } catch(error) {

            console.error(error);


            alert(
                'Terjadi kesalahan koneksi ke server.'
            );


            button.disabled =
                false;


            button.textContent =
                'Hapus';

        }

    }


    /* =====================================================
       CLICK OUTSIDE
    ===================================================== */

    userModal.addEventListener(
        'click',
        function(event) {

            if (event.target === userModal) {

                closeModal();

            }

        }
    );


    /* =====================================================
       ESC
    ===================================================== */

    document.addEventListener(
        'keydown',
        function(event) {

            if (
                event.key === 'Escape' &&
                userModal.classList.contains('show')
            ) {

                closeModal();

            }

        }
    );


    /* =====================================================
       ACTIVE TAB AFTER RELOAD
    ===================================================== */

    const savedTab =
        sessionStorage.getItem(
            'pengguna_active_tab'
        );


    const urlParams =
        new URLSearchParams(
            window.location.search
        );


    const urlTab =
        urlParams.get('tab');


    showTab(
        savedTab ||
        urlTab ||
        'operator'
    );


    sessionStorage.removeItem(
        'pengguna_active_tab'
    );

</script>

@endsection