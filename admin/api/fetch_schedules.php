<?php
require_once __DIR__ . "/../../classes/Students.php";
header("Content-Type: application/json");

// enable error reporting during dev
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (isset($_GET['studentID'], $_GET['year'], $_GET['sem'])) {
        $studentId = $_GET['studentID'];
        $year      = (int)$_GET['year'];
        $sem       = (int)$_GET['sem'];
        // echo "Params:", json_encode([$studentId, $year, $sem]);
        $student  = new Students();
        $subjects = $student->getSubjectsWithSchedules($studentId, $year, $sem);

        echo json_encode($subjects);
    } else {
        echo json_encode(["error" => "Missing parameters"]);
    }
} catch (Throwable $e) {
    echo json_encode([
        "error" => "Server error",
        "details" => $e->getMessage()
    ]);
}
