<?php
require_once __DIR__ . '/../../config/PDOConnection.php';

$conn = PDOConnection::getInstance()->getConnection();

$student_id = $_GET['student_id'] ?? '';
$prog_id    = (int) ($_GET['prog_id'] ?? 0);
$cur_year   = (int) ($_GET['cur_year'] ?? 0);
$sem        = (int) ($_GET['sem'] ?? 0);

if (empty($student_id) || $prog_id <= 0 || $cur_year <= 0 || $sem <= 0) {
    die("Invalid parameters.");
}

$stmt = $conn->prepare("
    SELECT 
        LPAD(st.Student_id, 4, '0') AS student_id,
        CONCAT(st.Student_LName, ', ', st.Student_FName, ' ', st.Student_MName) AS full_name,
        st.SY,
        p.p_des,
        ec.semester,
        ec.semester,
        ec.sy,
        s.sub_code,
        s.sub_name,
        s.units,
        s.withLab
    FROM students st
    JOIN programs p ON st.prog_id = p.program_id
    JOIN enrolled_curriculum ec 
        ON ec.student_id = st.Student_id 
       AND ec.program_id = p.program_id
    JOIN subjects s ON ec.subject_id = s.sub_id
    WHERE st.Student_id = ?
    AND ec.semester = ?
    ORDER BY ec.semester DESC, ec.semester DESC
");
$stmt->execute([$student_id, $sem]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    die("Student not found.");
}

$student = $rows[0];
$subjects = array_filter($rows, fn($r) => !empty($r['sub_code']));
$totalUnits = $subjects ? array_sum(array_column($subjects, 'units')) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>e-Registration Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .school-header {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .school-header img {
            height: 80px;
        }

        .school-header h2 {
            margin: 5px 0;
        }

        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        h2.title {
            text-align: center;
            margin: 20px 0;
            text-transform: uppercase;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="school-header">
        <img src="../../img/tcc_logo.jpg" alt="School Logo"><br>
        <h2>Tomas Claudio Colleges</h2>
        <div>Morong Rizal</div>
       
    </div>

    <div class="student-info">
        <div>
            <p><strong>Student No.:</strong> S<?= htmlspecialchars(substr($student['SY'], -2)) . '-' . str_pad($student['student_id'], 4, "0", STR_PAD_LEFT) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($student['full_name']) ?></p>
        </div>
        <div style="text-align:right;">
            <p><strong>Date Enrolled:</strong> <?= date("F j, Y") ?></p>
            <p><strong>School Year/Semester:</strong> <?= htmlspecialchars($student['sy']) ?> <?= $student['semester'] ?> Semester</p>
            <p><strong>Course:</strong> <?= htmlspecialchars($student['p_des']) ?></p>
        </div>
    </div>

    <h2 class="title">e-Registration Card</h2>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Subject</th>
                <th>Units</th>
                <th>With Lab</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($subjects): ?>
                <?php foreach ($subjects as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['sub_code']) ?></td>
                        <td><?= htmlspecialchars($row['sub_name']) ?></td>
                        <td><?= htmlspecialchars($row['units']) ?></td>
                        <td><?= $row['withLab'] ? "Yes" : "No" ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2" style="text-align:right;"><strong>Total Units:</strong></td>
                    <td colspan="2"><strong><?= $totalUnits ?></strong></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No subjects enrolled for this year/semester.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>