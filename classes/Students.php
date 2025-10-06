<?php
require_once __DIR__ . "/../config/PDOConnection.php";
class Students
{
    private $conn;
    public function __construct()
    {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }


    public function getSemestersEnrolled($student_id)
    {

        $stmt = $this->conn->prepare("SELECT e.*, s.* FROM enrollments e INNER JOIN students s ON e.student_id = s.Student_id WHERE e.student_id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjectsBySemesterYear($studentID, $year, $semester) {
         $stmt = $this->conn->prepare("
            SELECT 
                ec.level,
                ec.semester,
                ec.sy,
                ec.program_id,
                p.p_code
            FROM enrolled_curriculum ec
            JOIN programs p ON ec.program_id = p.program_id
            WHERE ec.student_id = ?
                AND ec.level = ?
                AND ec.semester = ?
            GROUP BY ec.level, ec.semester, ec.sy, ec.program_id
            ORDER BY ec.level ASC, ec.semester ASC
        ");
        $stmt->execute([$studentID, $year, $semester]);
        $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($enrollments as $key => $row) {
            $subjectsStmt = $this->conn->prepare("
                SELECT 
                    s.sub_id,
                    s.sub_code,
                    s.sub_name,
                    s.units,
                    s.withLab
                FROM enrolled_curriculum ec
                JOIN subjects s ON ec.subject_id = s.sub_id
                WHERE ec.student_id = ?
                  AND ec.level = ?
                  AND ec.semester = ?
                  AND ec.sy = ?
            ");
            $subjectsStmt->execute([
                $studentID,
                $row['level'],
                $row['semester'],
                $row['sy']
            ]);
            $enrollments[$key]['subjects'] = $subjectsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $enrollments;
    }
}
