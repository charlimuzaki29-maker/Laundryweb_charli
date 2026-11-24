# DOKUMENTASI PENGUMPULAN TUGAS GOOGLE FORM
## SISTEM INFORMASI MANAJEMEN LAUNDRY BERBASIS WEB (LARAVEL + MYSQL + TAILWIND CSS)

---

## 1. LINK GITHUB

- **URL Repository**: `https://github.com/charlimuzaki29-maker/Laundryweb_charli`
- **Branch Utama**: `main`

### Langkah Push ke Repository GitHub:
Pastikan repository di akun GitHub `charlimuzaki29-maker` sudah dibuat dengan nama `Laundryweb_charli`:
```bash
git remote set-url origin https://github.com/charlimuzaki29-maker/Laundryweb_charli.git
git branch -M main
git push -u origin main
```

---

## 2. JELASKAN MIGRATION YANG DIBUAT (BESERTA SS & PENJELASAN)

### A. Screenshot Migration & Struktur Database
- **File Screenshot**: `screenshots/00_migration_ss.png`
*(File gambar siap diunggah ke Google Drive)*

### B. Penjelasan Struktur Tabel & Relasi Database (MySQL)

Database yang digunakan bernama `laundry_db` dengan mesin penyimpanan MySQL InnoDB. Skema database dirancang dengan normalisasi relasional untuk menjamin integritas data transaksi dan harga layanan:

#### 1. Tabel Layanan (`layanans`)
- **Tujuan**: Menyimpan data master paket layanan cuci yang disediakan usaha laundry.
- **File Migration**: `database/migrations/2026_11_24_021212_create_layanans_table.php`
- **Struktur Kolom**:
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment): ID unik paket layanan.
  - `nama_layanan` (VARCHAR 255): Nama jenis layanan cuci (*Reguler*, *Ekspres*, *Superkilat*).
  - `harga_per_kg` (DECIMAL 12,2): Tarif layanan per kilogram cucian.
  - `estimasi_waktu` (VARCHAR 255): Durasi pengerjaan cucian (contoh: *3-4 hari*, *1-2 hari*, *5 jam selesai*).
  - `created_at` & `updated_at` (TIMESTAMP): Pencatatan waktu dibuat dan diubah.

#### 2. Tabel Transaksi (`transaksis`)
- **Tujuan**: Mencatat riwayat transaksi cucian pelanggan, berat, total biaya, dan status pengerjaan.
- **File Migration**: `database/migrations/2026_11_24_021215_create_transaksis_table.php`
- **Struktur Kolom**:
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment): ID unik transaksi / nomor nota.
  - `layanan_id` (BIGINT UNSIGNED, Foreign Key): Menghubungkan transaksi ke tabel `layanans(id)`. Dilengkapi konstrain `onDelete('cascade')` untuk menjaga integritas data.
  - `nama_pelanggan` (VARCHAR 255): Nama pelanggan yang memesan cucian.
  - `no_hp` (VARCHAR 255): Nomor telepon / kontak WhatsApp pelanggan.
  - `berat` (DECIMAL 8,2): Berat cucian dalam satuan kilogram (contoh: *3.50 kg*).
  - `total_tarif` (DECIMAL 12,2): Hasil perhitungan otomatis dari `berat * harga_per_kg`.
  - `status` (ENUM: `'Masuk'`, `'Proses'`, `'Selesai'`, Default: `'Masuk'`): Status alur operasional pengerjaan cucian.
  - `created_at` & `updated_at` (TIMESTAMP): Waktu transaksi masuk dan diperbarui.

#### 3. Tabel Pengguna & Autentikasi (`users`)
- **File Migration**: `database/migrations/0001_01_01_000000_create_users_table.php`
- **Penambahan Kolom Role**:
  - `role` (ENUM: `'admin'`, `'kasir'`, Default: `'kasir'`): Menentukan hak akses pengguna di sistem.

#### 4. Relasi Eloquent ORM
- **One-to-Many**: `Layanan` memiliki banyak `Transaksi` (`$this->hasMany(Transaksi::class)`).
- **Many-to-One**: Setiap `Transaksi` terikat wajib pada satu `Layanan` (`$this->belongsTo(Layanan::class)`).

---

## 3. SCREENSHOT LOGIN & REGISTER (DESKTOP, TAB, HP) SERTA PERBEDAAN ROLE ADMIN & KASIR

