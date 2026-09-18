<?php
require_once __DIR__ .'/includes/config.php';

if(isset($_SESSION['user'])){
    header('Location: dashboard.php');
    exit;
}

$error = '[]';
$success = '';

$old = ['nama' => '', 'email' => ''];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nama =trim($_POST['nama'] ?? '');
    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';
    $konfirmasi = trim($_POST['konfirmasi_password'] ?? '');
    $old['nama'] = $nama;
    $old['email'] = $email;

    if ($nama === '' || $email === '' || $password ===''){
        $errors[] = 'Semua filed wajib disini';
    }

    if($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Format email tidak valid';
    }

    if(strlen($password) <6){
        $errors[] = 'Password minimal 6 karakter';
    }

    if($password !== $konfirmasi){
        $errors[] = 'Password tidak cocok';
    }

    if(empty($errors) && findUserByEmail($email) !== null){
        $errors[] = 'Email sudah tedaftar, silahkan gunakna email lain';
    }

    if(empty($errors)){
        $users = getUsers();

        $users[]= [
            'id' => uniqid('user_'),
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created at' => date('Y-m-d H:i:s'),
        ];

        if(saveUsers($users)){
            $success = 'Registrasi berhasil! silahkan login';
            $old = ['nama' => '', 'email' => ''];
        } else {
            $errors[] = 'Terjadi kesalahan saat menyimpan data. Silahlan coba lagi';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RidosaurusApp - LoginRegister</title>
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
        <h2>📝 Daftar Akun</h2>
 
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= clean($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
 
        <?php if ($success): ?>
            <div class="alert alert-success" style="text-align: center;">
                <?= clean($success) ?>
            </div>
        <?php endif; ?>
 
        <?php if (!$success): ?>
        <form method="POST" action="register.php">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= clean($old['nama']) ?>" required>
 
            <label>Email</label>
            <input type="email" name="email" value="<?= clean($old['email']) ?>" required>
 
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">👁</button>
            </div>

 
            <label>Konfirmasi Password</label>
            <div class="password-wrapper">
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('konfirmasi_password', this)">👁</button>
            </div>

            <button type="submit">Daftar</button>
        </form>
        <?php endif; ?>
 
        <p class="footer-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
