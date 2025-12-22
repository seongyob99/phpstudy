<?php
require 'src/db.php';
require 'src/TodoRepository.php';
require 'src/TodoService.php';

$service = new TodoService(new TodoRepository($pdo));

// POST 요청이면 저장 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    
    $service->add($title, $content);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>할 일 추가</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3 class="mb-4">✏️ 할 일 추가</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">제목</label>
      <input type="text" name="title" class="form-control" placeholder="할 일을 입력하세요" required>
    </div>
    <div class="mb-3">
      <label class="form-label">내용</label>
      <textarea name="content" class="form-control" rows="5" placeholder="상세 내용을 입력하세요"></textarea>
    </div>
    <button class="btn btn-primary">저장</button>
    <a href="index.php" class="btn btn-secondary">취소</a>
  </form>
</div>
</body>
</html>
