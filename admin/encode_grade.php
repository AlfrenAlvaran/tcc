<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    require_once __DIR__ . '/../classes/Curriculum.php';
    require_once __DIR__ . "/../classes/Students.php";
    $students = new Curriculum();
    $classList = $students->getStudentsBySubjectID($_GET['subID'] ?? '');
    $subjects = $students->getOneSubject((int)$_GET['subID']);
    $programs = $students->getCourse((int)$_GET['programID']);
    $count = $students->countEnrolledSubjects($_GET['subID']);
    // echo $count['total_enrolled'];

    $semester = '';


    switch ($_GET['sem']) {
        case 1:
            $semester = '1st Semester';
            break;
        case 2:
            $semester = '2nd Semester';
            break;
        case 3:
            $semester = '3rd Semester';
            break;
        default:
            $semester = 'Unknown Semester';
    }

    ?>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">

                    <div class="card shadow mb-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-center flex-column">
                                <b>
                                    <h4 style="color: black;"><?= $subjects['sub_code'] ?> - <?= $subjects['sub_name'] ?> </h4>
                                </b>

                                <span><?= $programs['p_code'] . ' ' .  $semester ?></span>
                                <span class="mt-3 mb-3">Total Student Enrolled <b style="color: black;"><?= $count['total_enrolled'] ?? 0 ?></b> </span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Encode Grades</h6>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table text-center table-bordered advised-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-start" style="text-align: start !important;">Student No.</th>x
                                            <th class="text-start" style="text-align: start !important;">Name</th>
                                            <th class="text-start">Course</th>
                                            <th class="text-start" style="text-align: start !important;">Gender</th>
                                            <th class="text-start" style="text-align: start !important;">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="advised-table-body">
                                        <?php if (!empty($classList)): ?>

                                            <?php foreach ($classList as $index => $item): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td class="text-start" style="text-align: start !important;"><?php echo $item['SY'] . '-' . $item['Student_id']; ?></td>
                                                    <td class="text-start" style="text-align: start !important;"><?php echo $item['Student_LName'] . ', ' . $item['Student_FName'] . ' ' . $item['Student_FName']; ?></td>
                                                    <td class="text-start"><?php echo $item['p_code']; ?></td>
                                                    <td class="text-start" style="text-align: start !important;"><?php echo $item['gender']; ?></td>
                                                    <td class="text-start" style="text-align: start !important;">
                                                        <input type="number" step="0.01" min="0" max="100"
                                                            name="grade"
                                                            class="grade-input"
                                                            data-student="<?= $item['Student_id']; ?>"
                                                            data-subject="<?= $_GET['subID']; ?>"
                                                            value="<?= $item['grade'] ?? ''; ?>">
                                                    </td>


                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endif; ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            let timer;

            $('.grade-input').on('input', function() {
                clearTimeout(timer);
                const input = $(this);

                timer = setTimeout(() => {
                    const grade = input.val().trim();
                    const studentID = input.data('student');
                    const subjectID = input.data('subject');

                    if (grade === '') return; // skip empty grades

                    $.ajax({
                        url: 'ajax/update_grade.php', // make sure file name matches your backend
                        type: 'POST',
                        data: {
                            student_id: studentID,
                            subject_id: subjectID,
                            grade: grade
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.affected_rows > 0) {
                                input.css('background-color', '#d4edda'); // success - green
                            } else if (response.success && response.affected_rows === 0) {
                                input.css('background-color', '#fff3cd'); // no record updated - yellow
                            } else {
                                input.css('background-color', '#f8d7da'); // error - red
                                console.error(response.error || 'No update');
                            }

                            setTimeout(() => {
                                input.css('background-color', '');
                            }, 1500);
                        },
                        error: function(xhr, status, error) {
                            input.css('background-color', '#f8d7da');
                            console.error('AJAX Error:', error);
                        }
                    });
                }, 800); // waits 0.8s after user stops typing
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $('.grade-input').on('input', function() {
                console.log('Typing detected...'); // for debugging
                const input = $(this);
            });
        });
    </script>

</body>

</html>