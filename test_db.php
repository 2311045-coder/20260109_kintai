<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=kintaidb;charset=utf8',
    'kintaiuser',
    'kintaipass123'
);
echo 'DB接続成功';
