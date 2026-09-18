<?php
if (session_status  () == PHP_SESSION_NONE) {
    session_start ();
}

define('USERS_FILE',__DIR__ . '/../data/users.json' );

function getUsers(): array
{
    if(!file_exists(USERS_FILE)){
        file_put_contents(USERS_FILE, json_encode([]));
    }
    $json =file_get_contents(USERS_FILE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function saveUsers(array $users): bool
{
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(USERS_FILE, $json) !== false; 
}

function findUserByEmail(string $email): ?array
{
    $users = getUsers();
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
                    return $user;
        }
    }
    return null;
}

function clean(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES,'utf-8');
}