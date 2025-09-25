<!DOCTYPE html>
<html lang="en">

<head>
 <?php
	session_start();
    include("includes/header.php"); 
	include("../config/database.php");
	$sql="SELECT * FROM programs";
	$results = $database->view($sql);
	//print_r($results);
	//fetch Programs
	
	$sql = "SELECT * FROM programs ORDER BY p_code ASC";
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Programs</h1>
                        <a href="curriculum-add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#program"><i
                                class="fas fa-plus fa-sm text-white-50"></i> New Program</a>
                    </div>

                    <!-- Content Row -->
                   
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Subject List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Year</th>
                                            <th>Action</th>
                                           
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
									<?php
										foreach($results as $row){
									?>
                                        <tr>
                                            <td><?=$row['p_code']; ?></td>
                                            <td><?=$row['p_des']; ?></td>
                                            <td><?=$row['p_year']; ?></td>
                                            <td>
											<!--
												<a href="curr-view.php" class="btn btn-primary">
													 <i class="fas fa-eye fa-sm text-white-50"></i>
												</a>
												-->
												<a href="program_edit.php?id=<?=$row['program_id']; ?>" class="btn btn-primary">
													 <i class="fas fa-edit fa-sm text-white-50"></i>
												</a>
												<a href="curr-edit.php" class="btn btn-primary">
													<i class="fas fa-archive fa-sm text-white-50"></i>
												</a>
											</td>
                                            
                                        </tr>
                                     
										<?php  } ?>
                                        
                                        
                                       
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
         
                    

                    <!-- Content Row -->


                    <!-- Content Row -->
             

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
	<!-- Add Subject modal -->
		<div class="modal fade" id="program" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Program</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
				<form id="addSub" class="user">
                <div class="modal-body">
						<div class="alert alert-warning d-none">
						</div>
						<div class="form-group">
							<input type="text" name="pcode" class="form-control" placeholder="Code">
						</div>
						<div class="form-group">
							<input type="text" name="pdes" class="form-control" placeholder="Description">
						</div>
						<div class="form-group">
							<input type="number" name="pyear" class="form-control" placeholder="Number of Years">
						</div>
					

					
				</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onClick="sendForm()">Submit</button>
                </div>
				</form>
            </div>
        </div>
 </div>
	<!--End of Curriculum modal -->
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
	
	<script type="text/javascript">
		function sendForm(){
			$.ajax({
				type: "POST",
				url: "ajax/program_add.php",
				data: jQuery("#addSub").serialize(),
				success: function(data){
					console.log(data);
					}
				});
			}
	</script>

</body>

</html>