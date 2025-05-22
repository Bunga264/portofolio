<?php
session_start();
require "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil kategori untuk filter
$result_kategori = $conn->query("SELECT * FROM kategori");

// Ambil filter dari query params
$filter_kategori = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query buku dengan filter dan search
$sql = "SELECT b.*, k.nama_kategori FROM books b 
        LEFT JOIN kategori k ON b.id_kategori = k.id_kategori 
        WHERE 1=1";

if ($filter_kategori > 0) {
    $sql .= " AND b.id_kategori = $filter_kategori";
}

if ($search !== '') {
    $search_esc = $conn->real_escape_string($search);
    $sql .= " AND (b.judul LIKE '%$search_esc%' OR b.penulis LIKE '%$search_esc%')";
}

$sql .= " ORDER BY b.judul ASC";

$result_buku = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<title>Katalog Buku - E-Library</title>
</head>
<body>

<div class="app-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">eLibrary</div>
        
        <div class="menu-title">MENU</div>
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard_user.php">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="katalog.php" class="active">
                    <i class="fas fa-book"></i>
                    Katalog
                </a>
            </li>
            <li>
                <a href="bookmark.php">
                    <i class="fas fa-bookmark"></i>
                    Bookmark
                </a>
            </li>
            <li>
                <a href="profil.php">
                    <i class="fas fa-user"></i>
                    Profil Saya
                </a>
            </li>
        </ul>
        
        <div class="menu-title">LAINNYA</div>
        <ul class="sidebar-menu">
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="main-content">
        <header>
            <div class="user-greeting">
                <i class="fas fa-user-circle"></i>
                Halo, <?=htmlspecialchars($_SESSION['user']['username'])?>
            </div>
            <form class="search-container" method="GET" action="">
          <i class="fas fa-search"></i>
          <input type="text" name="search" placeholder="Cari judul, penulis, atau tahun..." value="<?=htmlspecialchars($search)?>" />
          <button type="submit" class="search-btn">Cari</button>
        </form>
        </header>

        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-book"></i> Katalog Buku</h2>
                
                <div class="category-filter">
                    <form method="GET" action="katalog.php">
                        <select name="kategori" onchange="this.form.submit()">
                            <option value="0">-- Semua Kategori --</option>
                            <?php while($kat = $result_kategori->fetch_assoc()): ?>
                                <option value="<?=$kat['id_kategori']?>" <?=($filter_kategori == $kat['id_kategori']) ? 'selected' : ''?>>
                                    <?=htmlspecialchars($kat['nama_kategori'])?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>
            </div>

            <?php if ($result_buku->num_rows === 0): ?>
                <div class="no-results">Tidak ada buku ditemukan.</div>
            <?php else: ?>
                <div class="book-list">
                    <?php while ($book = $result_buku->fetch_assoc()): ?>
                        <div class="book-card">
                            <img src="<?=htmlspecialchars($book['cover'] ?: 'https://via.placeholder.com/200x280?text=No+Cover')?>" alt="Cover <?=htmlspecialchars($book['judul'])?>">
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
        </div>
    </div>
</div>

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

</body>
</html>