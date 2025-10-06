<?php
session_start();
include("includes/header.php");
require_once __DIR__ . "/../classes/Curriculum.php";

$std_id = $_GET['id'];
$curriculum = new Curriculum();
$curriculum_data = $curriculum->getCurriculumByStudent($std_id);


$student_info = $curriculum->getStudentBasicInfo(id: $std_id);
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
                                <?php
                                if (!empty($curriculum_data)) {
                                    $grouped = [];

                                    foreach ($curriculum_data as $row) {
                                        // ✅ skip if this enrollment has no subjects
                                        if (empty($row['subjects'])) continue;

                                        $year = (int)$row['level'];
                                        $semester = (int)$row['semester'];
                                        $schoolYear = $row['sy'];

                                        if ($year <= 0 || $semester <= 0) continue;

                                        $yearLabel = $year == 1 ? "1st Year" : ($year == 2 ? "2nd Year" : ($year == 3 ? "3rd Year" : $year . "th Year"));

                                        $semLabel = $semester == 1 ? "1st Semester" : ($semester == 2 ? "2nd Semester" : $semester . "th Semester");

                                        $semKey = "$yearLabel - $semLabel (S.Y. {$schoolYear})";

                                        $grouped[$semKey][] = $row;
                                    }

                                    if (!empty($grouped)) {
                                        foreach ($grouped as $sem => $rows) {
                                            echo "<h5 class='text-primary mt-4'>$sem</h5>";
                                            echo "<table class='table table-striped table-hover align-middle table-bordered'>";
                                            echo "<thead class='table-light'>
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th class='text-center'>Units</th>
                                                    <th class='text-center'>With Lab</th>
                                                    <th class='text-center'>Actions</th>
                                                </tr>
                                              </thead>
                                              <tbody>";

                                            foreach ($rows as $r) {
                                                foreach ($r['subjects'] as $sub) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($sub['sub_code']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($sub['sub_name']) . "</td>";
                                                    echo "<td class='text-center'>" . htmlspecialchars($sub['units']) . "</td>";
                                                    echo "<td class='text-center'>" . ($sub['withLab'] ? "Yes" : "No") . "</td>";
                                                    echo "<td class='text-center'>
                                                        <button class='btn btn-danger btn-sm remove-subject' 
                                                                data-student='{$std_id}' 
                                                                data-subject='{$sub['sub_id']}'>
                                                            <i class='fas fa-trash'></i>
                                                        </button>
                                                      </td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            echo "</tbody></table>";
                                        }
                                    } else {
                                        echo "<p class='text-muted'>No enrolled subjects found.</p>";
                                    }
                                } else {
                                    echo "<p class='text-muted'>No enrolled subjects found.</p>";
                                }
                                ?>
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