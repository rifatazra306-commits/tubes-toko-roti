Product Requirement Document (PRD) – Sistem Simulasi Pembayaran Berbasis Laravel
1. Latar Belakang dan Tujuan Pengembangan
Fitur pembayaran merupakan komponen krusial dalam sebuah website e-commerce. Namun, mengingat batasan waktu pengembangan proyek (2 hari) serta fokus utama pengujian pada pemahaman arsitektur framework Laravel, sistem pembayaran ini dirancang menggunakan pendekatan Simulated Payment Flow (Alur Simulasi Transaksi Internal).

Tujuan utama dari spesifikasi produk ini adalah menyediakan alur transaksi yang lengkap dari hulu ke hilir (end-to-end) yang logis secara sistem, tanpa melibatkan ketergantungan (dependency) pada API Payment Gateway pihak ketiga yang memerlukan proses verifikasi legalitas rumit. Selain itu, fitur ini dirancang secara modular agar mudah beradaptasi; jika di masa mendatang sistem harus diintegrasikan dengan API nyata (seperti Midtrans atau Xendit), perubahan kode hanya perlu dilakukan pada tingkat Controller tanpa merombak struktur dasar database dan antarmuka pengguna.

2. Kebutuhan Fungsional Sisi Pengguna (Pembeli)
Dari sudut pandang pembeli, sistem harus mampu menangani proses pemesanan hingga tahap konfirmasi pembayaran secara mandiri. Setelah pengguna menentukan produk di dalam keranjang dan menekan tombol Checkout, sistem akan menyajikan halaman ringkasan pesanan yang memuat rincian item, kuantitas, serta total nominal harga yang ditarik secara dinamis dari database.

Pada halaman yang sama, pengguna disediakan komponen pilihan metode pembayaran statis (seperti Transfer Virtual Account Bank atau QRIS). Jika salah satu metode dipilih, sistem akan menampilkan instruksi bayar berupa nomor rekening contoh atau gambar kode QR statis. Skenario penggunaan diakhiri dengan aksi pengguna mengeklik tombol "Saya Sudah Bayar". Aksi ini akan memicu pengiriman data pesanan ke server, menyimpan transaksi baru dengan status awal berupa Pending, lalu mengarahkan halaman pengguna secara otomatis ke riwayat belanja mereka untuk melihat status pemrosesan.

3. Kebutuhan Fungsional Sisi Admin dan Logika Backend Laravel
Di balik layar, arsitektur backend memanfaatkan Laravel Eloquent ORM untuk mengelola status transaksi di dalam database. Struktur tabel transaksi minimal memiliki kolom status yang secara bawaan (default) bernilai Pending ketika pengguna menyelesaikan proses checkout.

Untuk menjembatani validasi pembayaran, sistem menyediakan fungsionalitas khusus pada Dasbor Manajemen Admin. Halaman admin ini berfungsi untuk menampilkan seluruh daftar transaksi yang masuk dari semua pengguna. Admin dibekali kendali penuh berupa tombol "Konfirmasi Pembayaran" di setiap baris transaksi yang berstatus Pending. Ketika tombol tersebut dieksekusi, Laravel akan mengirimkan permintaan POST ke endpoint admin, memproses perubahan data melalui Controller, dan mengubah status transaksi pada database menjadi Lunas. Ketika pengguna melakukan penyegaran (refresh) pada halaman riwayat belanja mereka, status pesanan akan langsung berubah secara real-time menjadi Lunas.

4. Rencana Skalabilitas dan Modifikasi Sistem
Salah satu aspek penting dalam dokumen kebutuhan ini adalah fleksibilitas arsitektur kode. Pemisahan rute (routing) antara proses inisiasi pembayaran oleh pengguna (/checkout/proses) dan konfirmasi manual oleh admin (/admin/transaksi/{id}/konfirmasi) dibuat sangat sederhana.

Jika arsitektur ini ingin ditingkatkan ke skala produksi menggunakan penyedia Payment Gateway, fungsi konfirmasi admin tersebut dapat dialihfungsikan secara langsung menjadi fungsi penangkap Webhook / Callback otomatis dari server eksternal. Dengan demikian, sistem ini memenuhi standar perancangan perangkat lunak yang bersih (clean code), mudah dipelihara (maintainable), dan siap dikembangkan lebih lanjut di luar kebutuhan Tugas Besar.