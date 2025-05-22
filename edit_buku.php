<?php
session_start();
require "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manajemen_buku.php");
    exit;
}

$id = (int)$_GET['id'];

// Ambil data buku
$stmt = $conn->prepare("SELECT * FROM books WHERE id_book = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$buku = $result->fetch_assoc();
$stmt->close();

if (!$buku) {
    echo "Buku tidak ditemukan.";
    exit;
}

// Ambil kategori
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Handle update
if (isset($_POST['update'])) {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun = (int)$_POST['tahun_terbit'];
    $id_kategori = (int)$_POST['id_kategori'];
    $deskripsi = trim($_POST['deskripsi']);
    $cover = trim($_POST['cover']);
    $isi_buku = trim($_POST['isi_buku']);

    $stmt = $conn->prepare("UPDATE books SET judul=?, penulis=?, tahun_terbit=?, id_kategori=?, deskripsi=?, cover=?, isi_buku=? WHERE id_book=?");
    $stmt->bind_param("ssissssi", $judul, $penulis, $tahun, $id_kategori, $deskripsi, $cover, $isi_buku, $id);
    $stmt->execute();
    $stmt->close();

    // Catat aktivitas
    $id_user = $_SESSION['user']['id_user'];
    $aktivitas = "Mengedit buku: $judul";
    $stmt2 = $conn->prepare("INSERT INTO aktivitas (id_user, aktivitas, waktu) VALUES (?, ?, NOW())");
    $stmt2->bind_param("is", $id_user, $aktivitas);
    $stmt2->execute();
    $stmt2->close();

    header("Location: manajemen_buku.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Buku</title>
  
  <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Buku</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            color: #1f2937;
        }

        form {
            background: #ffffffdd;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            max-width: 620px;
            width: 100%;
            transition: box-shadow 0.3s ease;
            backdrop-filter: saturate(180%) blur(10px);
        }

        form:hover {
            box-shadow: 0 18px 60px rgba(0, 0, 0, 0.15);
        }

        h2 {
            margin-bottom: 35px;
            font-weight: 700;
            color: #0f172a;
            text-align: center;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            margin-top: 25px;
            font-size: 1rem;
            user-select: none;
        }

        input[type="text"],
        input[type="number"],
        input[type="url"],
        select,
        textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 400;
            color: #334155;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="url"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
            background-color: #fff;
        }

        textarea {
            min-height: 120px;
            font-size: 0.95rem;
            line-height: 1.5;
            font-family: 'Poppins', sans-serif;
        }

        button {
            margin-top: 40px;
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            padding: 16px;
            font-size: 1.15rem;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 700;
            width: 100%;
            letter-spacing: 0.05em;
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }

        button:hover {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.6);
        }

        a.back-link {
            display: block;
            margin-top: 30px;
            text-align: center;
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
            user-select: none;
        }

        a.back-link:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 700px) {
            form {
                padding: 30px 25px;
            }

            h2 {
                font-size: 1.6rem;
            }

            button {
                font-size: 1.05rem;
                padding: 14px;
            }
        }

        @media (max-width: 400px) {
            body {
                padding: 30px 15px;
            }

            form {
                padding: 25px 15px;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>

    <form method="POST" autocomplete="off" novalidate>
        <h2>Edit Buku</h2>
        
        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" name="judul" value="<?=htmlspecialchars($buku['judul'])?>" required>

        <label for="penulis">Penulis</label>
        <input type="text" id="penulis" name="penulis" value="<?=htmlspecialchars($buku['penulis'])?>" required>

        <label for="tahun_terbit">Tahun Terbit</label>
        <input type="number" id="tahun_terbit" name="tahun_terbit" min="1900" max="<?=date('Y')?>" value="<?=$buku['tahun_terbit']?>" required>

        <label for="id_kategori">Kategori</label>
        <select id="id_kategori" name="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php while($k = $kategori->fetch_assoc()): ?>
                <option value="<?=$k['id_kategori']?>" <?=($k['id_kategori'] == $buku['id_kategori']) ? 'selected' : ''?>>
                    <?=htmlspecialchars($k['nama_kategori'])?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4"><?=htmlspecialchars($buku['deskripsi'])?></textarea>

        <label for="isi_buku">Isi Buku</label>
        <textarea id="isi_buku" name="isi_buku" rows="7"><?=htmlspecialchars($buku['isi_buku'])?></textarea>

        <label for="cover">Cover (URL gambar)</label>
        <input type="url" id="cover" name="cover" value="<?=htmlspecialchars($buku['cover'])?>">

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>

  

</body>
</html>
