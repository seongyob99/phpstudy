<!-- 삭제 php -->
<?php
require 'src/db.php';
require 'src/TodoRepository.php';
require 'src/TodoService.php';

$id =(int)$_POST['id'] ?? 0;

$service = new TodoService(new TodoRepository($pdo));
$service->remove($id);

header("Location: index.php");
exit;
