<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    require_once __DIR__ . "/../classes/Curriculum.php";

    $std_id = $_GET['id'];
    $curriculum = new Curriculum();
    $curriculum_data = $curriculum->getCurriculumByStudent($std_id);
    $student_info = !empty($curriculum_data) ? $curriculum_data[0] : null;
    ?>
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
                                            <td><?= $student_info['SY'] ?>-<?= htmlspecialchars($student_info['student_id']) ?></td>
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
                                            <td><?= $student_info['sy'] ?> <?= $student_info['semester'] == 1 ? '1st' : ($student_info['semester'] == 2 ? '2nd' : $student_info['semester'] . 'th') ?> Semester</td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <!-- <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Curriculum by Semester</h6>
                        </div> -->
                        <div class="card-body">

                            <!-- Student Information -->

                            <div class="table-responsive">
                                <?php
                                if (!empty($curriculum_data)) {
                                    $grouped = [];
                                    foreach ($curriculum_data as $row) {
                                        $semKey = "Semester {$row['semester']} - S.Y. {$row['sy']} - Year {$row['yr']}";
                                        $grouped[$semKey][] = $row;
                                    }

                                    foreach ($grouped as $sem => $rows) {
                                        echo "<h5 class='text-primary mt-4'>$sem</h5>";
                                        echo "<table class='table table-striped table-hover align-middle table-bordered'>";
                                        echo "<thead class='table-light'>
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th class='text-center'>Units</th>
                                                    <th class='text-center'>With Lab</th>
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
                                                echo "</tr>";
                                            }
                                        }

                                        echo "</tbody></table>";
                                    }
                                } else {
                                    echo "<p class='text-muted'>No curriculum data available.</p>";
                                }
                                ?>
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
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("includes/logout-modal.php"); ?>
    <?php include("includes/js-link.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>