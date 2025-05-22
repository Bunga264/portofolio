<?php
session_start();
require "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['ubah_role'])) {
    $id = (int)$_GET['ubah_role'];
    $user = $conn->query("SELECT * FROM users WHERE id_user = $id")->fetch_assoc();
    if ($user) {
        $new_role = ($user['role'] === 'admin') ? 'user' : 'admin';
        $conn->query("UPDATE users SET role='$new_role' WHERE id_user = $id");
        header("Location: manajemen_pengguna.php");
        exit;
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Manajemen Pengguna - Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="stylee.css">
</head>
<body>
<nav class="navbar">
        <div class="container navbar-container">
            <div class="logo">
                <i class="fas fa-book"></i>
                elibrary
            </div>
            <a href="dashboard_admin.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Dashboard Admin
            </a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Manajemen Pengguna</h2>
        
        <div class="card">
            <h3 class="table-title"><i class="fas fa-user-cog"></i> Daftar Pengguna</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Registrasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $users->fetch_assoc()): ?>
                            <tr class="<?php echo $_SESSION['user']['id_user'] === $user['id_user'] ? 'current-user' : ''; ?>">
                                <td data-label="Pengguna">
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name"><?=htmlspecialchars($user['username'])?></div>
                                            <?php if($_SESSION['user']['id_user'] === $user['id_user']): ?>
                                                <small>(Anda)</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Email">
                                    <a href="mailto:<?=htmlspecialchars($user['email'])?>" style="color:#4b5563; text-decoration:none;">
                                        <?=htmlspecialchars($user['email'])?>
                                    </a>
                                </td>
                                <td data-label="Role">
                                    <?php if($user['role'] === 'admin'): ?>
                                        <span class="badge badge-admin">
                                            <i class="fas fa-shield-alt"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-user">
                                            <i class="fas fa-user"></i> User
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Tanggal Registrasi">
                                    <div class="user-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?=date('d M Y, H:i', strtotime($user['created_at']))?>
                                    </div>
                                </td>
                                <td data-label="Aksi">
                                    <?php if($_SESSION['user']['id_user'] !== $user['id_user']): ?>
                                        <a href="manajemen_pengguna.php?ubah_role=<?=$user['id_user']?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin mengubah role pengguna ini?')" 
                                           class="btn-change-role">
                                            <i class="fas fa-exchange-alt"></i>
                                            <?php if($user['role'] === 'admin'): ?>
                                                Jadikan User
                                            <?php else: ?>
                                                Jadikan Admin
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <em>-</em>
                                    <?php endif; ?>
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