<?php
session_start();
require "db.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $conn->real_escape_string(trim($_POST['nama_lengkap']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama!";
    } else {
        // cek username/email sudah ada?
        $check = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
        if ($check->num_rows > 0) {
            $error = "Username atau email sudah digunakan!";
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $sql = "INSERT INTO users (nama_lengkap, username, email, password, role) VALUES ('$nama_lengkap', '$username', '$email', '$pass_hash', '$role')";
            if ($conn->query($sql)) {
                $success = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
            } else {
                $error = "Gagal mendaftar. Coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #dcd0ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .login-form {
            width: 50%;
            padding: 7px;
            display: flex;
            flex-direction: column;
        }
        
        .login-image {
            width: 50%;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }
        
        .logo-icon {
            width: 30px;
            height: 30px;
            background-color: #ffd54f;
            border-radius: 50%;
            margin-right: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }
        
        .logo-text {
            font-weight: 600;
            color: #333;
        }
        
        .login-header {
            margin-bottom: 40px;
        }
        
        .login-header h1 {
            font-size: 28px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #a883ff;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 8px;
        }
        
        .forgot-password a {
            font-size: 14px;
            color: #666;
            text-decoration: none;
        }
        
        .forgot-password a:hover {
            color: #a883ff;
        }
        
        .login-button {
            background-color: #ffd54f;
            color: #333;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }
        
        .login-button:hover {
            background-color: #ffca28;
        }
        
        .register-link {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .register-link a {
            color: #a883ff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .separator {
            text-align: center;
            margin-bottom: 20px;
            color: #999;
            font-size: 14px;
        }
        
        .google-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .google-login img {
            width: 20px;
            height: 20px;
        }
        
        .google-login span {
            color: #666;
            font-size: 14px;
        }
        
        .illustration {
            width: 80%;
            height: auto;
            position: relative;
            z-index: 1;
        }
        
        /* Illustration content (using CSS for simplicity) */
        .illustration {
            position: relative;
            height: 300px;
        }
        
        .book-stack {
            position: absolute;
            width: 100%;
            height: 250px;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .book {
            height: 40px;
            border-radius: 4px;
            margin-bottom: 2px;
        }
        
        .book-1 {
            width: 80%;
            background-color: #6a4c93;
        }
        
        .book-2 {
            width: 90%;
            background-color: #ffca28;
        }
        
        .book-3 {
            width: 85%;
            background-color: #1a759f;
        }
        
        .book-4 {
            width: 70%;
            background-color: #ff8fa3;
        }
        
        .book-5 {
            width: 75%;
            background-color: #52b788;
        }
        
        .person-top {
            position: absolute;
            top: 20px;
            right: 30px;
            width: 70px;
            height: 100px;
            background-color: #ff8fa3;
            border-radius: 10px 10px 0 0;
            z-index: 5;
        }
        
        .person-top::before {
            content: "";
            position: absolute;
            width: 40px;
            height: 40px;
            background-color: #f8edeb;
            border-radius: 50%;
            top: -30px;
            left: 15px;
        }
        
        .person-bottom {
            position: absolute;
            bottom: 60px;
            left: 30px;
            width: 70px;
            height: 100px;
            background-color: #ffd166;
            border-radius: 10px 10px 0 0;
            z-index: 5;
        }
        
        .person-bottom::before {
            content: "";
            position: absolute;
            width: 40px;
            height: 40px;
            background-color: #f8edeb;
            border-radius: 50%;
            top: -30px;
            left: 15px;
        }
        
        .plant {
            position: absolute;
            width: 15px;
            height: 40px;
            bottom: 10px;
            right: 20px;
            background-color: #61d095;
            border-radius: 0 15px 0 15px;
        }
        
        .plant::before {
            content: "";
            position: absolute;
            width: 15px;
            height: 30px;
            background-color: #61d095;
            border-radius: 15px 0 15px 0;
            top: 0;
            left: -20px;
        }
        
        .plant::after {
            content: "";
            position: absolute;
            width: 5px;
            height: 50px;
            background-color: #2d6a4f;
            bottom: 0;
            left: 5px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-form, .login-image {
                width: 100%;
            }
            
            .login-image {
                order: -1;
                padding: 30px 0;
                height: 200px;
            }
            
            .login-form {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <div class="login-form">
            <div class="logo">
                <div class="logo-icon">Hi</div>
                <div class="logo-text">E-Library</div>
            </div>
            
           <form method="POST" action="">
    <h2>Register E-Library</h2>
    <?php if($error): ?>
        <div class="error"><?=htmlspecialchars($error)?></div>
    <?php elseif($success): ?>
        <div class="success"><?=$success?></div>
    <?php endif; ?>
    <div class="form-group">
<input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required /><br><br>
    <input type="text" name="username" placeholder="Username" required /><br><br>
    <input type="email" name="email" placeholder="Email" required /><br><br>
    <input type="password" name="password" placeholder="Password" required /><br><br>
    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required /><br><br>
    <button class="login-button" type="submit">Register</button>
    <div class="login-link">Sudah punya akun? <a href="login.php">Login di sini</a></div>
    </div>
    
</form>
        </div>
        
        <div class="login-image">
            <div class="illustration">
                <div class="book-stack">
                    <div class="book book-1"></div>
                    <div class="book book-2"></div>
                    <div class="book book-3"></div>
                    <div class="book book-4"></div>
                    <div class="book book-5"></div>
                </div>
                <div class="person-top"></div>
                <div class="person-bottom"></div>
                <div class="plant"></div>
            </div>
        </div>
    </div>
   
</body>
</html>