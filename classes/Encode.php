<?php

require_once __DIR__ . "/../config/PDOConnection.php";

class Encode
{
    private $conn;

    public function __construct()
    {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }


   
    public function getStudentAssignedSubjects($id)
    {
        $stmt = $this->conn->prepare("SELECT 
            sc.id,
            sub.sub_code,
            sub.sub_name,
            p.p_code,
            p.p_year,
            sc.day,
            sc.time_start,
            sc.time_end,
            COALESCE(sc.room,  'No Room Assigned') AS room,
            e.student_id,
            e.curriculum_id,
            CONCAT(a.fname, ' ', a.lname) AS teacher_name,
            GROUP_CONCAT(CONCAT(s.Student_FName, ' ', s.Student_LName) SEPARATOR ', ') AS students
            FROM schedule sc
            JOIN subjects sub ON sc.subject_id = sub.sub_id
            JOIN programs p ON sc.program_id = p.program_id
            JOIN accounts a ON sc.teacher_id = a.id
            LEFT JOIN enrollments e ON  e.student_id = sc.id
            LEFT JOIN students s ON s.Student_id = e.student_id
            WHERE sc.teacher_id = ?
            GROUP BY sc.id, sub.sub_code, sub.sub_name, sub.units, 
                 p.p_code, p.p_year, sc.day, sc.time_start, sc.time_end, sc.room, teacher_name
        ORDER BY sc.day, sc.time_start, sub.sub_code");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function encodeGrade() {
        
    }
    
}
