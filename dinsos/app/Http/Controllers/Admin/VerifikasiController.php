<?php 
    
   namespace App\Http\Controllers\Admin; 
    
   use App\Http\Controllers\Controller; 
   use App\Models\Keluarga; 
   use App\Models\KeluargaPart1; 
   use Illuminate\Http\Request; 
   use Illuminate\Pagination\LengthAwarePaginator; 
    
   class VerifikasiController extends Controller 
   { 
       /** 
        * ============================================================ 
        * MENGAMBIL DATA VERIFIKASI 
        * ============================================================ 
        * 
        * Data utama berasal dari tabel part1_keluarga. 
        * Nama kepala keluarga diambil dari tabel keluargas. 
        */ 
        protected function getData() 
        { 
            return KeluargaPart1::query() 
                ->orderByDesc('id') 
                ->get() 
                ->map(function ($item) { 
     
                    // ------------------------------------------------- 
                    // NORMALISASI STATUS 
                    // ------------------------------------------------- 
                    $status = $this->normalizeStatus($item->status); 
     
                    // ------------------------------------------------- 
                    // CARI DATA RESPONDEN / KEPALA KELUARGA 
                    // ------------------------------------------------- 
                    // 
                    // Prioritas pencarian: 
                    // 1. Berdasarkan No. KK 
                    // 2. Jika belum ditemukan, berdasarkan NIK 
                    // 
                    $keluarga = Keluarga::query() 
                        ->where('no_kk', $item->no_kk) 
                        ->first(); 
     
                    // Jika berdasarkan No. KK belum ditemukan, 
                    // coba cari berdasarkan NIK. 
                    if (!$keluarga && !empty($item->nik)) { 
                        $keluarga = Keluarga::query() 
                            ->where('nik', $item->nik) 
                            ->first(); 
                    } 
     
                    return [ 
                        // ------------------------------------------------- 
                        // IDENTITAS DATA 
                        // ------------------------------------------------- 
                        'id' => $item->id, 
     
                        'no_kk' => $item->no_kk ?? '-', 
     
                        'nik' => $item->nik ?? '-', 
     
                        // Nama diambil dari tabel keluargas 
                        'nama' => $keluarga?->nama_lengkap ?? '-', 
     
                        // ------------------------------------------------- 
                        // JUMLAH ANGGOTA 
                        // ------------------------------------------------- 
                        'anggota' => $item->jml_keluarga ?? 0, 
     
                        // ------------------------------------------------- 
                        // DETAIL ANGGOTA KELUARGA 
                        // ------------------------------------------------- 
                        'anggota_detail' => $keluarga?->anggota?->map(function ($anggota) { 
                            return [
                                'nik' => $anggota->nik ?? '-',
                                'nama_lengkap' => $anggota->nama_lengkap ?? '-',
                                'status_keluarga' => $anggota->status_keluarga ?? '-',
                            ];
                        })->values()->all() ?? [],
    
                       // ------------------------------------------------- 
                       // STATUS 
                       // ------------------------------------------------- 
                       'status' => $status, 
    
                       'status_label' => $this->getStatusLabel($status), 
    
                       // ------------------------------------------------- 
                       // WILAYAH 
                       // ------------------------------------------------- 
                       'wilayah' => $this->buildWilayah($item), 
    
                       // ------------------------------------------------- 
                       // PETUGAS 
                       // ------------------------------------------------- 
                       'petugas' => $item->created_by ?? '-', 
    
                       // ------------------------------------------------- 
                       // TANGGAL 
                       // ------------------------------------------------- 
                       'tanggal' => $item->created_at 
                           ? $item->created_at->format('d F Y H:i') 
                           : '-', 
    
                       // ------------------------------------------------- 
                       // DATA PART 1 
                       // ------------------------------------------------- 
                       'provinsi' => $item->provinsi ?? '-', 
    
                       'daerah' => $item->daerah ?? '-', 
    
                       'kecamatan' => $item->kecamatan ?? '-', 
    
                       'kelurahan' => $item->kelurahan ?? '-', 
    
                       'kode_pos' => $item->kode_pos ?? '-', 
    
                       'rt_rw' => $item->rt_rw ?? '-', 
    
                       'alamat_lengkap' => $item->alamat_lengkap ?? '-', 
    
                       'jalan_rumah' => $item->jalan_rumah ?? '-', 
    
                       'is_alamat_sesuai' => $item->is_alamat_sesuai, 
    
                       'geotangging' => $item->geotangging ?? '-', 
    
                       // ------------------------------------------------- 
                       // PART SAAT INI 
                       // ------------------------------------------------- 
                       'current_part' => $item->current_part ?? 1, 
    
                       // ------------------------------------------------- 
                       // PERIODE 
                       // ------------------------------------------------- 
                       'keluarga_periode_kode' => 
                           $item->keluarga_periode_kode ?? null, 
    
                       // ------------------------------------------------- 
                       // CREATED AT 
                       // ------------------------------------------------- 
                       'created_at' => $item->created_at, 
    
                       // ------------------------------------------------- 
                       // DATA KUISONER 
                       // ------------------------------------------------- 
                       'kuisioner' => $this->buildQuestionnaire($item), 
                   ]; 
               }); 
       } 
    
       /** 
        * ============================================================ 
        * MEMBUAT LABEL WILAYAH 
        * ============================================================ 
        */ 
       protected function buildWilayah($item) 
       { 
           $wilayah = collect([ 
               $item->kecamatan, 
               $item->kelurahan, 
           ]) 
               ->filter(function ($value) { 
                   return !empty($value); 
               }) 
               ->implode(' - '); 
    
           return $wilayah ?: '-'; 
       } 
    
       /** 
        * ============================================================ 
        * MEMBUAT DATA KUISONER PART 1 
        * ============================================================ 
        * 
        * Data diambil dari tabel part1_keluarga. 
        */ 
       protected function buildQuestionnaire($item) 
       { 
           return [ 
               [ 
                   'part' => 1, 
    
                   'title' => 'Data Keluarga & Alamat', 
    
                   'questions' => [ 
    
                       [ 
                           'number' => 1, 
                           'question' => 'NIK', 
                           'answer' => $item->nik ?? '-', 
                       ], 
    
                       [ 
                           'number' => 2, 
                           'question' => 'Nomor Kartu Keluarga', 
                           'answer' => $item->no_kk ?? '-', 
                       ], 
    
                       [ 
                           'number' => 3, 
                           'question' => 'Jumlah anggota keluarga', 
                           'answer' => $item->jml_keluarga ?? '-', 
                       ], 
    
                       [ 
                           'number' => 4, 
                           'question' => 'Provinsi tempat tinggal keluarga saat ini', 
                           'answer' => $item->provinsi ?? '-', 
                       ], 
    
                       [ 
                           'number' => 5, 
                           'question' => 'Kabupaten/Kota tempat tinggal keluarga saat ini', 
                           'answer' => $item->daerah ?? '-', 
                       ], 
    
                       [ 
                           'number' => 6, 
                           'question' => 'Kecamatan tempat tinggal keluarga saat ini', 
                           'answer' => $item->kecamatan ?? '-', 
                       ], 
    
                       [ 
                           'number' => 7, 
                           'question' => 'Desa/Kelurahan tempat tinggal keluarga saat ini', 
                           'answer' => $item->kelurahan ?? '-', 
                       ], 
    
                       [ 
                           'number' => 8, 
                           'question' => 'Nomor Kode Pos', 
                           'answer' => $item->kode_pos ?? '-', 
                       ], 
    
                       [ 
                           'number' => 9, 
                           'question' => 'Satuan Lingkungan Setempat (RT/RW/Dusun dll)', 
                           'answer' => $item->rt_rw ?? '-', 
                       ], 
    
                       [ 
                           'number' => 10, 
                           'question' => 'Alamat Lengkap Rumah/Tempat Tinggal Anda?', 
                           'answer' => $item->alamat_lengkap ?? '-', 
                       ], 
    
                       [ 
                           'number' => 11, 
                           'question' => 'Nama jalan Rumah/Tempat Tinggal Anda?', 
                           'answer' => $item->jalan_rumah ?? '-', 
                       ], 
    
                       [ 
                           'number' => 12, 
                           'question' => 'Nomor Rumah', 
                           'answer' => '-', 
                       ], 
    
                       [ 
                           'number' => 13, 
                           'question' => 'Apakah alamat tempat tinggal saat ini sesuai dengan Kartu Keluarga?', 
                           'answer' => $this->formatAlamatSesuai( 
                               $item->is_alamat_sesuai 
                           ), 
                       ], 
    
                       [ 
                           'number' => 14, 
                           'question' => 'Titik lokasi (geotagging) tempat tinggal saat ini', 
                           'answer' => $item->geotangging ?? '-', 
                       ], 
                   ], 
               ], 
           ]; 
       } 
    
       /** 
        * ============================================================ 
        * FORMAT JAWABAN ALAMAT SESUAI KK 
        * ============================================================ 
        */ 
       protected function formatAlamatSesuai($value) 
       { 
           if ($value === null || $value === '') { 
               return '-'; 
           } 
    
           return (bool) $value 
               ? 'Ya, Sesuai' 
               : 'Tidak Sesuai'; 
       } 
    
       /** 
        * ============================================================ 
        * NORMALISASI STATUS 
        * ============================================================ 
        */ 
       protected function normalizeStatus($status) 
       { 
           return match ($status) { 
    
               'pending' => 'pending', 
    
               'draft' => 'draft', 
    
               'not_processed' => 'not_processed', 
    
               'approved' => 'approved', 
    
               'rejected' => 'rejected', 
    
               // Status lama 
               'menunggu' => 'pending', 
    
               'belum' => 'not_processed', 
    
               'disetujui' => 'approved', 
    
               'ditolak' => 'rejected', 
    
               null, '' => 'pending', 
    
               default => 'pending', 
           }; 
       } 
    
       /** 
        * ============================================================ 
        * LABEL STATUS 
        * ============================================================ 
        */ 
       protected function getStatusLabel($status) 
       { 
           return match ($status) { 
    
               'pending' => 'Pending Verification', 
    
               'draft' => 'Draft', 
    
               'not_processed' => 'Not Processed', 
    
               'approved' => 'Approved', 
    
               'rejected' => 'Rejected', 
    
               default => 'Pending Verification', 
           }; 
       } 
    
       /** 
        * ============================================================ 
        * HALAMAN INDEX VERIFIKASI 
        * ============================================================ 
        */ 
       public function index(Request $request) 
       { 
           // Ambil seluruh data 
           $data = $this->getData(); 
    
           // --------------------------------------------------------- 
           // SEARCH 
           // --------------------------------------------------------- 
           $search = trim( 
               $request->input('search', '') 
           ); 
    
           if ($search !== '') { 
    
               $searchLower = strtolower($search); 
    
               $data = $data->filter( 
                   function ($item) use ($searchLower) { 
    
                       return 
                           str_contains( 
                               strtolower( 
                                   $item['no_kk'] ?? '' 
                               ), 
                               $searchLower 
                           ) 
    
                           || 
    
                           str_contains( 
                               strtolower( 
                                   $item['nik'] ?? '' 
                               ), 
                               $searchLower 
                           ) 
    
                           || 
    
                           str_contains( 
                               strtolower( 
                                   $item['nama'] ?? '' 
                               ), 
                               $searchLower 
                           ) 
    
                           || 
    
                           str_contains( 
                               strtolower( 
                                   $item['wilayah'] ?? '' 
                               ), 
                               $searchLower 
                           ) 
    
                           || 
    
                           str_contains( 
                               strtolower( 
                                   $item['petugas'] ?? '' 
                               ), 
                               $searchLower 
                           ); 
                   } 
               ); 
           } 
    
           // --------------------------------------------------------- 
           // FILTER STATUS 
           // --------------------------------------------------------- 
           $status = $request->input( 
               'status', 
               '' 
           ); 
    
           if ($status === 'all') { 
               $status = ''; 
           } 
    
           if ($status !== '') { 
    
               $status = $this->normalizeStatus( 
                   $status 
               ); 
    
               $data = $data->filter( 
                   function ($item) use ($status) { 
    
                       return ( 
                           $item['status'] ?? '' 
                       ) === $status; 
                   } 
               ); 
           } 
    
           // --------------------------------------------------------- 
           // PAGINATION 
           // --------------------------------------------------------- 
           $perPage = 5; 
    
           $currentPage = 
               LengthAwarePaginator::resolveCurrentPage(); 
    
           $data = $data->values(); 
    
           $currentItems = $data 
               ->slice( 
                   ($currentPage - 1) * $perPage, 
                   $perPage 
               ) 
               ->values(); 
    
           $data = new LengthAwarePaginator( 
               $currentItems, 
               $data->count(), 
               $perPage, 
               $currentPage, 
               [ 
                   'path' => $request->url(), 
    
                   'query' => $request->query(), 
               ] 
           ); 
    
           // --------------------------------------------------------- 
           // KIRIM KE VIEW 
           // --------------------------------------------------------- 
           return view( 
               'admin.verifikasi.index', 
               [ 
                   'data' => $data, 
    
                   'search' => $search, 
    
                   'status' => $status, 
               ] 
           ); 
       } 
    
       /** 
        * ============================================================ 
        * DETAIL DATA 
        * ============================================================ 
        * 
        * Untuk sementara method ini tetap dipertahankan. 
        * Detail utama sekarang ditampilkan melalui modal 
        * di halaman index. 
        */ 
       public function show($id) 
       { 
           $item = $this->getData() 
               ->firstWhere( 
                   'id', 
                   (int) $id 
               ); 
    
           if (!$item) { 
               abort(404); 
           } 
    
           return view( 
               'admin.verifikasi.show', 
               compact('item') 
           ); 
       } 
    
       /** 
        * ============================================================ 
        * UPDATE STATUS VERIFIKASI 
        * ============================================================ 
        * 
        * Menyimpan status verifikasi ke tabel part1_keluarga. 
        * Status approved/rejected dapat diubah kembali selama data bukan Draft. 
        */ 
       public function update( 
           Request $request, 
           $id 
       ) { 
    
           $request->validate( 
               [ 
                   'status' => 
                       'required|in:approved,rejected', 
               ], 
               [ 
                   'status.required' => 
                       'Silakan pilih status verifikasi.', 
    
                   'status.in' => 
                       'Status yang dipilih tidak valid.', 
               ] 
           ); 
    
           $item = KeluargaPart1::find($id); 
    
           if (!$item) { 
               abort(404); 
           } 
    
           // Draft sengaja tidak dapat diberi status verifikasi. 
           if ($this->normalizeStatus($item->status) === 'draft') { 
               return redirect() 
                   ->route('verifikasi.index') 
                   ->with('success', 'Data Draft belum dapat diberi status verifikasi.'); 
           } 
    
           // Status boleh diubah kembali, termasuk dari approved -> rejected 
           // maupun rejected -> approved. Data tidak dihapus dari monitoring. 
           $item->status = $request->input('status'); 
           $item->save(); 
    
           $label = $request->input('status') === 'approved'
               ? 'Disetujui'
               : 'Ditolak'; 
    
           return redirect() 
               ->route('verifikasi.index') 
               ->with(
                   'success',
                   'Status data berhasil diubah menjadi ' . $label . '.'
               ); 
       } 
   } 
