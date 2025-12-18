<!-- 삭제 php -->
<?php
require 'db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM todos WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
