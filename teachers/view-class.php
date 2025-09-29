<?php
require_once "./includes/header.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_GET['id'] ?? 0;
require_once __DIR__ . '/../classes/Classes.php';
$classes = new Classes();
$subjectName = $_GET['subject'] ?? 'Class';

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
                            <h6 class="m-0 font-weight-bold text-primary">Students List</h6>

                            <div class="d-flex gap-2 justify-content-end mb-2">
                                <!-- Export Button -->
                                <button type="button" id="exportStudents" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel"></i> Export to Excel
                                </button>

                                <!-- Upload Button -->
                                <form id="uploadExcelForm" enctype="multipart/form-data">
                                    <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx" hidden>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="document.getElementById('excelFile').click()">
                                        <i class="fas fa-upload"></i> Upload Grades
                                    </button>
                                </form>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width: 20px;">#</th>
                                            <th>Name</th>
                                            <th class="text-nowrap" style="width: 50px; text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($classes->getStudentWithProgramAndStudents($id) as $student): ?>
                                            <?php
                                            $name = $student['Complete_Name'];
                                            $parts = explode(",", $name);
                                            if (count($parts) === 2) {
                                                $lastname = trim($parts[0]);
                                                $firstMiddle = trim($parts[1]);
                                                $formattedName = $firstMiddle . " " . $lastname;
                                            } else {
                                                $formattedName = $name;
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $student['id'] ?></td>
                                                <td><?= $formattedName ?></td>
                                                <td>
                                                    <a href="view-student.php?id=<?= $student['id'] ?>&subject=<?= urlencode($subjectName) ?>"
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

    <script>
        $('#exportStudents').on('click', function() {
            window.location.href = `ajax/export-students.php?id=<?= $id ?>&subject=<?= urlencode($subjectName) ?>`;
        });
        $('#excelFile').on('change', function() {
            const formData = new FormData($('#uploadExcelForm')[0]);
            formData.append("subject", "<?= $subjectName ?>");

            fetch('ajax/upload-grade.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    location.reload();
                });
        });
    </script>
</body>

</html>