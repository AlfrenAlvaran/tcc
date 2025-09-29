<?php
require_once __DIR__ . '/../config/PDOConnection.php';

class Grades
{
    private $conn;

    public function __construct()
    {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }

    public function getStudent(int $id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM enrolled_students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getGrades(int $id, string $subject, int $teacherId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM grade WHERE student_id = ? AND subject = ? AND teacher_id = ?");
        $stmt->execute([$id, $subject, $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function recordGrade(int $studentId, int $teacher_id, string $subject, string $quarter, float $grade, int $semester)
    {
        $stmt = $this->conn->prepare("SELECT id FROM grade WHERE student_id = ? AND subject = ?");
        $stmt->execute([$studentId, $subject]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($quarter === "Prelim") {
                $sql = "UPDATE grade SET prelim = ? WHERE id = ?";
            } elseif ($quarter === "Midterms") {
                $sql = "UPDATE grade SET midterm = ? WHERE id = ?";
            } elseif ($quarter === "Finals") {
                $sql = "UPDATE grade SET finals = ? WHERE id = ?";
            }
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$grade, $existing['id']]);
        } else {
            $sql = "INSERT INTO grade (student_id, subject, prelim, midterm, finals, teacher_id, semesters) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $prelim  = $quarter === "Prelim"   ? $grade : null;
            $midterm = $quarter === "Midterms" ? $grade : null;
            $finals  = $quarter === "Finals"   ? $grade : null;

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$studentId, $subject, $prelim, $midterm, $finals, $teacher_id, $semester]);
        }
    }
}