### A. Daftar File Screenshot
1. **Login Desktop**: `screenshots/01_login_desktop.png` (Ukuran: 1280 × 800)
2. **Login Tab**: `screenshots/01_login_tab.png` (Ukuran: 768 × 1024)
3. **Login HP**: `screenshots/01_login_hp.png` (Ukuran: 375 × 812)
4. **Register Desktop**: `screenshots/02_register_desktop.png` (Ukuran: 1280 × 800)
5. **Register Tab**: `screenshots/02_register_tab.png` (Ukuran: 768 × 1024)
6. **Register HP**: `screenshots/02_register_hp.png` (Ukuran: 375 × 812)

### B. Penjelasan Perbedaan Hak Akses Role (Admin vs Kasir)

Aplikasi menerapkan sistem pembatasan hak akses berbasis peran (*Role-Based Access Control / RBAC*) yang ditegakkan secara ketat melalui Middleware Laravel (`RoleMiddleware.php`):

| Fitur / Hak Akses | Role Admin (`admin@laundry.com`) | Role Kasir (`kasir@laundry.com`) | Mekanisme Keamanan |
|---|---|---|---|
| **Login ke Sistem** | Ya (Full Access) | Ya (Operational Access) | Laravel `Auth::attempt()` & Session |
| **Akses Dashboard** | Ya (Melihat ringkasan omset & statistik) | Ya (Melihat operasional & order aktif) | Route Middleware `'auth'` |
| **Lihat Daftar Layanan** | Ya | Ya (Sebagai acuan tarif operasional) | Route `layanan.index` |
| **Tambah Layanan Baru** | **YA** (Dapat menambah paket cuci) | **DITOLAK (403 Forbidden)** | Middleware `'role:admin'` & FormRequest |
| **Ubah / Edit Layanan** | **YA** (Dapat mengubah tarif & estimasi) | **DITOLAK (403 Forbidden)** | Middleware `'role:admin'` & FormRequest |
| **Hapus Layanan** | **YA** (Dapat menghapus jenis layanan) | **DITOLAK (403 Forbidden)** | Middleware `'role:admin'` |
| **Catat Transaksi Baru** | Ya | Ya (Tugas utama pencatatan order) | Route `transaksi.create` / `store` |
| **Kalkulasi Otomatis** | Ya | Ya | Frontend JS + Backend Validation |
| **Update Status Pengerjaan** | Ya | Ya (*Masuk* &rarr; *Proses* &rarr; *Selesai*) | Route `transaksi.update-status` |
| **Rekap Transaksi & Laporan** | Ya | Ya | Route `transaksi.index` |

---

## 4. SCREENSHOT BAGIAN CRUD LAYANAN (DESKTOP, TAB, HP) BESERTA PENJELASAN

### A. Daftar File Screenshot
1. **Daftar Layanan Desktop**: `screenshots/03_crud_layanan_index_desktop.png`
2. **Daftar Layanan Tab**: `screenshots/03_crud_layanan_index_tab.png`
3. **Daftar Layanan HP**: `screenshots/03_crud_layanan_index_hp.png`
4. **Form Tambah Layanan Desktop**: `screenshots/04_crud_layanan_tambah_desktop.png`
5. **Form Tambah Layanan Tab**: `screenshots/04_crud_layanan_tambah_tab.png`
6. **Form Tambah Layanan HP**: `screenshots/04_crud_layanan_tambah_hp.png`
7. **Form Ubah Layanan Desktop**: `screenshots/05_crud_layanan_ubah_desktop.png`
8. **Form Ubah Layanan Tab**: `screenshots/05_crud_layanan_ubah_tab.png`
9. **Form Ubah Layanan HP**: `screenshots/05_crud_layanan_ubah_hp.png`

### B. Penjelasan Alur CRUD Layanan
1. **Create (Tambah Layanan)**: Admin mengisi formulir nama layanan, tarif per kg, dan estimasi waktu pengerjaan. Form divalidasi oleh `StoreLayananRequest` (memastikan tarif berupa angka non-negatif dan field tidak kosong).
2. **Read (Lihat Layanan)**: Disajikan dalam grid kartu responsif 3 kolom di desktop dan kartu vertikal di ponsel. Menampilkan nama layanan, harga per kg berformat Rupiah yang menonjol, estimasi pengerjaan, dan jumlah transaksi yang telah menggunakan paket tersebut (`withCount('transaksis')`).
3. **Update (Ubah Layanan)**: Admin dapat memperbarui informasi paket cuci. Data lama terisi otomatis di form, dan divalidasi oleh `UpdateLayananRequest`.
4. **Delete (Hapus Layanan)**: Admin dapat menghapus paket layanan cuci yang sudah tidak berlaku. Dilengkapi konfirmasi dialog JavaScript untuk mencegah penghapusan tidak disengaja.

