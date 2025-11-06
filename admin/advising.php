<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    require_once __DIR__ . '/../classes/Enrollment.php';
    $students = new Enrollment();
    $student_list = $students->getStudentsWithEnrollments();
    ?>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Student List</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Program</th>
                                            <th>Semester & Year Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($student_list as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['SY'] . '-' . $student['Student_id']); ?></td>
                                                <td><?php echo htmlspecialchars($student['Student_FName'] . ' ' . $student['Student_LName']); ?></td>
                                                <td><?php echo htmlspecialchars($student['p_code']); ?></td>

                                                <!-- Merged Dropdown -->
                                                <td>
                                                    <select class="form-control form-control-sm semyear-select">
                                                        <?php 
                                                        $semYears = explode(',', $student['sem_year']);
                                                        foreach ($semYears as $sy) {
                                                            list($sem, $year) = explode('-', $sy);
                                                            $semText = $sem == 1 ? '1st Semester' : '2nd Semester';
                                                            echo "<option value='{$sem}-{$year}'>{$semText} - Year {$year}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>

                                                <!-- View Button -->
                                                <td>
                                                    <a href="#"
                                                       class="btn btn-info btn-sm view-btn"
                                                       data-user-id="<?= htmlspecialchars($student['user_id']); ?>"
                                                       data-student-name="<?= htmlspecialchars($student['Student_FName'] . ' ' . $student['Student_LName']); ?>"
                                                       data-prog-id="<?= htmlspecialchars($student['prog_id']); ?>"
                                                       data-student-id="<?= htmlspecialchars($student['Student_id']); ?>">
                                                       View
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
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("includes/logout-modal.php"); ?>
    <?php include("includes/js-link.php"); ?>

    <!-- JS: handle merged dropdown and View button -->
    <script>
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const row = this.closest('tr');
                const selected = row.querySelector('.semyear-select').value;
                const [sem, year] = selected.split('-');

                const userId = this.dataset.userId;
                const studentName = encodeURIComponent(this.dataset.studentName);
                const progId = encodeURIComponent(this.dataset.progId);
                const studentId = encodeURIComponent(this.dataset.studentId);

                const url = `view_student.php?id=${userId}&student=${studentName}&year=${year}&sem=${sem}&progID=${progId}&student_id=${studentId}`;
                window.location.href = url;
            });
        });
    </script>

</body>
</html>
