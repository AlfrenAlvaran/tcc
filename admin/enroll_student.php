<?php
session_start();
include("includes/header.php");

require_once __DIR__ . '/../classes/Enrollment.php';
$enrollment = new Enrollment();


$student = $enrollment->GetData(
    "SELECT * FROM students WHERE Student_id = ?",
    [$_GET['id']]
);

if (!$student) {
    die("Student not found.");
}


$curriculum = $enrollment->getCurriculumByStudent((int)$student['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        function getRole() {
            var role = "<?= $student['acc_level'] ?? '' ?>";

        }
    </script>
</head>

<body id="page-top" onload="getRole()">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("includes/left-nav.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("includes/top-bar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- DataTales Example -->

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <a href="students.php">Back</a>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-0 d-none d-lg-block bg-register-image"></div>
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Enroll Student</h1>
                                        </div>

                                        <form action="ajax/enroll_stud.php?status=1" method="POST" class="user">
                                            <!-- hidden curriculum ids -->
                                            <?php foreach ($curriculum as $cur): ?>
                                                <input type="hidden" name="cur_id[]" value="<?= $cur['cur_id']; ?>">
                                            <?php endforeach; ?>

                                            <div class="form-group row">
                                                <div class="col-sm-3 mb-1 mb-sm-0">
                                                    Student ID: <?= htmlspecialchars($student['SY']); ?>
                                                </div>
                                                <div class="col-sm-2 mb-1 mb-sm-0">
                                                    <input type="text" readonly name="stid"
                                                        value="<?= htmlspecialchars($student['Student_id']); ?>"
                                                        class="form-control">
                                                </div>
                                                <div class="col-sm-1 mb-1 mb-sm-0"></div>
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" name="cname" readonly
                                                        value="<?= htmlspecialchars($student["Student_LName"] . ", " . $student["Student_FName"] . " " . $student["Student_MName"]); ?>"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <select class="form-control" required name="semester" id="course">
                                                        <option value="" selected>Semester</option>
                                                        <option value="1">1st Semester</option>
                                                        <option value="2">2nd Semester</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?php
                                                    $current_year = date("Y");
                                                    $next_year = $current_year + 1;
                                                    $sy = $current_year . '-' . $next_year;
                                                    ?>
                                                    <input type="text" name="sy" readonly value="<?= $sy; ?>"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <select name="yr_level" id="" class="form-control">
                                                        <option value="" selected>Year Level</option>
                                                        <option value="1">1st Year</option>
                                                        <option value="2">2nd Year</option>
                                                        <option value="3">3rd Year</option>
                                                        <option value="4">4th Year</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <input type="submit" name="enroll" value="Submit"
                                                class="btn btn-primary btn-user btn-block">
                                        </form>

                                    </div>
                                </div>
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
    <?php include("includes/logout-modal.php"); ?>

    <!-- Bootstrap core JavaScript-->
    <?php include("includes/js-link.php"); ?>

</body>

</html>