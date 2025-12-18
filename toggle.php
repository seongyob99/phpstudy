<?php
require 'db.php';

$id = (int)$_GET['id'] ?? 0;

$stmt = $pdo->prepare("
  UPDATE todos 
  SET is_done = IF(is_done = 1, 0, 1)
  WHERE id = ?
");
$stmt->execute([$id]);

header("Location: index.php");
exit;
