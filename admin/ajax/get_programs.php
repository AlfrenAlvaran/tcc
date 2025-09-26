<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../classes/Teacher.php';

$teacher = new Teacher();
$programs = $teacher->getPrograms();

if (!$programs) {
    echo json_encode(['status' => 'error', 'message' => 'No programs found']);
} else {
    echo json_encode(['status' => 'success', 'programs' => $programs]);
}
