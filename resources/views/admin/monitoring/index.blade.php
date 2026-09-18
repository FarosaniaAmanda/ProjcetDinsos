<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring - DINSOS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#252A86',
                        secondary: '#55B5D5',
                        success: '#299447',
                        warning: '#F5C928',
                        danger: '#D9364F'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-[#222222]">

    <!-- Navbar -->
    <nav class="bg-[#252A86] text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <div>
                <h1 class="text-xl font-bold">DINSOS</h1>
                <p class="text-xs text-blue-100">
                    Sistem Informasi Pendataan Sosial
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                    <span class="font-semibold">A</span>
                </div>

                <div class="hidden sm:block">
                    <p class="text-sm font-semibold">Administrator</p>
                    <p class="text-xs text-blue-100">Admin</p>
                </div>
            </div>

        </div>
    </nav>


    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">

        <!-- Breadcrumb -->
        <div class="mb-6">
            <p class="text-sm text-gray-500">
                Dashboard
                <span class="mx-2">/</span>
                <span class="text-[#252A86] font-medium">Monitoring</span>
            </p>
        </div>


        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-7">

            <div>
                <h2 class="text-2xl font-bold text-[#252A86]">
                    Monitoring Pendataan
                </h2>

                <p class="text-gray-500 mt-1">
                    Pantau data responden dan riwayat pendataan yang telah dilakukan.
                </p>
            </div>

        </div>


        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-7">

            <!-- Total Responden -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Total Responden
                        </p>

                        <h3 class="text-3xl font-bold text-[#252A86] mt-2">
                            125
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#252A86]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 3a3 3 0 10-6 0 3 3 0 006 0z"/>
                        </svg>
                    </div>

                </div>
            </div>


            <!-- Sudah Didata -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Sudah Didata
                        </p>

                        <h3 class="text-3xl font-bold text-[#299447] mt-2">
                            98
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#299447]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                </div>
            </div>


            <!-- Belum Didata -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Belum Didata
                        </p>

                        <h3 class="text-3xl font-bold text-[#D9364F] mt-2">
                            27
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#D9364F]"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                </div>
            </div>

        </div>


        <!-- Filter -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">

            <div class="flex items-center gap-2 mb-5">

                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#252A86]"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 11.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-9.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </div>

                <h3 class="font-semibold text-gray-800">
                    Filter Data
                </h3>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Periode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Periode
                    </label>

                    <select class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#55B5D5]">
                        <option>September 2026</option>
                        <option>Agustus 2026</option>
                        <option>Juli 2026</option>
                    </select>
                </div>


                <!-- Wilayah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Wilayah
                    </label>

                    <select class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#55B5D5]">
                        <option>Semua Wilayah</option>
                        <option>Kecamatan Panggungrejo</option>
                        <option>Kecamatan Purworejo</option>
                        <option>Kecamatan Bugul Kidul</option>
                    </select>
                </div>


                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Pendataan
                    </label>

                    <select class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#55B5D5]">
                        <option>Semua Status</option>
                        <option>Sudah Didata</option>
                        <option>Belum Didata</option>
                    </select>
                </div>

            </div>


            <div class="flex justify-end mt-5">

                <button class="bg-[#252A86] hover:bg-[#1d216d] text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
                    Terapkan Filter
                </button>

            </div>

        </div>


        <!-- Data Table -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Data Responden
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Daftar responden yang telah tercatat dalam sistem.
                    </p>
                </div>

                <span class="px-3 py-1.5 rounded-full bg-blue-50 text-[#252A86] text-xs font-medium">
                    125 Data
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                No
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Nama Responden
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                NIK
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Wilayah
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Periode
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        <!-- Data 1 -->
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4">
                                1
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">
                                    Budi Santoso
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                3575010101010002
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                Panggungrejo
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                September 2026
                            </td>

                            <td class="px-6 py-4">

                                <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-[#299447] text-xs font-semibold">
                                    Sudah Didata
                                </span>

                            </td>

                            <td class="px-6 py-4 text-center">

                                <a href="{{ route('admin.monitoring.detail', 1) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-lg bg-[#55B5D5] hover:bg-[#45a5c5] text-white text-xs font-medium transition">

                                    Lihat Detail

                                </a>

                            </td>

                        </tr>


                        <!-- Data 2 -->
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4">
                                2
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">
                                    Siti Aminah
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                3575010101010004
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                Bugul Kidul
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                September 2026
                            </td>

                            <td class="px-6 py-4">

                                <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-[#299447] text-xs font-semibold">
                                    Sudah Didata
                                </span>

                            </td>

                            <td class="px-6 py-4 text-center">

                                <a href="{{ route('admin.monitoring.detail', 2) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-lg bg-[#55B5D5] hover:bg-[#45a5c5] text-white text-xs font-medium transition">

                                    Lihat Detail

                                </a>

                            </td>

                        </tr>


                        <!-- Data 3 -->
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4">
                                3
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">
                                    Ahmad Fauzi
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                3575010101010006
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                Purworejo
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                September 2026
                            </td>

                            <td class="px-6 py-4">

                                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-50 text-[#F4A629] text-xs font-semibold">
                                    Belum Didata
                                </span>

                            </td>

                            <td class="px-6 py-4 text-center">

                                <a href="{{ route('admin.monitoring.detail', 3) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-lg bg-[#55B5D5] hover:bg-[#45a5c5] text-white text-xs font-medium transition">

                                    Lihat Detail

                                </a>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- Pagination sementara -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">

                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-medium text-gray-700">1–3</span> dari
                    <span class="font-medium text-gray-700">125</span> data
                </p>

                <div class="flex gap-2">

                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400">
                        Sebelumnya
                    </button>

                    <button class="px-3 py-2 bg-[#252A86] text-white rounded-lg text-sm">
                        1
                    </button>

                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        2
                    </button>

                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        Selanjutnya
                    </button>

                </div>

            </div>

        </div>

    </main>

</body>
</html>