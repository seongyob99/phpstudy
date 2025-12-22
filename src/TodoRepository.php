<?php
class TodoRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getStats() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total, SUM(is_done) as completed FROM todos");
        return $stmt->fetch();
    }

    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM todos";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE title LIKE ?";
            $params[] = "%$search%";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function findAll($search, $limit, $offset) {
        $sql = "SELECT * FROM todos";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE title LIKE ?";
            $params[] = "%$search%";
        }
        $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function save($title) {
        $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (?)");
        $stmt->execute([$title]);
    }

    public function update($id, $title) {
        $stmt = $this->pdo->prepare("UPDATE todos SET title = ? WHERE id = ?");
        $stmt->execute([$title, $id]);
    }

    public function toggle($id) {
        $stmt = $this->pdo->prepare("UPDATE todos SET is_done = IF(is_done = 1, 0, 1) WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$id]);
    }
}