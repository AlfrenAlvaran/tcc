<!DOCTYPE html>
<html lang="en">

<head>
 <?php
	session_start();
    include("includes/header.php"); 
	include("../config/database.php");
	$sql="SELECT * FROM curriculum as c, programs as p WHERE c.cur_program_id=p.program_id ORDER BY cur_year DESC";
	$results = $database->view($sql);
	//print_r($results);
	//fetch Programs
	
	$sql = "SELECT * FROM programs ORDER BY p_des ASC";
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
                        <h1 class="h3 mb-0 text-gray-800">Curriculum</h1>
                        <a href="curriculum-add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#curr"><i
                                class="fas fa-plus fa-sm text-white-50"></i> New Curriculum</a>
                    </div>

                    <!-- Content Row -->
                   
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Curriculum List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Curriculum Year</th>
                                            <th>Program</th>
                                            <th>Date Created</th>
                                            <th>Action</th>
                                           
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
									<?php
										foreach($results as $row){
									?>
                                        <tr>
                                            <td><?=$row['cur_year']; ?></td>
                                            <td><?=$row['p_code']; ?></td>
                                            <td><?=$row['date_created']; ?></td>
                                            <td>
												<a href="curriculum-create.php?id=<?=$row['cur_id']; ?>" class="btn btn-primary">
													 <i class="fas fa-edit fa-sm text-white-50"></i>
												</a>
												<a href="curriculum-create.php?id=<?=$row['cur_id']; ?>" class="btn btn-primary">
													 <i class="fas fa-eye fa-sm text-white-50"></i>
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
	<!-- Add Curriculum modal -->
		<div class="modal fade" id="curr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Curriculum</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
				<form id="addCurr" class="user">
                <div class="modal-body">
					<div class="alert alert-warning d-none">
					</div>
					<div class="row">
						<div class="col-lg-6">
							<select class="form-control" name="cyear">
								<option value="" selected>Choose Curriculum Year</option>
								<?php
									$cyear=date("Y");	
									for($x= ($cyear +1); $x>=($cyear-3); $x--){
								?>
										<option value="<?=$x; ?>"><?=$x; ?></option>
									<?php } ?>
								
							</select>
						</div>
						<div class="col-lg-6">
							<select class="form-control" name="pid">
								<option value=""  selected>Choose Programs</option>
								<?php
									foreach($programs as $p){
								?>
										<option value="<?=$p['program_id']; ?>"><?=$p['p_des']; ?></option>
									<?php } ?>
								
							</select>
						</div>
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
				url: "ajax/curr_code.php",
				data: jQuery("#addCurr").serialize(),
				success: function(data){
					console.log(data);
					}
				});
			}
	</script>

</body>

</html>