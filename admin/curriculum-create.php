<!DOCTYPE html>
<html lang="en">

<head>
	<?php
	session_start();

	if (isset($_GET['id'])) {
		$_SESSION['curr_id'] = (int) $_GET['id'];
	}


	include("../config/database.php");
	include("includes/header.php");
	$sql = "SELECT * FROM curriculum as c, programs as p WHERE c.cur_program_id=p.program_id AND cur_id=" . $_GET['id'];
	$row = $database->getdata($sql);
	//print_r($results);
	$sql = "SELECT * FROM subjects ORDER BY sub_code ASC";
	$course = $database->view($sql);
	//print_r($course);

	//Year=1, Sem=1
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=1 AND cc.cc_sem=1 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content11 = $database->view($sql);

	//Year=1, Sem=2
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=1 AND cc.cc_sem=2 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content12 = $database->view($sql);

	//Year=2, Sem=1
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=2 AND cc.cc_sem=1 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content21 = $database->view($sql);

	//Year=2, Sem=2
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su
	         WHERE cc.cc_year=2 AND cc.cc_sem=2 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content22 = $database->view($sql);

	//Year=3, Sem=1
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=3 AND cc.cc_sem=1 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content31 = $database->view($sql);

	//Year=3, Sem=2
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=3 AND cc.cc_sem=2 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content32 = $database->view($sql);

	//Year=3, Sem=3
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=3 AND cc.cc_sem=3 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content33 = $database->view($sql);

	//Year=4, Sem=1
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su 
	         WHERE cc.cc_year=4 AND cc.cc_sem=1 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content41 = $database->view($sql);

	//Year=4, Sem=2
	$sql = "SELECT * FROM curriculum_content as cc, subjects as su
	         WHERE cc.cc_year=4 AND cc.cc_sem=2 AND cc.cc_course_id=su.sub_id AND cc.curr_id=" . $_GET['id'];
	$cur_content42 = $database->view($sql);

	error_reporting(0);
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
						<h1 class="h3 mb-0 text-gray-800">Curriculum</h1>
						<a href="#" class="d-none d-sm-inline-block btn btn-md btn-primary shadow-sm"><i
								class="fas fa-print fa-md text-white-50"></i> PRINT </a>
					</div>
					<div class="col-lg-12">
						<table class="table table-sm" style="font-size: 20px;font-weight:bold; color: black">
							<tr>
								<td>Program</td>
								<td><?= $row['p_des']; ?></td>
							<tr>
								<td>Curriculum</td>
								<td><?= $row['cur_year']; ?> - Curriculum</td>
								</td>
						</table>
					</div>
					<br>
					<div class="col-lg-12">
						<table class="table table-sm" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">First Year - First Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content11 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- First Year, 2nd Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">First Year - Second Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content12 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Second Year, First Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Second Year - First Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content21 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Second Year, 2nd Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Second Year - Second Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content22 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Third Year, First Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Third Year - First Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content31 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Third Year, 2nd Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Third Year - Second Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content32 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Third Year, Summer -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Third Year - Summer</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content33 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Fourth Year, First Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Fourth Year - First Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content41 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- Fourth Year, 2nd Semester -->
						<table class="table table-sm my-3" style="color: black !important">
							<thead>
								<td colspan=5 style="font-size: 18px;font-weight:bold;  border-top: 3px solid black; border-bottom: 2px solid black">Fourth Year - Second Semester</td>

							</thead>
							<thead style="font-weight: bold; border-top: 2px solid black; ">
								<td width="15%">Course Code</td>
								<td width="50%">Description</td>
								<td width="10%">Units</td>
								<td width="15%">Pre-Requesite</td>
								<td align=center width="10%">Action</td>
							</thead>
							<tbody>
								<?php foreach ($cur_content42 as $row) { ?>
									<tr>
										<td width="15%"><?= $row['sub_code']; ?></td>
										<td width="50%"><?= $row['sub_name']; ?></td>
										<td width="10%"><?= $row['units']; ?></td>
										<td width="15%"></td>
										<td width="10%" align=center>
											<a href="function.php?status=4&&id=<?= $row['cc_id']; ?>" class="btn btn-danger btn-sm btn-circle">
												<i class="fas fa-trash"></i>
											</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>

						<fieldset>
							<legend style="color: black">Add Curriculum Content</legend>
							<form class="user" action="function.php?status=3" method="POST">
								<table class="table table-sm">
									<tr style="font-size: 16px;font-weight:bold; color: black;">

										<td>
											<select class="form-control" name="year_level">
												<option selected>Year Level</option>
												<option>1</option>
												<option>2</option>
												<option>3</option>
												<option>4</option>
											</select>
										<td>
										<td>
											<select class="form-control" name="semester">
												<option selected>Select Semester</option>
												<option value='1'>First Semester</option>
												<option value='2'>Second Semester</option>
												<option value='3'>Summer</option>

											</select>
										<td>

										<td>
											<select class="form-control" name="course_id">
												<option selected>Select subjects</option>
												<?php
												foreach ($course as $row) {
												?>
													<option value="<?= $row['sub_id']; ?>"><?= $row['sub_code']; ?> - <?= $row['sub_name']; ?></option>
												<?php } ?>

											</select>
										<td>

										<td>
											<select class="form-control" name="pre_req">
												<option selected>Pre-Requisite</option>
												<option>None</option>

											</select>
										<td>
									</tr>
									<tr>
										<td colspan=11><button type="submit" class="btn btn-primary btn-md btn-block">Save</button>
									</tr>
								</table>
							</form>
						</fieldset>
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