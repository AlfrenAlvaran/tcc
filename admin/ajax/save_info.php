<?php
header('Content-Type: application/json');
ob_clean();

require_once __DIR__ . '/../../classes/Students.php';
$student = new Students();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    $data = $_POST;
    $files = $_FILES;

    try {
        if ($student_id) {
            $student->savePersonalInfo($student_id, $data, $files);
            echo json_encode(['status' => 'success', 'message' => 'Saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing student_id']);
        }
    } catch (Throwable $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
