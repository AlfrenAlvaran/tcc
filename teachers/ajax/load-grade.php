<?php

header("Content-Type: application/json");
require_once __DIR__ . '/../../classes/Grades.php';

$id = (int)$_GET['id'] ?? 0;
$grades = new Grades();
$data = $grades->getGrades($id);

echo json_encode($data);