<?php
require 'db.php';

$title = $_POST['title'] ?? '';

if ($title !== '') {
  $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (?)");
  $stmt->execute([$title]);
}

header("Location: index.php");
exit;
