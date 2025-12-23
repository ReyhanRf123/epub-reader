<?php
include 'db.php';
checkAuth();

if (!isset($_GET['id'])) header("Location: index.php");
$id = $_GET['id'];
$uid = $_SESSION['user_id'];

// Data Buku
$book = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
if (!$book) { echo "Buku hilang."; exit; }

// Data Bookmark
$bookmarks = $conn->query("SELECT * FROM bookmarks WHERE user_id=$uid AND book_id=$id ORDER BY created_at DESC");

// Data Highlight JSON (Kuning/User)
$highlights = [];
$h_query = $conn->query("SELECT cfi_range FROM highlights WHERE user_id=$uid AND book_id=$id");
while($row = $h_query->fetch_assoc()) { $highlights[] = $row['cfi_range']; }
$json_highlights = json_encode($highlights);
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['curr_lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?></title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/epubjs/dist/epub.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* CSS Global untuk memaksa warna Hijau pada SVG Annotation */
        /* Ini menangani kasus jika highlight dirender di layer atas (Overlay) */
        g.search-current path, 
        g.search-current rect {
            fill: #00ff00 !important;
            fill-opacity: 0.5 !important;
            stroke: #00cc00 !important;
            stroke-width: 1px !important;
        }
    </style>
</head>
<body id="body">

<div class="reader-container">
    
    <div class="reader-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 35px; height: 35px; display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h6 class="m-0 fw-bold text-truncate" style="max-width: 200px;"><?= htmlspecialchars($book['title']) ?></h6>
                <small class="text-muted" style="font-size: 0.75rem;">Mode Baca</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            
            <div id="search-input-group" class="input-group input-group-sm d-none d-md-flex" style="width: 220px;">
                <input type="text" id="searchInput" class="form-control rounded-start-pill" placeholder="Cari kata...">
                <button class="btn btn-outline-primary rounded-end-pill" onclick="doSearch()"><i class="fas fa-search"></i></button>
            </div>

            <div id="search-nav-group" class="bg-light border rounded-pill px-2 py-1 align-items-center gap-2" style="display: none;">
                <span id="search-counter" class="small fw-bold text-success ms-2">0/0</span>
                <div class="vr mx-1"></div>
                <button class="btn btn-sm btn-link p-0 text-dark" onclick="prevResult()"><i class="fas fa-chevron-up"></i></button>
                <button class="btn btn-sm btn-link p-0 text-dark" onclick="nextResult()"><i class="fas fa-chevron-down"></i></button>
                <button class="btn btn-sm btn-link p-0 text-danger ms-2" onclick="closeSearch()"><i class="fas fa-times"></i></button>
            </div>

            <button class="btn btn-sm btn-light text-warning shadow-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#bookmarkListModal" title="Bookmark"><i class="fas fa-bookmark"></i></button>
            <button class="btn btn-sm btn-light text-primary shadow-sm rounded-circle" onclick="saveBookmark()" title="Add Bookmark"><i class="fas fa-plus"></i></button>
            <button onclick="toggleTheme()" class="btn btn-sm btn-light text-dark shadow-sm rounded-circle" title="Dark Mode"><i class="fas fa-adjust"></i></button>
        </div>
    </div>

    <div id="viewer"></div>

    <button id="highlight-btn" onclick="applyHighlight()">
        <i class="fas fa-highlighter"></i> Sorot Teks
    </button>

    <div class="reader-footer">
        <button id="prev" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="fas fa-chevron-left"></i> Prev</button>
        <div class="text-center">
            <span id="chapter-info" class="fw-bold text-primary">Loading...</span>
        </div>
        <button id="next" class="btn btn-primary btn-sm rounded-pill px-3">Next <i class="fas fa-chevron-right"></i></button>
    </div>

</div>

<div class="modal fade" id="bookmarkListModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-bookmark text-warning"></i> Bookmark Saya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <ul class="list-group list-group-flush bg-transparent">
                <?php while($bm = $bookmarks->fetch_assoc()): ?>
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                        <a href="#" onclick="jumpTo('<?= $bm['cfi'] ?>')" class="text-decoration-none fw-bold text-primary">
                            <?= htmlspecialchars($bm['note']) ?>
                            <br><small class="text-muted fw-normal" style="font-size: 0.7rem;"><?= $bm['created_at'] ?></small>
                        </a>
                        <a href="bookmark_handler.php?delete=<?= $bm['id'] ?>&book_id=<?= $id ?>" class="btn btn-sm btn-light text-danger rounded-circle"><i class="fas fa-trash"></i></a>
                    </li>
                <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="bookmarkForm" action="bookmark_handler.php" method="POST" style="display:none;">
    <input type="hidden" name="add_bookmark" value="1">
    <input type="hidden" name="book_id" value="<?= $id ?>">
    <input type="hidden" name="cfi" id="inputCfi">
    <input type="hidden" name="note" id="inputNote">
</form>

<script>
    var book = ePub("<?= $book['file_path'] ?>");
    var rendition = book.renderTo("viewer", { width: "100%", height: "100%", flow: "scrolled-doc" });
    
    // --- THEME SETUP (KUNCI PERBAIKAN) ---
    // Kita menyuntikkan CSS class 'search-current' langsung ke dalam iframe buku
    rendition.themes.default({ 
        "mark": { "background": "yellow", "mix-blend-mode": "multiply" }, // User Highlights (Kuning)
        ".search-current": { 
            "fill": "#00ff00",       // Warna SVG Fill (Hijau)
            "fill-opacity": "0.5",   // Transparansi
            "stroke": "#00ff00",     // Garis pinggir
            "stroke-width": "1px",
            "background-color": "#00ff00", // Fallback HTML
            "mix-blend-mode": "multiply"
        } 
    });

    rendition.themes.register("light", { body: { color: "#333", background: "#fff", "font-family": "Poppins, sans-serif" } });
    rendition.themes.register("dark", { body: { color: "#cbd5e1", background: "#121212", "font-family": "Poppins, sans-serif" }, "a": { "color": "#60a5fa" } });
    
    var displayed = rendition.display();

    // --- LOGIKA SEARCH (SAMA, TAPI CLASS DIPASTIKAN BENAR) ---
    var searchResults = [];
    var currentSearchIndex = 0;

    function doSearch() {
        var q = document.getElementById('searchInput').value;
        if(!q) return;

        // Reset
        rendition.annotations.remove(undefined, "search-current");
        searchResults = [];
        document.getElementById('searchInput').placeholder = "Mencari...";
        
        Promise.all(book.spine.spineItems.map(item => 
            item.load(book.load.bind(book))
            .then(item.find.bind(item, q))
            .finally(item.unload.bind(item))
        )).then(results => {
            searchResults = results.flat();
            if (searchResults.length > 0) {
                // UI Switch
                document.getElementById('search-input-group').classList.remove('d-md-flex');
                document.getElementById('search-input-group').style.display = 'none';
                document.getElementById('search-nav-group').style.display = 'flex';
                
                currentSearchIndex = 0;
                applySearchResult();
            } else {
                alert("Tidak ditemukan.");
                document.getElementById('searchInput').placeholder = "Cari kata...";
            }
        });
    }

    function applySearchResult() {
        if(searchResults.length === 0) return;
        var result = searchResults[currentSearchIndex];
        
        // 1. Pindah Halaman
        rendition.display(result.cfi);
        
        // 2. Highlight Hijau (Pakai Class 'search-current')
        rendition.annotations.remove(undefined, "search-current");
        rendition.annotations.add("highlight", result.cfi, {}, null, "search-current");
        
        // 3. Update Counter
        document.getElementById('search-counter').innerText = (currentSearchIndex + 1) + "/" + searchResults.length;
    }

    function nextResult() {
        if(searchResults.length === 0) return;
        currentSearchIndex++;
        if(currentSearchIndex >= searchResults.length) currentSearchIndex = 0;
        applySearchResult();
    }

    function prevResult() {
        if(searchResults.length === 0) return;
        currentSearchIndex--;
        if(currentSearchIndex < 0) currentSearchIndex = searchResults.length - 1;
        applySearchResult();
    }

    function closeSearch() {
        rendition.annotations.remove(undefined, "search-current");
        searchResults = [];
        document.getElementById('searchInput').value = '';
        document.getElementById('searchInput').placeholder = "Cari kata...";
        document.getElementById('search-nav-group').style.display = 'none';
        document.getElementById('search-input-group').style.display = '';
        document.getElementById('search-input-group').classList.add('d-md-flex');
    }

    // --- FITUR LAIN (HIGHLIGHT KUNING & BOOKMARK) ---
    var myHighlights = <?= $json_highlights ?>;
    rendition.on("rendered", function(section){
        myHighlights.forEach(function(cfiRange) { rendition.annotations.add("highlight", cfiRange); });
    });

    var currentSelectionCfi = null;
    rendition.on("selected", function(cfiRange, contents) {
        currentSelectionCfi = cfiRange;
        var btn = document.getElementById("highlight-btn");
        var viewerRect = document.getElementById("viewer").getBoundingClientRect();
        btn.style.display = "block";
        btn.style.top = (viewerRect.top + 20) + "px"; 
        btn.style.left = (viewerRect.left + (viewerRect.width / 2)) + "px";
        contents.window.getSelection().removeAllRanges();
    });

    function applyHighlight() {
        if(currentSelectionCfi) {
            rendition.annotations.add("highlight", currentSelectionCfi);
            $.post("highlight_handler.php", { action: "save", book_id: <?= $id ?>, cfi_range: currentSelectionCfi });
            document.getElementById("highlight-btn").style.display = "none";
            currentSelectionCfi = null;
        }
    }

    rendition.on("markClicked", function(cfiRange) {
        if(confirm("Hapus sorotan?")) {
            rendition.annotations.remove(cfiRange, "highlight");
            $.post("highlight_handler.php", { action: "delete", cfi_range: cfiRange });
        }
    });

    if(localStorage.getItem('theme') === 'dark') { toggleTheme(); }
    function toggleTheme() {
        var isDark = document.body.classList.toggle('dark-mode');
        rendition.themes.select(isDark ? "dark" : "light");
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    function saveBookmark() {
        var currentCfi = rendition.currentLocation().start.cfi;
        var note = prompt("Nama Bookmark:", "Halaman Penting");
        if (note) {
            document.getElementById('inputCfi').value = currentCfi;
            document.getElementById('inputNote').value = note;
            document.getElementById('bookmarkForm').submit();
        }
    }
    function jumpTo(cfi) {
        rendition.display(cfi);
        bootstrap.Modal.getInstance(document.getElementById('bookmarkListModal')).hide();
    }
    
    document.getElementById("next").addEventListener("click", function(){ rendition.next(); });
    document.getElementById("prev").addEventListener("click", function(){ rendition.prev(); });
    rendition.on("relocated", function(location){
        let percent = location.start.percentage ? (location.start.percentage * 100).toFixed(0) + "%" : "0%";
        document.getElementById("chapter-info").innerText = percent;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>