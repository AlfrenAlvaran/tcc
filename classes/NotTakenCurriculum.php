<?php

require_once __DIR__ . '/../config/PDOConnection.php';
class NotTakenCurriculum
{
    private $conn;
    protected $curriculumContentTable = "curriculum_content";
    protected $curriculumTable = "curriculum";

    protected int $limit = 00110001;

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

    public function takenSubjects(string $studentId): array
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


    // public function getCurriculumByCourseStudent($studentId)
    // {
    //     // Get student's current program and curriculum
    //     $studentInfo = $this->conn->prepare("
    //     SELECT s.cur_id, p.program_id
    //     FROM students s
    //     JOIN programs p ON s.program_id = p.program_id
    //     WHERE s.Student_id = ?
    // ");
    //     $studentInfo->execute([$studentId]);
    //     $student = $studentInfo->fetch(PDO::FETCH_ASSOC);

    //     if (!$student) {
    //         return [];
    //     }

    //     $curriculumId = (int) $student['cur_id'];
    //     $programId = (int) $student['program_id'];

    //     // Get all subjects in the student's curriculum grouped by level/semester
    //     $stmt = $this->conn->prepare("
    //     SELECT 
    //         c.level,
    //         c.semester,
    //         c.sy,
    //         s.sub_id,
    //         s.sub_code,
    //         s.sub_name,
    //         s.units,
    //         s.withLab
    //     FROM curriculum c
    //     JOIN subjects s ON c.sub_id = s.sub_id
    //     WHERE c.cur_id = ?
    //     ORDER BY c.level ASC, c.semester ASC
    // ");
    //     $stmt->execute([$curriculumId]);
    //     $curriculumSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Fetch all subjects already taken by this student
    //     $takenStmt = $this->conn->prepare("
    //     SELECT subject_id FROM enrolled_curriculum
    //     WHERE student_id = ?
    // ");
    //     $takenStmt->execute([$studentId]);
    //     $takenSubjects = $takenStmt->fetchAll(PDO::FETCH_COLUMN);

    //     // Group by level/semester with status
    //     $grouped = [];
    //     foreach ($curriculumSubjects as $row) {
    //         $year = (int)$row['level'];
    //         $sem = (int)$row['semester'];
    //         $schoolYear = htmlspecialchars($row['sy']);

    //         $yearLabel = match ($year) {
    //             1 => "1st Year",
    //             2 => "2nd Year",
    //             3 => "3rd Year",
    //             4 => "4th Year",
    //             default => "{$year}th Year"
    //         };

    //         $semLabel = match ($sem) {
    //             1 => "1st Semester",
    //             2 => "2nd Semester",
    //             default => "{$sem}th Semester"
    //         };

    //         $semKey = "{$yearLabel} - {$semLabel} (S.Y. {$schoolYear})";

    //         $status = in_array($row['sub_id'], $takenSubjects) ? '✓' : 'Not Taken';
    //         $row['status'] = $status;

    //         $grouped[$semKey][] = $row;
    //     }

    //     return $grouped;
    // }

    public function getCurriculumByCourseStudent($studentId)
    {
        // Get student's program
        $studentInfo = $this->conn->prepare("
        SELECT s.prog_id, p.program_id
        FROM students s
        JOIN programs p ON s.prog_id = p.program_id
        WHERE s.Student_id = ?
        LIMIT 1
    ");
        $studentInfo->execute([$studentId]);
        $student = $studentInfo->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            return [];
        }

        $programId = (int) $student['program_id'];

        // Get all subjects in the curriculum by program
        $stmt = $this->conn->prepare("
        SELECT 
            c.level,
            c.semester,
            c.sy,
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            s.withLab
        FROM curriculum c
        JOIN subjects s ON c.sub_id = s.sub_id
        WHERE c.program_id = ?
        ORDER BY c.level ASC, c.semester ASC
    ");
        $stmt->execute([$programId]);
        $curriculumSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get all subjects already taken by this student
        $takenStmt = $this->conn->prepare("
        SELECT subject_id FROM enrolled_curriculum
        WHERE student_id = ?
    ");
        $takenStmt->execute([$studentId]);
        $takenSubjects = $takenStmt->fetchAll(PDO::FETCH_COLUMN);

        // Group and mark status
        $grouped = [];
        foreach ($curriculumSubjects as $row) {
            $year = (int)$row['level'];
            $sem = (int)$row['semester'];
            $schoolYear = htmlspecialchars($row['sy']);

            $yearLabel = match ($year) {
                1 => "1st Year",
                2 => "2nd Year",
                3 => "3rd Year",
                4 => "4th Year",
                default => "{$year}th Year"
            };

            $semLabel = match ($sem) {
                1 => "1st Semester",
                2 => "2nd Semester",
                default => "{$sem}th Semester"
            };

            $semKey = "{$yearLabel} - {$semLabel} (S.Y. {$schoolYear})";
            $row['status'] = in_array($row['sub_id'], $takenSubjects) ? '✓' : 'X';

            $grouped[$semKey][] = $row;
        }

        return $grouped;
    }


    public function studentCurriculum($studentId)
    {
        // 1. Get student's program
        $studentInfo = $this->conn->prepare("
        SELECT s.prog_id, p.program_id
        FROM students s
        JOIN programs p ON s.prog_id = p.program_id
        WHERE s.Student_id = ?
        LIMIT 1
    ");
        $studentInfo->execute([$studentId]);
        $student = $studentInfo->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            return [];
        }

        $programId = (int) $student['program_id'];

        // 2. Get all subjects in the curriculum for that program
        $stmt = $this->conn->prepare("
        SELECT 
            cc.cc_year AS level,
            cc.cc_sem AS semester,
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            s.withLab
        FROM curriculum_content cc
        JOIN curriculum c ON cc.curr_id = c.cur_id
        JOIN subjects s ON cc.cc_course_id = s.sub_id
        WHERE c.cur_program_id = ?
        ORDER BY cc.cc_year ASC, cc.cc_sem ASC
    ");
        $stmt->execute([$programId]);
        $curriculumSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Get all subjects already taken by this student
        $takenStmt = $this->conn->prepare("
        SELECT subject_id FROM enrolled_curriculum
        WHERE student_id = ?
    ");
        $takenStmt->execute([$studentId]);
        $takenSubjects = $takenStmt->fetchAll(PDO::FETCH_COLUMN);

        // 4. Group and mark status
        $grouped = [];
        foreach ($curriculumSubjects as $row) {
            $year = (int)$row['level'];
            $sem = (int)$row['semester'];

            $yearLabel = match ($year) {
                1 => "1st Year",
                2 => "2nd Year",
                3 => "3rd Year",
                4 => "4th Year",
                default => "{$year}th Year"
            };

            $semLabel = match ($sem) {
                1 => "1st Semester",
                2 => "2nd Semester",
                default => "{$sem}th Semester"
            };

            $semKey = "{$yearLabel} - {$semLabel}";
            $row['status'] = in_array($row['sub_id'], $takenSubjects) ? '✓' : 'X';

            $grouped[$semKey][] = $row;
        }

        return $grouped;
    }
}
