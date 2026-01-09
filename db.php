<?php
$dsn = 'mysql:host=localhost;dbname=kintaidb;charset=utf8';
$user = 'kintaiuser';
$password = 'kintaipass123';

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    exit('DB接続エラー: ' . $e->getMessage());
}
