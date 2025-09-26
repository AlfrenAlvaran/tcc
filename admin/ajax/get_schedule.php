<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../classes/Teacher.php';

$teacher_id = (int)($_GET['id'] ?? 0);

if (!$teacher_id) {
    echo json_encode([]);
    exit;
}

$teacher = new Teacher();
$schedules = $teacher->getSchedule($teacher_id); 

$result = [];
foreach ($schedules as $sch) {
    $result[] = [
        // Subject fields
        'sub_id'     => $sch['sub_id'] ?? '',
        'sub_code'   => $sch['sub_code'] ?? '',
        'sub_name'   => $sch['sub_name'] ?? '',
        'withLab'    => $sch['withLab'] ?? '',
        'units'      => $sch['units'] ?? '',

        // Program fields
        'program_id' => $sch['program_id'] ?? '',
        'p_code'     => $sch['p_code'] ?? '',
        'p_des'      => $sch['p_des'] ?? '',
        'p_major'    => $sch['p_major'] ?? '',
        'p_year'     => $sch['p_year'] ?? '',

        // Schedule info
        'day'        => $sch['day'] ?? '',
        'time_start' => $sch['time_start'] ?? '',
        'time_end'   => $sch['time_end'] ?? '',
        'room'       => $sch['room'] ?? ''
    ];
}

echo json_encode($result);
