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



    public function studentCurriculum($studentId)
    {

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


        $takenStmt = $this->conn->prepare("
        SELECT subject_id, grade FROM enrolled_curriculum
        WHERE student_id = ?
    ");
        $takenStmt->execute([$studentId]);
        $takenSubjects = $takenStmt->fetchAll(PDO::FETCH_ASSOC);

       
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
            // $row['status'] = in_array($row['sub_id'], $takenSubjects) ? '✓' : 'X';
            $row['status'] = array_reduce($takenSubjects, function ($carry, $t) use ($row) {
                if ($t['subject_id'] == $row['sub_id']) {
                    return is_null($t['grade']) ? '✔' : $t['grade'];
                }
                return $carry;
            }, 'X');

            $grouped[$semKey][] = $row;
        }

        return $grouped;
    }

    public function studentCurriculums($studentId)
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

        $programId = (int)$student['program_id'];

        // 2. Get all curriculum subjects
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

        // 3. Get all enrolled subjects with grades
        $takenStmt = $this->conn->prepare("
        SELECT subject_id, grade
        FROM enrolled_curriculum
        WHERE student_id = ?
    ");
        $takenStmt->execute([$studentId]);
        $takenSubjects = $takenStmt->fetchAll(PDO::FETCH_ASSOC);

        // Map subject_id to grade for fast lookup
        $takenMap = [];
        foreach ($takenSubjects as $t) {
            $takenMap[$t['subject_id']] = $t['grade']; // can be NULL
        }

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

            // Determine status
            if (!isset($takenMap[$row['sub_id']])) {
                $row['status'] = 'X'; // Not enrolled
            } else {
                $row['status'] = is_null($takenMap[$row['sub_id']])
                    ? 'Enrolled' // Enrolled but grade not yet given
                    : $takenMap[$row['sub_id']]; // Show actual grade
            }

            $grouped[$semKey][] = $row;
        }

        return $grouped;
    }
}
