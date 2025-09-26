<?php
require_once "./includes/header.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../classes/Teacher.php';
$teacher = new Teacher();
$tables = $teacher->showTable();

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
                            <h6 class="m-0 font-weight-bold text-primary">Schedule List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width: 20px;">#</th>
                                            <th>Subject</th>
                                            <th class="text-nowrap">Program</th>
                                            <th>Day</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                        </tr>
                                    </thead>
                                    <tbody>

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
        document.addEventListener("DOMContentLoaded", () => {
            const scheduleList = document.querySelector("#dataTable tbody");
            const teacherId = <?php echo json_encode($_SESSION['id']); ?>;
            
           (()=>{
            fetch(`ajax/get-schedules.php?id=${teacherId}`).then(res=>res.json()).then(data=>{
                scheduleList.innerHTML = data.map((sch, index) => `
                    <tr>
                        <td class="text-nowrap">${index + 1}</td>
                        <td>${sch.sub_code} - (${sch.units} units)</td>
                        <td class="text-nowrap">${sch.p_code} - Year ${sch.p_year}</td>
                        <td>${sch.day}</td>
                        <td>${sch.time_start} - ${sch.time_end}</td>
                        <td>${sch.room}</td>
                    </tr>
                `).join('');
            })
           })();

            console.log(`Fetching: ajax/get-schedules.php?id=${teacherId}`);
        })

       
    </script>
</body>

</html>