<?php

require_once __DIR__ . '/../config/PDOConnection.php';

class Settings
{
    private $conn;

    public function __construct()
    {
        $database = new PDOConnection();
        $this->conn = $database->getConnection();
    }

    public function saveSchoolYear(array $data)
    {
        $stmt = $this->conn->prepare("INSERT INTO school_year_tb (sy, created_at) VALUES (:sy, :created_at)");
        return $stmt->execute([
            ':sy' => $data['sy'],
            ':created_at' => $data['created_at']
        ]);
    }
}
