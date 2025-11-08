<?php
require_once __DIR__ . "/../../classes/Encode.php";
header("Content-Type: application/json");

try {
    if (isset($_GET['studentID'], $_GET['year'], $_GET['sem'])) {
        $studentId = $_GET['studentID'];
        $year = (int)$_GET['year'];
        $sem = (int)$_GET['sem'];

        $student = new Encode();
        $grades = $student->getSubjectsWithGrades($studentId, $year, $sem);

        echo json_encode($grades);
    } else {
        echo json_encode(["error" => "Missing parameters"]);
    }
} catch (Throwable $e) {
    echo json_encode(["error" => "Server error", "details" => $e->getMessage()]);
}
