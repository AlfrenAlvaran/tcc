<?php
require_once __DIR__ . '/../config/PDOConnection.php';
class Grades {
    private $conn;
    public function __construct() {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }

    public function getStudent(int $id) {
        $stmt=$this->conn->prepare("SELECT * FROM enrolled_students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
   
    public function getGrades(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM grade WHERE student_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}