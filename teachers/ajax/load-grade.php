<?php
require_once __DIR__ . '/../../classes/Grades.php';

$studentId = (int)($_GET['id'] ?? 0);
$subject   = $_GET['subject'] ?? '';

$grades = new Grades();
$data = $grades->getGrades($studentId, $subject);

// Compute remarks
foreach ($data as &$g) {
    $prelim  = (float)($g['prelim'] ?? 0);
    $midterm = (float)($g['midterm'] ?? 0);
    $finals  = (float)($g['finals'] ?? 0);

    if ($prelim && $midterm && $finals) {
        $avg = ($prelim + $midterm + $finals) / 3;
        $g['remarks'] = $avg >= 75 ? "Passed" : "Failed";
    } else {
        $g['remarks'] = "Not Encoded";
    }
}

header("Content-Type: application/json");
echo json_encode($data);
