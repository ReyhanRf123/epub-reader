<?php
// 1. Masukkan koneksi database dan fungsi helper
include 'db.php';

// 2. Cek apakah user sudah login. Jika belum, tendang ke login page.
checkAuth(); 

// 3. Proses Form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data text dari form
    $title = $_POST['title'];
    $category = $_POST['category'];
    
    // [PENTING] Ambil ID User yang sedang login dari Session
    // Variable ini diset saat login sukses di login.php
    $user_id = $_SESSION['user_id']; 

    // Konfigurasi File Upload
    $target_dir = "uploads/";
    
    // Cek apakah folder uploads ada, jika tidak buat dulu (untuk jaga-jaga)
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Rename file biar unik (pake waktu upload) biar gak bentrok kalau nama file sama
    $fileName = time() . "_" . basename($_FILES["epub_file"]["name"]);
    $target_file = $target_dir . $fileName;
    
    // Ambil ekstensi file (harus .epub)
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi Ekstensi
    if($fileType != "epub") {
        echo "<script>alert('Error: Hanya file .epub yang boleh diupload!'); window.location='index.php';</script>";
        exit;
    }

    // Proses Pindahkan File dari Temp ke Folder Uploads
    if (move_uploaded_file($_FILES["epub_file"]["tmp_name"], $target_file)) {
        
        // [MODIFIKASI UTAMA DISINI]
        // Kita masukkan user_id ke dalam query SQL
        $stmt = $conn->prepare("INSERT INTO books (user_id, title, category, file_path) VALUES (?, ?, ?, ?)");
        
        // bind_param: "isss" artinya (Integer, String, String, String)
        // Integer = user_id
        // String  = title, category, file_path
        $stmt->bind_param("isss", $user_id, $title, $category, $target_file);
        
        if ($stmt->execute()) {
            // Jika sukses, kembali ke dashboard
            header("Location: index.php?msg=success");
        } else {
            echo "Error Database: " . $stmt->error;
        }
        
    } else {
        echo "<script>alert('Gagal mengupload file ke server.'); window.location='index.php';</script>";
    }
}
?>