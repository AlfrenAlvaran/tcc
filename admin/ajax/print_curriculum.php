<?php
require_once __DIR__ . '/../../config/PDOConnection.php';

// ✅ Get PDO connection via your Singleton
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
        st.student_id,
        CONCAT(st.Student_LName, ', ', st.Student_FName, ' ', st.Student_MName) AS full_name,
        st.gender,
        st.birthday,
        st.age,
        st.SY,
        st.complete_addres,
        st.province,
        st.city,
        st.barangay,
        p.p_code,
        p.p_des,
        p.p_major,
        ec.yr,
        ec.semester,
        ec.sy,
        s.sub_code,
        s.sub_name,
        s.units,
        s.withLab
    FROM enrolled_curriculum ec
    JOIN students st ON ec.student_id = st.student_id
    JOIN subjects s ON ec.subject_id = s.sub_id
    JOIN programs p ON ec.program_id = p.program_id
    WHERE ec.student_id = ?
      AND ec.program_id = ?
      AND ec.yr = ?
      AND ec.semester = ?
    ORDER BY s.sub_code ASC
");
$stmt->execute([$student_id, $prog_id, $cur_year, $sem]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    die("No curriculum found for this student.");
}

$student = $rows[0];
$totalUnits = array_sum(array_column($rows, 'units'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Curriculum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2,
        h3 {
            margin-bottom: 5px;
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

        .header {
            margin-bottom: 20px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h2>Curriculum Report</h2>
    </div>

    <h3>Student Information</h3>
    <p><strong>ID:</strong> <?= htmlspecialchars($student['student_id']) ?></p>
    <p><strong>Name:</strong> <?= htmlspecialchars($student['full_name']) ?></p>
    <p><strong>School Year:</strong> <?= htmlspecialchars($student['sy']) ?></p>

    <h3>Program Information</h3>
    <p><strong>Program:</strong> <?= htmlspecialchars($student['p_des']) ?></p>
    <p><strong>Year:</strong> <?= $student['yr'] ?> | <strong>Semester:</strong> <?= $student['semester'] ?></p>

    <h3>Enrolled Subjects</h3>
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
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['sub_code']) ?></td>
                    <td><?= htmlspecialchars($row['sub_name']) ?></td>
                    <td><?= htmlspecialchars($row['units']) ?></td>
                    <td><?= $row['withLab'] ? "Yes" : "No" ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2"><strong>Total Units</strong></td>
                <td colspan="2"><strong><?= $totalUnits ?></strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>