<?php
include 'db.php';

// Jika sudah login, lempar ke dashboard
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['register'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // 1. Cek apakah username sudah ada di database?
    $check = $conn->query("SELECT * FROM users WHERE username='$u'");
    
    if ($check->num_rows > 0) {
        $err = "Username sudah dipakai, pilih yang lain.";
    } else {
        // 2. Jika belum ada, buat akun baru
        $passHash = password_hash($p, PASSWORD_DEFAULT);
        // Default role adalah 'user'
        $sql = "INSERT INTO users (username, password, role) VALUES ('$u', '$passHash', 'user')";
        
        if ($conn->query($sql)) {
            // Sukses daftar, arahkan ke login
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
            exit;
        } else {
            $err = "Terjadi kesalahan sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="card glass-card p-5" style="width: 400px;">
        <h3 class="text-center mb-4 fw-bold text-success">📝 Daftar Akun</h3>
        
        <?php if(isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>
        
        <form method="post">
            <div class="mb-3">
                <label>Username Baru</label>
                <input type="text" name="username" class="form-control" placeholder="Buat username unik" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100 py-2">Daftar Sekarang</button>
        </form>

        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="login.php" class="text-green fw-bold">Login disini</a></small>
        </div>
    </div>
</body>
</html>