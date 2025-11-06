<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once __DIR__ . '/../../classes/Classes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section_name = $_POST['section_name'] ?? null;
    $year_level = $_POST['year_level'] ?? null;

    if ($section_name && $year_level) {
        try {
            $classes = new Classes();
            $result = $classes->saveSection([
                'section_name' => $section_name,
                'year_level' => $year_level
            ]);

            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Section saved successfully.' : 'Failed to save section.'
            ]);
        } catch (Throwable $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
exit;
