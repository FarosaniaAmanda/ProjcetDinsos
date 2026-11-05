@extends('admin.layouts.app')

@section('title', 'Manajemen Periode')

@push('styles')
<style>
.page-header {
            margin-bottom: 25px;
        }

        .page-kicker {
            font-size: 11px;
            font-weight: 700;
            color: #252A86;
            letter-spacing: 1.2px;
            margin-bottom: 7px;
        }

        .page-title {
            font-size: 27px;
            font-weight: 700;
            color: #252A86;
            margin-bottom: 8px;
        }

        .page-description {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }


        /* =====================================================
           TOOLBAR
        ====================================================== */

        .toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .btn-tambah {
            min-width: 143px;
            height: 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            background: #292d8f;
            color: #fff;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-tambah:hover {
            background: #202475;
            transform: translateY(-1px);
        }


        /* =====================================================
           CARD
        ====================================================== */

        .card {
            width: 100%;
            padding: 20px 22px;
            background: #fff;
            border: 1px solid #e2e3e9;
            border-radius: 12px;
            box-shadow: 0 2px 7px rgba(0,0,0,.03);
        }


        /* =====================================================
           SEARCH
        ====================================================== */

        .search {
            width: 100%;
            height: 44px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 14px;
            margin-bottom: 18px;
            background: #fff;
            border: 1px solid #cfd2dc;
            border-radius: 8px;
            transition: .2s ease;
        }

        .search:focus-within {
            border-color: #292d8f;
            box-shadow: 0 0 0 2px rgba(41,45,143,.10);
        }

        .search-icon {
            color: #777;
            font-size: 18px;
        }

        .search input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13px;
        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead th {
            padding: 13px 14px;
            background: #f7f7fa;
            color: #555;
            border-bottom: 1px solid #e3e4e9;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
        }

        tbody td {
            padding: 14px;
            color: #444;
            border-bottom: 1px solid #eeeef2;
            font-size: 12px;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafaff;
        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .akan-datang {
            background: #FFF4DD;
            color: #A66B00;
        }

        .aktif {
            background: #E8F8EE;
            color: #21874B;
        }

        .selesai {
            background: #FDEAEA;
            color: #C0392B;
        }


        /* =====================================================
           AKSI
        ====================================================== */

        .action-group {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-edit,
        .btn-hapus {
            height: 32px;
            padding: 0 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-edit {
            height: 31px;
            padding: 0 10px;
            border: 1px solid #dfe1e8;
            background: #fff;
            color: #252A86;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-edit:hover {
            background: #252A86;
            border-color: #252A86;
            color: #fff;
        }

        .btn-hapus {
            height: 31px;
            padding: 0 10px;
            border: 1px solid #edd5d5;
            background: #fff7f7;
            color: #b34a4a;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-hapus:hover {
            background: #b34a4a;
            border-color: #b34a4a;
            color: #fff;
        }


        /* =====================================================
           PAGINATION
           10 DATA PER HALAMAN
        ====================================================== */

        .pagination-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid #eeeef2;
        }

        .pagination-info {
            display: block;
            color: #777;
            font-size: 11px;
            white-space: nowrap;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }

        .pagination button {
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            border: 1px solid #dfe1e8;
            background: #ffffff;
            color: #555;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: .2s ease;
        }

        .pagination button:hover:not(:disabled):not(.page-number.active) {
            background: #f1f2fa;
            border-color: #292d8f;
            color: #292d8f;
        }

        .pagination button.arrow-button {
            font-size: 16px;
            font-weight: 400;
        }

        .pagination button.page-number {
            font-weight: 500;
        }

        .pagination button.page-number.active {
            background: #292d8f;
            border-color: #292d8f;
            color: #ffffff;
            font-weight: 700;
            cursor: default;
        }

        .pagination button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }


        /* =====================================================
           MODAL
        ====================================================== */

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(20, 23, 70, 0.42);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
            backdrop-filter: blur(3px);
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            width: 470px;
            max-width: 100%;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.20);
            animation: modalShow .2s ease;
        }

        @keyframes modalShow {

            from {
                opacity: 0;
                transform: translateY(-15px) scale(.97);
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
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #292d8f;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f3f3f7;
            color: #555;
            border-radius: 7px;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-close:hover {
            background: #e8e8ef;
            color: #222;
        }

        .modal-body {
            padding: 22px;
        }

        .form-group-modal {
            margin-bottom: 17px;
        }

        .form-group-modal label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-size: 12px;
            font-weight: 700;
        }

        .form-group-modal input,
        .form-group-modal select {
            width: 100%;
            height: 43px;
            padding: 0 12px;
            border: 1px solid #d5d7df;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: #333;
            font-size: 13px;
        }

        .form-group-modal input:focus,
        .form-group-modal select:focus {
            border-color: #292d8f;
            box-shadow: 0 0 0 3px rgba(41,45,143,.08);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding: 17px 22px;
            border-top: 1px solid #eee;
            background: #fafafa;
            border-radius: 0 0 14px 14px;
        }

        .btn-modal {
            height: 39px;
            padding: 0 18px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-batal {
            background: #fff;
            color: #555;
            border: 1px solid #d5d7df;
        }

        .btn-simpan {
            background: #292d8f;
            color: #fff;
            border: 1px solid #292d8f;
        }


        /* =====================================================
           PESAN
        ====================================================== */

        .alert {
            padding: 13px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 13px;
        }

        .alert-success {
            background: #e7f7ed;
            color: #21864a;
            border: 1px solid #ccebd8;
        }

        .error-box {
            margin-bottom: 15px;
            padding: 10px 12px;
            background: #fdecec;
            color: #c74343;
            border-radius: 7px;
            font-size: 12px;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 900px) {
            .content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {

            .page-title {
                font-size: 24px;
            }

            .toolbar {
                justify-content: stretch;
            }

            .btn-tambah {
                width: 100%;
            }

            .card {
                padding: 16px;
            }

            table {
                min-width: 720px;
            }

            table th:last-child,
            table td:last-child {
                width: 130px !important;
                min-width: 130px;
            }


            .pagination-wrapper {
                justify-content: space-between;
            }

        }


        @media (max-width: 600px) {

            .content {
                padding: 16px;
            }

            .page-title {
                font-size: 22px;
            }

            .modal-box {
                width: 100%;
            }

            .pagination-info {
                font-size: 10px;
            }

        }

    </style>
@endpush

@section('content')
            @if ($errors->any())

                <div class="error-box">

                    @foreach ($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            @endif


            <div class="page-header">

                <div class="page-kicker">
                    MASTER
                </div>

                <h1 class="page-title">
                    Manajemen Periode
                </h1>

                <p class="page-description">
                    Kelola periode pendataan dan jadwal kegiatan secara terstruktur.
                </p>

            </div>


            <!-- TOMBOL TAMBAH -->

            <div class="toolbar">

                <button
                    type="button"
                    class="btn-tambah"
                    onclick="openAddModal()"
                >
                    + Tambah Periode
                </button>

            </div>


            <!-- CARD -->

            <div class="card">


                <!-- SEARCH -->

                <div class="search">

                    <span class="search-icon">
                        ⌕
                    </span>

                    <input
                        type="text"
                        id="searchPeriode"
                        placeholder="Search..."
                        autocomplete="off"
                    >

                </div>


                <!-- TABLE -->

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th style="width: 7%;">
                                    No.
                                </th>

                                <th style="width: 25%;">
                                    Nama Kegiatan
                                </th>

                                <th style="width: 20%;">
                                    Tanggal Mulai
                                </th>

                                <th style="width: 20%;">
                                    Tanggal Selesai
                                </th>

                                <th style="width: 13%;">
                                    Status
                                </th>

                                <th style="width: 15%;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody id="periodeTable">

                            @forelse ($periodes as $index => $periode)

                                <tr class="periode-row">

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td class="nama-kegiatan">

                                        {{ $periode->nama }}

                                    </td>


                                    <td class="tanggal-mulai">

                                        {{ $periode->tgl_awal->format('d-m-Y') }}

                                    </td>


                                    <td class="tanggal-selesai">

                                        {{ $periode->tgl_akhir->format('d-m-Y') }}

                                    </td>


                                    <td>

                                        @if (strtolower(trim($periode->status_periode)) == 'aktif')

                                            <span class="status aktif">
                                                Aktif
                                            </span>

                                        @elseif (strtolower(trim($periode->status_periode)) == 'selesai')

                                            <span class="status selesai">
                                                Selesai
                                            </span>

                                        @elseif (strtolower(trim($periode->status_periode)) == 'akan datang')

                                            <span class="status akan-datang">
                                                {{ $periode->status_periode }}
                                            </span>

                                        @else

                                            <span class="status selesai">
                                                {{ $periode->status_periode }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div class="action-group">


                                            <!-- EDIT -->

                                            <button
                                                type="button"
                                                class="btn-edit edit-button"

                                                data-id="{{ $periode->id }}"
                                                data-nama="{{ $periode->nama }}"
                                                data-mulai="{{ $periode->tgl_awal?->format('Y-m-d') }}"
                                                data-selesai="{{ $periode->tgl_akhir?->format('Y-m-d') }}"
                                                data-status="{{ $periode->status_periode }}"
                                            >
                                                Edit
                                            </button>


                                            <!-- HAPUS -->

                                            <form
                                                action="{{ route('periode.destroy', $periode->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?')"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-hapus"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr id="emptyPeriodeRow">

                                    <td
                                        colspan="6"
                                        style="text-align:center; padding:30px; color:#888;"
                                    >
                                        Belum ada data periode.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     PAGINATION
                ================================================== -->

                @if ($periodes->count() > 0)

                    <div class="pagination-wrapper">

                        <div
                            class="pagination-info"
                            id="paginationInfo"
                        ></div>

                        <div
                            class="pagination"
                            id="paginationButtons"
                        ></div>

                    </div>

                @endif


            </div>

        <!-- =====================================================
     MODAL TAMBAH / EDIT
===================================================== -->

<div
    class="modal-overlay"
    id="periodeModal"
    onclick="closeModalOutside(event)"
>


    <div
        class="modal-box"
        onclick="event.stopPropagation()"
    >


        <div class="modal-header">

            <h2
                class="modal-title"
                id="modalTitle"
            >
                Tambah Periode
            </h2>


            <button
                type="button"
                class="modal-close"
                onclick="closeModal()"
            >
                ×
            </button>

        </div>


        <!-- FORM -->

        <form
            id="periodeForm"
            action="{{ route('periode.store') }}"
            method="POST"
        >

            @csrf


            <!-- METHOD EDIT -->

            <input
                type="hidden"
                name="_method"
                id="methodField"
                value="POST"
            >


            <div class="modal-body">


                <!-- NAMA -->

                <div class="form-group-modal">

                    <label for="nama_periode">
                        Nama Kegiatan
                    </label>

                    <input
                        type="text"
                        id="nama_periode"
                        name="nama_periode"
                        placeholder="Contoh: Pendataan Awal 2026"
                        required
                    >

                </div>


                <!-- TANGGAL MULAI -->

                <div class="form-group-modal">

                    <label for="tanggal_mulai">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        required
                    >

                </div>


                <!-- TANGGAL SELESAI -->

                <div class="form-group-modal">

                    <label for="tanggal_selesai">
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        required
                    >

                </div>


                <!-- STATUS -->

                <div class="form-group-modal">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >

                        <option value="Akan Datang">
                            Akan Datang
                        </option>

                        <option value="Aktif">
                            Aktif
                        </option>

                        <option value="Selesai">
                            Selesai
                        </option>

                    </select>

                </div>

            </div>


            <!-- FOOTER -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-modal btn-batal"
                    onclick="closeModal()"
                >
                    Batal
                </button>


                <button
                    type="submit"
                    class="btn-modal btn-simpan"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>



    /* =====================================================
       TAMBAH
    ===================================================== */

    function openAddModal()
    {

        document.getElementById(
            "modalTitle"
        ).textContent = "Tambah Periode";


        document.getElementById(
            "periodeForm"
        ).action =
            "{{ route('periode.store') }}";


        document.getElementById(
            "methodField"
        ).value = "POST";


        document.getElementById(
            "nama_periode"
        ).value = "";


        document.getElementById(
            "tanggal_mulai"
        ).value = "";


        document.getElementById(
            "tanggal_selesai"
        ).value = "";


        document.getElementById(
            "status"
        ).value = "Akan Datang";


        document.getElementById(
            "periodeModal"
        ).classList.add("show");


        setTimeout(function () {

            document.getElementById(
                "nama_periode"
            ).focus();

        }, 100);

    }



    /* =====================================================
       EDIT
    ===================================================== */

    document
        .querySelectorAll(".edit-button")
        .forEach(function(button)
        {

            button.addEventListener(
                "click",
                function()
                {

                    const id =
                        this.dataset.id;

                    const nama =
                        this.dataset.nama;

                    const mulai =
                        this.dataset.mulai;

                    const selesai =
                        this.dataset.selesai;

                    const status =
                        this.dataset.status;


                    document.getElementById(
                        "modalTitle"
                    ).textContent =
                        "Edit Periode";


                    document.getElementById(
                        "nama_periode"
                    ).value =
                        nama;


                    document.getElementById(
                        "tanggal_mulai"
                    ).value =
                        mulai;


                    document.getElementById(
                        "tanggal_selesai"
                    ).value =
                        selesai;


                    document.getElementById(
                        "status"
                    ).value =
                        status;


                    document.getElementById(
                        "periodeForm"
                    ).action =
                        "{{ url('/periode') }}/" + id;


                    document.getElementById(
                        "methodField"
                    ).value =
                        "PUT";


                    document.getElementById(
                        "periodeModal"
                    ).classList.add("show");

                }
            );

        });



    /* =====================================================
       CLOSE MODAL
    ===================================================== */

    function closeModal()
    {

        document.getElementById(
            "periodeModal"
        ).classList.remove("show");

    }


    function closeModalOutside(event)
    {

        if (
            event.target.id ===
            "periodeModal"
        )
        {

            closeModal();

        }

    }



    /* =====================================================
       PAGINATION PERIODE

       10 DATA PER HALAMAN

       Contoh:

       14 DATA

       HALAMAN 1
       Menampilkan 1–10 dari 14 periode
       ←  1  2  →

       HALAMAN 2
       Menampilkan 11–14 dari 14 periode
       ←  1  2  →
    ===================================================== */

    const rowsPerPage = 10;

    let currentPage = 1;



    /* =====================================================
       AMBIL SEMUA BARIS DATA
    ===================================================== */

    function getPeriodeRows()
    {

        return Array.from(
            document.querySelectorAll(
                "#periodeTable .periode-row"
            )
        );

    }



    /* =====================================================
       RENDER PAGINATION
    ===================================================== */

    function renderPagination()
    {

        const allRows =
            getPeriodeRows();


        const searchInput =
            document.getElementById(
                "searchPeriode"
            );


        const keyword =
            searchInput
                ? searchInput.value
                    .toLowerCase()
                    .trim()
                : "";


        /* =================================================
           FILTER SEARCH
        ================================================= */

        const filteredRows =
            allRows.filter(function(row)
            {

                const text =
                    row.textContent
                        .toLowerCase();

                return text.includes(keyword);

            });



        /* =================================================
           HITUNG TOTAL HALAMAN
        ================================================= */

        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    filteredRows.length /
                    rowsPerPage
                )
            );



        /* =================================================
           PASTIKAN CURRENT PAGE VALID
        ================================================= */

        if (currentPage > totalPages)
        {

            currentPage = totalPages;

        }


        if (currentPage < 1)
        {

            currentPage = 1;

        }



        /* =================================================
           SEMBUNYIKAN SEMUA BARIS
        ================================================= */

        allRows.forEach(function(row)
        {

            row.style.display = "none";

        });



        /* =================================================
           TENTUKAN DATA HALAMAN SEKARANG
        ================================================= */

        const start =
            (currentPage - 1) *
            rowsPerPage;


        const end =
            start +
            rowsPerPage;


        const pageRows =
            filteredRows.slice(
                start,
                end
            );



        /* =================================================
           TAMPILKAN DATA HALAMAN SEKARANG
        ================================================= */

        pageRows.forEach(function(row)
        {

            row.style.display = "";

        });



        /* =================================================
           PAGINATION CONTAINER
        ================================================= */

        const paginationButtons =
            document.getElementById(
                "paginationButtons"
            );


        const paginationInfo =
            document.getElementById(
                "paginationInfo"
            );


        if (!paginationButtons)
        {

            return;

        }



        /* =================================================
           HAPUS PAGINATION LAMA
        ================================================= */

        paginationButtons.innerHTML = "";



        /* =================================================
           INFORMASI DATA
        ================================================= */

        if (paginationInfo)
        {

            if (filteredRows.length === 0)
            {

                paginationInfo.textContent =
                    "Tidak ada data";

            }
            else
            {

                const displayStart =
                    start + 1;


                const displayEnd =
                    Math.min(
                        end,
                        filteredRows.length
                    );


                paginationInfo.textContent =
                    "Menampilkan " +
                    displayStart +
                    "–" +
                    displayEnd +
                    " dari " +
                    filteredRows.length +
                    " periode";

            }

        }



        /* =================================================
           KALAU TIDAK ADA DATA HASIL SEARCH
        ================================================= */

        if (filteredRows.length === 0)
        {

            return;

        }



        /* =================================================
           TOMBOL SEBELUMNYA
        ================================================= */

        const previousButton =
            document.createElement(
                "button"
            );


        previousButton.type =
            "button";


        previousButton.className =
            "arrow-button";


        previousButton.innerHTML =
            "‹";


        previousButton.title =
            "Halaman sebelumnya";


        previousButton.disabled =
            currentPage === 1;


        previousButton.addEventListener(
            "click",
            function()
            {

                if (currentPage > 1)
                {

                    currentPage--;

                    renderPagination();

                }

            }
        );


        paginationButtons.appendChild(
            previousButton
        );



        /* =================================================
           NOMOR HALAMAN
        ================================================= */

        for (
            let page = 1;
            page <= totalPages;
            page++
        )
        {

            const pageButton =
                document.createElement(
                    "button"
                );


            pageButton.type =
                "button";


            pageButton.className =
                "page-number";


            pageButton.textContent =
                page;


            if (page === currentPage)
            {

                pageButton.classList.add(
                    "active"
                );

            }


            pageButton.addEventListener(
                "click",
                function()
                {

                    currentPage = page;

                    renderPagination();

                }
            );


            paginationButtons.appendChild(
                pageButton
            );

        }



        /* =================================================
           TOMBOL BERIKUTNYA
        ================================================= */

        const nextButton =
            document.createElement(
                "button"
            );


        nextButton.type =
            "button";


        nextButton.className =
            "arrow-button";


        nextButton.innerHTML =
            "›";


        nextButton.title =
            "Halaman selanjutnya";


        nextButton.disabled =
            currentPage === totalPages;


        nextButton.addEventListener(
            "click",
            function()
            {

                if (
                    currentPage <
                    totalPages
                )
                {

                    currentPage++;

                    renderPagination();

                }

            }
        );


        paginationButtons.appendChild(
            nextButton
        );

    }



    /* =====================================================
       SEARCH

       Search tetap bekerja bersama pagination.
    ===================================================== */

    const searchInput =
        document.getElementById(
            "searchPeriode"
        );


    if (searchInput)
    {

        searchInput.addEventListener(
            "input",
            function()
            {

                currentPage = 1;

                renderPagination();

            }
        );

    }



    /* =====================================================
       JALANKAN PAGINATION
    ===================================================== */

    renderPagination();



    /* =====================================================
       ESC CLOSE MODAL
    ===================================================== */

    document.addEventListener(
        "keydown",
        function(event)
        {

            if (
                event.key ===
                "Escape"
            )
            {

                closeModal();

            }

        }
    );



</script>
@endpush
