# DOKUMEN LOG PENGGUNAAN AI (ARTIFICIAL INTELLIGENCE)
## SISTEM INFORMASI MANAJEMEN LAUNDRY BERBASIS WEB
**Tech Stack**: Laravel (PHP 8.5) • MySQL • Tailwind CSS (Blade)  
**Pengembang / Akun GitHub**: `charlimuzaki29-maker`  
**Repository**: `https://github.com/charlimuzaki29-maker/Laundryweb_charli`  

---

### RINGKASAN EKSEKUTIF PENGGUNAAN AI
Pengembangan aplikasi **Sistem Informasi Manajemen Laundry Berbasis Web** memanfaatkan AI Assistant (Google DeepMind Antigravity) sebagai mitra *pair programming*. AI digunakan secara terarah untuk mempercepat perancangan arsitektur, pembuatan skema database relasional, logika backend dan autentikasi, implementasi styling Tailwind CSS yang responsif multi-device, pengujian otomatis berstandar PHPUnit, hingga pengambilan tangkapan layar otomatis (*automated screenshot capture*).

---

### LOG AKTIVITAS & RIWAYAT PROMPT AI

#### 1. Fase Perancangan Arsitektur & Inisialisasi Proyek
* **Instruksi Pengguna (Prompt)**:  
  *"Bangun aplikasi Sistem Informasi Manajemen Laundry Berbasis Web dengan Laravel, MySQL, dan Tailwind CSS (Blade). Ikuti aturan ketat: menu navigasi utama hanya 'Data Layanan' dan 'Rekap Transaksi Laundry', autentikasi role Admin dan Kasir, CRUD Layanan, transaksi laundry dengan kalkulasi tarif otomatis, serta rekap transaksi."*
* **Tindakan & Solusi AI**:
  - Melakukan inisialisasi framework Laravel.
  - Mengonfigurasi environment `.env` untuk koneksi basis data MySQL (`laundry_db`) di port 3306.
  - Mempersiapkan arsitektur MVC (Model-View-Controller) sesuai standar Laravel Best Practices.

#### 2. Fase Database Migration, Model, & Seeder
* **Instruksi Pengguna (Prompt)**:  
  *"Buat skema database MySQL minimal 2 tabel utama: Layanan (jenis layanan, harga/kg, estimasi waktu) dan Transaksi (nama pelanggan, no hp, FK ke layanan, berat/kg, total tarif, status pengerjaan). Tambahkan seeder paket layanan (Reguler, Ekspres, Superkilat) dan akun demo Admin & Kasir."*
* **Tindakan & Solusi AI**:
  - Menghasilkan migration `2026_11_24_021212_create_layanans_table.php` dengan field `nama_layanan`, `harga_per_kg`, dan `estimasi_waktu`.
  - Menghasilkan migration `2026_11_24_021215_create_transaksis_table.php` dengan foreign key `layanan_id` terhubung ke `layanans` (`constrained()->cascadeOnDelete()`), `nama_pelanggan`, `no_hp`, `berat`, `total_tarif`, dan enum `status` (`proses`, `selesai`).
  - Menambahkan kolom `role` (`admin`, `kasir`) pada migration tabel `users`.
  - Membuat relasi Eloquent: `Layanan::hasMany(Transaksi::class)` dan `Transaksi::belongsTo(Layanan::class)`.
  - Membuat `LayananSeeder` dengan 3 paket utama serta `DatabaseSeeder` dengan kredensial aman Admin dan Kasir.

#### 3. Fase Autentikasi, Middleware, & Logika Bisnis
* **Instruksi Pengguna (Prompt)**:  
  *"Terapkan pembatasan hak akses role Admin dan Kasir secara jelas dan konsisten dengan Middleware. CRUD Layanan hanya boleh diakses Admin, sedangkan Kasir hanya boleh melihat. Kasir dan Admin dapat mencatat dan menyelesaikan transaksi."*
