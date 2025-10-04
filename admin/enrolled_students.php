<!DOCTYPE html>
<html lang="en">

<head>
<?php
    session_start();
    include("includes/header.php"); 
    include("../config/database.php");

    $sql = "SELECT 
                s.Student_id,
                s.Student_FName,
                s.Student_LName,
                s.Student_MName,
                es.sy,
                es.sem,
                es.yr_level,
                es.curriculum_id
            FROM students s
            INNER JOIN enrollments es ON s.Student_id = es.student_id
            ORDER BY s.Student_LName ASC, es.sy DESC, es.sem ASC";

    $results = $database->view($sql);
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
                                            <th>Year Level</th>
                                            <th>Semester</th>
                                            <th>School Year</th>
                                            <th>Curriculum ID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($results as $row){ ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['sy']) ?>-<?= htmlspecialchars($row['Student_id']) ?></td>
                                            <td><?= htmlspecialchars($row["Student_LName"]) ?>, <?= htmlspecialchars($row["Student_FName"]) ?> <?= htmlspecialchars($row["Student_MName"]) ?></td>
                                            <td><?= htmlspecialchars($row['yr_level']) ?></td>
                                            <td><?= htmlspecialchars($row['sem']) ?></td>
                                            <td><?= htmlspecialchars($row['sy']) ?></td>
                                            <td><?= htmlspecialchars($row['curriculum_id']) ?></td>
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
