<?php
require_once 'BaseModel.php';
class Task extends BaseModel
{
    public $tableName = 'tasks';
    public $columns = ['title', 'content', 'status', 'user_id'];

    public function getTask()
    {
        $stmt = $this->connection->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
        return $result;
    }

    public function searchTask($searchTerm)
    {
        $stmt = $this->connection->prepare("SELECT * FROM tasks WHERE title LIKE :searchTerm OR content LIKE :searchTerm OR status LIKE :searchTerm OR user_id LIKE :searchTerm");
        $stmt->bindValue(':searchTerm', "%$searchTerm%");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
        return $result;

    }
}
?>