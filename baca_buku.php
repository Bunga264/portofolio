<?php
session_start();
require "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM books WHERE id_book = $id");
$book = $result->fetch_assoc();

// Pisahkan isi buku berdasarkan "### Bab"
$isiBuku = $book['isi_buku'];
$babList = preg_split('/###\s*(Bab\s+\d+)/i', $isiBuku, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($book['judul']) ?> - Baca Buku</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Merriweather:ital,wght@0,400;0,700;1,400&display=swap');

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Merriweather', serif;
      margin: 0;
      padding: 0;
      background: #fafafa;
      color: #444;
      scroll-behavior: smooth;
      line-height: 1.6;
    }

    .container {
      display: flex;
      height: 100vh;
      background: white;
      max-width: 1200px;
      margin: 30px auto;
      border-radius: 12px;
      box-shadow: 0 12px 25px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      background: #f0f4ff;
      padding: 30px 25px;
      border-right: 1px solid #d1d9ff;
      position: sticky;
      top: 30px;
      height: 100vh; /* penuh tinggi layar */
      overflow-y: auto;
      box-shadow: inset -3px 0 8px -4px rgba(0,0,0,0.05);
      border-top-left-radius: 12px;
      border-bottom-left-radius: 12px;
    }

    .sidebar h2 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 24px;
      margin: 0 0 15px;
      color: #1a3d7c;
      user-select: none;
    }

    .sidebar h3 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      font-size: 18px;
      margin-bottom: 15px;
      color: #3b4cca;
    }

    .sidebar ul {
      list-style: none;
      padding-left: 0;
      margin: 0;
    }

    .sidebar li {
      margin-bottom: 14px;
    }

    .sidebar a {
      color: #4a5ad6;
      text-decoration: none;
      font-weight: 600;
      font-size: 15px;
      display: block;
      padding: 6px 10px;
      border-radius: 6px;
      transition: background-color 0.25s, color 0.25s;
    }

    .sidebar a:hover,
    .sidebar a:focus {
      background-color: #4a5ad6;
      color: white;
      outline: none;
    }

    /* Content */
    .content {
      flex-grow: 1;
      padding: 50px 60px;
      max-width: 900px;
      overflow-y: auto;
    }

    .content h1 {
      font-family: 'Montserrat', sans-serif;
      color: #2c3e99;
      text-align: center;
      margin-bottom: 50px;
      font-weight: 700;
      letter-spacing: 1.2px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
    }

    .content h2 {
      color: #344d9a;
      margin-top: 50px;
      border-bottom: 2px solid #d0d9ff;
      padding-bottom: 10px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .content p {
      font-size: 17px;
      color: #555;
      margin-top: 12px;
      text-align: justify;
      white-space: pre-wrap;
    }

    /* Back link */
    .back {
      margin-top: 70px;
      text-align: center;
    }

    .back a {
      color: #2c3e99;
      font-weight: 600;
      font-size: 16px;
      text-decoration: none;
      border: 2px solid #2c3e99;
      padding: 10px 18px;
      border-radius: 30px;
      transition: all 0.3s ease;
      display: inline-block;
      box-shadow: 0 4px 6px rgba(44, 62, 153, 0.3);
    }

    .back a:hover,
    .back a:focus {
      background-color: #2c3e99;
      color: white;
      box-shadow: 0 6px 12px rgba(44, 62, 153, 0.6);
      outline: none;
    }

    /* Scrollbar for sidebar */
    .sidebar::-webkit-scrollbar {
      width: 8px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: #f0f4ff;
      border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background-color: #4a5ad6;
      border-radius: 10px;
      border: 2px solid #f0f4ff;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .container {
        flex-direction: column;
        max-width: 100%;
        margin: 15px;
        border-radius: 0;
        box-shadow: none;
      }

      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        border-radius: 0;
        border-right: none;
        box-shadow: none;
        padding: 20px 15px;
        max-height: 300px;
      }

      .content {
        padding: 30px 20px;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="dashboard_user.php" style="text-decoration: none; color: #1a3d7c;">
        <h2 style="margin-top: 0; font-size: 24px;">📖 Dashboard</h2>
      </a>
      <hr style="border: none; border-top: 1px solid #d1d9ff; margin: 10px 0 25px;">
      <h3>📚 Daftar Isi</h3>
      <ul>
        <?php
        for ($i = 0; $i < count($babList); $i += 2):
            $judulBab = isset($babList[$i]) ? htmlspecialchars($babList[$i]) : '';
            $idAnchor = strtolower(str_replace(' ', '-', $judulBab));
        ?>
            <li><a href="#<?= $idAnchor ?>"><?= $judulBab ?></a></li>
        <?php endfor; ?>
      </ul>
    </div>

    <!-- Konten Buku -->
    <div class="content">
      <h1><?= htmlspecialchars($book['judul']) ?></h1>

      <?php
      for ($i = 0; $i < count($babList); $i += 2):
          $judulBab = isset($babList[$i]) ? htmlspecialchars($babList[$i]) : '';
          $idAnchor = strtolower(str_replace(' ', '-', $judulBab));
          $isiBab = isset($babList[$i+1]) ? nl2br(htmlspecialchars($babList[$i+1])) : '';
      ?>
          <h2 id="<?= $idAnchor ?>"><?= $judulBab ?></h2>
          <p><?= $isiBab ?></p>
      <?php endfor; ?>

      <div class="back">
        <a href="detail_buku.php?id=<?= $book['id_book'] ?>">← Kembali ke Detail</a>
      </div>
    </div>
  </div>
</body>
</html>
