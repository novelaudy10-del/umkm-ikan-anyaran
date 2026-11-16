# Sistem Informasi Penjualan & Pemesanan UMKM Ikan Anyaran

Aplikasi web e-commerce untuk pengelolaan katalog produk, keranjang belanja, pemesanan pelanggan, serta rekapitulasi penjualan UMKM Ikan Anyaran yang telah dilengkapi pengamanan aplikasi web (*Secure Coding*).

- **URL Aplikasi Online:** https://ikananyaran.my.id
- **URL Panel Administrator:** https://ikananyaran.my.id

## Fitur Utama
- **Autentikasi & Otorisasi:** Registrasi dan login pelanggan serta administrator dengan kontrol akses berbasis peran (*Role-Based Access Control*).
- **Pengelolaan Data (CRUD):** Manajemen produk ikan (tambah, lihat, ubah, hapus beserta unggah foto produk) dan pemrosesan status pesanan oleh administrator.
- **Laporan & Transaksi:** Keranjang belanja, checkout pemesanan, riwayat transaksi pelanggan, serta rekapitulasi penjualan pada dasbor panel admin.

## Teknologi yang Digunakan
- **Backend:** PHP 8 Native (`mysqli`)
- **Database:** MariaDB / MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Infrastruktur & Keamanan:** Shared Hosting (cPanel), Domain `.my.id`, SSL/TLS (Let's Encrypt)

## Cara Instalasi & Menjalankan Secara Lokal
1. Unduh atau *clone* repositori ini ke dalam direktori `htdocs` pada XAMPP.
2. Buat basis data baru bernama `db_ikan_anyaran` di phpMyAdmin, lalu impor file `db_ikan_anyaran.sql`.
3. Salin file `.env.example` menjadi `.env` di folder utama proyek, lalu isi sesuai kredensial basis data lokal Anda.
4. Jalankan modul Apache dan MySQL di XAMPP, kemudian akses `http://localhost/ikan_anyaran` melalui peramban.

## Konfigurasi Environment Variable (`.env`)
```ini
DB_HOST=localhost
DB_USER=username_database_saya
DB_PASS=password_database_saya
DB_NAME=nama_database_saya
```

## Ringkasan Penggunaan AI Agent & Self-Testing Keamanan
- **AI Agent yang Digunakan:** **Google Antigravity** (IDE Coding Agent) dan **Gemini** untuk audit kode serta perancangan perbaikan keamanan secara bertahap tanpa mengubah struktur UI.
- **Bagian yang Dibantu AI Agent:**
  1. Modul Autentikasi (`registrasi.php`, `login.php`, `admin/login.php`): migrasi hashing kata sandi ke Bcrypt dan pengamanan kueri login.
  2. Modul Transaksi (`keranjang.php`, `checkout.php`, `riwayat.php`): konversi kueri SQL ke *Prepared Statements* dan sanitasi output HTML.
  3. Modul Panel Admin (`admin/`): pengamanan kueri CRUD, sanitasi output, dan validasi format unggah gambar.
- **Contoh Prompt Utama:**
  > *"Fokus hanya pada file registrasi.php, login.php, dan admin/login.php (JANGAN ubah tampilan UI): ubah hashing password menjadi Bcrypt menggunakan password_hash() dan password_verify(), ubah kueri SQL menjadi Prepared Statements, serta tambahkan session_regenerate_id(true) saat login sukses."*
- **Hasil Self-Testing Keamanan:**
  - **Enkripsi Kata Sandi:** Seluruh password tersimpan dalam format hash Bcrypt (`$2y$10$...`).
  - **Anti-SQL Injection & XSS:** Form input kebal terhadap pengujian payload `' OR '1'='1` dan `<script>alert('XSS')</script>`.
  - **Validasi Upload & Proteksi Server:** Unggahan dibatasi khusus format gambar valid, akses `.env` diblokir via `.htaccess`, dan protokol HTTPS aktif.
