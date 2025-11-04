<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    require_once __DIR__ . '/../classes/Students.php';
    require_once __DIR__ . '/../helpers/prev.php';
    $info = new Students();
    $student_id = $_GET['id'] ?? null;
    $info  = $info->getPersonalInfo($student_id);

    $student = $info['student'];
    $personal = $info['personal'];
    $uploads = $info['uploads'];
    // print_pre($personal);

    ?>

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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>


                    </div>

                    <!-- Content Row -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header p-4 d-flex align-items-center">
                            <i class="fas fa-fw fa-folder fa-lg text-primary mr-2"></i>
                            <h6 class="m-0 font-weight-bold text-primary text-uppercase">
                                View Student Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table table-striped table-borderless">
                                    <h1 style="font-size: large;"><i class="fas fa-fw fa-user"></i>Student Personal Information</h1>
                                    <tr>
                                        <th style="width: 200px;">Student Picture</th>
                                        <?php if (!empty($uploads['profile'])): ?>
                                            <td><img src="<?php echo $uploads['profile']; ?>" alt="Student Picture" class="img-fluid" style="max-width: 150px;"></td>
                                        <?php else: ?>
                                            <td><img src="./../img/unknown.jpg" alt="Unknown"></td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <th>Student ID</th>
                                        <td><?php echo $student['SY'] . "-" . $student['Student_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Student Name</th>
                                        <td><?= $student['Student_FName']. " " . $student['Student_MName'] . " " . $student['Student_LName'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td><?= $student['birthday'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td><?= $student['gender'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td><?= $personal['address'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>LRN/ULI</th>
                                        <td><?= $personal['LRN'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>

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