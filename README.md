# 🏥 Sistem Peminjaman UKS PNP

## Aplikasi Web untuk Pengelolaan Peminjaman Fasilitas dan Perlengkapan UKS

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/mysql-%5E8.0-orange.svg)](https://www.mysql.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## 📌 Tentang Aplikasi

**Sistem Peminjaman UKS PNP** adalah aplikasi berbasis web yang dibuat untuk memudahkan pengelolaan peminjaman fasilitas dan perlengkapan Unit Kegiatan Seni (UKS) Politeknik Negeri Padang.

Aplikasi ini hadir sebagai solusi untuk membantu proses administrasi peminjaman agar lebih praktis, terstruktur, dan mudah dikelola.

Melalui sistem ini, pengelolaan data fasilitas, pengajuan peminjaman, verifikasi, serta pengembalian barang dapat dilakukan secara digital dalam satu aplikasi.

Dengan adanya sistem ini, diharapkan proses peminjaman menjadi lebih efisien dan pengelolaan fasilitas dapat dilakukan dengan lebih baik.

---

## 🎯 Tujuan Pengembangan

- Mempermudah proses peminjaman fasilitas UKS.
- Membantu pengelolaan inventaris dan data peminjaman.
- Mengurangi proses administrasi yang dilakukan secara manual.
- Memudahkan Admin dalam memantau aktivitas peminjaman.
- Meningkatkan keteraturan dan efisiensi pengelolaan fasilitas.

---

## 🚀 Fitur Aplikasi

### 🔑 Autentikasi dan Hak Akses

Sistem login untuk Admin dan Pengguna dengan akses halaman yang disesuaikan berdasarkan peran masing-masing.

### 📦 Manajemen Fasilitas

Pengelolaan data fasilitas dan perlengkapan, termasuk informasi barang yang tersedia untuk dipinjam.

### 📝 Peminjaman Fasilitas

Pengguna dapat mengajukan peminjaman fasilitas melalui sistem dengan mengisi informasi yang diperlukan.

### 📋 Pengelolaan Data Peminjaman

Admin dapat melihat dan mengelola data peminjaman yang masuk melalui aplikasi.

### ✅ Verifikasi Peminjaman

Membantu Admin dalam memeriksa pengajuan serta mengelola status peminjaman.

### 🔄 Pengembalian Fasilitas

Mendukung pengelolaan proses pengembalian barang yang telah dipinjam.

### 📊 Dashboard Admin

Menyediakan halaman pengelolaan untuk membantu Admin memantau dan mengatur aktivitas sistem.

### 🔔 Notifikasi Interaktif

Menggunakan notifikasi untuk memberikan informasi dan konfirmasi terhadap aktivitas tertentu di dalam aplikasi.

---

## 💻 Teknologi

| Teknologi | Penggunaan |
| --- | --- |
| PHP Native | Logika backend dan pemrosesan aplikasi |
| MySQL | Penyimpanan data |
| PDO | Koneksi dan interaksi dengan database |
| HTML5 | Struktur halaman web |
| CSS3 | Desain dan tampilan antarmuka |
| JavaScript | Interaksi dan fungsionalitas halaman |
| SweetAlert2 | Notifikasi interaktif |
| XAMPP | Lingkungan server lokal |

---

## 📥 Instalasi dan Penggunaan

Berikut panduan untuk menjalankan aplikasi secara lokal menggunakan XAMPP.

### Persyaratan

Pastikan perangkat telah memiliki:

- [XAMPP](https://www.apachefriends.org/)
- [Git](https://git-scm.com/)
- Web browser
- Visual Studio Code atau editor kode lainnya

### 1. Clone Repository

Jalankan perintah berikut melalui terminal:

```bash
git clone https://github.com/zahriadnannst/sistem-peminjaman-uks-pnp.git
```

### 2. Siapkan Folder Project

Masuk ke folder project:

```bash
cd sistem-peminjaman-uks-pnp
```

Pastikan folder project berada di dalam direktori `htdocs` XAMPP.

### 3. Konfigurasi Database

1. Jalankan Apache dan MySQL melalui XAMPP.
2. Buka phpMyAdmin melalui browser.
3. Buat database dengan nama `peminjaman_uks`.
4. Import file database yang tersedia di repository.
5. Sesuaikan konfigurasi koneksi database pada aplikasi.

### 4. Jalankan Aplikasi

Buka browser dan akses:

```text
http://localhost/sistem-peminjaman-uks-pnp/
```

Jika konfigurasi server dan database sudah benar, aplikasi dapat diakses melalui browser.

---

## 🔐 Keamanan

Untuk menjaga keamanan aplikasi dan data:

- Jangan mengunggah password atau kredensial database ke repository.
- Hindari mengunggah data pribadi pengguna dan dokumen sensitif.
- Gunakan konfigurasi database yang sesuai dengan lingkungan lokal.

---

## 📄 Lisensi

Project **Sistem Peminjaman UKS PNP** menggunakan [MIT License](LICENSE).

Lisensi ini memungkinkan penggunaan, modifikasi, dan distribusi software dengan tetap menyertakan pemberitahuan hak cipta dan lisensi.

---

**Sistem Peminjaman UKS PNP**

Digitalisasi pengelolaan peminjaman fasilitas untuk proses yang lebih praktis, terorganisir, dan efisien.