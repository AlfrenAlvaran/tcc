<!DOCTYPE html>
<html lang="en">

<head>
<?php 
	session_start();
    include("../config/database.php");
	include("includes/header.php"); 
    $sql ="SELECT * FROM accounts as a, account_level as l
            WHERE a.acc_level=l.level_id";
    $results = $database->view($sql);
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
                        <h1 class="h3 mb-0 text-gray-800">Users</h1>
						
                        <a href="users-add.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-user fa-sm text-white-50"></i> Add User</a>
                    </div>

                    <!-- Content Row -->
                   
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">User List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Account Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                        <?php
                                        foreach($results as $row){
                                        ?>
                                        <tr>
                                            <td><?=$row['lname'].", ".$row['fname']; ?></td>
                                            <td><?=$row['email']; ?></td>
                                            <td><?=$row['password']; ?></td>
                                            <td><?=$row['level_name']; ?></td>
                                            <td>
                                                <a href="users-edit.php?id=<?=$row['id']; ?>">Edit</a>
                                                <a href="">Delete</a>
                                            </td>
                                        </tr>
                                     <?php } ?>
                                       
                                        
                                        
                                       
                                       
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