* **Tindakan & Solusi AI**:
  - Membuat `RoleMiddleware` untuk memeriksa otorisasi pengguna berdasarkan kolom `role`.
  - Mendaftarkan middleware role pada aplikasi Laravel.
  - Mengembangkan `AuthController` untuk alur login, proteksi session, dan logout yang aman dari serangan CSRF.
  - Membangun `LayananController` dengan proteksi middleware `role:admin` pada method create, store, edit, update, dan destroy.
  - Membangun `TransaksiController` dengan validasi `StoreTransaksiRequest`, kalkulasi harga server-side (`berat * harga_per_kg`), dan endpoint update status AJAX/PATCH.

#### 4. Fase Pengujian Otomatis (PHPUnit) & Formatter Code
* **Instruksi Pengguna (Prompt)**:  
  *"Pastikan seluruh fitur berjalan tanpa error dan lulus pengujian."*
* **Tindakan & Solusi AI**:
  - Menulis 4 file unit dan feature test:
    - `tests/Feature/AuthAndRoleTest.php`: Menguji login Admin/Kasir dan pembatasan otorisasi middleware.
    - `tests/Feature/LayananCrudTest.php`: Menguji create, read, update, delete layanan, pencegahan akses Kasir untuk memodifikasi layanan, dan validasi input.
    - `tests/Feature/TransaksiLaundryTest.php`: Menguji kalkulasi otomatis tarif, pembaruan status pengerjaan, filter pencarian, dan relasi tabel.
    - `tests/Feature/ExampleTest.php`: Menguji alur redirection root URL.
  - Menjalankan PHPUnit: **22 passed, 72 assertions, 0 failed**.
  - Merapikan standar penulisan kode PHP dengan `Laravel Pint`.

#### 5. Fase Redesign UI/UX: "Warm Stone Sidebar & Pure White Workspace"
* **Instruksi Pengguna (Prompt)**:  
  *"Lakukan redesign UI/UX agar bergaya 'Warm Stone Sidebar & Pure White Workspace'. Sidebar kiri lembut (#F6F5F2), kanvas kartu putih bersih, tombol Deep Forest Emerald, drawer responsif mobile, tanpa logo merek, form login bersih."*
* **Tindakan & Solusi AI**:
  - Membangun layout `resources/views/layouts/app.blade.php` dengan sidebar desktop tetap dan responsive mobile slide-out drawer (Alpine/vanilla JS toggle + backdrop).
  - Menyusun 2 menu navigasi utama bersih: "Data Layanan" dan "Rekap Transaksi Laundry".
  - Memperbarui kartu layanan dengan badge estimasi waktu, format mata uang rupiah, dan tombol aksi yang ergonomis.
  - Merancang tabel transaksi dengan visual card stack pada layar mobile serta live pricing calculator pada form pemesanan baru.
  - Menyediakan halaman registrasi (`/register`) dan form login murni (`/login`).
  - Mengompilasi build CSS frontend melalui Vite (`npm run build`).

#### 6. Fase Dokumentasi & Automated Multi-Device Screenshot Capture
* **Instruksi Pengguna (Prompt)**:  
  *"Selesaikan seluruh soal untuk pengumpulan Google Form, buat dokumen lengkap beserta tangkapan layar Desktop, Tablet, dan Smartphone."*
* **Tindakan & Solusi AI**:
  - Membuat script otomatisasi `take_screenshots.js` menggunakan headless Chromium/Edge (`puppeteer-core`).
  - Mengambil 24 screenshot beresolusi tinggi pada 3 breakpoint spesifik:
    - Desktop: 1280 x 800 px
    - Tablet: 768 x 1024 px
    - Smartphone: 375 x 812 px
  - Mengambil tangkapan layar struktur tabel dan migration MySQL.
  - Menyusun dokumen pengumpulan `DOKUMENTASI_PENGUMPULAN_GFORM.md`, `DOKUMENTASI_PENGUMPULAN_GFORM.html`, dan `LOG_PENGGUNAAN_AI.md`.

---

### KESIMPULAN & REFLEKSI PEMANFAATAN AI
Pemanfaatan AI dalam proyek ini berperan sebagai akselerator efisiensi teknis dan penjamin kualitas (*quality assurance*). AI tidak menggantikan logika sistem, melainkan membantu mengeksekusi instruksi dengan standar industri terbaik (clean code, migration berintegritas relasional, pengujian komprehensif, dan desain antarmuka yang modern serta terstandarisasi).
