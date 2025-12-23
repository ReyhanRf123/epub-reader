<?php
include 'db.php';
checkAuth();

// 1. SIMPAN HIGHLIGHT (via AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'save') {
    $uid = $_SESSION['user_id'];
    $bid = $_POST['book_id'];
    $range = $_POST['cfi_range'];
    
    // Cek duplikasi biar gak numpuk
    $check = $conn->query("SELECT id FROM highlights WHERE user_id=$uid AND cfi_range='$range'");
    if($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO highlights (user_id, book_id, cfi_range) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $uid, $bid, $range);
        $stmt->execute();
        echo "saved";
    }
    exit;
}

// 2. HAPUS HIGHLIGHT (via AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $uid = $_SESSION['user_id'];
    $range = $_POST['cfi_range'];
    
    $stmt = $conn->prepare("DELETE FROM highlights WHERE user_id=? AND cfi_range=?");
    $stmt->bind_param("is", $uid, $range);
    $stmt->execute();
    echo "deleted";
    exit;
}
?>