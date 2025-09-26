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

    echo json_encode($data); 
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

// $result = [];
// foreach ($schedules as $sch) {
//     $result[] = [
//         // Subject fields
//         'sub_id'     => $sch['sub_id'] ?? '',
//         'sub_code'   => $sch['sub_code'] ?? '',
//         'sub_name'   => $sch['sub_name'] ?? '',
//         'withLab'    => $sch['withLab'] ?? '',
//         'units'      => $sch['units'] ?? '',

//         // Program fields
//         'program_id' => $sch['program_id'] ?? '',
//         'p_code'     => $sch['p_code'] ?? '',
//         'p_des'      => $sch['p_des'] ?? '',
//         'p_major'    => $sch['p_major'] ?? '',
//         'p_year'     => $sch['p_year'] ?? '',

//         // Schedule info
//         'day'        => $sch['day'] ?? '',
//         'time_start' => $sch['time_start'] ?? '',
//         'time_end'   => $sch['time_end'] ?? '',
//         'room'       => $sch['room'] ?? '',

//         // Students info
//         'student_user_id' => $sch['user_id'] ?? '',
//         'Student_id'      => $sch['Student_id'] ?? '',
//         'Student_LName'   => $sch['Student_LName'] ?? '',
//         'Student_FName'   => $sch['Student_FName'] ?? '',
//         'Student_MName'   => $sch['Student_MName'] ?? '',
//     ];
// }

// echo json_encode($result);
