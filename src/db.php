<!-- DB 연결 PHP -->
<?php
$host = 'localhost';
$db = 'todo_app';
$user = 'root';
$pass = 'asd1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("서비스 이용이 원활하지 않습니다. 잠시 후 다시 시도해주세요");
}