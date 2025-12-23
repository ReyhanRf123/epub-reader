<?php
include 'db.php';
checkAuth();

// 1. TAMBAH BOOKMARK
if (isset($_POST['add_bookmark'])) {
    $uid = $_SESSION['user_id'];
    $bid = $_POST['book_id'];
    $cfi = $_POST['cfi'];
    $note = $_POST['note']; // Catatan dari user
    
    // Jika user tidak isi catatan, pakai tanggal otomatis
    if(empty($note)) $note = "Bookmark " . date("H:i d/m");

    $stmt = $conn->prepare("INSERT INTO bookmarks (user_id, book_id, cfi, note) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $uid, $bid, $cfi, $note);
    $stmt->execute();
    
    // Kembali ke reader
    header("Location: reader.php?id=$bid");
    exit;
}

// 2. HAPUS BOOKMARK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $bid = $_GET['book_id'];
    
    // Pastikan yang dihapus punya user sendiri
    $conn->query("DELETE FROM bookmarks WHERE id=$id AND user_id=" . $_SESSION['user_id']);
    
    header("Location: reader.php?id=$bid");
    exit;
}
?>