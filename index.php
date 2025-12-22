<!-- TODO 목록 화면 -->
<?php 
require 'db.php';

// 전체, 완료, 남은 할 일 개수 계산
$countStmt = $pdo->query("SELECT COUNT(*) as total, SUM(is_done) as completed FROM todos");
$counts = $countStmt->fetch();
$totalCount = (int)($counts['total'] ?? 0);
$completedCount = (int)($counts['completed'] ?? 0);
$remainingCount = $totalCount - $completedCount;

// 검색 및 페이징
$search = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// 검색 조건에 맞는 TODO 개수 계산 (페이징용)
$countSql = "SELECT COUNT(*) FROM todos";
$params = [];
if ($search !== '') {
    $countSql .= " WHERE title LIKE ?";
    $params[] = "%$search%";
}
$countStmtForPage = $pdo->prepare($countSql);
$countStmtForPage->execute($params);
$totalTodos = (int)$countStmtForPage->fetchColumn();

// 총 페이지 수 계산
$totalPages = (int)ceil($totalTodos / $limit);

// 현재 페이지의 TODO 목록 가져오기
$todosSql = "SELECT * FROM todos";
if ($search !== '') {
    $todosSql .= " WHERE title LIKE ?";
}
$todosSql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($todosSql);
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

  <!-- 페이징 -->
  <?php if ($totalPages > 1): ?>
  <nav class="mt-4">
    <ul class="pagination justify-content-center">
      <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">이전</a>
      </li>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">다음</a>
      </li>
    </ul>
  </nav>
  <?php endif; ?>

</div>
</body>
</html>