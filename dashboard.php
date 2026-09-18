<?php
require_once __DIR__ . '/includes/auth.php';

requireLogin();
 
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h2>👋 Selamat datang, <?= clean($user['nama']) ?>!</h2>
 
        <div class="profile-box">
            <p><strong>Nama:</strong> <?= clean($user['nama']) ?></p>
            <p><strong>Email:</strong> <?= clean($user['email']) ?></p>
            <p><strong>ID:</strong> <?= clean($user['id']) ?></p>
        </div>
 
        <p>Ini adalah halaman dashboard yang hanya bisa diakses jika kamu sudah login.</p>
 
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</body>
</html>
 