<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>TCC Curriculum - Home</title>
	<link rel="icon" href="img/tcc_logo.jpg" type="image/ico">
	<!-- Custom fonts for this template-->
	<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-success">

	<div class="container ">
		<?php
		if (!isset($_POST['submit'])) {
			$_SESSION['att'] = 0;
		}
		if (isset($_POST['submit'])) {
			$email = $_POST['email'];
			$pass = $_POST['pass'];
			$conn = mysqli_connect("localhost", "root", "", "tcc2024");
			$query = "SELECT * FROM accounts WHERE email='$email' AND password='$pass'";
			$result = mysqli_query($conn, $query) or die("Error in query!" . mysqli_error($conn));
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				$_SESSION['email'] = $row['email'];
				$_SESSION['id'] = $row['id'];
				if ($row['status'] == 1) {
					echo "<center><b align='center' style='font-size:285%; -webkit-filter: drop-shadow(5px 5px 5px #222); filter: drop-shadow(1px 2px 4px #222);'>Account not verified!</b>";
				} else {
					switch (strtoupper($row['acc_level'])) {
						case '1':
							header("refresh:0;url=admin/index.php");
							break;
						case '7':
							header("refresh:0;url=teacher/index.php");
							break;
						case '3':
							header("refresh:0;url=student/index.php");
							break;
						default:
							echo "<br>Invalid Position!";
					}
				}
			} else {
				$_SESSION['att']++;
				echo "<center><b align='center' style='font-size:285%; -webkit-filter: drop-shadow(5px 5px 5px #222); filter: drop-shadow(2px 5px 7px #222);;'>Log in Failed </b>";
				echo "<b align='center' style='font-size:285%; -webkit-filter: drop-shadow(5px 5px 5px #222); filter: drop-shadow(2px 5px 7px #222);'>" . $_SESSION['att'] . "</b></center>";
				if ($_SESSION['att'] >= 4) {
					header("refresh:0;url=block.php");
				}
			}
		}
		?>
		<!-- Outer Row -->
		<div class="row justify-content-center">

			<div class="col-xl-10 col-lg-12 col-md-9">

				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
							<div class="col-lg-6 d-none d-lg-block bg-login-image">
								<img src="img/logo.jpg" style="position: absolute; top: 20%; left:30%; width:50%; height: 50%;"></img>
							</div>
							<div class="col-lg-6">

								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
									</div>
									<form id="login" class="user" action="<?php $_SERVER['PHP_SELF']; ?>" method=POST>
										<div class="form-group">
											<input type="text" name="email" class="form-control form-control-user" data-stoppropagation="true"
												id="exampleInputEmail" aria-describedby="emailHelp" required="required"
												placeholder="Enter Email Address..." autocomplete="off">
											for student please type your student number
										</div>
										<div class="form-group">
											<input type="password" name="pass" class="form-control form-control-user" data-stoppropagation="true" id="password"
												title="Password is required" placeholder="Enter Password" required="required" autocomplete="off">
											for student follow this format(yyyy-mm-dd)<br>
											<input type="checkbox" onclick="togglePassword()"> Show Password
											<script>
												function togglePassword() {
													const pass = document.getElementById("password");
													if (pass.type === "password") {
														pass.type = "text";
													} else {
														pass.type = "password";
													}
												}
											</script>
										</div>

										<div class="alert alert-warning d-none" id="msgLogin">
										</div>
										<button type="submit" name="submit" class="btn btn-primary btn-user btn-block" value="Log In">
											Login
										</button>
									</form>
									<div class="text-center">
										<a class="small" href="forgot-password.html">Forgot Password?</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>

	</div>

	<!-- Bootstrap core JavaScript-->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="js/sb-admin-2.min.js"></script>
</body>

</html>