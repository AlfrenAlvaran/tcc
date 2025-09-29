<?php
require_once __DIR__ . '/../../classes/Grades.php';
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$teacherId = $_SESSION['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    $fileTmp = $_FILES['excelFile']['tmp_name'];
    $subject = $_POST['subject'] ?? '';

    if (!$subject) {
        echo json_encode(["success" => false, "message" => "Subject missing."]);
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($fileTmp);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $grades = new Grades();
        $successCount = 0;
        $failCount = 0;

        // Skip header row
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $studentId = (int)($row[0] ?? 0); // Student ID
            $prelim    = $row[2] ?? null;
            $midterm   = $row[3] ?? null;
            $finals    = $row[4] ?? null;

            if ($studentId && $teacherId && $subject) {
                if (is_numeric($prelim)) {
                    $grades->recordGrade($studentId, $teacherId, $subject, "Prelim", (float)$prelim, 1);
                }
                if (is_numeric($midterm)) {
                    $grades->recordGrade($studentId, $teacherId, $subject, "Midterms", (float)$midterm, 1);
                }
                if (is_numeric($finals)) {
                    $grades->recordGrade($studentId, $teacherId, $subject, "Finals", (float)$finals, 1);
                }
                $successCount++;
            } else {
                $failCount++;
            }
        }

        echo json_encode([
            "success" => true,
            "message" => "Upload complete. Success: $successCount, Failed: $failCount"
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error reading Excel file: " . $e->getMessage()
        ]);
    }
}
