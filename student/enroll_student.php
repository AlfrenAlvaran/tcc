<?php
	session_start();
	include("../config/database.php");
	include("includes/header.php"); 
	$sql ="SELECT * FROM curriculum";
	$results = $database->view($sql);
	
	$sql ="SELECT * FROM programs";
	$prog = $database->view($sql);
	
	$sql="SELECT * FROM students";
    $acc = $database->count($sql);
	//echo "Account: ".($acc);

    $sql1="SELECT * FROM students WHERE Student_id=".$_GET['id'];
	$row=$database->getdata($sql1);
    //print_r($row);
    //echo "".$row['password'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        function getRole(){
            var role = "<?=$row['acc_level']; ?>"
           // alert(role);
        }
    </script>
</head>

<body id="page-top" onload="getRole()" >

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
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
							<a href="students.php">Back</a>
							</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
								<div class="col-lg-0 d-none d-lg-block bg-register-image">
								</div>
								<div class="col-lg-3"></div>
								<div class="col-lg-6">
									<div class="p-5">
										<div class="text-center">
											<h1 class="h4 text-gray-900 mb-4">Enroll Student</h1>
										</div>
										<form action="ajax/enroll_stud.php?status=1" method="POST" class="user" >
											<div class="form-group row">
												<div class="col-sm-3 mb-1 mb-sm-0">
													Student ID: <?=$row['SY']; ?>
												</div>
												<div class="col-sm-2 mb-1 mb-sm-0">
													<input type="text" readonly name="stid" value="<?=$row['Student_id']; ?>" class="form-control">
												</div>
												<div class="col-sm-1 mb-1 mb-sm-0"></div>
												<div class="col-sm-6 mb-3 mb-sm-0">
													<input type="text" name="cname" readonly value="<?=$row["Student_LName"]; ?>, <?=$row["Student_FName"]; ?> <?=$row["Student_MName"]; ?>" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<select class="form-control" name="course" id="course">
														<option value="" selected>Course</option>
														<?php
															foreach($prog as $row){
																 ?>
																	<option value="<?=$row["p_code"]; ?>"><?=$row["p_code"]; ?></option>
																 <?php
															}
														?>
													</select>
												</div>
												<div class="col-sm-6">
													<select class="form-control" name="cur" id="cur">
														<option value="" selected>Curriculum</option>
														<?php
															foreach($results as $row){
														?>
															<option value="<?=$row["cur_year"]; ?>"><?=$row["cur_year"]; ?></option>
														<?php
															}
														
														?>
													</select>
												</div>
											</div>
											
											<input type="submit" name="enroll" value="Submit" class="btn btn-primary btn-user btn-block">
										</form>
									   
									</div>
								</div>
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