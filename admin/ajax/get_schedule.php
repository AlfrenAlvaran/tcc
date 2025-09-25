<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../classes/Teacher.php';

$teacher_id = (int)($_GET['id'] ?? 0);

if (!$teacher_id) {
    echo json_encode([]);
    exit;
}

$teacher = new Teacher();
$schedules = $teacher->getSchedule($teacher_id); // use your correct method

// Format to ensure keys are consistent
$result = [];
foreach ($schedules as $sch) {
    $result[] = [
        'sub_name'   => $sch['subject_name'] ?? '',
        'sub_code'   => $sch['code'] ?? '',
        'day'        => $sch['day'] ?? '',
        'time_start' => $sch['time_start'] ?? '',
        'time_end'   => $sch['time_end'] ?? '',
        'room'       => $sch['room'] ?? ''
    ];
}

echo json_encode($result);
