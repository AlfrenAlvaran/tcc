<?php
session_start();
include("includes/header.php");
require_once __DIR__ . "/../classes/Curriculum.php";

$std_id = $_GET['id'];
$curriculum = new Curriculum();
$curriculum_data = $curriculum->getSubjectStudent($_GET['id']);


$student_info = $curriculum->getStudentBasicInfo(id: $std_id);

// $data = $curriculum->getSubjectStudent($_GET['id']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
        }

        .sidebar {
            min-height: 60vh;
        }

        .nav-link.active {
            background: #0d6efd;
            color: white !important;
            border-radius: .375rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-bold text-primary">Student Information</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($student_info): ?>
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>

                                        <tr>
                                            <th style="width: 150px;">Student No.</th>
                                            <td><?= $student_info['SY'] ?>-<?= htmlspecialchars($student_info['Student_id']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Student Name</th>
                                            <td>
                                                <?= strtoupper($student_info['Student_LName']) . ', ' .
                                                    strtoupper($student_info['Student_FName']) . ' ' .
                                                    strtoupper($student_info['Student_MName']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Course</th>
                                            <td><?= htmlspecialchars($student_info['p_code']); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 200px;">School Year/Semester</th>
                                            <td>
                                                <?= $student_info['sy'] ?>
                                                <?= $student_info['sem'] == 1 ? '1st' : ($student_info['sem'] == 2 ? '2nd' : $student_info['sem'] . 'th') ?>
                                                Semester
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php if (!empty($curriculum_data)): ?>
<?php
$allGradesExist = true;
$totalUnits = 0;
$weightedSum = 0;

foreach ($curriculum_data as $sub) {
    if (!isset($sub['grade']) || $sub['grade'] === '') {

        $allGradesExist = false;
        break;
    }
    $weightedSum += floatval($sub['grade']) * floatval($sub['units']);
    $totalUnits += floatval($sub['units']);
}


$gwa = ($allGradesExist && $totalUnits > 0) ? number_format($weightedSum / $totalUnits, 2) : null;
?>



                                    <table class="table table-striped table-hover align-middle table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subject Code</th>
                                                <th>Subject Name</th>
                                                <th class="text-center">Units</th>
                                                <th class="text-center">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($curriculum_data as $sub): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($sub['sub_code']) ?></td>
                                                    <td><?= htmlspecialchars($sub['sub_name']) ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($sub['units']) ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($sub['grade'] ?: '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <?php if ($gwa !== null): ?>
                                                <tr class="table-primary fw-bold">
                                                    <td colspan="3" class="text-end">GWA</td>
                                                    <td class="text-center"><?= $gwa ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="text-muted">No enrolled subjects found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2021</span>
            </div>
        </div>
    </footer>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("includes/logout-modal.php"); ?>
    <?php include("includes/js-link.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".remove-subject").on("click", function(e) {
                e.preventDefault();
                const btn = $(this);
                const studentId = btn.data("student");
                const subjectId = btn.data("subject");

                if (!confirm("Are you sure you want to remove this subject?")) return;

                $.ajax({
                    url: "ajax/remove_subject.php",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        student_id: studentId,
                        subject_id: subjectId
                    }),
                    success: function(response) {
                        if (typeof response === "string") {
                            try {
                                response = JSON.parse(response);
                            } catch (e) {}
                        }
                        if (response.success) {
                            btn.closest("tr").remove();
                            alert("✅ " + response.message);
                        } else {
                            alert("❌ " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("XHR:", xhr.responseText);
                        console.error("Status:", status);
                        console.error("Error:", error);
                        alert("⚠️ An error occurred while removing the subject.");
                    }
                });
            });
        });
    </script>

</body>

</html>