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
                            <h6 class="m-0 font-weight-bold text-primary">Class List</h6>
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






</body>

</html>