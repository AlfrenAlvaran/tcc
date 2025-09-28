<?php
require_once "./includes/header.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = (int)($_GET['id'] ?? 0);
require_once __DIR__ . '/../classes/Grades.php';
$name_of_student = new Grades();
$student = $name_of_student->getStudent($id);
// print_r($student);
$subjectName = $_GET['subject'] ?? '';


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
                            <h6 class="m-0 font-weight-bold text-primary"><?= $formattedName ?></h6>
                            <div class="d-flex justify-content-end mb-2">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                                    Add Grade
                                </button>

                            </div>

                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width: 20px;">#</th>
                                            <th>Subject</th>
                                            <th>Prelim</th>
                                            <th>Midterms</th>
                                            <th>Finals</th>
                                            <th>Remarks</th>
                                            <th>actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->



            <!-- Modal -->
            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Record Grade</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="subject">Subject </label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($subjectName) ?>" id="subject" name="subject" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="prelim">Quarter</label>
                                    <select name="quarter" id="quarter" class="form-control">
                                        <option value="Prelim">Prelim</option>
                                        <option value="Midterms">Midterms</option>
                                        <option value="Finals">Finals</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="grade">Grade</label>
                                    <input type="number" class="form-control" id="grade" name="grade" min="0" max="100" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

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
        document.addEventListener('DOMContentLoaded', load());

        function loadData() {
            const table = document.querySelector('#dataTable tbody');

            fetch(`ajax/load-grade.php?id=<?= $id ?>`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    table.innerHTML = '';

                    if (data.length === 0) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>1</td>
                    <td><?= $subjectName ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                `;
                        table.appendChild(row);
                    } else {
                        data.forEach((grade, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${index + 1}</td>
                        <td><?= $subjectName ?></td>
                        <td>${grade.prelim ?? ''}</td>
                        <td>${grade.midterms ?? ''}</td>
                        <td>${grade.finals ?? ''}</td>
                        <td>${grade.remarks ?? ''}</td>
                        <td>
                            <button class="btn btn-sm btn-warning">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    `;
                            table.appendChild(row);
                        });
                    }
                })
                .catch(error => console.error('Error fetching grades:', error));
        }


        function load() {
            loadData();
        }
    </script>
</body>

</html>