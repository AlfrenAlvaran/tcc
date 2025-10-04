<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../classes/Enrollment.php';
$enrollment = new Enrollment();

$status = $_GET['status'] ?? 0;
if ($status == 1) {
    $stid     = intval($_POST['stid']);
    $semester = intval($_POST['semester']);
    $sy       = $_POST['sy'];
    $cur      = $_POST['cur_id'];
    $yr_level = intval($_POST['yr_level'] ?? 0);

    $existing = $enrollment->GetData("SELECT sem FROM enrollments WHERE student_id = ? AND sy = ? AND yr_level = ?", [$stid, $sy, $yr_level]);

    if (!is_array($existing)) {
        $existing = [];
    } else if ($existing['sem'] == $semester) {
        $existing = [$existing];
    }
    $semestersEnrolled = array_column($existing, column_key: 'sem');
    if (in_array(1, $semestersEnrolled) && in_array(2, $semestersEnrolled)) {
        $_SESSION['error'] = "This student has already completed both semesters for Year Level $yr_level ($sy).";
        header("Location: ../enroll_student.php?id=" . $stid);
        exit;
    }


    if (in_array($semester, $semestersEnrolled)) {
        $_SESSION['error'] = "This student is already enrolled in Semester $semester for Year Level $yr_level ($sy).";
        header("Location: ../enroll_student.php?id=" . $stid);
        exit;
    }


    foreach ($cur as $cur_id) {
        $enrollment->enrollStudent($stid, intval($cur_id), $yr_level, $semester, $sy);
        header("Location: ../enrolled_students.php");
    }
}
