<?php
require_once __DIR__ .'/includes/config.php';

if(isset($_SESSION['user'])){
    header('Location: dahsboard.php');
}else{
    header('Location: login.php');
}
exit;