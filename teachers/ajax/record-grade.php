<?php
require_once __DIR__ . '/../../classes/Grades.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = (int)$_POST['teacher_id'] ?? 0;
    $studentId = (int)$_POST['student_id'] ?? 0;
    $subject   = $_POST['subject'] ?? '';
    $quarter   = $_POST['quarter'] ?? '';
    $grade     = isset($_POST['grade']) ? (float)$_POST['grade'] : null;
    $semester  = $_POST['semester'] ?? '';

    if ($studentId && $subject && $quarter && $grade !== null && $semester) {
        $grades = new Grades();
        $saved = $grades->recordGrade($studentId,$teacher_id, $subject, $quarter, $grade, $semester);

        echo json_encode([
            "success" => $saved,
            "message" => $saved ? "Grade recorded successfully" : "Failed to record grade"
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid data"]);
    }
}
