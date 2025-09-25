<?php
	session_start();
	include("../config/database.php");
	include("includes/header.php"); 
	$sql ="SELECT * FROM account_level";
	$results = $database->view($sql);
	//print_r($results);
	
	$sql="SELECT * FROM accounts";
    $acc = $database->count($sql);
	//echo "Account: ".($acc);

    $sql1="SELECT * FROM accounts WHERE id=".$_GET['id'];
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Users</h1>
						
                        <a href="users-add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-user fa-sm text-white-50"></i> Add User</a>
                    </div>

                    <!-- Content Row -->
                   
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit User</h6>
                        </div>
                        <div class="card-body">
                                <div class="row">
                    <div class="col-lg-0 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form action="function.php?status=2" method="POST" class="user" >
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input type="hidden" name="id" value="<?=$row['id']; ?>">
                                        <input type="text" name="fname" value="<?=$row['fname']; ?>" class="form-control" exampleFirstName"
                                            placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="lname" value="<?=$row['lname']; ?>" class="form-control" id="exampleLastName"
                                            placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="email" name="email" value="<?=$row['email']; ?>" class="form-control" id="exampleFirstName"
                                            placeholder="Email">
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="acc_level" id="exampleAccLevel">
											<option value="" selected>User Level</option>
                                            <?php
                                                foreach($results as $row){
                                                     ?>
                                                        <option value="<?=$row['level_id']; ?>"><?=$row['level_name']; ?></option>
                                                     <?php
                                                }
											
											?>
										</select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control"
                                            id="exampleInputPassword" name="password"  value="" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control"
                                            id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <input type="submit" name="submit" value="Edit Account" class="btn btn-primary btn-user btn-block">
                                                     
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