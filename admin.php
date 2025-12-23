<?php
include 'db.php';
checkAuth();
checkAdmin();

// Hapus Buku
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM books WHERE id=$id");
    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar p-3 d-flex flex-column" style="width: 250px;">
            <h4 class="mb-4">🛡️ Admin Panel</h4>
            <a href="#" class="text-white text-decoration-none mb-3"><i class="fas fa-book"></i> Manajemen Buku</a>
            <a href="logout.php" class="mt-auto btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <div class="p-4 w-100">
            <div class="glass-card p-4">
                <h3>Daftar Semua Buku Uploaded</h3>
                <table class="table table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pengunggah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT books.*, users.username FROM books JOIN users ON books.user_id = users.id";
                        $res = $conn->query($sql);
                        while($row = $res->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['title'] ?></td>
                            <td><span class="badge bg-info"><?= $row['category'] ?></span></td>
                            <td><?= $row['username'] ?></td>
                            <td>
                                <a href="reader.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus buku ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>