# Expanse Tracker

[![PHP Version](https://img.shields.io/badge/php-8.2-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Expanse Tracker** adalah aplikasi web yang dirancang untuk membantu pengguna melacak pemasukan dan pengeluaran harian. Dibangun dengan PHP native dan menerapkan pola arsitektur **Model-View-Controller (MVC)**, aplikasi ini memisahkan logika bisnis dari antarmuka pengguna, membuatnya lebih terstruktur, mudah dikelola, dan skalabel.

---

## Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Panduan Instalasi & Konfigurasi](#️-panduan-instalasi--konfigurasi)
- [Struktur Proyek & Penjelasan Detail](#-struktur-proyek--penjelasan-detail)
- [Alur Kerja Aplikasi (Request Lifecycle)](#-alur-kerja-aplikasi-request-lifecycle)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

- **Dashboard Interaktif**: Visualisasi data keuangan dengan ringkasan total pemasukan, pengeluaran, saldo, dan diagram lingkaran pengeluaran berdasarkan kategori.
- **Manajemen Kategori**: Kemampuan untuk menambah dan melihat daftar kategori transaksi.
- **Pencatatan Transaksi**: Fitur untuk mencatat transaksi pemasukan dan pengeluaran secara detail.
- **Riwayat & Filter**: Menampilkan seluruh riwayat transaksi dengan opsi filter berdasarkan rentang tanggal, jenis transaksi (pemasukan/pengeluaran), dan kategori.
- **Notifikasi Real-time**: Sistem notifikasi _flash message_ untuk memberikan umpan balik langsung kepada pengguna setelah melakukan sebuah aksi.
- **Routing Dinamis**: URL yang bersih dan ramah pengguna berkat sistem routing kustom.

---

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP
- **Database**: MySQL / MariaDB
- **Frontend**: HTML, CSS, JavaScript (dengan Chart.js untuk visualisasi)
- **Dependency Manager**: Composer
- **Environment Variables**: `vlucas/phpdotenv`

---

## ⚙️ Panduan Instalasi & Konfigurasi

Untuk menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

#### 1. Prasyarat

- Web Server (e.g., XAMPP, WAMP, MAMP)
- PHP >= 7.4
- Composer
- Database (MySQL/MariaDB)

#### 2. Clone Repository

````bash
git clone [https://github.com/NAMA_USERNAME_ANDA/NAMA_REPO_ANDA.git](https://github.com/NAMA_USERNAME_ANDA/NAMA_REPO_ANDA.git)
cd NAMA_REPO_ANDA
3. Instal Dependensi
Jalankan perintah berikut di terminal untuk menginstal dependensi PHP yang dibutuhkan.

Bash

composer install
4. Konfigurasi Database
Buat sebuah database baru di phpMyAdmin (atau tools sejenis).

Impor file .sql yang berisi struktur tabel (jika tersedia) atau buat tabel secara manual sesuai kebutuhan model.

5. Konfigurasi Lingkungan
Proyek ini menggunakan file app/config/config.php untuk konfigurasi utama.
Sesuaikan detail koneksi database di app/config/config.php:

PHP

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'nama_database_anda');
Sesuaikan juga BASEURL agar sesuai dengan URL proyek Anda di lingkungan lokal.

PHP

define('BASEURL', 'http://localhost/folder_proyek_anda/public');
6. Jalankan Aplikasi
Arahkan document root dari web server virtual host Anda ke direktori public/.

Atau, buka browser dan akses http://localhost/folder_proyek_anda/public/.

📂 Struktur Proyek & Penjelasan Detail
Proyek ini mengikuti pola desain MVC untuk memastikan kode yang bersih dan terorganisir.

.
├── app/                  # Direktori utama aplikasi (logika inti)
│   ├── config/
│   │   └── config.php    # File konfigurasi (database, URL dasar)
│   ├── controllers/
│   │   ├── Dashboard.php # Mengelola logika untuk halaman dashboard
│   │   ├── Kategori.php  # Mengelola logika untuk CRUD kategori
│   │   └── Transaksi.php # Mengelola logika untuk CRUD transaksi
│   ├── core/
│   │   ├── App.php       # Kelas inti untuk routing URL
│   │   ├── Controller.php# Kelas dasar yang di-extend oleh semua controller
│   │   ├── Database.php  # Wrapper untuk koneksi database (PDO) dengan Singleton Pattern
│   │   ├── Flasher.php   # Mengelola session flash messages untuk notifikasi
│   │   └── Validator.php # Kelas untuk validasi data input
│   ├── models/
│   │   ├── Kategori_model.php  # Berinteraksi dengan tabel 'kategori' di database
│   │   └── Transaksi_model.php # Berinteraksi dengan tabel 'transaksi' di database
│   ├── views/
│   │   ├── dashboard/
│   │   │   └── index.php # Tampilan halaman dashboard
│   │   ├── kategori/
│   │   │   └── index.php # Tampilan halaman manajemen kategori
│   │   └── transaksi/
│   │       └── index.php # Tampilan halaman manajemen transaksi
│   └── init.php            # Bootstrap file, memuat semua file inti aplikasi
│
├── public/               # Satu-satunya direktori yang dapat diakses publik
│   ├── css/              # File-file CSS
│   ├── js/               # File-file JavaScript
│   ├── .htaccess         # Mengarahkan semua request ke index.php
│   └── index.php         # Entry point (titik masuk) aplikasi
│
├── vendor/               # Direktori untuk dependensi Composer
│
# Expanse Tracker

[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Expanse Tracker** adalah aplikasi web yang dirancang untuk membantu pengguna melacak pemasukan dan pengeluaran harian. Dibangun dengan PHP native dan menerapkan pola arsitektur **Model-View-Controller (MVC)**, aplikasi ini memisahkan logika bisnis dari antarmuka pengguna, membuatnya lebih terstruktur, mudah dikelola, dan skalabel.

---

## Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Panduan Instalasi & Konfigurasi](#-panduan-instalasi--konfigurasi)
- [Struktur Proyek & Penjelasan Detail](#-struktur-proyek--penjelasan-detail)
- [Alur Kerja Aplikasi (Request Lifecycle)](#-alur-kerja-aplikasi-request-lifecycle)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

- **Dashboard Interaktif**: Visualisasi data keuangan dengan ringkasan total pemasukan, pengeluaran, saldo, dan diagram lingkaran pengeluaran berdasarkan kategori.
- **Manajemen Kategori**: Kemampuan untuk menambah dan melihat daftar kategori transaksi.
- **Pencatatan Transaksi**: Fitur untuk mencatat transaksi pemasukan dan pengeluaran secara detail.
- **Riwayat & Filter**: Menampilkan seluruh riwayat transaksi dengan opsi filter berdasarkan rentang tanggal, jenis transaksi (pemasukan/pengeluaran), dan kategori.
- **Notifikasi Real-time**: Sistem notifikasi _flash message_ untuk memberikan umpan balik langsung kepada pengguna setelah melakukan sebuah aksi.
- **Routing Dinamis**: URL yang bersih dan ramah pengguna berkat sistem routing kustom.

---

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP
- **Database**: MySQL / MariaDB
- **Frontend**: HTML, CSS, JavaScript (dengan Chart.js untuk visualisasi)
- **Dependency Manager**: Composer
- **Environment Variables**: `vlucas/phpdotenv`

---

## ⚙️ Panduan Instalasi & Konfigurasi

Untuk menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

1. Prasyarat

	- Web Server (mis. XAMPP, WAMP, MAMP)
	- PHP >= 7.4
	- Composer
	- Database (MySQL/MariaDB)

2. Clone repository

```bash
git clone https://github.com/NAMA_USERNAME_ANDA/NAMA_REPO_ANDA.git
cd NAMA_REPO_ANDA
````

3. Instal dependensi

Jalankan perintah berikut di terminal untuk menginstal dependensi PHP yang dibutuhkan:

```bash
composer install
```

4. Konfigurasi database

- Buat sebuah database baru di phpMyAdmin (atau tools sejenis).
- Impor file `.sql` yang berisi struktur tabel (jika tersedia) atau buat tabel secara manual sesuai kebutuhan model.

5. Konfigurasi lingkungan

Proyek ini menggunakan file `app/config/config.php` untuk konfigurasi utama. Sesuaikan detail koneksi database di `app/config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'nama_database_anda');
```

Sesuaikan juga `BASEURL` agar sesuai dengan URL proyek Anda di lingkungan lokal:

```php
define('BASEURL', 'http://localhost/folder_proyek_anda/public');
```

6. Jalankan aplikasi

- Arahkan document root dari web server/virtual host Anda ke direktori `public/`.
- Atau, buka browser dan akses `http://localhost/folder_proyek_anda/public/`.

---

## 📂 Struktur Proyek & Penjelasan Detail

Proyek ini mengikuti pola desain MVC untuk memastikan kode yang bersih dan terorganisir.

```
.
├── app/                  # Direktori utama aplikasi (logika inti)
│   ├── config/
│   │   └── config.php    # File konfigurasi (database, URL dasar)
│   ├── controllers/
│   │   ├── Dashboard.php # Mengelola logika untuk halaman dashboard
│   │   ├── Kategori.php  # Mengelola logika untuk CRUD kategori
│   │   └── Transaksi.php # Mengelola logika untuk CRUD transaksi
│   ├── core/
│   │   ├── App.php       # Kelas inti untuk routing URL
│   │   ├── Controller.php# Kelas dasar yang di-extend oleh semua controller
│   │   ├── Database.php  # Wrapper untuk koneksi database (PDO) dengan Singleton Pattern
│   │   ├── Flasher.php   # Mengelola session flash messages untuk notifikasi
│   │   └── Validator.php # Kelas untuk validasi data input
│   ├── models/
│   │   ├── Kategori_model.php  # Berinteraksi dengan tabel 'kategori' di database
│   │   └── Transaksi_model.php # Berinteraksi dengan tabel 'transaksi' di database
│   ├── views/
│   │   ├── dashboard/
│   │   │   └── index.php # Tampilan halaman dashboard
│   │   ├── kategori/
│   │   │   └── index.php # Tampilan halaman manajemen kategori
│   │   └── transaksi/
│   │       └── index.php # Tampilan halaman manajemen transaksi
│   └── init.php            # Bootstrap file, memuat semua file inti aplikasi
│
├── public/               # Satu-satunya direktori yang dapat diakses publik
│   ├── css/              # File-file CSS
│   ├── js/               # File-file JavaScript
│   ├── .htaccess         # Mengarahkan semua request ke index.php
│   └── index.php         # Entry point (titik masuk) aplikasi
│
├── vendor/               # Direktori untuk dependensi Composer
│
└── composer.json         # Mendefinisikan dependensi proyek
```

---

## 💡 Alur Kerja Aplikasi (Request Lifecycle)

1. Entry point: Semua permintaan HTTP (HTTP Request) dari pengguna masuk melalui `public/index.php`.
2. Konfigurasi `.htaccess`: File `public/.htaccess` akan menulis ulang URL dan mengarahkan semua permintaan yang bukan file atau direktori fisik ke `public/index.php`.
3. Inisialisasi (Bootstrap): `public/index.php` memuat `app/init.php`, yang kemudian memuat semua kelas inti (core), konfigurasi (config), dan dependensi dari `vendor/`.
4. Routing: Objek dari kelas `App` di `app/core/App.php` diinstansiasi. Kelas ini mem-parsing URL menjadi: Controller, Method, dan Parameter.

Contoh: `http://localhost/expanse_tracker/public/transaksi/hapus/1` akan diterjemahkan menjadi:

- Controller: `Transaksi`
- Method: `hapus`
- Parameter: `[1]`

5. Eksekusi Controller: App memuat file controller yang sesuai (mis. `app/controllers/Transaksi.php`) dan memanggil method yang diminta (mis. `hapus()`) sambil meneruskan parameter.
6. Interaksi dengan Model: Controller memanggil method di Model (mis. `$this->model('Transaksi_model')->hapusDataTransaksi(1)`) untuk operasi database.
7. Menampilkan View: Setelah logika selesai, Controller memuat View yang sesuai dan melewatkan data untuk dirender ke pengguna.

---

## 🤝 Kontribusi

Kontribusi sangat kami harapkan! Jika Anda ingin berkontribusi, silakan:

- Fork repositori ini.
- Buat branch baru untuk fitur Anda: `git checkout -b fitur/NamaFitur`.
- Lakukan commit terhadap perubahan Anda: `git commit -m "Menambahkan Fitur Baru"`.
- Buat Pull Request.

---

## 📜 Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT.
