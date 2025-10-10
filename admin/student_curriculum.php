<?php
session_start();
include("includes/header.php");
require_once __DIR__ . '/../classes/Enrollment.php';
$enrollment = new Enrollment();
$students = $enrollment->getAllStudentNotEnrolled();



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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Program </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>


                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td><?php echo $student['SY'] . '-' . $student['Student_id']; ?></td>
                                                <td><?php echo $student['Student_FName'] . ' ' . $student['Student_LName']; ?></td>
                                                <td><?php echo $student['p_code']; ?></td>
                                                <td>
                                                    <a href="student_curriculum_info.php?id=<?= $student['Student_id']; ?>&program=<?= urlencode($student['prog_id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-fw fa-eye"></i>
                                                    </a>
                                                   
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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