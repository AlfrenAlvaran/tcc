<?php
require_once __DIR__ . "/../../classes/Students.php";
header("Content-Type: application/json");

if (isset($_GET['student_id'], $_GET['year'], $_GET['sem'])) {
    $studentId = $_GET['student_id'];
    $year = $_GET['year'];
    $sem = $_GET['sem'];

    $student = new Students();
    $subjects = $student->advised($studentId, $year, $sem);

    echo json_encode($subjects);
} else {
    echo json_encode(["error" => "Missing parameters"]);
}
?>
