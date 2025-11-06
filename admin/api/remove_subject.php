<?php
header('Content-Type: application/json');
require_once __DIR__ . "/../../classes/Curriculum.php";

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Missing subject ID']);
    exit;
}

$curriculum = new Curriculum();
$deleted = $curriculum->deleteSubject($id);

if ($deleted) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete subject']);
}
