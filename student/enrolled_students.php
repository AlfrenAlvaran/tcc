<!DOCTYPE html>
<html lang="en">

<head>
<?php
	session_start();
    include("includes/header.php"); 
	include("../config/database.php");
	$sql="SELECT * FROM students as s, enrolled_students as es WHERE s.Student_id=es.Student_id";
	$results = $database->view($sql);
	//fetch Programs
	
	//$sql="SELECT * FROM curriculum as c, enrolled_students as es WHERE c.cur_year=es.curriculum";
	//$results = $database->view($sql);
	
	$sql = "SELECT * FROM students ORDER BY Student_LName ASC";
	$programs = $database->view($sql);
 ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
		<?php 
			include("includes/left-nav.php"); 
		?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
				<?php
					include("includes/top-bar.php"); 
				?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
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
											<th>Course</th>
											<th>Curriculum</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
									<?php foreach($results as $row){ ?>
                                        <tr>
											<td><?=$row['SY']; ?>-<?=$row['Student_id']; ?></td>
                                            <td><?=$row["Student_LName"]; ?>, <?=$row["Student_FName"]; ?> <?=$row["Student_MName"]; ?></td>
											<td><?=$row['course']; ?></td>
											<td><?=$row['curriculum']; ?></td>
                                            <td>
												<a class="btn btn-info fas fa-edit fa-sm text-whire-100" href="test.php?id=<?=$row['Student_id']; ?>">Edit Curriculum</a>
											</td>
                                        </tr>
										<?php  } ?>
                                    </tbody>
                                </table>
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
	<?php 
		include("includes/logout-modal.php");
	?>

    <!-- Bootstrap core JavaScript-->
    <?php 
		include("includes/js-link.php");
	?>

</body>

</html>