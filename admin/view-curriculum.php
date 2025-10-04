<!DOCTYPE html>
<html lang="en">

<head>
<?php
    session_start();
    include("includes/header.php"); 
    require_once __DIR__ . "/../classes/Curriculum.php";

    $std_id = $_GET['id'];
    $curriculum = new Curriculum();
    $curriculum_data = $curriculum->getCurriculumByStudent($std_id);
?>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">                   
                    <!--  -->

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Curriculum by Semester</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                if (!empty($curriculum_data)) {
                                    $grouped = [];
                                    foreach ($curriculum_data as $row) {
                                        $semKey = "S.Y. {$row['sy']} - Year {$row['yr']} - Sem {$row['semester']}";
                                        $grouped[$semKey][] = $row;
                                    }

                                    foreach ($grouped as $sem => $rows) {
                                        echo "<h5 class='text-primary mt-4'>$sem</h5>";
                                        echo "<table class='table table-sm table-striped table-bordered'>";
                                        echo "<thead class='thead-light'>
                                                <tr>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Units</th>
                                                    <th>With Lab</th>
                                                </tr>
                                              </thead>
                                              <tbody>";

                                        foreach ($rows as $r) {
                                            foreach ($r['subjects'] as $sub) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($sub['sub_code']) . "</td>";
                                                echo "<td>" . htmlspecialchars($sub['sub_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($sub['units']) . "</td>";
                                                echo "<td>" . ($sub['withLab'] ? "Yes" : "No") . "</td>";
                                                echo "</tr>";
                                            }
                                        }

                                        echo "</tbody></table>";
                                    }
                                } else {
                                    echo "<p class='text-muted'>No curriculum data available.</p>";
                                }
                                ?>
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
