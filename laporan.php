<?php
session_start();
require "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 1. Aktivitas membaca buku
$baca = $conn->query("
    SELECT a.*, u.nama AS nama_user, b.judul AS judul_buku 
    FROM aktivitas a
    JOIN users u ON a.id_user = u.id_user
    JOIN books b ON a.id_book = b.id_book
    WHERE a.aksi = 'baca'
    ORDER BY a.waktu DESC
");

// 2. Aktivitas admin: upload buku
$upload = $conn->query("
    SELECT b.judul, u.nama AS uploader, b.tanggal_upload 
    FROM books b
    JOIN users u ON b.id_user = u.id_user
    WHERE u.role = 'admin'
    ORDER BY b.tanggal_upload DESC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Laporan Aktivitas</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; background:#f0f4f8; }
h2 { color: #003366; margin-top: 40px; }
table { width: 100%; border-collapse: collapse; background: white; margin-top: 10px;}
th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
th { background-color: #003366; color: white; }
</style>
</head>
<body>

<h1>Laporan Aktivitas</h1>

<!-- Bagian 1: Siapa saja yang membaca buku -->
<h2>📖 Daftar User yang Membaca Buku</h2>
<table>
<thead>
<tr>
    <th>Nama</th>
    <th>Judul Buku</th>
    <th>Waktu Baca</th>
</tr>
</thead>
<tbody>
<?php while($row = $baca->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['nama_user']) ?></td>
    <td><?= htmlspecialchars($row['judul_buku']) ?></td>
    <td><?= date('d M Y, H:i', strtotime($row['waktu'])) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- Bagian 2: Aktivitas Admin Upload Buku -->
<h2>📝 Aktivitas Admin - Upload Buku</h2>
<table>
<thead>
<tr>
    <th>Judul Buku</th>
    <th>Admin</th>
    <th>Tanggal Upload</th>
</tr>
</thead>
<tbody>
<?php while($row = $upload->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['judul']) ?></td>
    <td><?= htmlspecialchars($row['uploader']) ?></td>
    <td><?= date('d M Y', strtotime($row['tanggal_upload'])) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</body>
</html>
