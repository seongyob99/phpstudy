<!-- DB 연결 PHP -->
<?php
$pdo = new PDO(
  "mysql:host=localhost;dbname=todo_app;charset=utf8mb4",
  "root",
  "asd1234",
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]
);
