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

    public function enrollStudent($studentID, int $curriculumID, int $yr_level, int $sem, string $sy)
    {
        // Force string conversion before binding
        $stmt = $this->conn->prepare("
        INSERT INTO enrollments (student_id, curriculum_id, yr_level, sem, sy)
        VALUES (?, ?, ?, ?, ?)
    ");
        return $stmt->execute([
            strval(intval($studentID)),  // convert "0005" safely → "5"
            $curriculumID,
            $yr_level,
            $sem,
            $sy
        ]);
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
        $stmt = $this->conn->prepare("SELECT s.*, p.* FROM students s JOIN programs p ON s.prog_id = p.program_id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStudentsWithEnrollments()
{
    $sql = "SELECT 
                s.user_id,
                s.Student_id,
                s.SY,
                s.Student_FName,
                s.Student_LName,
                s.prog_id,
                p.p_code,
                e.sy,
                GROUP_CONCAT(DISTINCT CONCAT(e.sem, '-', e.yr_level, '-', e.sy) ORDER BY e.yr_level, e.sem SEPARATOR ',' ) AS sem_year
                
            FROM enrollments e
            INNER JOIN students s ON e.student_id = s.Student_id
            INNER JOIN programs p ON s.prog_id = p.program_id
            GROUP BY s.Student_id
            ORDER BY s.Student_LName;";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
