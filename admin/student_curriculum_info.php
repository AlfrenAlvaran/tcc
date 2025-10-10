<?php
session_start();
include("includes/header.php");
require_once __DIR__ . '/../classes/NotTakenCurriculum.php';
$notTakenCurriculum = new NotTakenCurriculum();
$program_id = (int) $_GET['program'];
$studentID = (string) $_GET['id'];
$student_info = $notTakenCurriculum->getStudentBasicInfo($studentID);
// $students = $notTakenCurriculum->getAllStudentNotEnrolled($studentID, $program_id);
$notTakenSubjects=$notTakenCurriculum->notTakenSubjects($studentID, $program_id);

echo '<pre>';
// print_r($student_info);
print_r($notTakenSubjects);
echo '</pre>';


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