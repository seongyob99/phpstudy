<?php
require 'src/db.php';
require 'src/TodoRepository.php';
require 'src/TodoService.php';

$id = (int)$_GET['id'] ?? 0;

$service = new TodoService(new TodoRepository($pdo));
$service->toggle($id);

header("Location: index.php");
exit;
