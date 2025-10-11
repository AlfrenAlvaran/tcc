<?php
require_once "./includes/header.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_SESSION['id'] ?? 0;
require_once __DIR__ . '/../classes/Classes.php';
$classes = new Classes();
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once "./includes/left-nav.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once "./includes/top-bar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Class List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width: 20px;">#</th>
                                            <th>Program</th>
                                            <th class="text-nowrap">Subject</th>
                                            <th>Subject Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($classes->getClasses($id) as $class): ?>
                                            <tr>

                                                <td class="text-nowrap"><?php echo htmlspecialchars($class['schedule_id']); ?></td>
                                                <td><?php echo htmlspecialchars($class['p_code'] . ' - ' . $class['p_year']); ?></td>
                                                <td><?php echo htmlspecialchars($class['sub_name']); ?></td>
                                                <td><?php echo htmlspecialchars($class['sub_code']); ?></td>
                                                <td>
                                                    <a href="view-class.php?id=<?= urlencode($class['schedule_id']); ?>&subject=<?= urlencode($class['sub_name']); ?>"
                                                        class="btn btn-primary btn-sm">View</a>

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
                        <span>Copyright &copy; Your Website 2025</span>
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