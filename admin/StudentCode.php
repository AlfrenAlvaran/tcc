<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    require_once __DIR__ . '/../classes/Curriculum.php';
    require_once __DIR__ . "/../classes/Students.php";
    $students = new Curriculum();
    $curriculum = $students->SemesterAndProgram();
    // var_dump($curriculum);
    $unique = $students->getUniqueSemesters();

    $selectedSem = $_GET['sem'] ?? '';
    $selectedProgram = $_GET['programID'] ?? '';



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
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">School Year & Semester</label>
                                    <select name="sysem" id="sysem" class="form-control">
                                        <option value="">-- Select School Year & Semester --</option>
                                        <?php foreach ($unique as $item): ?>
                                            <?php
                                            switch ($item['sem']) {
                                                case 1:
                                                    $semText = '1st Semester';
                                                    break;
                                                case 2:
                                                    $semText = '2nd Semester';
                                                    break;
                                                case 3:
                                                    $semText = '3rd Semester';
                                                    break;
                                                default:
                                                    $semText = 'Unknown Semester';
                                            }

                                            $isSelected = (!empty($selectedSem) && $selectedSem == $item['sem']) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $item['sem']; ?>" <?php echo $isSelected; ?>>
                                                <?php echo  $item['sy'] . $semText; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Course</label>
                                    <select name="course" id="course" class="form-control">
                                        <option value="">-- Select Course --</option>
                                        <?php foreach ($curriculum as $item): ?>
                                            <option value="<?php echo $item['cur_program_id']; ?>" <?php echo ($selectedProgram == $item['cur_program_id']) ? 'selected' : ''; ?>>
                                                <?php echo $item['p_code']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Schedule</h6>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table text-center table-bordered advised-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-start">Subject Code</th>
                                            <th class="text-start">Subject Description</th>
                                            <th>Units</th>
                                            <!-- <th>Student Count</th> -->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="advised-table-body">
                                        <!-- Dynamic content here -->
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
            let userTriggered = false;

            $('#sysem').on('mousedown keydown', function() {
                userTriggered = true;
            });

            $('#sysem').on('change', function() {
                if (!userTriggered) return;
                const value = $(this).val();
                if (value) {
                    const sem = value;
                    window.location.href = `StudentCode.php?sem=${sem}`;
                }
            });

            $('#course').on('change', function() {
                const programID = $(this).val();
                const sem = new URLSearchParams(window.location.search).get('sem');
                if (sem && programID) {
                    window.location.href = `StudentCode.php?sem=${sem}&programID=${programID}`;
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sem = urlParams.get('sem');
            const programID = urlParams.get('programID');

            function fetchSchedule(sem, programID) {
                $.ajax({
                    url: 'ajax/fetch-schedule.php',
                    type: 'GET',
                    data: {
                        sem: sem,
                        programID: programID
                    },
                    dataType: 'json',
                    success: function(response) {
                        const tbody = $('.advised-table-body');
                        tbody.empty();

                        if ($.fn.DataTable.isDataTable('.advised-table')) {
                            $('.advised-table').DataTable().clear().destroy();
                        }

                        if (response.length > 0) {
                            response.forEach((item, index) => {
                                console.log(item);
                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td class="text-start">${item.sub_code}</td>
                                        <td class="text-start">${item.sub_name}</td>
                                        <td>${item.units}</td>
                                       
                                        <td>
                                        <a href="encode_grade.php?sub_id=${item.sub_id}&sem=${sem}&programID=${programID}&subID=${item.sub_id}&count=${item.student_count}" class="btn btn-primary btn-sm">View Classes</a>
                                      
                                        </td>
                                    </tr>
                            `);
                            });
                        } else {
                            tbody.append('<tr><td colspan="5">No subjects found</td></tr>');
                        }

                        $('.advised-table').DataTable();
                    }
                });
            }

            if (sem && programID) {
                fetchSchedule(sem, programID);
            }


        });
    </script>

</body>

</html>