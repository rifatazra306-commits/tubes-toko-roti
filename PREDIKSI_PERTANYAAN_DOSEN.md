# Panduan Persiapan Ujian / Sidang: Aplikasi Toko Roti (TOKO-ROTI)

Dokumen ini berisi daftar prediksi pertanyaan yang sering diajukan oleh dosen penguji/pembimbing saat sidang tugas besar atau ujian proyek, beserta analisis kelemahan kode sistem saat ini dan solusi untuk menjawab atau memperbaikinya.

---

## BAGIAN 1: Prediksi Pertanyaan Dosen & Cara Menjawab

### Kategori A: Arsitektur Sistem & Struktur Proyek (Native vs Laravel)

#### 1. "Kenapa di proyek ini ada dua versi aplikasi? Ada file PHP Native di folder root dan proyek Laravel di dalam folder `TOKO-ROTI`?"
* **Jawaban Terbaik**: 
  > "Aplikasi ini sedang dalam proses **migrasi** (transisi) dari arsitektur monolitik PHP Native lama ke Framework Laravel 11. Halaman front-end untuk customer (registrasi, login, katalog produk, keranjang, dan checkout) telah berhasil dipindahkan secara penuh ke Laravel menggunakan pola MVC. Namun, bagian Back-end Admin saat ini masih berjalan menggunakan sistem PHP Native yang lama di folder root untuk menjaga fungsionalitas operasional admin tetap berjalan sementara proses migrasi back-end diselesaikan."

#### 2. "Saya mencoba menjalankan perintah `php artisan route:list` di folder Laravel (`TOKO-ROTI`), tetapi sistem error `ReflectionException: Class App\Http\Controllers\Admin\AuthController does not exist`. Kenapa hal ini bisa terjadi?"
* **Jawaban Terbaik**:
  > "Hal ini terjadi karena di dalam file [web.php](file:///c:/laragon/www/tubes-toko-roti/TOKO-ROTI/routes/web.php) baris 9-15 dan 44-88, rute-rute untuk panel admin (seperti `AdminAuthController`, `AdminProductController`, `AdminInventoryController`, dll.) sudah dideklarasikan dan diimpor. Namun, berkas-berkas controller admin tersebut secara fisik belum dibuat di dalam folder `app/Http/Controllers/Admin/`. Ini adalah bagian dari rencana kerja migrasi tahap berikutnya. Untuk mengatasinya sementara agar Laravel tidak error saat booting, kita dapat menonaktifkan (*comment out*) rute panel admin tersebut di [web.php](file:///c:/laragon/www/tubes-toko-roti/TOKO-ROTI/routes/web.php)."

---

### Kategori B: Desain Database & Keanehan Skema (Database Design)

#### 1. "Kenapa tipe data kolom `qty` di tabel `inventory` dan kolom `kebutuhan` di tabel `bom_produk` bertipe `varchar(200)`? Mengapa tidak menggunakan `int` atau `decimal`?"
* **Jawaban Terbaik**:
  > "Ini adalah bagian dari kelemahan desain basis data awal. Kolom kuantitas (`qty` dan `kebutuhan`) seharusnya menggunakan tipe data numerik seperti `INT` (untuk bilangan bulat) atau `DECIMAL/FLOAT` (jika membutuhkan satuan pecahan seperti 0.5 Kg). 
  > **Dampak buruk menggunakan `varchar`**:
  > 1. Terjadi konversi tipe data secara implisit (*implicit type conversion*) oleh database engine saat melakukan kalkulasi aritmatika (seperti perkalian/pengurangan stok).
  > 2. Operasi pengurutan (*sorting*) atau filter perbandingan numerik (misal: mencari bahan baku yang stoknya `< 5`) akan menghasilkan urutan teks alfanumerik, bukan numerik asli (misal: teks `'10'` dianggap lebih kecil dari teks `'2'`).
  > 3. Ketiadaan validasi bawaan database untuk mencegah data non-angka masuk."

