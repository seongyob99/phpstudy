<!-- TODO 목록 화면 -->
<?php 
require 'src/db.php';
require 'src/TodoRepository.php';
require 'src/TodoService.php';

$service = new TodoService(new TodoRepository($pdo));

$search = $_GET['search'] ?? '';
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$data = $service->getDashboardData($page, $search);

$totalCount = $data['counts']['total'];
$completedCount = $data['counts']['completed'];
$remainingCount = $data['counts']['remaining'];
$todos = $data['todos'];
$totalPages = $data['pagination']['totalPages'];
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

  <!-- 검색 및 글쓰기 버튼 -->
  <form class="d-flex mb-4 gap-2" method="get" action="index.php">
    <div class="input-group">
      <input class="form-control" name="search" placeholder="검색어 입력" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button class="btn btn-primary" type="submit">검색</button>
    </div>
    <a href="add.php" class="btn btn-success text-nowrap">글쓰기</a>
    <?php if(isset($_GET['search'])): ?>
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
            <p class="card-text text-muted small">
              <?= nl2br(htmlspecialchars($todo['content'] ?? '')) ?>
            </p>
            <p class="card-text text-end"><small class="text-muted">
              작성: <?= date('Y-m-d', strtotime($todo['created_at'])) ?>
            </small></p>
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