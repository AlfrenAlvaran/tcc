<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../../config/database.php");

$status = $_GET['status'] ?? 0;

if ($status == 1) {
    $stid   = $_POST['stid'];
    $cname  = $_POST['cname'];
    $course = $_POST['course']; 
    $cur    = $_POST['cur'];

    
    $prog_id = $database->getProgramId($course);
    $cur_id  = $database->getCurriculumId($cur);

    if ($prog_id && $cur_id) {
        $sql = "INSERT INTO enrolled_students 
                (Student_id, Complete_Name, course, curriculum, cur_id, cur_program_id, enrolled_date)
                VALUES ('$stid', '$cname', '$course', '$cur', '$cur_id', '$prog_id', NOW())";

        $database->enroll($sql, "enrolled_students.php");
    } else {
        die("Invalid program or curriculum selected.");
    }
} else {
    echo "failed";
}
