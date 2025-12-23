<?php
include 'db.php';

// Jika sudah login, jangan kasih masuk halaman login lagi
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    
    // Cek User di Database
    $res = $conn->query("SELECT * FROM users WHERE username='$u'");
    
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        // Verifikasi Password
        if (password_verify($p, $row['password'])) {
            // Set Session
            $_SESSION['user'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            
            // Redirect sesuai Role (Admin / User)
            if($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $err = "Password salah!";
        }
    } else {
        $err = "Username tidak ditemukan. Silakan daftar dulu.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="glass-card auth-card animate__animated animate__fadeIn">
            <div class="text-center mb-4">
                <i class="fas fa-book-reader fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold">Welcome Back!</h3>
                <p class="text-muted">Silakan masuk untuk membaca</p>
            </div>

            <?php if(isset($err)) echo "<div class='alert alert-danger py-2 small'>$err</div>"; ?>

            <form method="post">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control border-start-0" placeholder="Username" required>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" placeholder="Password" required>
                    </div>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-2 mb-3 shadow">MASUK</button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Belum punya akun?</small><br>
                <a href="register.php" class="fw-bold text-decoration-none">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
</body>
</html>