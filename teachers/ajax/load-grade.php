<?php
require_once __DIR__ . '/../../classes/Grades.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$teacherId = $_SESSION['id'] ?? 0;

$studentId = (int)($_GET['id'] ?? 0);
$subject   = $_GET['subject'] ?? '';

$grades = new Grades();
$data = $grades->getGrades($studentId, $subject, $teacherId);

foreach ($data as &$g) {
    // Convert values to float if numeric, otherwise null
    $prelim  = ($g['prelim']  !== null && $g['prelim']  !== '') ? (float)$g['prelim']  : null;
    $midterm = ($g['midterm'] !== null && $g['midterm'] !== '') ? (float)$g['midterm'] : null;
    $finals  = ($g['finals']  !== null && $g['finals']  !== '') ? (float)$g['finals']  : null;

    // Compute remarks only if all 3 grades are present
    if ($prelim !== null && $midterm !== null && $finals !== null) {
        $avg = ($prelim + $midterm + $finals) / 3;
        $g['remarks'] = $avg >= 75 ? "Passed" : "Failed";
    } else {
        $g['remarks'] = "Not Encoded";
    }

    // Replace original values with normalized values
    $g['prelim']  = $prelim;
    $g['midterm'] = $midterm;
    $g['finals']  = $finals;
}

header("Content-Type: application/json");
echo json_encode($data);
