<?php
session_start();
require "db.php";

// Cek session dan role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Statistik pengguna baru bulan ini
$result_user_new = $conn->query("SELECT COUNT(*) as total FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$user_new = $result_user_new->fetch_assoc()['total'];

// Statistik buku populer (top 5)
$result_popular_books = $conn->query("
    SELECT b.judul, b.cover, COUNT(u.id_ulasan) AS total_ulasan
    FROM books b
    LEFT JOIN ulasan u ON b.id_book = u.id_book
    GROUP BY b.id_book
    ORDER BY total_ulasan DESC
    LIMIT 5
");

// Statistik total buku
$result_total_books = $conn->query("SELECT COUNT(*) as total FROM books");
$total_books = $result_total_books->fetch_assoc()['total'];

// Statistik aktivitas terbaru (5 aktivitas)
$result_activities = $conn->query("
    SELECT a.aktivitas AS aksi, a.waktu, u.username 
    FROM aktivitas a 
    LEFT JOIN users u ON a.id_user = u.id_user 
    ORDER BY a.waktu DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.4rem;
            font-weight: 600;
            border-left: 4px solid #3498db;
            padding-left: 10px;
        }

        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            flex: 1;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 3px solid #3498db;
        }

        .stat-box h3 {
            margin: 0;
            font-size: 2.2rem;
            color: #3498db;
            font-weight: 600;
        }

        .stat-box p {
            margin: 10px 0 0;
            font-weight: 500;
            color: #7f8c8d;
        }

        /* Buku Terbaru (pakai gambar) */
        .book-list-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        ul.book-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        ul.book-list li {
            width: 140px;
            text-align: center;
        }

        ul.book-list img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .book-title {
            margin-top: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            color: #2c3e50;
        }

        .activity-list {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .activity-list ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .activity-list li {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            align-items: center;
        }

        .activity-list li:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e3f2fd;
            color: #3498db;
            border-radius: 50%;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .activity-content {
            flex-grow: 1;
        }

        .activity-user {
            font-weight: 600;
            color: #2c3e50;
        }

        .activity-date {
            font-size: 0.85rem;
            color: #95a5a6;
            margin-top: 3px;
        }

        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
            }

            ul.book-list {
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
            <li><a href="dashboard_Admin.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="manajemen_buku.php"><i class="fas fa-book"></i> Manajemen Buku</a></li>
            <li><a href="manajemen_pengguna.php"><i class="fas fa-user"></i> Manajemen Pengguna</a></li>
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
            <h2>Statistik Bulan Ini</h2>
            <div class="stats">
                <div class="stat-box">
                    <h3><?=$user_new?></h3>
                    <p>Pengguna Baru</p>
                </div>
                <div class="stat-box">
                    <h3><?=$total_books?></h3>
                    <p>Total Buku</p>
                </div>
            </div>

            <h2>Buku Terbaru</h2>
            <div class="book-list-container">
                <ul class="book-list">
                    <?php while($book = $result_popular_books->fetch_assoc()): ?>
                        <li>
                             <img src="<?=htmlspecialchars($book['cover'] ?: 'https://via.placeholder.com/200x280?text=No+Cover')?>" alt="Cover <?=htmlspecialchars($book['judul'])?>" />
                            <div class="book-title"><?=htmlspecialchars($book['judul'])?></div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <h2>Aktivitas Terbaru</h2>
            <div class="activity-list">
                <ul>
                    <?php while($act = $result_activities->fetch_assoc()): ?>
                        <li>
                            <div class="activity-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">
                                    <span class="activity-user"><?=htmlspecialchars($act['username'])?></span> 
                                    <?=htmlspecialchars($act['aksi'])?>
                                </div>
                                <div class="activity-date">
                                    <?=date('d M Y', strtotime($act['waktu']))?>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>
