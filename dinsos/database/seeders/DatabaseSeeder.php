<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Periode;
use App\Models\Wilayah;
use App\Models\Petugas;
use App\Models\PetugasPeriode;
use App\Models\Keluarga;
use App\Models\AnggotaKeluarga;
use App\Models\Kuesioner;
use App\Models\OpsiJawaban;
use App\Models\Pendataan;
use App\Models\Jawaban;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USER ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@dinsos.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

        $periode = Periode::create([
            'nama_periode' => 'Pendataan September 2026',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'status' => 'aktif',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WILAYAH
        |--------------------------------------------------------------------------
        */

        $wilayah1 = Wilayah::create([
            'nama_wilayah' => 'Wilayah Sukamaju',
            'rt' => '02',
            'rw' => '03',
            'desa_kelurahan' => 'Sukamaju',
            'kecamatan' => 'Bangil',
            'kabupaten_kota' => 'Pasuruan',
            'provinsi' => 'Jawa Timur',
        ]);

        $wilayah2 = Wilayah::create([
            'nama_wilayah' => 'Wilayah Kalisari',
            'rt' => '01',
            'rw' => '02',
            'desa_kelurahan' => 'Kalisari',
            'kecamatan' => 'Bangil',
            'kabupaten_kota' => 'Pasuruan',
            'provinsi' => 'Jawa Timur',
        ]);


        /*
        |--------------------------------------------------------------------------
        | USER PETUGAS
        |--------------------------------------------------------------------------
        */

        $userPetugas1 = User::create([
            'name' => 'Siti Aminah',
            'username' => 'siti',
            'email' => 'siti@dinsos.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $userPetugas2 = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@dinsos.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        */

        $petugas1 = Petugas::create([
            'user_id' => $userPetugas1->id,
            'nik' => '3575010101900001',
            'nomor_hp' => '081234567890',
            'email' => 'siti@dinsos.test',
            'alamat' => 'Bangil, Pasuruan',
            'wilayah_id' => $wilayah1->id,
            'status' => 'aktif',
        ]);

        $petugas2 = Petugas::create([
            'user_id' => $userPetugas2->id,
            'nik' => '3575010101900002',
            'nomor_hp' => '081234567891',
            'email' => 'budi@dinsos.test',
            'alamat' => 'Bangil, Pasuruan',
            'wilayah_id' => $wilayah2->id,
            'status' => 'aktif',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PETUGAS PERIODE
        |--------------------------------------------------------------------------
        */

        PetugasPeriode::create([
            'petugas_id' => $petugas1->id,
            'periode_id' => $periode->id,
            'wilayah_id' => $wilayah1->id,
        ]);

        PetugasPeriode::create([
            'petugas_id' => $petugas2->id,
            'periode_id' => $periode->id,
            'wilayah_id' => $wilayah2->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluarga1 = Keluarga::create([
            'periode_id' => $periode->id,
            'petugas_id' => $petugas1->id,
            'wilayah_id' => $wilayah1->id,
            'nomor_kk' => '3575010101260001',
            'nama_kepala_keluarga' => 'Budi Santoso',
            'alamat' => 'Jl. Sukamaju No. 10',
            'status_pendataan' => 'selesai',
            'tanggal_pendaftaran' => now(),
        ]);

        $keluarga2 = Keluarga::create([
            'periode_id' => $periode->id,
            'petugas_id' => $petugas1->id,
            'wilayah_id' => $wilayah1->id,
            'nomor_kk' => '3575010101260002',
            'nama_kepala_keluarga' => 'Siti Aminah',
            'alamat' => 'Jl. Sukamaju No. 15',
            'status_pendataan' => 'belum_didata',
            'tanggal_pendaftaran' => now(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        $anggota1 = AnggotaKeluarga::create([
            'keluarga_id' => $keluarga1->id,
            'nik' => '3575010101900003',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'umur' => 36,
            'status_keluarga' => 'kepala_keluarga',
        ]);

        $anggota2 = AnggotaKeluarga::create([
            'keluarga_id' => $keluarga1->id,
            'nik' => '3575014101950001',
            'nama_lengkap' => 'Siti Aminah',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '1995-01-01',
            'umur' => 31,
            'status_keluarga' => 'istri',
        ]);

        AnggotaKeluarga::create([
            'keluarga_id' => $keluarga1->id,
            'nik' => '3575010101200001',
            'nama_lengkap' => 'Andi Santoso',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2020-01-01',
            'umur' => 6,
            'status_keluarga' => 'anak',
        ]);


        /*
        |--------------------------------------------------------------------------
        | KUESIONER
        |--------------------------------------------------------------------------
        */

        $q1 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q001',
            'pertanyaan' => 'Status kepemilikan tempat tinggal saat ini?',
            'jenis_jawaban' => 'pilihan_ganda',
            'is_active' => true,
            'urutan' => 1,
        ]);

        $q2 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q002',
            'pertanyaan' => 'Kondisi fisik rumah tempat tinggal?',
            'jenis_jawaban' => 'pilihan_ganda',
            'is_active' => true,
            'urutan' => 2,
        ]);

        $q3 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q003',
            'pertanyaan' => 'Sumber utama air untuk kebutuhan sehari-hari?',
            'jenis_jawaban' => 'pilihan_ganda',
            'is_active' => true,
            'urutan' => 3,
        ]);

        $q4 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q004',
            'pertanyaan' => 'Fasilitas sanitasi yang digunakan?',
            'jenis_jawaban' => 'pilihan_ganda',
            'is_active' => true,
            'urutan' => 4,
        ]);

        $q5 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q005',
            'pertanyaan' => 'Kepemilikan jaminan kesehatan?',
            'jenis_jawaban' => 'pilihan_ganda',
            'is_active' => true,
            'urutan' => 5,
        ]);

        $q6 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q006',
            'pertanyaan' => 'Pekerjaan utama kepala keluarga?',
            'jenis_jawaban' => 'jawaban_singkat',
            'is_active' => true,
            'urutan' => 6,
        ]);

        $q7 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q007',
            'pertanyaan' => 'Sumber penghasilan utama keluarga?',
            'jenis_jawaban' => 'jawaban_singkat',
            'is_active' => true,
            'urutan' => 7,
        ]);

        $q8 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q008',
            'pertanyaan' => 'Jumlah tanggungan keluarga?',
            'jenis_jawaban' => 'jawaban_singkat',
            'is_active' => true,
            'urutan' => 8,
        ]);

        $q9 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q009',
            'pertanyaan' => 'Kondisi khusus anggota keluarga?',
            'jenis_jawaban' => 'jawaban_singkat',
            'is_active' => true,
            'urutan' => 9,
        ]);

        $q10 = Kuesioner::create([
            'periode_id' => $periode->id,
            'kode_pertanyaan' => 'Q010',
            'pertanyaan' => 'Keterangan tambahan jika ada?',
            'jenis_jawaban' => 'jawaban_singkat',
            'is_active' => true,
            'urutan' => 10,
        ]);


        /*
        |--------------------------------------------------------------------------
        | OPSI JAWABAN
        |--------------------------------------------------------------------------
        */

        OpsiJawaban::create([
            'kuesioner_id' => $q1->id,
            'kode_opsi' => 'A',
            'opsi_jawaban' => 'Milik sendiri',
            'urutan' => 1,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q1->id,
            'kode_opsi' => 'B',
            'opsi_jawaban' => 'Kontrak',
            'urutan' => 2,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q1->id,
            'kode_opsi' => 'C',
            'opsi_jawaban' => 'Sewa',
            'urutan' => 3,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q1->id,
            'kode_opsi' => 'D',
            'opsi_jawaban' => 'Menumpang',
            'urutan' => 4,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q2->id,
            'kode_opsi' => 'A',
            'opsi_jawaban' => 'Baik',
            'urutan' => 1,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q2->id,
            'kode_opsi' => 'B',
            'opsi_jawaban' => 'Cukup',
            'urutan' => 2,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q2->id,
            'kode_opsi' => 'C',
            'opsi_jawaban' => 'Tidak Layak',
            'urutan' => 3,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q3->id,
            'kode_opsi' => 'A',
            'opsi_jawaban' => 'PDAM',
            'urutan' => 1,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q3->id,
            'kode_opsi' => 'B',
            'opsi_jawaban' => 'Sumur',
            'urutan' => 2,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q3->id,
            'kode_opsi' => 'C',
            'opsi_jawaban' => 'Sumber lainnya',
            'urutan' => 3,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q4->id,
            'kode_opsi' => 'A',
            'opsi_jawaban' => 'Jamban sendiri',
            'urutan' => 1,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q4->id,
            'kode_opsi' => 'B',
            'opsi_jawaban' => 'Jamban bersama',
            'urutan' => 2,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q4->id,
            'kode_opsi' => 'C',
            'opsi_jawaban' => 'Tidak memiliki',
            'urutan' => 3,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q5->id,
            'kode_opsi' => 'A',
            'opsi_jawaban' => 'BPJS',
            'urutan' => 1,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q5->id,
            'kode_opsi' => 'B',
            'opsi_jawaban' => 'Jaminan lainnya',
            'urutan' => 2,
        ]);

        OpsiJawaban::create([
            'kuesioner_id' => $q5->id,
            'kode_opsi' => 'C',
            'opsi_jawaban' => 'Tidak memiliki',
            'urutan' => 3,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PENDATAAN
        |--------------------------------------------------------------------------
        */

        $pendataan = Pendataan::create([
            'keluarga_id' => $keluarga1->id,
            'periode_id' => $periode->id,
            'petugas_id' => $petugas1->id,
            'status' => 'approved',
            'catatan' => 'Data telah diverifikasi.',
            'mulai_diisi' => now(),
            'disubmit_at' => now(),
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | JAWABAN
        |--------------------------------------------------------------------------
        */

        $opsiQ1 = OpsiJawaban::where('kuesioner_id', $q1->id)
            ->where('kode_opsi', 'A')
            ->first();

        $opsiQ2 = OpsiJawaban::where('kuesioner_id', $q2->id)
            ->where('kode_opsi', 'A')
            ->first();

        $opsiQ3 = OpsiJawaban::where('kuesioner_id', $q3->id)
            ->where('kode_opsi', 'B')
            ->first();

        $opsiQ4 = OpsiJawaban::where('kuesioner_id', $q4->id)
            ->where('kode_opsi', 'A')
            ->first();

        $opsiQ5 = OpsiJawaban::where('kuesioner_id', $q5->id)
            ->where('kode_opsi', 'A')
            ->first();

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q1->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => $opsiQ1->id,
            'jawaban_text' => null,
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q2->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => $opsiQ2->id,
            'jawaban_text' => null,
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q3->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => $opsiQ3->id,
            'jawaban_text' => null,
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q4->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => $opsiQ4->id,
            'jawaban_text' => null,
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q5->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => $opsiQ5->id,
            'jawaban_text' => null,
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q6->id,
            'anggota_keluarga_id' => $anggota1->id,
            'opsi_jawaban_id' => null,
            'jawaban_text' => 'Wiraswasta',
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q7->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => null,
            'jawaban_text' => 'Usaha sendiri',
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q8->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => null,
            'jawaban_text' => '2',
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q9->id,
            'anggota_keluarga_id' => $anggota2->id,
            'opsi_jawaban_id' => null,
            'jawaban_text' => 'Tidak ada',
        ]);

        Jawaban::create([
            'pendataan_id' => $pendataan->id,
            'kuesioner_id' => $q10->id,
            'anggota_keluarga_id' => null,
            'opsi_jawaban_id' => null,
            'jawaban_text' => 'Tidak ada keterangan tambahan.',
        ]);
    }
}