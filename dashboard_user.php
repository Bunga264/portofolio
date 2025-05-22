<?php
session_start();
require "db.php";

// Cek login & role user
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Prepare query based on search and category
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query = "SELECT * FROM books WHERE judul LIKE '%$search%' OR penulis LIKE '%$search%' OR tahun_terbit LIKE '%$search%' ORDER BY id_book DESC LIMIT 20";
} else {
    $query = "SELECT * FROM books ORDER BY id_book DESC LIMIT 20";
}

// Execute query
$result_latest = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard User - eLibrary</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="app-container">
    <div class="sidebar">
      <div class="brand">eLibrary</div>
      <div class="menu-title">MENU</div>
      <ul class="sidebar-menu">
        <li><a href="dashboard_user.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="katalog.php"><i class="fas fa-book"></i> Katalog</a></li>
        <li><a href="bookmark.php"><i class="fas fa-bookmark"></i> Bookmark</a></li>
       
        <li><a href="profile.php"><i class="fas fa-user"></i> Profil Saya</a></li>
      </ul>
      <div class="menu-title">LAINNYA</div>
      <ul class="sidebar-menu">
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </div>
    
    <div class="main-content">
      <header>
        <div class="user-greeting">
          <i class="fas fa-user"></i>
          <span>Halo, <?=htmlspecialchars($_SESSION['user']['username'])?></span>
        </div>
        <form class="search-container" method="GET" action="">
          <i class="fas fa-search"></i>
          <input type="text" name="search" placeholder="Cari judul, penulis, atau tahun..." value="<?=htmlspecialchars($search)?>" />
          <button type="submit" class="search-btn">Cari</button>
        </form>
      </header>
      
      <!-- Featured Banner -->
      <div class="special-banner">
        <div class="banner-content">
          <h3>Koleksi Digital Terlengkap</h3>
          <p>Temukan ribuan buku dalam genggaman Anda. Mulai membaca sekarang!</p>
          <a href="katalog.php" class="banner-btn">Jelajahi Katalog</a>
        </div>
        <div class="banner-image">
          <img src="https://png.pngtree.com/png-clipart/20220125/original/pngtree-a-stack-of-books-png-image_7203441.png" alt="Books" />
        </div>
      </div>
      
      <!-- Books Section -->
      <section>
        <div class="section-header">
          <h2><i class="fas fa-book-open"></i> Buku Terbaru</h2>
          <div class="category-filter">
            <a href="?category=all<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="category-btn <?= $category === 'all' ? 'active' : '' ?>">Semua</a>
          </div>
        </div>
        
        <div class="book-list">
          <?php if($result_latest->num_rows > 0): ?>
            <?php while ($book = $result_latest->fetch_assoc()): ?>
              <div class="book-card">
                <?php if (rand(0, 5) == 1): ?>
                  <div class="ribbon">Baru</div>
                <?php endif; ?>
                <div class="book-cover-container">
                  <img src="<?=htmlspecialchars($book['cover'] ?: 'https://via.placeholder.com/200x280?text=No+Cover')?>" alt="Cover <?=htmlspecialchars($book['judul'])?>" />
                </div>
                <div class="info">
                  <h3><?=htmlspecialchars($book['judul'])?></h3>
                  <p><?=htmlspecialchars($book['penulis'])?> - <?=htmlspecialchars($book['tahun_terbit'])?></p>
                  <a href="detail_buku.php?id=<?=urlencode($book['id_book'])?>" class="btn">
                    <i class="fas fa-eye"></i> Lihat Detail
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="no-results">
              <i class="fas fa-search" style="font-size: 32px; margin-bottom: 10px; color: #ddd;"></i>
              <p>Tidak ada buku yang sesuai dengan pencarian Anda.</p>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</body>
</html>