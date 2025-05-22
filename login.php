<?php
session_start();
require "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = $conn->real_escape_string($_POST['username_email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // simpan session
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'nama_lengkap' => $user['nama_lengkap'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
                exit();
            } else {
                header("Location: dashboard_user.php");
                exit();
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username atau email tidak ditemukan!";
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
            padding: 50px;
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
            
            <div class="login-header">
                <h1>Log in</h1>
            </div>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="email">Email or Phone number</label>
                    <input type="text" id="email" name="username_email" placeholder="" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="" required>
                </div>
                
                <button type="submit" class="login-button">Log in</button>
                 <div class="login-link">Belum punya akun? <a href="register.php">Register</a></div>
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