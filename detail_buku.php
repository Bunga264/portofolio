<?php
session_start();
require "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id_book = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_book <= 0) {
    header("Location: katalog.php");
    exit;
}

// Ambil data buku
$stmt = $conn->prepare("SELECT b.*, k.nama_kategori FROM books b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori WHERE b.id_book = ?");
$stmt->bind_param("i", $id_book);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Buku tidak ditemukan.";
    exit;
}

$book = $result->fetch_assoc();

// Ambil ulasan buku
$stmt2 = $conn->prepare("SELECT u.rating, u.komentar, u.created_at, us.username FROM ulasan u JOIN users us ON u.id_user = us.id_user WHERE u.id_book = ? ORDER BY u.created_at DESC");
$stmt2->bind_param("i", $id_book);
$stmt2->execute();
$reviews = $stmt2->get_result();

// Cek apakah buku sudah di bookmark user
$id_user = $_SESSION['user']['id_user'];
$stmt3 = $conn->prepare("SELECT * FROM bookmark WHERE id_user = ? AND id_book = ?");
$stmt3->bind_param("ii", $id_user, $id_book);
$stmt3->execute();
$bookmark_result = $stmt3->get_result();
$is_bookmarked = $bookmark_result->num_rows > 0;

// Handle tambah / hapus bookmark
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_bookmark'])) {
        if ($is_bookmarked) {
            // Hapus bookmark
            $del = $conn->prepare("DELETE FROM bookmark WHERE id_user = ? AND id_book = ?");
            $del->bind_param("ii", $id_user, $id_book);
            $del->execute();
        } else {
            // Tambah bookmark
            $ins = $conn->prepare("INSERT INTO bookmark (id_user, id_book, created_at) VALUES (?, ?, NOW())");
            $ins->bind_param("ii", $id_user, $id_book);
            $ins->execute();
        }
        header("Location: detail_buku.php?id=$id_book");
        exit;
    } elseif (isset($_POST['submit_review'])) {
        $rating = intval($_POST['rating']);
        $komentar = trim($_POST['komentar']);
        if ($rating >= 1 && $rating <= 5 && $komentar !== '') {
            $ins_rev = $conn->prepare("INSERT INTO ulasan (id_user, id_book, rating, komentar, created_at) VALUES (?, ?, ?, ?, NOW())");
            $ins_rev->bind_param("iiis", $id_user, $id_book, $rating, $komentar);
            $ins_rev->execute();
        }
        header("Location: detail_buku.php?id=$id_book");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Detail Buku - <?=htmlspecialchars($book['judul'])?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
.container {
    max-width: 1000px;
    margin: 25px auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.book-header {
    display: flex;
    gap: 40px;
    padding: 40px;
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    position: relative;
}

.book-cover {
    position: relative;
}

.book-cover img {
    width: 240px;
    height: 360px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.book-cover img:hover {
    transform: scale(1.03);
}

.book-info {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.book-info h1 {
    margin: 0 0 15px 0;
    font-family: 'Playfair Display', serif;
    font-size: 2.3rem;
    font-weight: 700;
    color: #0a294e;
    line-height: 1.2;
}

.book-meta {
    display: grid;
    grid-template-columns: max-content 1fr;
    gap: 8px 15px;
    margin-bottom: 20px;
}

.meta-label {
    font-weight: 600;
    color: #555;
}

.meta-value {
    color: #333;
}

.book-description {
    margin: 15px 0;
    line-height: 1.6;
    color: #555;
    font-size: 0.95rem;
    flex-grow: 1;
}

.book-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.btn-bookmark {
    background: white;
    color: #0077cc;
    border: 2px solid #0077cc;
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-bookmark:hover {
    background: #0077cc;
    color: white;
}

.btn-bookmark i {
    font-size: 1rem;
}

.baca-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background-color: #0077cc;
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 119, 204, 0.2);
}

.baca-btn:hover {
    background-color: #005fa3;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 119, 204, 0.3);
}

section {
    padding: 30px 40px;
}

.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: #0a294e;
    margin-bottom: 25px;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 70%;
    height: 3px;
    background: #0077cc;
    border-radius: 2px;
}

.reviews-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    position: relative;
    transition: transform 0.2s ease;
}

.review:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}

.review-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #0077cc;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}

.username {
    font-weight: 600;
    color: #333;
}

.review-meta {
    text-align: right;
}

.rating {
    color: #ffb400;
    font-size: 1rem;
    letter-spacing: 2px;
}

.date {
    color: #999;
    font-size: 0.8rem;
    margin-top: 4px;
}

.komentar {
    margin-top: 10px;
    line-height: 1.6;
    color: #444;
}

.review-form {
    margin-top: 30px;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 12px;
}

