<?php


header("Content-Type: application/json");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../classes/Teacher.php';

$teacher = new Teacher();

$id = (int)($_GET['id'] ?? 0);
$day = $_GET['day'] ?? '';
$room = $_GET['room'] ?? '';

if (!$id || !$day) {
    echo json_encode([]);
    exit;
}

$available = $teacher->getAvailableTimeSlots($id, $day, $room);

$result = array_map(function($slot) {
    return array(
        "start" => $slot[0],
        "end"   => $slot[1]
    );
}, $available);

echo json_encode(['status' => 'success', 'slots' => $result]);