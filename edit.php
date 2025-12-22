<?php
require 'src/db.php';
require 'src/TodoRepository.php';
require 'src/TodoService.php';

$id =(int)$_GET['id'] ?? 0;
$service = new TodoService(new TodoRepository($pdo));

// POST 요청이면 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $service->edit($id, $title, $content);

    header("Location: index.php");
    exit;
}

// GET 요청이면 기존 데이터 조회
$todo = $service->get($id);

if (!$todo) {
    echo "존재하지 않는 TODO";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>TODO 수정</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h3 class="mb-3">✏️ TODO 수정</h3>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">제목</label>
      <input type="text" name="title" class="form-control" 
             value="<?= htmlspecialchars($todo['title']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">내용</label>
      <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars($todo['content'] ?? '') ?></textarea>
    </div>

    <button class="btn btn-primary">수정</button>
    <a href="index.php" class="btn btn-secondary">취소</a>
  </form>
</div>

</body>
</html>
