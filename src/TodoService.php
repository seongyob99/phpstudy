<?php
class TodoService {
    private $repository;

    public function __construct(TodoRepository $repository) {
        $this->repository = $repository;
    }

    public function getDashboardData($page, $search) {
        $limit = 6;
        $offset = ($page - 1) * $limit;

        // 통계
        $stats = $this->repository->getStats();
        // DB 조회 실패 시 기본값 설정 (Warning 방지)
        if (!$stats) {
            $stats = ['total' => 0, 'completed' => 0];
        }

        $totalCount = (int)($stats['total'] ?? 0);
        $completedCount = (int)($stats['completed'] ?? 0);
        $remainingCount = $totalCount - $completedCount;

        // 페이징 및 목록
        $totalTodos = $this->repository->countAll($search);
        $totalPages = (int)ceil($totalTodos / $limit);
        $todos = $this->repository->findAll($search, $limit, $offset);

        return [
            'counts' => [
                'total' => $totalCount,
                'completed' => $completedCount,
                'remaining' => $remainingCount
            ],
            'pagination' => [
                'totalPages' => $totalPages,
                'page' => $page,
                'search' => $search
            ],
            'todos' => $todos
        ];
    }

    public function add($title) {
        if ($title !== '') {
            $this->repository->save($title);
        }
    }

    public function get($id) {
        return $this->repository->findById($id);
    }

    public function edit($id, $title) {
        if ($title !== '') {
            $this->repository->update($id, $title);
        }
    }

    public function toggle($id) {
        $this->repository->toggle($id);
    }

    public function remove($id) {
        if ($id > 0) {
            $this->repository->delete($id);
        }
    }
}