#### Data Awal Seeder Layanan Sesuai Spesifikasi:
- **Reguler**: Rp 7.000 / kg (Estimasi: 3-4 hari)
- **Ekspres**: Rp 12.000 / kg (Estimasi: 1-2 hari)
- **Superkilat**: Rp 25.000 / kg (Estimasi: 5 jam selesai)

---

## 5. SCREENSHOT ORDER & REKAP ORDER (DESKTOP, TAB, HP) BESERTA PENJELASAN

### A. Daftar File Screenshot
1. **Form Order Baru Desktop**: `screenshots/06_order_baru_desktop.png`
2. **Form Order Baru Tab**: `screenshots/06_order_baru_tab.png`
3. **Form Order Baru HP**: `screenshots/06_order_baru_hp.png`
4. **Rekap Order & Laporan Desktop**: `screenshots/07_rekap_order_desktop.png`
5. **Rekap Order & Laporan Tab**: `screenshots/07_rekap_order_tab.png`
6. **Rekap Order & Laporan HP**: `screenshots/07_rekap_order_hp.png`
7. **Dashboard Desktop**: `screenshots/08_dashboard_desktop.png`
8. **Dashboard Tab**: `screenshots/08_dashboard_tab.png`
9. **Dashboard HP**: `screenshots/08_dashboard_hp.png`

### B. Penjelasan Fitur Order & Rekap Order
1. **Pencatatan Order Baru (`/transaksi/create`)**:
   - Petugas/Kasir menginput: Nama Pelanggan, Nomor HP/WhatsApp, memilih Jenis Layanan melalui dropdown yang terhubung ke database `layanans`, berat cucian (kg), dan status awal (*Masuk* atau *Proses*).
   - **Perhitungan Tarif Otomatis**: Dilengkapi modul JavaScript interaktif yang secara otomatis mengalikan `Berat (kg) * Tarif Layanan (Rp/kg)` secara real-time di layar. Backend di `TransaksiController@store` memvalidasi dan menghitung ulang nilai `total_tarif` secara matematis untuk menjamin keamanan dan akurasi keuangan.
2. **Alur Status Pengerjaan Cucian**:
   - Cucian masuk berstatus **Masuk** (Diterima).
   - Petugas mengklik tombol **Proses** untuk memulai pencucian.
   - Setelah cucian selesai disetrika dan dipacking, petugas mengklik tombol **Tandai Selesai**.
3. **Rekap Order & Laporan Keuangan (`/transaksi`)**:
   - **Bar Metrik Horizontal**: Menampilkan Total Pendapatan Akumulasi (Rp), Jumlah Transaksi Dalam Proses (dengan titik kuning), dan Jumlah Transaksi Selesai (dengan titik hijau).
   - **Filter & Pencarian**: Memfilter data berdasarkan status pengerjaan, jenis layanan, serta kolom pencarian nama pelanggan atau nomor HP.
   - **Tampilan Tabel & Responsivitas Mobile**: Pada layar desktop disajikan dalam tabel berbingkai tipis dan garis pembatas horizontal tanpa garis vertikal. Pada layar HP, otomatis bertransformasi menjadi tumpukan kartu rapi sehingga tombol aksi tidak terpotong.

---

## 6. LOG PENGGUNAAN AI (DOKUMENTASI LENGKAP)

Berikut adalah rekaman aktivitas dan log interaksi prompt AI dari tahap perancangan awal hingga pengujian akhir:

### Fase 1: Perancangan Arsitektur & Inisialisasi Backend
- **Prompt Pengguna**: Meminta pembangunan "Sistem Informasi Manajemen Laundry Berbasis Web" dengan tech stack wajib Laravel, MySQL, Tailwind CSS (Blade), 2 menu navigasi utama ("Data Layanan" & "Rekap Transaksi Laundry"), akun Admin & Kasir, CRUD Layanan, transaksi, dan rekap keuangan.
- **Tindakan AI**:
  - Menginisialisasi proyek Laravel di direktori kerja.
  - Mengonfigurasi koneksi MySQL ke database `laundry_db`.
  - Menginstal dependensi frontend Tailwind CSS v4 dan Vite.
  - Menginstal paket `laravel/boost`.

