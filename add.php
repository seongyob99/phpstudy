<!-- 글 추가 -->
<?php
require 'src/db.php';
require 'src/TodoRepository.php';
require 'src/TodoService.php';

$title = $_POST['title'] ?? '';

$service = new TodoService(new TodoRepository($pdo));
$service->add($title);

header("Location: index.php");
exit;
