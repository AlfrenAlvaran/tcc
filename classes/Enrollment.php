<?php
require_once __DIR__ . '/../config/PDOConnection.php';
class Enrollment
{
    private $conn;
    public function __construct()
    {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }

    function GetData($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("SQL Error: " . $e->getMessage() . " in query: " . $sql);
        }
    }



    public function enrollStudent(int $studentID, int $curriculumID, int $yr_level, int $sem, string $sy)
    {
        $stmt = $this->conn->prepare("INSERT INTO enrollments (student_id, curriculum_id, yr_level, sem, sy) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$studentID, $curriculumID, $yr_level, $sem, $sy]);
    }

    public function getCurriculumByStudent(int $userId)
    {
        $sql = "SELECT 
                s.user_id,
                s.Student_id,
                s.Student_LName,
                s.Student_FName,
                s.Student_MName,
                s.prog_id,
                p.p_code,
                p.p_des,
                p.p_major,
                p.p_year,
                c.cur_id,
                c.cur_year,
                c.date_created
            FROM students s
            INNER JOIN programs p ON s.prog_id = p.program_id
            INNER JOIN curriculum c ON p.program_id = c.cur_program_id
            WHERE s.user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStudentNotEnrolled()
    {
        $stmt = $this->conn->prepare("SELECT * FROM students");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStudentsWithEnrollments()
    {
      
        $sql = "SELECT 
                    e.id,
                    s.user_id,
                    s.Student_id,
                    e.yr_level,
                    e.sem,
                    e.yr_level,
                    e.sy,
                    s.SY,
                    s.Student_FName,
                    s.Student_MName,
                    s.Student_LName,
                    p.p_code,
                    s.prog_id
                FROM enrollments e
                INNER JOIN students s ON e.student_id = s.Student_id
                INNER JOIN programs p ON s.prog_id = p.program_id
                ORDER BY s.Student_LName;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