### Fase 2: Skema Database, Relasi, & Seeder
- **Tindakan AI**:
  - Membuat migration `create_layanans_table` dengan kolom `nama_layanan`, `harga_per_kg`, `estimasi_waktu`.
  - Membuat migration `create_transaksis_table` dengan foreign key `layanan_id` terikat ke `layanans`, `nama_pelanggan`, `no_hp`, `berat`, `total_tarif`, `status`.
  - Menambahkan kolom `role` (`admin`, `kasir`) pada tabel `users`.
  - Mengonfigurasi relasi Eloquent ORM di `Layanan.php` (`hasMany`) dan `Transaksi.php` (`belongsTo`).
  - Mengimplementasikan `LayananSeeder` (*Reguler*, *Ekspres*, *Superkilat*) dan `DatabaseSeeder` (*admin@laundry.com* dan *kasir@laundry.com*).
  - Menjalankan `php artisan migrate:fresh --seed`.

### Fase 3: Logika Bisnis, Middleware, & Form Requests
- **Tindakan AI**:
  - Membuat `RoleMiddleware` untuk validasi peran pengguna.
  - Membuat `AuthController` (login & logout session).
  - Membuat `LayananController` dengan proteksi middleware `role:admin` untuk create/store/edit/update/destroy.
  - Membuat `TransaksiController` untuk pencatatan transaksi baru dengan kalkulasi tarif otomatis (`berat * harga_per_kg`), pembaruan status pengerjaan, dan filter rekap keuangan.
  - Membuat Form Requests: `StoreLayananRequest`, `UpdateLayananRequest`, `StoreTransaksiRequest`.

### Fase 4: Pengujian Otomatis (PHPUnit)
- **Tindakan AI**:
  - Menulis 22 pengujian fitur otomatis (`AuthAndRoleTest`, `LayananCrudTest`, `TransaksiLaundryTest`, `ExampleTest`).
  - Menjalankan pengujian: **22 passed, 72 assertions, 0 failed**.
  - Menjalankan format cleaner `vendor/bin/pint`.

### Fase 5: Redesign UI/UX "Minimalist Top-Nav Monochrome"
- **Prompt Pengguna**: Redesign tampilan dengan gaya minimalis monokrom (ala Shadcn/Linear) berlatar `bg-zinc-50`.
- **Tindakan AI**:
  - Memperbarui layout menjadi Sticky Topbar dengan 2 menu navigasi utama.
  - Mengonfigurasi palet monokrom, tombol solid hitam, dan pil status pastel.

### Fase 6: Redesign UI/UX "Warm Stone Sidebar & Pure White Workspace"
- **Prompt Pengguna**: Redesign tampilan dengan gaya "Warm Stone Sidebar & Pure White Workspace" (`bg-[#F6F5F2]`, kanvas putih bersih, tombol `bg-emerald-900`, sidebar kiri desktop dan drawer mobile).
- **Tindakan AI**:
  - Mengubah layout utama menjadi Sidebar Kiri tetap di desktop dengan judul bersih "SIM Laundry" dan 2 menu navigasi.
  - Menambahkan drawer responsif mobile dengan tombol hamburger dan backdrop overlay.
  - Mengubah tombol aksi utama ke warna *Deep Forest Emerald*.
  - Menyederhanakan halaman login menjadi form login murni tanpa pemilih akun demo.
  - Menambahkan halaman registrasi akun kasir (`/register`).
  - Mengompilasi ulang aset via `npm run build`.

### Fase 7: Pengambilan Tangkapan Layar Multi-Device & Finalisasi Laporan
- **Prompt Pengguna**: Meminta pengerjaan seluruh soal Google Form, push ke repo, dan pembuatan dokumentasi.
- **Tindakan AI**:
  - Menginstal `puppeteer-core` dan menjalankan script otomatisasi `take_screenshots.js`.
  - Mengambil 24 screenshot beresolusi tinggi pada ukuran layar Desktop (1280x800), Tab (768x1024), dan HP (375x812).
  - Membuat screenshot skema migration database.
  - Menginisialisasi Git repository, menambahkan commit, dan menyusun panduan lengkap jawaban Google Form.

---

### PANDUAN MENGUNGGAH KE GOOGLE DRIVE:
1. Buat folder baru di Google Drive Anda, misalnya: `SIM_Laundry_Tugas`.
2. Unggah seluruh isi folder `screenshots/` ke dalam folder Google Drive tersebut.
3. Anda juga dapat mengunggah file `DOKUMENTASI_PENGUMPULAN_GFORM.md` atau mengekspornya ke Google Docs.
4. Klik kanan folder di Google Drive &rarr; **Bagikan (Share)** &rarr; Ubah akses menjadi **"Siapa saja yang memiliki link dapat melihat (Anyone with the link can view)"**.
5. Salin link tersebut dan tempelkan ke pertanyaan Google Form yang meminta link Google Drive.
