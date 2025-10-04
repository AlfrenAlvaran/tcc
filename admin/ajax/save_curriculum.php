<?php
session_start();
require_once __DIR__ . '/../../classes/Curriculum.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = (int)$_POST['student_id'];
    $subjectId = (int)$_POST['subject_id'];
   
    $curriculum = new Curriculum();
    $result = $curriculum->recordCurriculum($studentId, $subjectId);

    if ($result) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
