<?php
session_start();
require "db.php";

// Cek login & role user
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];

// Ambil buku yang di-bookmark user
$result_bookmark = $conn->query("
    SELECT b.* FROM books b
    JOIN bookmark bm ON b.id_book = bm.id_book
    WHERE bm.id_user = $id_user
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Bookmark Saya - E-Library</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
  /* Sama seperti style di dashboard_user.php, bisa kamu simpan di file CSS terpisah agar DRY */
  
</style>

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
                <i class="fas fa-user-circle"></i>
                Halo, <?=htmlspecialchars($_SESSION['user']['username'])?>
            </div>
            <!-- <form class="search-container" method="GET" action="">
          <i class="fas fa-search"></i>
          <input type="text" name="search" placeholder="Cari judul, penulis, atau tahun..." value="<?=htmlspecialchars($search)?>" />
          <button type="submit" class="search-btn">Cari</button>
        </form> -->
        </header>
      
<section>
  <h2>Bookmark Saya</h2>
  <?php if ($result_bookmark->num_rows == 0): ?>
    <p>Kamu belum menambahkan buku apa pun ke bookmark.</p>
  <?php else: ?>
  <div class="book-list">
    <?php while ($book = $result_bookmark->fetch_assoc()): ?>
      <div class="book-card">
        <img src="<?=htmlspecialchars($book['cover'] ?: 'https://via.placeholder.com/200x280?text=No+Cover')?>" alt="Cover <?=htmlspecialchars($book['judul'])?>" />
        <div class="info">
          <h3><?=htmlspecialchars($book['judul'])?></h3>
          <p><?=htmlspecialchars($book['penulis'])?> - <?=htmlspecialchars($book['tahun_terbit'])?></p>
        <a href="detail_buku.php?id=<?=$book['id_book']?>" class="btn">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</section>
</div> </div> 
<style>
.book-list {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 20px;
}

.book-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
    width: 200px;
    margin-bottom: 15px;
}

.book-card:hover {
    transform: translateY(-5px);
}

.book-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.book-card .info {
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.book-card h3 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #333;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.book-card p {
    margin: 0 0 10px;
    color: #666;
    font-size: 12px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.book-card .btn {
    display: block;
    background: #1e88e5;
    color: white;
    text-align: center;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    transition: background 0.3s;
}

.book-card .btn:hover {
    background: #1565c0;
}
</style>
</body> </html>