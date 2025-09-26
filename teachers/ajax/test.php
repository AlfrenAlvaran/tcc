<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../classes/Teacher.php';

$teacher_id = (int)($_GET['id'] ?? 0);

if (!$teacher_id) {
    echo json_encode([]);
    exit;
}

try {
    $schedule = new Teacher();
    $data = $schedule->getAllSchedules($teacher_id);

    echo json_encode($data); // ✅ only once
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
