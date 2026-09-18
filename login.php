<?php
require_once __DIR__ . '/includes/config.php';
 
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}
 
$errors = [];
$old = ['email' => ''];

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_email'])) {
    $cookieUser = findUserByEmail($_COOKIE['remember_email']);
    if ($cookieUser !== null) {
        $_SESSION['user'] = [
            'id'    => $cookieUser['id'],
            'nama'  => $cookieUser['nama'],
            'email' => $cookieUser['email'],
        ];
        header('Location: dashboard.php');
        exit;
    }
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
 
    $old['email'] = $email;
 
    if ($email === '' || $password === '') {
        $errors[] = 'Email dan password wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    } else {
        $user = findUserByEmail($email);
 
        if ($user === null || !password_verify($password, $user['password'])) {
            $errors[] = 'Email atau password salah.';
        } else {
             $_SESSION['user'] = [
                'id'    => $user['id'],
                'nama'  => $user['nama'],
                'email' => $user['email'],
            ];
            if ($remember) {
                setcookie('remember_email', $user['email'], time() + (30 * 24 * 60 * 60), '/');
            }
 
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<script>
const eyeIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;

const eyeOffIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        btn.innerHTML = eyeIcon;
    } else {
        input.type = "password";
        btn.innerHTML = eyeOffIcon;
    }
}
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.innerHTML = eyeOffIcon;
});
</script>
<body>
    <div class="card">
        <h2>🔐 Login</h2>
 
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= clean($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
 
        <?php if (isset($_GET['logout'])): ?>
            <div class="alert alert-success">Kamu berhasil logout.</div>
        <?php endif; ?>
 
        <form method="POST" action="login.php">
            <label>Email</label>
            <input type="email" name="email" value="<?= clean($old['email']) ?>" required>
 
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">👁</button>
            </div>
 
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
 
            <button type="submit">Login</button>
        </form>
 
        <p class="footer-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
 