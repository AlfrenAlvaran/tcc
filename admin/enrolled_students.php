<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    include("../config/database.php");

    $sql = "
SELECT 
    e.id,
    s.Student_id,
    e.yr_level,
    e.sem AS sem,
    e.yr_level,
    e.sy,
    s.Student_FName,
    s.Student_MName,
    s.Student_LName,
    s.SY,
    p.p_code
FROM enrollments e
INNER JOIN students s ON e.student_id = s.Student_id
INNER JOIN programs p ON s.prog_id = p.program_id
ORDER BY s.Student_LName;
";


    $results = $database->view($sql);
    // echo '<pre>';print_r($results);
    // echo '</pre>';
    ?>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Enrolled Students List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Program</th>
                                           <th>options</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $row) { ?>

                                            <?php
                                               switch($row['sem']) {
                                                   case 1:
                                                       $row['sem'] = '1st Semester';
                                                       break;
                                                   case 2:
                                                       $row['sem'] = '2nd Semester';
                                                       break;
                                                   default:
                                                       $row['sem'] = 'Unknown Semester';
                                               }

                                               switch($row['yr_level']) {
                                                   case 1:
                                                       $row['yr_level'] = '1st Year';
                                                       break;
                                                   case 2:
                                                       $row['yr_level'] = '2nd Year';
                                                       break;
                                                   case 3:
                                                       $row['yr_level'] = '3rd Year';
                                                       break;
                                                   case 4:
                                                       $row['yr_level'] = '4th Year';
                                                       break;
                                                   default:
                                                       $row['yr_level'] = 'Unknown Year Level';
                                               }

                                               $group = $row['sy'].' '.$row['yr_level'] . " - " . $row['sem'];


                                            ?>


                                            <tr>
                                                <td><?= htmlspecialchars($row['SY']) ?>-<?= htmlspecialchars($row['Student_id']) ?></td>
                                                <td><?= htmlspecialchars($row["Student_LName"]) ?>, <?= htmlspecialchars($row["Student_FName"]) ?> <?= htmlspecialchars($row["Student_MName"]) ?></td>
                                                <td><?= htmlspecialchars($row['p_code']) ?></td>
                                                <td>
                                                    <Select>
                                                        <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                                                    </Select>
                                                </td>
                                                <td>
                                                    <a class="btn btn-info fas fa-edit fa-sm text-white" href="view-curriculum.php?id=<?= urlencode($row['Student_id']) ?>">View</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
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

</body>

</html>