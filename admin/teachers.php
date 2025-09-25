<?php
session_start();
require "./includes/header.php";
require "../classes/Teacher.php"
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        require("includes/left-nav.php");
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                require "./includes/top-bar.php";
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">



                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Teachers List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width: 80px;">#</th>
                                            <th>Name</th>
                                            <th class="text-nowrap" style="width: 100px;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $objects = new Teacher();
                                        $teachers = $objects->getAllTeachers();
                                        foreach ($teachers as $t) {
                                        ?>

                                        <tr>
                                            <td><?= $t['id'] ?></td>
                                           <td><?= ucwords($t['fname'] . ' ' . $t['lname']) ?></td>
                                            <td>
                                               <a href="./assigned_subjects.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-primary">Assign</a>
                                                
                                            </td>
                                        </tr>

                                        <?php  } ?>
                                    </tbody>
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