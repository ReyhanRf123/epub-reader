<?php
session_start();
$conn = new mysqli("localhost", "root", "", "epub_reader");

// Fungsi Cek Login
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

// Fungsi Cek Admin
function checkAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: index.php"); // Tendang balik ke halaman user
        exit;
    }
}
?>

<?php

// --- LOGIKA BAHASA ---

// 1. Cek apakah user klik ganti bahasa?
if (isset($_GET['lang'])) {
    $_SESSION['curr_lang'] = $_GET['lang'];
}

// 2. Set default bahasa (Indonesia) jika belum ada
if (!isset($_SESSION['curr_lang'])) {
    $_SESSION['curr_lang'] = 'id';
}

// 3. Definisi Kamus Kata (Dictionary)
$dictionary = [
    'id' => [
        'welcome' => 'Selamat Datang',
        'library' => 'Perpustakaan FST',
        'upload' => 'Unggah Buku',
        'read' => 'Baca Sekarang',
        'category' => 'Kategori',
        'logout' => 'Keluar',
        'search_placeholder' => 'Cari kata...',
        'back' => 'Kembali',
        'dark_mode' => 'Mode Gelap',
        'login_title' => 'Masuk ke Sistem',
        'username' => 'Nama Pengguna',
        'password' => 'Kata Sandi',
        'no_book' => 'Belum ada buku. Unggah sekarang!',
        'upload_modal_title' => 'Unggah ePub Baru',
        'title_label' => 'Judul Buku',
        'file_label' => 'File Buku (.epub)',
        'btn_close' => 'Tutup',
        'btn_upload' => 'Unggah'
    ],
    'en' => [
        'welcome' => 'Welcome',
        'library' => 'FST Library',
        'upload' => 'Upload Book',
        'read' => 'Read Now',
        'category' => 'Category',
        'logout' => 'Logout',
        'search_placeholder' => 'Search words...',
        'back' => 'Back',
        'dark_mode' => 'Dark Mode',
        'login_title' => 'System Login',
        'username' => 'Username',
        'password' => 'Password',
        'no_book' => 'No books yet. Upload now!',
        'upload_modal_title' => 'Upload New ePub',
        'title_label' => 'Book Title',
        'file_label' => 'Book File (.epub)',
        'btn_close' => 'Close',
        'btn_upload' => 'Upload'
    ]
];

// 4. Pilih bahasa aktif
$lang = $dictionary[$_SESSION['curr_lang']];
?>