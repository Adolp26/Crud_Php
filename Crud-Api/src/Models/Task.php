<?php
namespace App\Models;

use PDO;
use DI\Container;

class Task
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAlltasks()
    {
        $query = "SELECT * FROM tasks";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function createTasks($title, $description)
{
    // Verifica se os valores não são nulos
    if ($title !== null && $description !== null) {
        $query = 'INSERT INTO tasks (title, description, status) VALUES (?, ?, true)';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$title, $description]);
        
    }
    // Se algum dos valores for nulo, retorne false
    return false;
}
    public function deleteTasks($id)
    {
        $query = 'DELETE FROM tasks WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function updateTasks($id, $description)
    {
        $query = 'UPDATE tasks SET description = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$description, $id]);
    }

    public function toggleStatus($id)
    {
        $query = 'UPDATE tasks SET status = NOT status WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    public function readStatusTrue()
{
    $query = 'SELECT * FROM tasks WHERE status = true';
    $stmt = $this->db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function readStatusFalse()
{
    $query = 'SELECT * FROM tasks WHERE status = false';
    $stmt = $this->db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

