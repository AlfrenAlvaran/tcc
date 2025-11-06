<?php
require_once __DIR__ . '/../config/PDOConnection.php';

class Classes
{
    private $conn;
    public function __construct()
    {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }

    public function getClasses($id)
    {
        $stmt = $this->conn->prepare("SELECT 
            sc.id AS schedule_id,
            subj.sub_code,
            subj.sub_name,
            subj.units,
            p.p_code,
            p.p_year,
            sc.day,
            sc.time_start,
            sc.time_end,
            COALESCE(sc.room, 'No Room Assigned') AS room,
            CONCAT(a.fname, ' ', a.lname) AS teacher_name,
            GROUP_CONCAT(CONCAT(s.Student_FName, ' ', s.Student_LName) SEPARATOR ', ') AS students
        FROM schedule sc
        JOIN subjects subj ON sc.subject_id = subj.sub_id
        JOIN programs p ON sc.program_id = p.program_id
        JOIN accounts a ON sc.teacher_id = a.id
        LEFT JOIN enrolled_students es ON es.schedule_id = sc.id
        LEFT JOIN students s ON es.student_id = s.Student_id
        WHERE sc.teacher_id = {$id}
        GROUP BY sc.id, subj.sub_code, subj.sub_name, subj.units, 
                 p.p_code, p.p_year, sc.day, sc.time_start, sc.time_end, sc.room, teacher_name
        ORDER BY sc.day, sc.time_start, subj.sub_code");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentWithProgramAndStudents(int $id)
    {
        $stmt = $this->conn->prepare("
        SELECT es.*
        FROM schedule s
        INNER JOIN programs p ON s.program_id = p.program_id
        INNER JOIN enrolled_students es ON es.course = p.p_code
        WHERE s.id = ?
    ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveSection(array $data)
    {
        $stmt = $this->conn->prepare("INSERT INTO section_tb (section_name, year_level) VALUES (?, ?)");
        return $stmt->execute([$data['section_name'], $data['year_level']]);
    }
}
