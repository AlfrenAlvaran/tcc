<?php
require_once __DIR__ . '/../config/PDOConnection.php';
class Curriculum
{
    private $conn;

    public function __construct()
    {
        $this->conn = PDOConnection::getInstance()->getConnection();
    }


    public function getProgram()
    {
        $stmt = $this->conn->prepare("SELECT * FROM programs");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function assign_curriculum(int $id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM curriculum WHERE cur_program_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


    public function getStudents()
    {
        $sql = "SELECT 
    s.user_id, 
    s.SY, 
    s.Student_id, 
    s.Student_FName, 
    s.Student_LName, 
    p.p_code,
    GROUP_CONCAT(DISTINCT c.cur_year ORDER BY c.cur_year DESC) AS curriculum_years,
    GROUP_CONCAT(DISTINCT cc.cc_sem ORDER BY cc.cc_sem ASC) AS semesters
FROM students s
JOIN programs p ON s.prog_id = p.program_id
JOIN curriculum c ON c.cur_program_id = p.program_id
JOIN curriculum_content cc ON cc.curr_id = c.cur_id
GROUP BY s.user_id;
";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubject()
    {
        $stmt = $this->conn->prepare("SELECT * FROM subjects");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function recordCurriculum(int $studentId, int $subjectId)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO advising_tb (student_id, subject_id) VALUES (?, ?)"
        );
        return $stmt->execute([$studentId, $subjectId]);
    }

    public function getAdvisedSubjects($studentId, $year, $sem)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            s.withLab,
            cc.cc_year,
            cc.cc_sem
        FROM curriculum_content cc
        JOIN subjects s 
            ON cc.cc_course_id = s.sub_id
        JOIN enrollments e 
            ON e.curriculum_id = cc.curr_id
           AND e.student_id = ?
        WHERE cc.cc_year = ?
          AND cc.cc_sem = ?
    ");

        $stmt->execute([$studentId, $year, $sem]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCurriculumByProgram()
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM curriculum_content "
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function GetData($sql, $params = [], $single = true)
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $single
                ? $stmt->fetch(PDO::FETCH_ASSOC)
                : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("SQL Error: " . $e->getMessage() . " in query: " . $sql);
        }
    }


    public function getStudentSubjects($studentId)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            st.Student_id,
            CONCAT(st.Student_FName, ' ', st.Student_LName) AS student_name,
            p.p_code AS program_code,
            cc.cc_year,
            cc.cc_sem,
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            s.withLab
        FROM students st
        JOIN programs p 
            ON st.prog_id = p.program_id
        JOIN curriculum c 
            ON p.program_id = c.cur_program_id
        JOIN curriculum_content cc 
            ON c.cur_id = cc.curr_id
        JOIN subjects s 
            ON cc.cc_course_id = s.sub_id
        WHERE st.Student_id = ?
        ORDER BY cc.cc_year, cc.cc_sem, s.sub_code
    ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurriculumSubjectsWithAdvising(int $studentId)
    {

        $student = $this->GetData(
            "SELECT * FROM students WHERE user_id = ?",
            [$studentId]
        );

        if (!$student) {
            return [];
        }

        $sql = "
        SELECT 
            cc.cc_id,
            cc.cc_year,
            cc.cc_sem,
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            adv.id AS advising_id,
            adv.date_created AS advising_date
        FROM curriculum c
        JOIN curriculum_content cc ON c.cur_id = cc.curr_id
        JOIN subjects s ON cc.cc_course_id = s.sub_id
        LEFT JOIN advising_tb adv 
            ON adv.subject_id = s.sub_id 
           AND adv.student_id = :student_id
        WHERE c.cur_program_id = :prog_id
        ORDER BY cc.cc_year, cc.cc_sem, s.sub_code
    ";

        return $this->GetData(
            $sql,
            [
                ':student_id' => $student['Student_id'],
                ':prog_id'    => $student['prog_id']
            ],
            false
        );
    }

    public function cc_content(int $curr_id, int $year, int $sem)
    {
        $stmt = $this->conn->prepare("SELECT * FROM curriculum_content WHERE curr_id = ? AND cc_year = ? AND cc_sem = ?");
        $stmt->execute([$curr_id, $year, $sem]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getSubjectsByStudentAndSem(int $studentId, int $programId, int $curYear, int $sem)
    {
        $sql = "
        SELECT s.sub_id, s.sub_code, s.sub_name, s.units, cc.cc_year, cc.cc_sem
        FROM enrollments e
        INNER JOIN curriculum c 
            ON e.curriculum_id = c.cur_id
        INNER JOIN curriculum_content cc 
            ON cc.curr_id = c.cur_id AND cc.cc_year = e.yr_level
        INNER JOIN subjects s 
            ON s.sub_id = cc.cc_course_id
        WHERE e.student_id = ?
          AND c.cur_program_id = ?
          AND c.cur_year = ?
          AND cc.cc_sem = ?
    ";

        return $this->GetData($sql, [$studentId, $programId, $curYear, $sem], false);
    }


    public function getSubjectsWithAdvising(
        int $studentId,
        int $userId,
        int $programId,
        int $curYear,
        int $sem
    ) {
        $sql = "
        SELECT 
            st.Student_id,
            st.user_id,
            s.sub_id,
            s.sub_code,
            s.sub_name,
            s.units,
            cc.cc_year,
            cc.cc_sem,
            a.id,
            IF(a.id IS NULL, 0, 1) AS is_advised,
            a.date_created
        FROM enrollments e
        INNER JOIN students st 
            ON st.Student_id = e.student_id
        INNER JOIN curriculum c 
            ON e.curriculum_id = c.cur_id
        INNER JOIN curriculum_content cc 
            ON cc.curr_id = c.cur_id 
           AND cc.cc_year = e.yr_level
        INNER JOIN subjects s 
            ON s.sub_id = cc.cc_course_id
        LEFT JOIN advising_tb a 
            ON a.student_id = ? 
            AND a.subject_id = s.sub_id
        WHERE st.Student_id = ? 
          AND c.cur_program_id = ?
          AND c.cur_year = ?
          AND cc.cc_sem = ?
        ORDER BY s.sub_code
    ";

        return $this->GetData(
            $sql,
            [$userId, $studentId, $programId, $curYear, $sem],
            false
        );
    }



    public function enroll_curriculum(string $std_id, int $prog_id, int $sem, array $subjects, int $cur_year): array
    {
        $std_id = str_pad($std_id, 4, '0', STR_PAD_LEFT);

        if (empty($std_id) || $prog_id <= 0 || $sem <= 0 || empty($subjects)) {
            return [
                'success' => false,
                'message' => 'Invalid input',
                'inserted' => 0,
                'skipped' => 0
            ];
        }

        $inserted = 0;
        $skipped  = 0;
        $currentYear = date("Y");
        $nextYear = $currentYear + 1;

        $range = $currentYear . "-" . $nextYear;
        try {
            $this->conn->beginTransaction();

            foreach ($subjects as $subject) {
                $sub_id = $subject['sub_id'] ?? null;
                if (!$sub_id) {
                    $skipped++;
                    continue;
                }

                $stmt = $this->conn->prepare("
                    SELECT COUNT(*) FROM enrolled_curriculum
                    WHERE student_id = ? AND program_id = ? AND semester = ? AND subject_id = ?
                ");
                $stmt->execute([$std_id, $prog_id, $sem, $sub_id]);

                if ($stmt->fetchColumn() > 0) {
                    $skipped++;
                    continue;
                }


                $stmt = $this->conn->prepare("
                    INSERT INTO enrolled_curriculum (student_id, program_id,  semester, subject_id, sy, level)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$std_id, $prog_id, $sem, $sub_id, $range, $cur_year]);
                $inserted++;
            }

            $this->conn->commit();

            return [
                'success'  => true,
                'message'  => "Enrolled $inserted subject(s), skipped $skipped duplicate(s).",
                'inserted' => $inserted,
                'skipped'  => $skipped
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Enroll curriculum failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'inserted' => 0,
                'skipped' => 0
            ];
        }
    }

    public function get_enrolled_subjects(string $std_id): array
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT ec.id,
                   ec.student_id,
                   ec.program_id,
                   ec.yr,
                   ec.semester,
                   ec.subject_id,
                   s.subject_code,
                   s.subject_name,
                   s.units
            FROM enrolled_curriculum ec
            INNER JOIN subjects s ON ec.subject_id = s.id
            WHERE ec.student_id = ?
            ORDER BY ec.yr ASC, ec.semester ASC, s.subject_code ASC
        ");

            $stmt->execute([$std_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Retrieve enrolled subjects failed: " . $e->getMessage());
            return [];
        }
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
    public function getSubjectsEnrolled($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM subjects WHERE sub_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeStudentSubject($studentId, $subjectId)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM enrolled_curriculum WHERE student_id = ? AND subject_id = ?");
            return $stmt->execute([$studentId, $subjectId]);
        } catch (PDOException $e) {
            error_log("Remove subject error: " . $e->getMessage());
            return false;
        }
    }

    public function getStudentBasicInfo($id)
    {
        $stmt = $this->conn->prepare("SELECT s.*, p.*, e.* FROM students s JOIN programs p ON s.prog_id = p.program_id JOIN enrollments e ON s.Student_id = e.student_id WHERE s.Student_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
