<?php
session_start();
require "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua kategori untuk dropdown
$categories = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Handle tambah buku
if (isset($_POST['tambah'])) {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = (int)$_POST['tahun_terbit'];
    $id_kategori = (int)$_POST['id_kategori'];
    $deskripsi = trim($_POST['deskripsi']);
    $cover = trim($_POST['cover']);
    $isi_buku = trim($_POST['isi_buku']);

    if ($judul !== '' && $penulis !== '' && $tahun > 0 && $id_kategori > 0) {
        $stmt = $conn->prepare("INSERT INTO books (judul, penulis, tahun_terbit, id_kategori, deskripsi, cover, isi_buku) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissss", $judul, $penulis, $tahun, $id_kategori, $deskripsi, $cover, $isi_buku);
        $stmt->execute();
        $stmt->close();

        // Catat aktivitas
        $id_user = $_SESSION['user']['id_user'];
        $aktivitas = "Menambahkan buku berjudul: $judul";
        $stmt2 = $conn->prepare("INSERT INTO aktivitas (id_user, aktivitas, waktu) VALUES (?, ?, NOW())");
        $stmt2->bind_param("is", $id_user, $aktivitas);
        $stmt2->execute();
        $stmt2->close();

        header("Location: manajemen_buku.php");
        exit;
    } else {
        $error = "Data buku tidak valid. Semua field wajib diisi dengan benar.";
    }
}

// Handle hapus buku
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM books WHERE id_book = $id");

    // Catat aktivitas
    $id_user = $_SESSION['user']['id_user'];
    $aktivitas = "Menghapus buku dengan ID: $id";
    $stmt3 = $conn->prepare("INSERT INTO aktivitas (id_user, aktivitas, waktu) VALUES (?, ?, NOW())");
    $stmt3->bind_param("is", $id_user, $aktivitas);
    $stmt3->execute();
    $stmt3->close();

    header("Location: manajemen_buku.php");
    exit;
}

// Ambil data buku lengkap dengan nama kategori
$books = $conn->query("SELECT b.*, k.nama_kategori FROM books b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori ORDER BY b.id_book DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Manajemen Buku - Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="stylee.css">
<body>
    

<nav class="navbar">
        <div class="container navbar-container">
            <div class="logo">
                <i class="fas fa-book"></i>
               eLibrary
            </div>
            <a href="dashboard_admin.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Dashboard Admin
            </a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Manajemen Buku</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <?=htmlspecialchars($error)?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3 class="form-title"><i class="fas fa-plus-circle"></i> Tambah Buku Baru</h3>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="judul">Judul Buku</label>
                        <input type="text" id="judul" name="judul" class="form-control" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="penulis">Penulis</label>
                        <input type="text" id="penulis" name="penulis" class="form-control" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control" min="1900" max="<?=date('Y')?>" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="kategori">Kategori (Genre Buku)</label>
                        <select id="kategori" name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php 
                            // Reset pointer since we used it to display categories earlier
                            $categories->data_seek(0);
                            while($cat = $categories->fetch_assoc()): 
                            ?>
                                <option value="<?=$cat['id_kategori']?>"><?=htmlspecialchars($cat['nama_kategori'])?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control textarea-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="isi_buku">Isi Buku</label>
                    <textarea id="isi_buku" name="isi_buku" class="form-control textarea-control" rows="5" placeholder="Isi lengkap buku..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="cover">Cover (URL gambar, optional)</label>
                    <input type="url" id="cover" name="cover" class="form-control" placeholder="http://contoh.com/cover.jpg" />
                </div>
                
                <button type="submit" name="tambah" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Buku
                </button>
            </form>
        </div>

        <div class="card">
            <h3 class="form-title"><i class="fas fa-book"></i> Daftar Buku</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Informasi Buku</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Isi Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = $books->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Cover">
                                    <?php if ($book['cover']): ?>
                                        <img src="<?=htmlspecialchars($book['cover'])?>" alt="Cover" class="cover-img" />
                                    <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-image"></i><br>
                                            No Cover
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Informasi Buku">
                                    <div class="table-title"><?=htmlspecialchars($book['judul'])?></div>
                                    <div style="font-size:14px;color:#4b5563;">
                                        <i class="fas fa-user"></i> <?=htmlspecialchars($book['penulis'])?><br>
                                        <i class="fas fa-calendar"></i> <?=htmlspecialchars($book['tahun_terbit'])?>
                                    </div>
                                </td>
                                <td data-label="Kategori">
                                    <span class="badge badge-primary"><?=htmlspecialchars($book['nama_kategori'] ?? 'Tidak ada')?></span>
                                </td>
                                <td data-label="Deskripsi">
                                    <div class="book-desc">
                                        <?php if ($book['deskripsi']): ?>
                                            <?=nl2br(htmlspecialchars($book['deskripsi']))?>
                                        <?php else: ?>
                                            <em>Tidak ada deskripsi</em>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td data-label="Isi Buku">
                                    <div class="book-desc">
                                        <?php if ($book['isi_buku']): ?>
                                            <?=nl2br(htmlspecialchars($book['isi_buku']))?>
                                        <?php else: ?>
                                            <em>Tidak ada isi buku</em>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td data-label="Aksi">
                                    <div class="action-buttons">
                                        <a href="edit_buku.php?id=<?=$book['id_book']?>" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manajemen_buku.php?hapus=<?=$book['id_book']?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" class="btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
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