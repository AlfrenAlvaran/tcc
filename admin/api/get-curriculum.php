<?php

header('Content-Type: application/json');
require_once __DIR__ . "/../../classes/Curriculum.php";


$student_id = $_GET['student_id'];
$program_id = $_GET['progID'];
$level = $_GET['year'];
$semester = $_GET['sem'];

$curriculum = new Curriculum();
$Subjects = $curriculum->getCurriculum($student_id, $program_id, $level, $semester);

echo json_encode([
    [
        'subjects' => $Subjects
    ]
]);
