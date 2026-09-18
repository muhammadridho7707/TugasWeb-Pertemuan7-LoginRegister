<?php
require_once __DIR__ .'/includes/config.php';

$_SESSION = [];
session_destroy();

if(isset($_COOKIE['remember_email'])){
    setcookie('remember_email', '', time() - 300, '/');
}

header('Location: login.php?logout=1');
exit;