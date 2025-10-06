<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    // include("../config/database.php");
    require_once __DIR__ . '/../classes/Enrollment.php';


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
                            <i class="fas fa-fw fa-folder fa-lg text-primary me-2"></i>
                            <h6 class="m-0 font-weight-bold text-primary text-uppercase">
                                Student Personal Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- content -->
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