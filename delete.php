<!-- 삭제 php -->
<?php
require 'db.php';

$id =(int)$_POST['id'] ?? 0;

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
    }
}

header("Location: index.php");
exit;
