<?php
session_start();
include("includes/header.php");
require_once __DIR__ . '/../classes/NotTakenCurriculum.php';
$notTakenCurriculum = new NotTakenCurriculum();
$program_id = (int) $_GET['program'];
$studentID = (string) $_GET['id'];
$student_info = $notTakenCurriculum->getStudentBasicInfo($studentID);
$curriculumId = (int) $student_info['cur_id'];
// echo $curriculumId;
$notTakenSubjects = $notTakenCurriculum->notTakenSubjects($studentID, $curriculumId);

$takenSubjects = $notTakenCurriculum->takenSubjects($studentID);
$taken = $notTakenCurriculum->getCurriculumByStudent($studentID);

// echo '<pre>';
// print_r($taken);
// echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include("includes/left-nav.php");
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                include("includes/top-bar.php");
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card-body">
                        <?php if ($student_info): ?>
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>

                                    <tr>
                                        <th style="width: 150px;">Student No.</th>
                                        <td><?= $student_info['SY'] ?>-<?= htmlspecialchars($student_info['Student_id']) ?> [Student Info]</td>
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

                        <div class="card shadow mt-3">
                            <?php
                            if (!empty($taken)) {
                                foreach ($taken as $row) {
                                    if (empty($row['subjects'])) continue;

                                    $year = (int)$row['level'];
                                    $sem = (int)$row['semester'];
                                    $schoolYear = $row['sy'];

                                    if ($year <= 0 || $sem <= 0) {
                                        continue;
                                    }



                                    $yearLabel = $year == 1 ? "1st Year" : ($year == 2 ? "2nd Year" : ($year == 3 ? "3rd Year" : $year . "th Year"));
                                    $semLabel = $sem == 1 ? "1st Semester" : ($sem == 2 ? "2nd Semester" : $sem . "th Semester");
                                    $semKey = "$yearLabel - $semLabel (S.Y. {$schoolYear})";

                                    $grouped[$semKey][] = $row;
                                }
                            }
                            ?>

                            <div class="card-body">
                                <span class="text-primary mb-3">
                                    <?= $yearLabel . " " . $semLabel ?>
                                </span>
                                <table class="table table-borderless table-sm">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Units</th>
                                            <th>With Lab</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($grouped)): ?>
                                            <?php foreach ($grouped as $sem => $rows): ?>
                                                <?php foreach ($rows as $r) : ?>

                                                   <?php foreach($r['subjects'] as $sub) :?>
                                                    <tr>
                                                        <td><?= $sub['sub_code'] ?></td>
                                                        <td><?= $sub['sub_name'] ?></td>
                                                        <td class="text-center"><?= $sub['units'] ?></td>
                                                        <td class="text-center"><?= $sub['withLab'] ? 'Yes' : 'No' ?></td>
                                                        <td class="text-center">Taken</td>
                                                    </tr>
                                                   <?php endforeach ?>

                                                <?php endforeach ?>
                                            <?php endforeach; ?>
                                            <?php  ?>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card shadow mt-4">
                            <div class="card-body">
                                <span class="text-primary">
                                    Not Taken Subjects
                                </span>
                                <table class="table table-borderless table-sm mb-1">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th class="">Units</th>
                                            <th class="">With Lab</th>
                                            <th class="">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($notTakenSubjects)): ?>
                                            <?php foreach ($notTakenSubjects as $subject): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($subject['sub_code']) ?></td>
                                                    <td><?= htmlspecialchars($subject['sub_name']) ?></td>
                                                    <td class=""><?= htmlspecialchars($subject['units']) ?></td>
                                                    <td class=""><?= $subject['withLab'] ? 'Yes' : 'No' ?></td>
                                                    <td class="">
                                                        Not Taken
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No subjects available to add.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php
    include("includes/logout-modal.php");
    ?>

    <!-- Bootstrap core JavaScript-->
    <?php
    include("includes/js-link.php");
    ?>

</body>

</html>