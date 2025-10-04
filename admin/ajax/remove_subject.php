<!-- remove_subject.php -->
<?php
ob_clean();
require_once __DIR__ . '/../../classes/Curriculum.php';
$curriculum = new Curriculum();

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $student_id = $input['student_id'];
    $subject_id = $input['subject_id'];

    if ($student_id && $subject_id) {
        $deleted = $curriculum->removeStudentSubject($student_id, $subject_id);
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Subject removed successfully.' : 'Failed to remove subject.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;