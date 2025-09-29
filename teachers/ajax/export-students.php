<?php
require_once __DIR__ . '/../../classes/Grades.php';
require_once __DIR__ . '/../../classes/Classes.php';
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$teacherId = $_SESSION['id'] ?? 0;
$subject_name = $_GET['subject'] ?? '';
$scheduleId = (int)($_GET['id'] ?? 0);
$subject    = $_GET['subject'] ?? '';

$classes = new Classes();
$grades  = new Grades();


$students = $classes->getStudentWithProgramAndStudents($scheduleId);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$headers = ['Student ID','Student Name','Prelim','Midterms','Finals','Remarks'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col.'1', $header);
    $col++;
}

$row = 2;
foreach ($students as $student) {
    $studentId   = $student['id'];
    $studentName = $student['Complete_Name'];


    $gradeRecords = $grades->getGrades($studentId, $subject, $teacherId);

    $prelim = $midterm = $finals = "Not Encoded";
    $remarks = "Not Encoded";

    if ($gradeRecords && count($gradeRecords) > 0) {
        $g = $gradeRecords[0];

        $prelim  = $g['prelim']  ?? null;
        $midterm = $g['midterm'] ?? null;
        $finals  = $g['finals']  ?? null;

        if (is_numeric($prelim) && is_numeric($midterm) && is_numeric($finals)) {
            $avg = ((float)$prelim + (float)$midterm + (float)$finals) / 3;
            $remarks = $avg >= 75 ? "Passed" : "Failed";
        } else {
            $remarks = "Not Encoded";
        }

        $prelim  = is_numeric($prelim)  ? number_format((float)$prelim, 2)  : "Not Encoded";
        $midterm = is_numeric($midterm) ? number_format((float)$midterm, 2) : "Not Encoded";
        $finals  = is_numeric($finals)  ? number_format((float)$finals, 2)  : "Not Encoded";
    }

    $sheet->setCellValue("A$row", $studentId);
    $sheet->setCellValue("B$row", $studentName);
    $sheet->setCellValue("C$row", $prelim);
    $sheet->setCellValue("D$row", $midterm);
    $sheet->setCellValue("E$row", $finals);
    $sheet->setCellValue("F$row", $remarks);

    $row++;
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="students_grades_' . $subject_name . '.xlsx"');
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
