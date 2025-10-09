<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

$session = $_SESSION['email'];
$student_id = substr($session, -4);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Portal</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="icon" href="../img/tcc_logo.jpg" type="image/ico">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>

<body class="d-flex flex-column min-vh-100">
    <header class="bg-white text-center p-2 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <a class="d-flex align-items-center nav-link" href="index.php">
                <img src="../img/tcc_logo.jpg" alt="Logo" style="height: 50px; margin-right: 10px;">
                <h1 class="h4 mb-0 text-decoration-none hover-none" style="text-decoration: none; ">Teacher Portal</h1>
            </a>

            <div class="d-block">
                <span class="text-muted mr-4"><?php echo $session; ?></span>
            </div>
        </div>
    </header>
