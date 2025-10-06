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

    public function getSubjectsBySemesterYear($studentID, $year, $semester)
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




    public function getSubjectsWithSchedules(string $studentID, int $year, int $semester): array
    {
        $stmt = $this->conn->prepare("
        SELECT ec.level, ec.semester, ec.sy, ec.program_id, p.p_code
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
            // Fetch all subjects for this level, semester, and school year
            $subjectsStmt = $this->conn->prepare("
            SELECT 
                s.sub_id, 
                s.sub_code, 
                s.sub_name, 
                s.units, 
                s.withLab,
                sch.day,
                sch.time_start,
                sch.time_end,
                sch.room,
                CONCAT(t.fname, ' ', t.lname) AS teacher_name
            FROM enrolled_curriculum ec 
            JOIN subjects s ON ec.subject_id = s.sub_id 
            LEFT JOIN schedule sch ON s.sub_id = sch.subject_id 
            LEFT JOIN accounts t ON sch.teacher_id = t.id 
            WHERE ec.student_id = ? 
              AND ec.level = ? 
              AND ec.semester = ? 
              AND ec.sy = ?
        ");
            $subjectsStmt->execute([$studentID, $row['level'], $row['semester'], $row['sy']]);
            $subjects = $subjectsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Add schedules for each subject
            foreach ($subjects as $subKey => $subject) {
                $scheduleStmt = $this->conn->prepare("
                SELECT 
                    sc.day,
                    sc.time_start,
                    sc.time_end,
                    sc.room,
                    CONCAT(a.fname, ' ', a.lname) AS teacher_name
                FROM schedule sc
                JOIN accounts a ON sc.teacher_id = a.id
                WHERE sc.subject_id = ?
            ");
                $scheduleStmt->execute([$subject['sub_id']]);
                $schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

                $subjects[$subKey]['schedules'] = $schedules;
            }

            $enrollments[$key]['subjects'] = $subjects;
        }

        return $enrollments;
    }

    public function getPersonalInfo($id)
    {
        $stmt = $this->conn->prepare("
        SELECT 
            -- Student Info
            s.Student_id,
            s.Student_LName,
            s.Student_FName,
            s.Student_MName,
            s.complete_addres,
            s.province,
            s.city,
            s.barangay,
            s.birthday,
            s.age,
            s.gender,
            s.SY,

            -- Program Info
            p.program_id,
            p.p_code,
            p.p_des,
            p.p_major,
            p.p_year,

            -- Enrollment Info
            e.yr_level,
            e.curriculum_id,
            e.sem,
            e.sy,

            -- Personal Info
            info.address,
            info.email,
            info.LRN,
            info.gaurdian_name,
            info.occupation,
            info.guardian_no,
            info.gaurdian_address,
            info.elementary,
            info.high_school,
            info.last_college_attend,

            -- Upload Info
            u.profile,
            u.documents

        FROM enrollments e
        INNER JOIN students s ON e.student_id = s.Student_id
        INNER JOIN programs p ON s.prog_id = p.program_id
        LEFT JOIN student_personal_information info ON s.Student_id = info.student_id
        LEFT JOIN upload u ON s.Student_id = u.student_id
        WHERE s.Student_id = ?
        ORDER BY e.sy DESC, e.sem DESC
        LIMIT 1
    ");

        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

  
        $student = [
            'Student_id'       => $row['Student_id'],
            'Student_LName'    => $row['Student_LName'],
            'Student_FName'    => $row['Student_FName'],
            'Student_MName'    => $row['Student_MName'],
            'complete_addres'  => $row['complete_addres'],
            'province'         => $row['province'],
            'city'             => $row['city'],
            'barangay'         => $row['barangay'],
            'birthday'         => $row['birthday'],
            'age'              => $row['age'],
            'gender'           => $row['gender'],
            'SY'               => $row['SY'],
        ];

        $program = [
            'program_id' => $row['program_id'],
            'p_code'     => $row['p_code'],
            'p_des'      => $row['p_des'],
            'p_major'    => $row['p_major'],
            'p_year'     => $row['p_year'],
        ];

        $enrollment = [
            'yr_level'      => $row['yr_level'],
            'curriculum_id' => $row['curriculum_id'],
            'sem'           => $row['sem'],
            'sy'            => $row['sy'],
        ];

        $personal = [
            'address'           => $row['address'],
            'email'             => $row['email'],
            'LRN'               => $row['LRN'],
            'gaurdian_name'     => $row['gaurdian_name'],
            'occupation'        => $row['occupation'],
            'guardian_no'       => $row['guardian_no'],
            'gaurdian_address'  => $row['gaurdian_address'],
            'elementary'        => $row['elementary'],
            'high_school'       => $row['high_school'],
            'last_college_attend' => $row['last_college_attend'],
        ];

        $uploads = [
            'profile'   => $row['profile'],
            'documents' => $row['documents'],
        ];

        return [
            'student'    => $student,
            'program'    => $program,
            'enrollment' => $enrollment,
            'personal'   => $personal,
            'uploads'    => $uploads
        ];
    }

    public function savePersonalInfo($student_id, $data, $files)
    {
        $stmt = $this->conn->prepare("SELECT id FROM student_personal_information WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $exists = $stmt->fetchColumn();

        $personalFields = [
            'address' => $data['address'] ?? '',
            'email' => $data['email'] ?? '',
            'LRN' => $data['LRN'] ?? '',
            'gaurdian_name' => $data['gaurdian_name'] ?? '',
            'occupation' => $data['occupation'] ?? '',
            'guardian_no' => $data['guardian_no'] ?? '',
            'gaurdian_address' => $data['gaurdian_address'] ?? '',
            'elementary' => $data['elementary'] ?? '',
            'high_school' => $data['high_school'] ?? '',
            'last_college_attend' => $data['last_college_attend'] ?? ''
        ];

        if ($exists) {
            $sql = "UPDATE student_personal_information 
            SET address=:address, email=:email, LRN=:LRN, gaurdian_name=:gaurdian_name, 
                occupation=:occupation, guardian_no=:guardian_no, gaurdian_address=:gaurdian_address,
                elementary=:elementary, high_school=:high_school, last_college_attend=:last_college_attend
            WHERE student_id=:student_id";
        } else {
            $sql = "INSERT INTO student_personal_information 
            (student_id, address, email, LRN, gaurdian_name, occupation, guardian_no, gaurdian_address, 
             elementary, high_school, last_college_attend)
            VALUES (:student_id, :address, :email, :LRN, :gaurdian_name, :occupation, :guardian_no, 
                    :gaurdian_address, :elementary, :high_school, :last_college_attend)";
        }

        $stmt = $this->conn->prepare($sql);
        $personalFields['student_id'] = $student_id;
        $stmt->execute($personalFields);

        $stmt2 = $this->conn->prepare("SELECT id FROM upload WHERE student_id = ?");
        $stmt2->execute([$student_id]);
        $uploadExists = $stmt2->fetchColumn();

        $uploadDir = __DIR__ . "/../uploads/";
        $publicPath = "/tcc/uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $profileUrl = '';
        $documentUrls = [];

        if (!empty($files['profile']['name'])) {
            $profileName = time() . "_profile_" . basename($files['profile']['name']);
            $profilePath = $uploadDir . $profileName;
            move_uploaded_file($files['profile']['tmp_name'], $profilePath);
            $profileUrl = $publicPath . $profileName;
        }

        if (!empty($files['documents']['name'][0])) {
            foreach ($files['documents']['name'] as $index => $name) {
                if (!empty($name)) {
                    $docName = time() . "_document_" . basename($name);
                    $documentPath = $uploadDir . $docName;
                    move_uploaded_file($files['documents']['tmp_name'][$index], $documentPath);
                    $documentUrls[] = $publicPath . $docName;
                }
            }
        }

        $documentJson = !empty($documentUrls) ? json_encode($documentUrls) : '';

        if ($uploadExists) {
            $sql2 = "UPDATE upload 
                 SET profile = COALESCE(NULLIF(:profile, ''), profile), 
                     documents = COALESCE(NULLIF(:documents, ''), documents)
                 WHERE student_id = :student_id";
        } else {
            $sql2 = "INSERT INTO upload (student_id, profile, documents) 
                 VALUES (:student_id, :profile, :documents)";
        }

        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([
            ':student_id' => $student_id,
            ':profile' => $profileUrl,
            ':documents' => $documentJson
        ]);

        return true;
    }
}
