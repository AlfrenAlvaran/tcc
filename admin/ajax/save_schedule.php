<?php
header("Content-Type: application/json");

// Force all PHP errors/exceptions to JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

set_error_handler(function($errno, $errstr, $errfile, $errline){
    echo json_encode(['status'=>'error','message'=>"PHP Error: $errstr in $errfile on line $errline"]);
    exit;
});

set_exception_handler(function($e){
    echo json_encode(['status'=>'error','message'=>"Exception: ".$e->getMessage()]);
    exit;
});

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../classes/Teacher.php';

$teacher = new Teacher();
$conn = $teacher->connection();


$teacher_id = (int)($_POST['teacher_id'] ?? 0);
$subject_id = (int)($_POST['subject_id'] ?? 0);
$day        = mysqli_real_escape_string($conn, $_POST['day'] ?? '');
$time_start = mysqli_real_escape_string($conn, $_POST['time_start'] ?? '');
$time_end   = mysqli_real_escape_string($conn, $_POST['time_end'] ?? '');
$room       = mysqli_real_escape_string($conn, $_POST['room'] ?? '');
$program_id = mysqli_real_escape_string($conn, $_POST['program_id'] ?? '');

// Validate
if (!$teacher_id || !$subject_id || !$day || !$time_start || !$time_end || !$room || !$program_id) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

// Check room conflict
if ($teacher->isRoomOccupied($day, $time_start, $time_end, $room)) {
    echo json_encode(['status' => 'error', 'message' => 'Room already occupied!']);
    exit;
}

// Insert schedule
$sql = "
    INSERT INTO schedule (teacher_id, subject_id, program_id, day, time_start, time_end, room)
    VALUES ($teacher_id, $subject_id, '$program_id', '$day', '$time_start', '$time_end', '$room')
";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        'status' => 'success',
        'data' => compact('teacher_id','subject_id','day','time_start','time_end','room')
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'SQL Error: ' . mysqli_error($conn)
    ]);
}


