<?php
include 'db.php';
checkAuth();

// Admin redirect
if ($_SESSION['role'] == 'admin') { header("Location: admin.php"); exit; }

// Filter Logic
$cat_filter = "";
$active_cat = "All";
if (isset($_GET['cat'])) {
    $c = $conn->real_escape_string($_GET['cat']);
    $cat_filter = "WHERE category = '$c'";
    $active_cat = $c;
}

// Data Buku
$sql = "SELECT users.username, books.* FROM books 
        JOIN users ON books.user_id = users.id 
        $cat_filter 
        ORDER BY books.uploaded_at DESC";
$books = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['curr_lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['library'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent pt-3 px-3 px-md-5">
        <div class="container-fluid glass-card py-2">
            <a class="navbar-brand text-dark fw-bold" href="index.php">
                <i class="fas fa-book-reader text-primary"></i> <?= $lang['library'] ?>
            </a>
            
            <div class="d-flex align-items-center gap-2">
                
                <button onclick="toggleDarkMode()" class="btn btn-outline-secondary btn-sm rounded-circle" title="<?= $lang['dark_mode'] ?>">
                    <i class="fas fa-moon"></i>
                </button>

                <div class="btn-group btn-group-sm">
                    <a href="?lang=id" class="btn <?= $_SESSION['curr_lang'] == 'id' ? 'btn-primary' : 'btn-outline-primary' ?>">ID</a>
                    <a href="?lang=en" class="btn <?= $_SESSION['curr_lang'] == 'en' ? 'btn-primary' : 'btn-outline-primary' ?>">EN</a>
                </div>

                <div class="d-none d-md-block text-dark small ms-2">
                    <?= $lang['welcome'] ?>, <b><?= htmlspecialchars($_SESSION['user']) ?></b>
                </div>
                
                <button class="btn btn-success btn-sm rounded-pill px-3 ms-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-cloud-upload-alt"></i> <span class="d-none d-md-inline"><?= $lang['upload'] ?></span>
                </button>
                
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill ms-1">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> Upload Berhasil!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="d-flex gap-2 overflow-auto pb-3 mb-2">
            <a href="index.php" class="btn <?= $active_cat == 'All' ? 'btn-dark' : 'btn-light' ?> rounded-pill px-4">All</a>
            <?php 
            $categories = ['Teknik Informatika', 'Sistem Informasi', 'Biologi', 'Fisika', 'Matematika', 'Agribisnis', 'Kimia', 'Teknik Pertambangan'];
            foreach($categories as $cat): 
            ?>
                <a href="?cat=<?= $cat ?>" class="btn <?= $active_cat == $cat ? 'btn-dark' : 'btn-light' ?> rounded-pill text-nowrap">
                    <?= $cat ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="row g-4">
            <?php if($books->num_rows == 0): ?>
                <div class="col-12 text-center mt-5 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3"></i>
                    <h5><?= $lang['no_book'] ?></h5>
                </div>
            <?php endif; ?>

            <?php while($b = $books->fetch_assoc()): ?>
            <div class="col-6 col-md-4 col-lg-3">
    <div class="card book-card h-100 shadow-sm">
        
        <div class="book-icon-wrapper">
            <i class="fas fa-book fa-4x text-primary opacity-75"></i>
        </div>
        
        <div class="card-body d-flex flex-column p-3">
            <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($b['title']) ?>">
                <?= htmlspecialchars($b['title']) ?>
            </h6>
            
            <small class="text-muted mb-3 d-block">
                <span class="badge bg-light text-dark border"><?= $b['category'] ?></span>
            </small>
            
            <?php if(isset($b['progress_percent'])): ?>
                <div class="mb-3">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: <?= (int)$b['progress_percent'] ?>%"></div>
                    </div>
                <small class="text-muted" style="font-size: 0.65rem;">
                    <?= (int)$b['progress_percent'] ?>% Selesai
                </small>
            </div>
            <?php endif; ?>
            
            <a href="reader.php?id=<?= $b['id'] ?>" class="btn btn-outline-primary btn-sm mt-auto w-100 rounded-pill">
                <?= $lang['read'] ?>
            </a>
        </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-primary"><?= $lang['upload_modal_title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label class="form-label small fw-bold"><?= $lang['title_label'] ?></label>
                        <input type="text" name="title" class="form-control mb-2" required>
                        <label class="form-label small fw-bold"><?= $lang['category'] ?></label>
                        <select name="category" class="form-select mb-2" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat ?>"><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label class="form-label small fw-bold"><?= $lang['file_label'] ?></label>
                        <input type="file" name="epub_file" class="form-control" accept=".epub" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary w-100"><?= $lang['btn_upload'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 1. Cek apakah user sebelumnya milih dark mode?
        if(localStorage.getItem('theme') === 'dark'){
            document.body.classList.add('dark-mode');
        }

        // 2. Fungsi Toggle saat tombol diklik
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            
            // Simpan pilihan user ke memori browser
            if(document.body.classList.contains('dark-mode')){
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }
    </script>
</body>
</html>