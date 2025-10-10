<?php

require_once __DIR__ . '/../config/PDOConnection.php';
class NotTakenCurriculum
{
    private $conn;
    protected $curriculumContentTable = "curriculum_content";
    protected $curriculumTable = "curriculum";

    public function __construct()
    {
        $this->conn =  PDOConnection::getInstance()->getConnection();
    }


    public function getStudentBasicInfo($id)
    {
        $stmt = $this->conn->prepare("SELECT s.*, p.*, e.*, c.cur_id
        FROM students s
        JOIN programs p ON s.prog_id = p.program_id
        JOIN enrollments e ON s.Student_id = e.student_id
        JOIN curriculum c ON p.program_id = c.cur_program_id
        WHERE s.Student_id = ?
        LIMIT 1
    ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function notTakenSubjects(string $studentId, int $curriculumId)
    {
        $sql = "SELECT 
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            s.withLab,
            cc.cc_year,
            cc.cc_sem
        FROM curriculum_content cc
        JOIN subjects s ON cc.cc_course_id = s.sub_id
        JOIN curriculum c ON cc.curr_id = c.cur_id
        WHERE c.cur_id = :curriculumId
          AND s.sub_id NOT IN (
              SELECT subject_id 
              FROM enrolled_curriculum 
              WHERE student_id = :studentId
          )
        ORDER BY cc.cc_year, cc.cc_sem
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'curriculumId' => $curriculumId,
            'studentId' => $studentId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function takenSubjects(string $studentId)
    {
        $sql = "
        SELECT 
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            s.withLab,
            ec.level AS year_level,
            ec.semester AS semester,
            ec.sy
        FROM enrolled_curriculum ec
        JOIN subjects s ON ec.subject_id = s.sub_id
        WHERE ec.student_id = :studentId
        ORDER BY ec.level, ec.semester
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['studentId' => $studentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurriculumByStudent($studentId)
    {
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
            GROUP BY ec.level, ec.semester, ec.sy, ec.program_id
            ORDER BY ec.level ASC, ec.semester ASC
        ");
        $stmt->execute([$studentId]);
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
                $studentId,
                $row['level'],
                $row['semester'],
                $row['sy']
            ]);
            $enrollments[$key]['subjects'] = $subjectsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $enrollments;
    }
}
