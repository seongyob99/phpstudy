<!-- TODO 목록 화면 -->
<?php 
require 'db.php';

$todos = $pdo->query("SELECT * FROM todos ORDER BY id DESC")->fetchAll();
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>TODO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">📝 TODO List</h2>

  <!-- 추가 폼 -->
  <form class="d-flex mb-4" method="post" action="add.php">
    <input class="form-control me-2" name="title" placeholder="할 일 입력" required>
    <button class="btn btn-primary">추가</button>
  </form>

  <div class="row g-3">
    <?php foreach ($todos as $todo): ?>
      <div class="col-md-4">
        <div class="card <?= $todo['is_done'] ? 'border-success' : '' ?>">
          <div class="card-body">

            <h5 class="card-title <?= $todo['is_done'] ? 'text-decoration-line-through text-muted' : '' ?>">
              <?= htmlspecialchars($todo['title']) ?>
            </h5>
            <div class="d-flex justify-content-end gap-2">
              <a href="toggle.php?id=<?= $todo['id'] ?>"
                 class="btn btn-sm btn-outline-success">
                <?= $todo['is_done'] ? '되돌리기' : '완료' ?>
              </a>

            <form action="delete.php" method="POST" onsubmit="return confirm('삭제하시겠습니까?')" style="display:inline;">
                <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">삭제</button>
            </form>

             <a href="edit.php?id=<?= $todo['id'] ?>"
                class="btn btn-sm btn-outline-secondary">
                수정
            </a>
            </div>

          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>

</div>
</body>
</html>