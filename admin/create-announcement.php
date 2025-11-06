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

			
				<?php
				include("includes/top-bar.php");
				?>
			

				<!-- Begin Page Content -->



				<div class="container-fluid">
					<div class="card shadow mb-4">
						<!-- Header -->
						<div class="card-header">
							<h6 class="m-0 font-weight-bold text-primary">Create Announcements</h6>
						</div>
						<!-- Header -->
						<!-- Body -->
						<div class="card-body">
							<form action="ajax/save-announcement.php"  method="POST">
								<div class="mb-3">
									<label for="title" class="form-label">Title</label>
									<input type="text" class="form-control" id="title" name="title" required>
								</div>
								<div class="mb-3">
									<label for="content" class="form-label">Content</label>
									<textarea class="form-control" id="content" name="content" rows="4" required></textarea>
								</div>
								<button type="submit" name="create-announcement" class="btn btn-primary">Submit</button>
							</form>
						</div>
						<!-- Body -->
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