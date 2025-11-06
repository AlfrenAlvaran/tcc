<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../classes/Enrollment.php';
$enrollment = new Enrollment();

$status = $_GET['status'] ?? 0;

if ($status == 1) {
    $stid     = $_POST['stid']; // e.g., "0005"
    $semester = intval($_POST['semester']);
    $sy       = $_POST['sy'];
    $cur      = $_POST['cur_id'];
    $yr_level = intval($_POST['yr_level'] ?? 0);

    // ✅ Convert string ID like "0005" → integer 5 (safe conversion)
    $stid_int = (int)$stid;

    // ✅ Check for existing enrollment
    $existing = $enrollment->GetData(
        "SELECT sem FROM enrollments WHERE student_id = ? AND sy = ? AND yr_level = ?",
        [$stid_int, $sy, $yr_level]
    );

    // Normalize $existing result
    if (!is_array($existing)) {
        $existing = [];
    } elseif (isset($existing['sem'])) {
        // Single record returned — wrap in array
        $existing = [$existing];
    }

    $semestersEnrolled = array_column($existing, 'sem');

    // ✅ Prevent double enrollment in both semesters
    if (in_array(1, $semestersEnrolled) && in_array(2, $semestersEnrolled)) {
        $_SESSION['error'] = "This student has already completed both semesters for Year Level $yr_level ($sy).";
        header("Location: ../enroll_student.php?id=" . $stid);
        exit;
    }

    // ✅ Prevent duplicate enrollment in the same semester
    if (in_array($semester, $semestersEnrolled)) {
        $_SESSION['error'] = "This student is already enrolled in Semester $semester for Year Level $yr_level ($sy).";
        header("Location: ../enroll_student.php?id=" . $stid);
        exit;
    }

    // ✅ Enroll the student for all curriculum items
    foreach ($cur as $cur_id) {
        $enrollment->enrollStudent($stid_int, intval($cur_id), $yr_level, $semester, $sy);
    }

    // ✅ Redirect after success
    header("Location: ../enrolled_students.php");
    exit;
}
