<?php
session_start();
// if (!isset($_POST['login'])) {
//     $_SESSION['attempts'] = 3;
// }
require_once "./config/database.php";

$email = $_POST['email'];
$pass = $_POST['pass'];
if ($email == NULL || $pass == NULL) {
    echo 422;
} else {
    $sql = "SELECT * FROM accounts WHERE email='$email' AND password='$pass'";
    $results = $database->login($sql);
    if ($results == false) {
        echo 500;
    } else {
        $_SESSION['user_id'] = $results['id'];
        if ($results['acc_level'] == 1) {
            $_SESSION['user_name'] = $results['fname'];
            echo 200;
        } else {
            echo 201;
        }
    }
}

?>
<!DOCTYPE html>
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
                                    <form id="login" class="user">
                                        <div class="form-group">
                                            <input type="text" name="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="pass" class="form-control form-control-user" id="password" placeholder="Password">
                                            <input type="checkbox" onclick="togglePassword()"> Show Password
                                            <script>
                                                function togglePassword() {
                                                    var pass = document.getElementById("password");
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
                                        <button type="button" onclick="sendForm()" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>



                                    <hr>
                                    <!--
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
										-->

                                    <!-- <hr> -->
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <!--
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
									-->
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
<script type="text/javascript">
    function sendForm() {
        $.ajax({
            type: "POST",
            url: "functions.php",
            data: jQuery("#login").serialize(),
            success: function(response) {
                console.log(response);
                if (response == 422) {
                    $('#msgLogin').removeClass('d-none');
                    $('#msgLogin').text("All fields are mandatory");
                } else if (response == 500) {
                    $('#msgLogin').removeClass('d-none');
                    $('#msgLogin').text("Invalid account!");
                    $('#login')[0].reset();
                } else if (response == 201) {
                    $('#msgLogin').removeClass('d-none');
                    $('#msgLogin').text("Staff account not yet configured!");
                } else {
                    $(location).prop('href', 'admin/index.php');
                }

            }
        });
    }
</script>