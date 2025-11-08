<?php

require_once __DIR__ . '/../../classes/Curriculum.php';
$curriculum = new Curriculum();
header('Content-Type: application/json');

$sem = $_GET['sem'] ?? '';
$programID = $_GET['programID'] ?? '';

$schedule = $curriculum->getSubjectsBySemAndProgramWithStudentCount($sem, $programID); // You need this method

echo json_encode($schedule);