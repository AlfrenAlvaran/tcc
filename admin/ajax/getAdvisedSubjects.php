<?php
require_once __DIR__ . '/../../classes/Curriculum.php';
header('Content-Type: application/json');

if (isset($_GET['student_id'], $_GET['year'], $_GET['sem'])) {
    $studentId = (int)$_GET['student_id'];
    $year      = (int)$_GET['year'];
    $sem       = (int)$_GET['sem'];
    $userID    = (int)$_GET['id'];
    $progID    = (int)$_GET['progID'];

    $curriculum = new Curriculum();

    // your custom function
    $advisedSubjects = $curriculum->getSubjectsWithAdvising($studentId, $userID, $progID, $year, $sem);

    // get advising records
    $advised = $curriculum->GetData("SELECT * FROM advising_tb WHERE student_id = ?", [$userID], false);

    // collect subjects
    $subjects = [];
    foreach ($advised as $row) {
        $subject = $curriculum->GetData(
            "SELECT * FROM subjects WHERE sub_id = ?",
            [$row['subject_id']],
            true
        );
        if ($subject) {
            $subjects[] = $subject;
        }
    }

    echo json_encode([
        "status"  => "success",
        "data"    => $advisedSubjects,
        "advised" => $subjects        
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "student_id, year and sem are required"
    ]);
}
