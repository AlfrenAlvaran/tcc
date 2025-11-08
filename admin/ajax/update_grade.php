<?php
require_once __DIR__ . '/../../classes/Encode.php';
$encode = new Encode();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = $_POST['student_id'] ?? null;
    $subjectID = $_POST['subject_id'] ?? null;
    $grade = $_POST['grade'] ?? null;

    if (!$studentID || !$subjectID || $grade === null) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $result = $encode->updateGrade($studentID, $subjectID, $grade);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
