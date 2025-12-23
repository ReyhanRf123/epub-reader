# 📚 FST ePub Reader - Web Based Application

![PHP](https://img.shields.io/badge/Backend-PHP-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange?style=for-the-badge&logo=mysql)
![Frontend](https://img.shields.io/badge/Frontend-Bootstrap%205-purple?style=for-the-badge&logo=bootstrap)
![Library](https://img.shields.io/badge/Library-ePub.js-yellow?style=for-the-badge)

Aplikasi Web **ePub Reader Interaktif** yang dibangun untuk memenuhi tugas kuliah Metode Penelitian / Pemrograman Web Lanjut Semester 5. Aplikasi ini memungkinkan mahasiswa untuk mengunggah, membaca, dan mengelola buku digital format `.epub` dengan antarmuka modern.

## ✨ Fitur Utama

### 🚀 Core Features
* **Authentication:** Sistem Login & Register (Multi-role: Admin & User).
* **Book Management:** Upload buku `.epub` berdasarkan kategori Program Studi (FST).
* **ePub Reader:** Membaca buku langsung di browser tanpa plugin tambahan.
* **Localization (i18n):** Dukungan dwi-bahasa (Indonesia / Inggris).

### 🌟 Fitur Unggulan (Bonus)
* **🔍 Advanced Search:** Pencarian kata dalam buku dengan navigasi & highlight (hijau).
* **🖍️ Highlights:** Menandai teks penting (stabilo kuning) dan tersimpan di database.
* **🔖 Bookmarks:** Menandai halaman tertentu dengan catatan pribadi.
* **🌙 Dark Mode:** Mode gelap yang sinkron antara dashboard dan area baca.
* **📊 Reading Progress:** Menyimpan persentase dan lokasi terakhir dibaca secara otomatis.
* **🎨 Modern UI:** Desain Glassmorphism menggunakan CSS custom & Bootstrap 5.

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP Native (7.4 / 8.0+)
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3 (Glassmorphism), JavaScript
* **Framework CSS:** Bootstrap 5.3
* **Library:** [epub.js](https://github.com/futurepress/epub.js/) (Rendering Engine)
* **Server:** XAMPP (Apache)

## 💻 Cara Instalasi

Ikuti langkah ini untuk menjalankan proyek di komputer lokal:

1.  **Clone / Download** repository ini.
    ```bash
    git clone [https://github.com/username-kamu/fst-epub-reader.git](https://github.com/username-kamu/fst-epub-reader.git)
    ```
    *(Atau download ZIP dan ekstrak di folder `htdocs`)*.

2.  **Setup Database:**
    * Buka `phpMyAdmin` (http://localhost/phpmyadmin).
    * Buat database baru dengan nama `epub_reader`.
    * Import file `epub_reader.sql` yang ada di dalam folder proyek ini.

3.  **Konfigurasi (Opsional):**
    * Cek file `db.php` jika kamu menggunakan password database selain kosong.

4.  **Jalankan:**
    * Buka browser dan akses `http://localhost/fst-epub-reader`
    * **Akun Admin Default:**
        * User: `admin`
        * Pass: `admin123`

**Dibuat oleh:** Reyhan Ribelfa 
