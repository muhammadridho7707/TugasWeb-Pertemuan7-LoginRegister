<?php

require_once __DIR__ ."/config.php";

function requireLogin (): void
{
    if(!isset($_SESSION["user"])){
    header('location: login.php' );
    exit;
    }
}