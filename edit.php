<?php
require 'db.php';

$id =(int)$_GET['id'] ?? 0;

// POST 요청이면 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';

    if ($title !== '') {
        $stmt = $pdo->prepare("UPDATE todos SET title = ? WHERE id = ?");
        $stmt->execute([$title, $id]);
    }

    header("Location: index.php");
    exit;
}

// GET 요청이면 기존 데이터 조회
$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = ?");
$stmt->execute([$id]);
$todo = $stmt->fetch();

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
    <input
      class="form-control mb-3"
      name="title"
      value="<?= htmlspecialchars($todo['title']) ?>"
      required
    >

    <button class="btn btn-primary">수정</button>
    <a href="index.php" class="btn btn-secondary">취소</a>
  </form>
</div>

</body>
</html>
