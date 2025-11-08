<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	session_start();
	include("includes/header.php");
	include("../config/database.php");
	require "../classes/Curriculum.php";
	// Get the last student ID
	$sql = "SELECT * FROM students ORDER BY Student_id DESC LIMIT 1";
	$results = $database->view($sql);

	$newID = isset($results[0]['Student_id']) ? $results[0]['Student_id'] + 1 : 1;
	// fetch Programs
	$newID = str_pad($newID, 4, '0', STR_PAD_LEFT);
	$curr = new Curriculum();
	$assign_cur = $curr->assign_curriculum(1);
	// print_r($assign_cur);


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

					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800"></h1>
					</div>

					<!-- Content Row -->

					<!-- DataTales Example -->
					<div class="card shadow mb-4 d-flex justify-content-start">
						<div class="card-header">
							<h6 class="m-0 font-weight-bold text-primary"><a href="students.php">Back</a></h6>
							<div class=""></div>
						</div>
						<div class="card-body">
							<div class="" style="width: 100%;">
								<div class="col-lg-0 d-none d-lg-block bg-register-image"></div>
								<div class="col-lg-3"></div>
								<!-- <div class="col-lg-6"> -->
								<div class="p-5">

									<h1 class="h4 text-gray-900 mb-4">Add new Student</h1>

									<form action="ajax/student_add.php?status=1" method="POST" class="user">
										
										<div class="mb-3 ">
											<label for="studentID" class="form-label">Student ID</label>
											<div class="row">
												<div class="col-lg-5">
													<input type="text" name="sy" readonly class="form-control" value="S<?php echo date('y'); ?>">
												</div>
												<div class="col-lg-7 mb-3">
													<input type="text" name="stid" readonly class="form-control" value="<?= $newID ?>">
												</div>
											</div>
										</div>

										<div class="mb-3">
											<label for="program" class="form-label">Program</label>
											<select name="pid" id="pid" class="form-control">
												<?php foreach ($curr->getProgram() as $prog) {  ?>
													<option value="<?= $prog['program_id'] ?>"><?= $prog['p_des'] ?></option>
												<?php } ?>
											</select>
										</div>


										<div class="row mb-3">
											<div class="col-lg-4">
												<label for="fname" class="form-label">First Name</label>
												<input type="text" name="fname" class="form-control" required autocomplete="off" placeholder="First Name">
											</div>
											<div class="col-lg-4">
												<label for="mname" class="form-label">Middle Name</label>
												<input type="text" name="mname" class="form-control"  autocomplete="off" placeholder="Middle Name">
											</div>
											<div class="col-lg-4">
												<label for="lname" class="form-label">Last Name</label>
												<input type="text" name="lname" class="form-control" required autocomplete="off" placeholder="Last Name">
											</div>
										</div>

										<label for="address" class="form-label">Address</label>
									
										<div class="row mb-3">
											<div class="col-lg-12">
												<input type="text" name="compadd" class="form-control" required autocomplete="off" placeholder="Street, House No., Subdivision">
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-lg-4">
												<label for="prov" class="form-label">Province</label>
												<input type="text" name="prov" class="form-control" required autocomplete="off" placeholder="Province">
											</div>
											<div class="col-lg-4">
												<label for="city" class="form-label">City</label>
												<input type="text" name="city" class="form-control" required autocomplete="off" placeholder="City">
											</div>
											<div class="col-lg-4">
												<label for="brgy" class="form-label">Barangay</label>
												<input type="text" name="brgy" class="form-control" required autocomplete="off" placeholder="Barangay">
											</div>
										</div>
										<div class="row mb-3">
											<div class="col-lg-4">
												<label for="bday" class="form-label">Birth</label>
												<input type="date" class="form-control hidden" name='dt' required autocomplete="off" onchange="computeAge(this.value);">
											</div>
											<div class="col-lg-4">
												<label for="age" class="form-label">Age</label>
												<input type="text" class="form-control hidden" id='age' name='age' readonly required autocomplete="off" placeholder="Age"><br>
											</div>
											<div class="col-lg-4">
												<label for="gender" class="form-label">Gender</label>
												<select name='gender' class="form-control hidden">
													<option>Gender</option>
													<option value='Male'>Male</option>
													<option value='Female'>Female</option>
												</select>
											</div>
										</div>
										<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-user btn-block">
									</form>

								</div>
								<!-- </div> -->
							</div>
						</div>
					</div>

				</div>
				<!-- /.container-fluid -->

			</div>
			<script>
				function computeAge(bday) {
					var dt = new Date();
					var yr = dt.getFullYear();
					var my_bday = new Date(bday);
					var my_yr = my_bday.getFullYear();
					document.getElementById('age').value = ((yr / 1) - (my_yr / 1));
				}
			</script>

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