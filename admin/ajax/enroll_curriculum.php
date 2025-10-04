<?php
require_once __DIR__ . '/../../classes/Curriculum.php';
$students = new Curriculum();

header('Content-Type: application/json');

try {
    $student_id = $_POST['student_id'] ?? '';
    $prog_id    = (int) ($_POST['prog_id'] ?? 0);
    $cur_year   = (int) ($_POST['cur_year'] ?? 0);
    $sem        = (int) ($_POST['sem'] ?? 0);
    $subjects   = json_decode($_POST['subjects'] ?? '[]', true);

    $result = $students->enroll_curriculum($student_id, $prog_id, $cur_year, $sem, $subjects);

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode([
        'success'  => false,
        'message'  => 'Server error: ' . $e->getMessage(),
        'inserted' => 0,
        'skipped'  => 0
    ]);
}
