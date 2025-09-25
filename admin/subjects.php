<!DOCTYPE html>
<html lang="en">

<head>
 <?php
	session_start();
    include("includes/header.php"); 
	include("../config/database.php");
	$sql="SELECT * FROM subjects ORDER BY sub_code ASC";
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
                        <h1 class="h3 mb-0 text-gray-800">Subjects</h1>
                        <a href="curriculum-add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#subjects"><i
                                class="fas fa-plus fa-sm text-white-50"></i> New Subject</a>
                    </div>

                    <!-- Content Row -->
                   
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Subject List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Units</th>
                                            <th>With Lab</th>
                                            <th>Action</th>
                                           
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
									<?php
										foreach($results as $row){
									?>
                                        <tr>
                                            <td><?=$row['sub_code']; ?></td>
                                            <td><?=$row['sub_name']; ?></td>
                                            <td><?=$row['units']; ?></td>
                                            <td><?=$row['withLab']; ?></td>
                                            <td>
											<!--
												<a href="curr-view.php" class="btn btn-primary">
													 <i class="fas fa-eye fa-sm text-white-50"></i>
												</a>
												-->
												<button type="button" data-code="<?=$row['sub_code']; ?>" onclick="viewSubject(<?=$row['sub_id']; ?>)" class="btn btn-info" data-toggle="modal" data-target="#editSubject">
													 <i class="fas fa-edit fa-sm text-white-50"></i>
												</button>
												<a href="curr-archive.php" class="btn btn-primary">
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
		<div class="modal fade" id="subjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Subject</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
				<form id="addSub" class="user">
                <div class="modal-body">
						<div class="alert alert-warning d-none" id="msg">
						
						</div>
						<div class="form-group">
							<input type="text" name="subcode" class="form-control" placeholder="Subject Code">
						</div>
						<div class="form-group">
							<input type="text" name="description" class="form-control" placeholder="Description">
						</div>
						<div class="row mb-3">
							<div class="col-lg-6">
								<input type = "text" name="units" class="form-control" placeholder="Units">
								
							</div>
							<div class="col-lg-6">
								<select name="withlab" class="form-control">
									<option value="" selected>With Lab?</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
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
	<!--End of Subject modal -->
	<!-- Edit Subject modal -->
	<div class="modal fade" id="editSubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Subject</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
				<form id="viewSub" class="user">
                <div class="modal-body">
						<div class="alert alert-warning d-none" id="msgViewSub">
						
						</div>
						<div class="form-group">
							<input type="hidden" name="id" id="id">
							<input type="text" name="subcode" id="code" class="form-control" placeholder="Subject Code">
						</div>
						<div class="form-group">
							<input type="text" name="description" id="des" class="form-control" placeholder="Description">
						</div>
						<div class="row mb-3">
							<div class="col-lg-6">
								<input type = "text" name="units" id="units" class="form-control" placeholder="Units">
								
							</div>
							<div class="col-lg-6">
								<select name="withLab" class="form-control">
									<option value="" selected>With Lab?</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</div>
						</div>

					
				</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onClick="updateForm()">Update</button>
                </div>
				</form>
            </div>
        </div>
	</div>
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
				url: "ajax/subject_add.php",
				data: jQuery("#addSub").serialize(),
				success: function(response){
					console.log(response);
					if(response==422){
						$('#msg').removeClass('d-none');
						$('#msg').text("All fields are mandatory");
					}else{
						$('#msg').removeClass('d-none');
						$('#msg').text("Successfully added!");
						$('#addSub')[0].reset();
					}
					
					}
				});
			}
			function viewSubject(id){
				$.ajax({
					type: "GET",
					url: "ajax/viewsubject.php?id="+id,
					success: function(response){
						var res = jQuery.parseJSON(response);
						$("#id").val(id);
						$("#code").val(res.sub_code);
						$("#des").val(res.sub_name);
						$("#units").val(res.units);
						$("#withLab").val(res.withLab);
					}
				});
				
			}
			function updateForm(){
				$.ajax({
					type: "POST",
					url:"ajax/editsubject.php",
					data: jQuery("#viewSub").serialize(),
					success: function(data){
						console.log(data);
						if(data==200){
							$("#msgViewSub").removeClass("d-none");
							$("#msgViewSub").text("Update successfull!");
						}else{
							$("#msgViewSub").removeClass("d-none");
							$("#msgViewSub").text("All fields are required!");
						}
					}
					
				});
			}
	</script>

</body>

</html>