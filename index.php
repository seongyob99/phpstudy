<!-- TODO 목록 화면 -->
<?php 
require 'db.php';

// 전체, 완료, 남은 할 일 개수 계산
$countStmt = $pdo->query("SELECT COUNT(*) as total, SUM(is_done) as completed FROM todos");
$counts = $countStmt->fetch();
$totalCount = (int)($counts['total'] ?? 0);
$completedCount = (int)($counts['completed'] ?? 0);
$remainingCount = $totalCount - $completedCount;

// 검색
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM todos";
$params = [];

if ($search !== '') {
  $sql .= " WHERE title LIKE ?";
  $params[] = "%$search%";
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$todos = $stmt->fetchAll();
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

  <!-- 검색 -->
<form class="d-flex mb-3" method="get" action="index.php">
    <input class="form-control me-2" name="search" placeholder="검색어 입력" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-primary" type="submit">검색</button>
    <?php if(isset($_GET['search'])): ?>
        <a href="index.php" class="btn btn-outline-secondary">초기화</a>
    <?php endif; ?>
</form>

  <!-- TODO 요약 -->
  <div class="alert alert-info mb-4">
    총 <strong><?= $totalCount ?></strong>개 | 완료: <strong><?= $completedCount ?></strong>개 | 남은 할 일: <strong><?= $remainingCount ?></strong>개
  </div>

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