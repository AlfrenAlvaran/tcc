<?php
require_once __DIR__ . '/../../config/PDOConnection.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$conn = PDOConnection::getInstance()->getConnection();

$student_id = $_GET['student_id'] ?? '';
$prog_id    = (int) ($_GET['prog_id'] ?? 0);
$cur_year   = (int) ($_GET['cur_year'] ?? 0);
$sem        = (int) ($_GET['sem'] ?? 0);

if (empty($student_id) || $prog_id <= 0 || $cur_year <= 0 || $sem <= 0) {
    die("Invalid parameters.");
}

// Fetch student and subjects (same query as your print page)
$stmt = $conn->prepare("
    SELECT LPAD(st.Student_id, 4, '0') AS student_id,
           CONCAT(st.Student_LName, ', ', st.Student_FName, ' ', st.Student_MName) AS full_name,
           st.SY, p.p_des, ec.semester, ec.sy,
           s.sub_code, s.sub_name, s.units, s.withLab
    FROM students st
    JOIN programs p ON st.prog_id = p.program_id
    JOIN enrolled_curriculum ec ON ec.student_id = st.Student_id AND ec.program_id = p.program_id
    JOIN subjects s ON ec.subject_id = s.sub_id
    WHERE st.Student_id = ? AND ec.semester = ?
    ORDER BY ec.semester DESC
");
$stmt->execute([$student_id, $sem]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) die("Student not found.");

$student = $rows[0];
$subjects = array_filter($rows, fn($r) => !empty($r['sub_code']));
$totalUnits = $subjects ? array_sum(array_column($subjects, 'units')) : 0;


ob_start();
include __DIR__ . '/../ajax/print_curriculum.php';  
$html = ob_get_clean();


$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Force download
$dompdf->stream("Curriculum_{$student_id}.pdf", ["Attachment" => true]);
exit;