#### 2. "Saya melihat ada tabel bernama `report _penjualan` di database dengan spasi di dalam namanya. Kenapa diberi spasi? Bagaimana cara mengkuerinya di PHP native dan Laravel tanpa terjadi syntax error?"
* **Jawaban Terbaik**:
  > "Nama tabel `report _penjualan` yang mengandung spasi merupakan kesalahan penulisan (*typo*) saat pembuatan skema awal di phpMyAdmin. 
  > - **Di PHP Native**: Kita wajib mengapit nama tabel tersebut dengan simbol *backtick* (\`): 
  >   `SELECT * FROM \`report _penjualan\``
  > - **Di Laravel (Eloquent)**: Kita harus mendefinisikannya secara eksplisit di dalam Model:
  >   `protected $table = 'report _penjualan';`
  > Namun, solusi terbaik yang direkomendasikan adalah melakukan *rename* nama tabel tersebut menjadi `report_penjualan` (mengganti spasi dengan *underscore*) guna menghindari kebingungan sintaksis."

#### 3. "Kenapa tabel `bom_produk` menggunakan database engine `MyISAM`, sedangkan tabel lainnya menggunakan `InnoDB`? Apa perbedaan penting keduanya?"
* **Jawaban Terbaik**:
  > "Tabel `bom_produk` tersetting menggunakan `MyISAM` kemungkinan karena pengaturan default saat impor database lama. Perbedaan utamanya adalah:
  > - **InnoDB**: Mendukung *Foreign Keys* (integritas relasi), transaksi (*ACID compliance*: commit & rollback), dan penguncian baris (*row-level locking*) yang sangat cocok untuk sistem dengan intensitas tulis tinggi seperti transaksi pemesanan.
  > - **MyISAM**: Tidak mendukung transaksi maupun *foreign key*, dan hanya mendukung penguncian tabel (*table-level locking*). Namun, ia memiliki performa pembacaan data (*read-heavy*) yang sedikit lebih cepat pada MySQL versi lama.
  > Demi konsistensi data dan integritas relasi (terutama relasi resep produk), mesin tabel `bom_produk` sebaiknya diubah menjadi **InnoDB** agar mendukung relasi kunci tamu (*foreign key constraint*)."

#### 4. "Mengapa kolom primary key pada tabel `customer` (`kode_customer`) menggunakan format string kustom seperti `C0001`, `C0002`? Kenapa tidak menggunakan auto-increment integer saja?"
* **Jawaban Terbaik**:
  > "Penggunaan kode kustom bertujuan untuk mempermudah identifikasi entitas pelanggan secara visual pada dokumen cetak seperti invoice atau nota fisik. Namun, pendekatan ini memiliki konsekuensi yaitu kita harus membuat logika manual di aplikasi untuk mencari kode terakhir, mengambil angkanya, menjumlahkannya, dan memformat ulang string tersebut (seperti yang diimplementasikan pada `AuthController.php` Laravel baris 65-83). Jika ingin performa kueri yang lebih optimal, sebaiknya menggunakan tipe data `BIGINT` auto-increment sebagai Primary Key fisik, sedangkan kode `C0001` dijadikan kolom alternatif (*unique key*)."

---

### Kategori C: Logika Bisnis & Sistem BOM (Bill of Materials)

#### 1. "Bagaimana alur kerja sistem dalam memproses pengurangan stok bahan baku otomatis saat pesanan diterima oleh admin?"
* **Jawaban Terbaik**:
  > "Alurnya ditulis di file [terima.php](file:///c:/laragon/www/tubes-toko-roti/admin/proses/terima.php):
  > 1. Sistem mengambil seluruh baris pesanan dari tabel `produksi` berdasarkan kode `invoice` tertentu.
  > 2. Untuk setiap produk dalam pesanan, sistem mencari resepnya di tabel `bom_produk` berdasarkan `kode_produk`.
  > 3. Untuk setiap bahan baku (`kode_bk`) dalam resep tersebut, sistem mencocokkannya dengan stok saat ini di tabel `inventory`.
  > 4. Sistem mengalikan `kebutuhan` bahan baku per roti dengan `qty` (jumlah pesanan roti), lalu menguranginya dari stok inventory:
  >    `$hasil = $inven - ($kebutuhan * $qtyorder);`
  > 5. Sistem melakukan kueri `UPDATE` ke tabel `inventory` untuk memperbarui stok bahan baku, kemudian mengubah status pesanan (`terima = '1'`, `status = '0'`)."

#### 2. "Di file `admin/proses/terima.php`, apa yang terjadi jika stok bahan baku di inventory ternyata tidak mencukupi untuk membuat pesanan tersebut?"
* **Jawaban Terbaik**:
  > "Saat ini, sistem **tidak memiliki validasi ketersediaan stok sebelum pesanan diterima**. Sistem akan langsung melakukan pengurangan matematika biasa, sehingga nilai stok bahan baku di tabel `inventory` akan **berubah menjadi negatif** (di bawah 0). Ini adalah kelemahan logika yang kritis.
  > **Solusi perbaikan**: Sebelum menjalankan kueri `UPDATE` stok, sistem harus melakukan pemeriksaan terlebih dahulu. Jika hasil pengurangan (`$hasil`) bernilai kurang dari 0, proses penerimaan harus dibatalkan, dan admin diberi notifikasi bahwa stok bahan baku tidak mencukupi."

#### 3. "Saya melihat baris kode `echo \"<script>window.location = '../produksi.php';</script>\";` diletakkan di dalam nested loop pada file `admin/proses/terima.php`. Mengapa ini dianggap sebagai kesalahan penulisan kode?"
* **Jawaban Terbaik**:
  > "Meletakkan skrip pengalihan halaman (`window.location`) di dalam loop `while` sangat berisiko karena:
  > 1. Pada iterasi bahan baku pertama yang sukses diperbarui, browser akan langsung membaca perintah JavaScript redirect tersebut dan memindahkan halaman. Akibatnya, iterasi bahan baku berikutnya atau produk berikutnya dalam loop terancam tidak sempat dieksekusi oleh server.
  > 2. Kueri data menjadi tidak konsisten.
  > **Solusi perbaikan**: Semua operasi pembaruan database harus diselesaikan terlebih dahulu dalam loop. Setelah seluruh loop selesai dieksekusi tanpa ada error, baris kode redirect/alert diletakkan satu kali di bagian paling bawah setelah blok loop berakhir."

---

### Kategori D: Keamanan & Autentikasi (Security)

#### 1. "Bagaimana sistem Anda menyimpan kata sandi pengguna agar aman dari pencurian database?"
* **Jawaban Terbaik**:
  > "Sistem menggunakan enkripsi satu arah (*one-way hashing*):
  > - **Di PHP Native**: Menggunakan fungsi bawaan PHP `password_hash($password, PASSWORD_DEFAULT)` yang secara otomatis menerapkan algoritma **bcrypt** yang aman dan dinamis dengan penambahan *salt* acak secara otomatis.
  > - **Di Laravel**: Menggunakan fungsi helper `Hash::make()` atau enkripsi bawaan Model yang juga menggunakan algoritma bcrypt.
  > Saat proses login, kata sandi yang diinput dicocokkan dengan hash di database menggunakan fungsi `password_verify($password_input, $password_hash_db)`."

#### 2. "Bagaimana cara sistem membedakan hak akses halaman pelanggan biasa dengan halaman admin agar pelanggan tidak bisa mengetik langsung URL admin?"
* **Jawaban Terbaik**:
  > - **Di PHP Native**: Setiap halaman admin menyertakan pengecekan session admin di bagian atas file. Jika session admin tidak aktif, pengguna akan dilempar (*redirect*) kembali ke halaman login admin.
  > - **Di Laravel**: Sistem menggunakan mekanisme **Middleware** ([AdminAuth.php](file:///c:/laragon/www/tubes-toko-roti/TOKO-ROTI/app/Http/Middleware/AdminAuth.php) dan [CustomerAuth.php](file:///c:/laragon/www/tubes-toko-roti/TOKO-ROTI/app/Http/Middleware/CustomerAuth.php)) yang memproteksi grup rute admin (`admin/*`) di file rute `web.php`. Jika pengguna belum login sebagai admin, middleware akan memblokir request dan mengalihkan mereka ke halaman login admin."

---

## BAGIAN 2: Kemungkinan Perubahan yang Diminta Dosen (Tantangan Praktikum)

Berikut adalah beberapa modifikasi kode yang kemungkinan besar akan diminta oleh dosen penguji untuk menguji pemahaman pemrograman Anda secara langsung:

### 1. Tambahkan Validasi Stok Bahan Baku (Mencegah Stok Negatif)
**Instruksi**: Ubah proses penerimaan pesanan agar menolak proses jika stok bahan baku tidak cukup.

* **Lokasi File**: `admin/proses/terima.php`
* **Konsep Perbaikan (Native PHP)**:
```php
<?php 
include '../../koneksi/koneksi.php';
$inv = $_GET['inv'];

$result = mysqli_query($conn, "SELECT * from produksi where invoice = '$inv'");

// Flag untuk menandai apakah stok cukup untuk seluruh produk dalam invoice
$stok_cukup = true;
$bahan_kurang = [];

// Langkah 1: Cek semua bahan baku terlebih dahulu (dry run)
while($row = mysqli_fetch_assoc($result)){
    $kodep = $row['kode_produk'];
    $qtyorder = $row['qty'];
    
    $t_bom = mysqli_query($conn, "SELECT * FROM bom_produk WHERE kode_produk = '$kodep'");
    while($row1 = mysqli_fetch_assoc($t_bom)){
        $kodebk = $row1['kode_bk'];
        $kebutuhan = $row1['kebutuhan'];
        
        $inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE kode_bk = '$kodebk'");
        $r_inv = mysqli_fetch_assoc($inventory);
        $stok_sekarang = $r_inv['qty'];
        
        $total_kebutuhan = $kebutuhan * $qtyorder;
        
        if ($stok_sekarang < $total_kebutuhan) {
            $stok_cukup = false;
            $bahan_kurang[] = $r_inv['nama'];
        }
    }
}

// Reset pointer hasil query agar bisa di-loop kembali
mysqli_data_seek($result, 0);

// Langkah 2: Jika stok cukup, lakukan pengurangan stok dan update status
if ($stok_cukup) {
    while($row = mysqli_fetch_assoc($result)){
        $kodep = $row['kode_produk'];
        $qtyorder = $row['qty'];
        
        $t_bom = mysqli_query($conn, "SELECT * FROM bom_produk WHERE kode_produk = '$kodep'");
        while($row1 = mysqli_fetch_assoc($t_bom)){
            $kodebk = $row1['kode_bk'];
            $kebutuhan = $row1['kebutuhan'];
            
            $inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE kode_bk = '$kodebk'");
            $r_inv = mysqli_fetch_assoc($inventory);
            $stok_sekarang = $r_inv['qty'];
            
            $hasil_baru = $stok_sekarang - ($kebutuhan * $qtyorder);
            mysqli_query($conn, "UPDATE inventory SET qty = '$hasil_baru' WHERE kode_bk = '$kodebk'");
        }
    }
    
    // Update status produksi pesanan diterima
    mysqli_query($conn, "UPDATE produksi SET terima = '1', status = '0' WHERE invoice = '$inv'");
    
    echo "<script>
            alert('PESANAN BERHASIL DITERIMA, BAHAN BAKU TELAH DIKURANGKAN');
            window.location = '../produksi.php';
          </script>";
} else {
    // Tampilkan pesan error jika bahan baku kurang
    $list_bahan = implode(", ", array_unique($bahan_kurang));
    echo "<script>
            alert('GAGAL MENERIMA PESANAN! Stok Bahan Baku ($list_bahan) tidak mencukupi.');
            window.location = '../produksi.php';
          </script>";
}
?>
```

---

### 2. Implementasi Pagination (Membatasi Tampilan Data)
**Instruksi**: Dosen mungkin memprotes halaman `admin/produksi.php` atau `admin/inventory.php` yang lambat jika datanya ribuan. Anda diminta menambahkan pagination.

* **Konsep Perbaikan (Native PHP)**:
```php
// Tentukan jumlah data per halaman
$limit = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $limit) - $limit : 0;	

// Kueri hitung total data
$data = mysqli_query($conn, "SELECT * FROM produksi");
$jumlah_data = mysqli_num_rows($data);
$total_halaman = ceil($jumlah_data / $limit);

// Kueri ambil data dengan LIMIT
$data_produksi = mysqli_query($conn, "SELECT * FROM produksi LIMIT $halaman_awal, $limit");
```

---

### 3. Masalah Penghapusan Data (Cascading / Foreign Key Constraints)
**Instruksi**: Apa yang terjadi jika kita menghapus data di tabel `produk` padahal produk tersebut masih ada dalam resep `bom_produk` atau transaksi `produksi`? Database akan error/tidak konsisten. Bagaimana cara mengamankannya?

* **Jawaban & Konsep Perbaikan**:
  Kita perlu menangani relasi database dengan aman. Pilihan yang bisa diambil:
  1. **Soft Deletes (Laravel)**: Menambahkan kolom `deleted_at` pada tabel produk, sehingga produk tidak benar-benar dihapus dari database, melainkan hanya disembunyikan.
  2. **Restrict / Warning (PHP Native)**: Sebelum mengeksekusi `DELETE FROM produk`, lakukan kueri cek terlebih dahulu ke tabel `bom_produk` atau `produksi`. Jika ada, tolak penghapusan.
     ```php
     $cek_produksi = mysqli_query($conn, "SELECT * FROM produksi WHERE kode_produk = '$kode_produk'");
     if(mysqli_num_rows($cek_produksi) > 0) {
         echo "<script>alert('Produk tidak bisa dihapus karena sedang dalam transaksi active!');</script>";
     } else {
         // Lanjutkan hapus
     }
     ```

---

### 4. Pelaporan File Format PDF / Excel Dynamic
**Instruksi**: Admin meminta filter tanggal pada ekspor laporan agar tidak mengekspor data dari awal waktu.

* **Lokasi File**: `admin/laporan_omset.php` atau file export terkait.
* **Konsep Perbaikan**: Tambahkan formulir input date (`tanggal_mulai` dan `tanggal_selesai`), kemudian teruskan variabel tersebut ke berkas export (misal `exp_omset.php?mulai=2026-06-01&selesai=2026-06-30`) dan filter kuerinya menggunakan SQL `BETWEEN`:
  ```sql
  SELECT * FROM produksi WHERE tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
  ```
