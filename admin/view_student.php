<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    require_once __DIR__ . '/../classes/Curriculum.php';
    require_once __DIR__ . "/../classes/Students.php";
    $students = new Curriculum();
    $sy = (int) $_GET['year'] ?? 0;
    $sem = (int) $_GET['sem'] ?? 0;

    $advisedSubjects = $students->getAdvisedSubjects((int)$_GET['id'], $sy, $sem);
    $getSubjects = $students->getSubjectsByStudentAndSem((int)$_GET['student_id'], (int)$_GET['progID'], $sy, $sem);
    // echo "<pre>";
    // print_r($getSubjects);
    // echo "</pre>";

    $student = new Students();
    $semesters = $student->getSemestersEnrolled($_GET['student_id']);

    $advisedSubjects = $students->getSubjectsWithAdvising((int)$_GET['student_id'], (int)$_GET['id'], (int)$_GET['progID'], $sy, $sem);


    $advised = $students->GetData(
        "SELECT * FROM advising_tb WHERE student_id = ?",
        [$_GET['id']],
        false // fetchAll
    );

    foreach ($advised as $row) {
        $subject = $students->GetData(
            "SELECT * FROM subjects WHERE sub_id = ?",
            [$row['subject_id']],
            true // fetch single subject
        );
    }



    ?>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

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

                    <!-- Page Heading -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add Subject</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th style="width: 20px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="sub" class="form-control form-control-sm"
                                                    id="subjectDropdown">
                                                    <option value="">--- Select Subject ---</option>
                                                    <?php foreach ($students->getNotTakenSubjectsByStudent((string)$_GET['student_id']) as $subject): ?>
                                                        <option value="<?= $subject['sub_id'] ?>">
                                                            <?= $subject['sub_code'] . ' - ' . $subject['sub_name'] . ' - units ' . $subject['units'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" style="width: 200px;"
                                                    id="addSubject">Add</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><?= $_GET['student'] ?></h6>
                            <div class="float-right">
                                <button class="btn btn-primary btn-sm" id="addCurriculum" style="width: 200px;">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Subject Code</th>
                                            <th>Subject Name</th>
                                            <th class="text-center">Units</th>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Advised Subject</h6>
                            <button class="btn btn-primary btn-sm" style="width: 200px;" onclick="downloadPDF()">Download</button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="d-flex justify-content-center mt-4 flex-column align-items-center">


                                    <table class="table text-center table-bordered advised-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-start" style="text-align: left !important;">Subject Code</th>
                                                <th class="text-start" style="text-align: left !important;">Subject Description</th>
                                                <th>Units</th>
                                                <th>With Lab</th>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody class="advised-table-body">

                                        </tbody>
                                    </table>
                                </div>
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
    <?php include("includes/logout-modal.php"); ?>

    <!-- ================== JS SCRIPTS ================== -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#dataTable').DataTable();
            let subjectCart = [];

            $("#addSubject").on("click", function(e) {
                e.preventDefault();
                let dropdown = $("#subjectDropdown");
                let selectedValue = dropdown.val();
                let selectedText = dropdown.find("option:selected").text();
                let unitsMatch = selectedText.match(/units (\d+)/);
                let units = unitsMatch ? parseInt(unitsMatch[1]) : 0;

                if (!selectedValue) {
                    alert("Please select a subject first.");
                    return;
                }

                if (subjectCart.some(sub => sub.sub_id == selectedValue)) {
                    alert("This subject is already added to the cart!");
                    return;
                }

                subjectCart.push({
                    sub_id: selectedValue,
                    sub_code: selectedText.split(' - ')[0],
                    sub_name: selectedText.split(' - ')[1],
                    units: units
                });

                renderTable();
            });

            $('#dataTable tbody').on('click', '.remove-subject', function() {
                let subId = $(this).data('id');
                subjectCart = subjectCart.filter(sub => sub.sub_id != subId);
                renderTable();
            });

            function renderTable() {
                table.clear().draw();

                subjectCart.forEach(subject => {
                    table.row.add([
                        subject.sub_code,
                        subject.sub_name,
                        `<div class="text-center">${subject.units}</div>`,
                        `<button class="btn btn-danger btn-sm remove-subject" data-id="${subject.sub_id}">
                    <i class="fas fa-trash"></i>
                 </button>`
                    ]).draw(false);
                });
            }

            $("#addCurriculum").on("click", function() {
                if (subjectCart.length === 0) {
                    alert("No subjects to add. Please add subjects first.");
                    return;
                }


                $.ajax({
                    url: "ajax/enroll_curriculum.php",
                    type: "POST",
                    data: {
                        student_id: <?= (string)$_GET['student_id']; ?>,
                        prog_id: <?= (int)$_GET['progID']; ?>,
                        cur_year: <?= (int)$_GET['year']; ?>,
                        sem: <?= (int)$_GET['sem']; ?>,
                        subjects: JSON.stringify(subjectCart)
                    },
                    success: function(response) {
                        alert("Response: " + response.message);
                        subjectCart = [];
                        table.clear().draw();
                        location.load();
                    },
                    error: () => {
                        alert("An error occurred while adding the curriculum.");
                    }
                })
            })
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tbody = document.querySelector(".advised-table-body");
            const studentIDString = <?= json_encode($_GET['student_id']) ?>;
            const programID = <?= json_encode($_GET['progID']) ?>;
            const year = <?= json_encode($_GET['year']) ?>;
            const sem = <?= json_encode($_GET['sem']) ?>;

            fetchSubjects(studentIDString, programID, year, sem);

            async function fetchSubjects(studentIDString, programID, year, sem) {
                const res = await fetch(`api/get-curriculum.php?student_id=${studentIDString}&progID=${programID}&year=${year}&sem=${sem}`);
                const data = await res.json();

                tbody.innerHTML = '';

                if (!data.length || !data[0].subjects.length) {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-muted">No subjects found</td></tr>`;
                    return;
                }

                data[0].subjects.forEach((subject, index) => {
                    tbody.insertAdjacentHTML("beforeend", `
                <tr data-id="${subject.sub_id}">
                    <td>${index + 1}</td>
                    <td class="text-start" style="text-align: left !important;">${subject.sub_code}</td>
                    <td class="text-start" style="text-align: left !important;">${subject.sub_name}</td>
                    <td>${subject.units}</td>
                    <td>${subject.withLab == 1 ? "Yes" : "No"}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-subject">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
                });

                // 🧠 Attach listeners *after* inserting the rows
                tbody.querySelectorAll(".remove-subject").forEach(button => {
                    button.addEventListener("click", async (e) => {
                        const row = e.target.closest("tr");
                        const subjectID = row.dataset.id;

                        if (!confirm("Are you sure you want to remove this subject?")) return;

                        const res = await fetch(`api/remove_subject.php?id=${subjectID}`, {
                            method: "DELETE"
                        });

                        const result = await res.json();
                        console.log(result)

                        if (result.success) {
                            row.remove();
                            alert("Subject removed successfully!");

                            fetchSubjects(studentIDString, programID, year, sem);
                        } else {
                            alert("Failed to remove subject: " + result.message);
                        }
                    });
                });
            }
        });
    </script>


    <script>
        function downloadPDF() {
            window.location.href = `api/download_curriculum.php?student_id=<?= $_GET['student_id']; ?>&prog_id=<?= (int)$_GET['progID']; ?>&cur_year=<?= (int)$_GET['year']; ?>&sem=<?= (int)$_GET['sem']; ?>`;
        }
    </script>
</body>

</html>