.form-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #0a294e;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #444;
    margin-bottom: 8px;
}

.rating-select {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 1rem;
    width: 100%;
    max-width: 300px;
    background: white;
    color: #333;
}

.textarea-komentar {
    width: 100%;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    resize: vertical;
    min-height: 120px;
    background: white;
    color: #333;
    transition: border-color 0.3s;
}

.textarea-komentar:focus, .rating-select:focus {
    outline: none;
    border-color: #0077cc;
    box-shadow: 0 0 0 2px rgba(0, 119, 204, 0.1);
}

.submit-review {
    background: #0077cc;
    color: white;
    padding: 12px 30px;
    border-radius: 50px;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.submit-review:hover {
    background: #005fa3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 119, 204, 0.2);
}

.empty-reviews {
    text-align: center;
    padding: 30px;
    color: #777;
    font-style: italic;
}

/* Responsive */
@media (max-width: 768px) {
    .book-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 25px;
    }
    
    .book-meta {
        justify-content: center;
    }
    
    .book-actions {
        justify-content: center;
    }
}
</style>
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
      </header>

      <div class="container">
        <div class="book-header">
            <div class="book-cover">
                <img src="<?=htmlspecialchars($book['cover'] ?: 'https://via.placeholder.com/240x360?text=No+Cover')?>" alt="Cover <?=htmlspecialchars($book['judul'])?>">
            </div>
            <div class="book-info">
                <h1><?=htmlspecialchars($book['judul'])?></h1>
                
                <div class="book-meta">
                    <div class="meta-label">Penulis:</div>
                    <div class="meta-value"><?=htmlspecialchars($book['penulis'])?></div>
                    
                    <div class="meta-label">Tahun Terbit:</div>
                    <div class="meta-value"><?=htmlspecialchars($book['tahun_terbit'])?></div>
                    
                    <div class="meta-label">Kategori:</div>
                    <div class="meta-value"><?=htmlspecialchars($book['nama_kategori'] ?: 'Tidak ada')?></div>
                </div>
                
                <div class="book-description">
                    <?=nl2br(htmlspecialchars($book['deskripsi']))?>
                </div>

                <div class="book-actions">
                    <form method="POST">
                        <button type="submit" name="toggle_bookmark" class="btn-bookmark">
                            <i class="fas <?= $is_bookmarked ? "fa-bookmark" : "fa-bookmark" ?>"></i>
                            <?= $is_bookmarked ? "Hapus dari Bookmark" : "Tambah ke Bookmark" ?>
                        </button>
                    </form>
                    <a href="baca_buku.php?id=<?= $book['id_book'] ?>" class="baca-btn">
                        <i class="fas fa-book-open"></i> Baca Buku
                    </a>
                </div>
            </div>
        </div>

        <section>
            <h2 class="section-title">Ulasan Pembaca</h2>
            
            <?php if ($reviews->num_rows === 0): ?>
                <div class="empty-reviews">
                    <p>Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan ulasan!</p>
                </div>
            <?php else: ?>
                <div class="reviews-container">
                    <?php while ($rev = $reviews->fetch_assoc()): ?>
                        <div class="review">
                            <div class="review-header">
                                <div class="review-user">
                                    <div class="avatar"><?=substr(htmlspecialchars($rev['username']), 0, 1)?></div>
                                    <div class="username"><?=htmlspecialchars($rev['username'])?></div>
                                </div>
                                <div class="review-meta">
                                    <div class="rating"><?=str_repeat("★", $rev['rating']) . str_repeat("☆", 5 - $rev['rating'])?></div>
                                    <div class="date"><?=date('d M Y, H:i', strtotime($rev['created_at']))?></div>
                                </div>
                            </div>
                            <div class="komentar"><?=nl2br(htmlspecialchars($rev['komentar']))?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <div class="review-form">
                <div class="form-title">Tulis Ulasan Anda</div>
                <form method="POST">
                    <div class="form-group">
                        <label for="rating">Rating Buku:</label>
                        <select id="rating" name="rating" class="rating-select" required>
                            <option value="">Pilih rating</option>
                            <option value="5">5 - Sangat Baik</option>
                            <option value="4">4 - Baik</option>
                            <option value="3">3 - Cukup</option>
                            <option value="2">2 - Kurang</option>
                            <option value="1">1 - Buruk</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="komentar">Komentar:</label>
                        <textarea id="komentar" name="komentar" class="textarea-komentar" 
                                placeholder="Bagikan pengalaman membaca dan pendapat Anda tentang buku ini..." required></textarea>
                    </div>

                    <button type="submit" name="submit_review" class="submit-review">
                       Kirim Ulasan
                    </button>
                </form>
            </div>
        </section>
      </div>
    </div>
</div>

</body>
</html>