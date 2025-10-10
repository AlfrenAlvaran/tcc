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
        $stmt = $this->conn->prepare("SELECT s.*, p.*, e.* FROM students s JOIN programs p ON s.prog_id = p.program_id JOIN enrollments e ON s.Student_id = e.student_id WHERE s.Student_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function notTakenSubjects(string $studentId, int $curriculumId)
    {
        $sql = "SELECT 
            s.sub_id,
            s.sub_code,
            s.units,
            s.withLab
        FROM curriculum_content cc
        JOIN subjects s ON cc.cc_course_id = s.sub_id
        JOIN curriculum c ON cc.curr_id = c.cur_program_id
        WHERE c.cur_id = :curriculumId
          AND s.sub_id NOT IN (
              SELECT subject_id 
              FROM enrolled_curriculum 
              WHERE student_id = :studentId
          )
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'curriculumId' => $curriculumId,
            'studentId' => $studentId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
