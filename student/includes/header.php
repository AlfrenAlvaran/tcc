<?php

// echo __DIR__ . "/../../classes/Students.php";

require_once __DIR__ . "/../../classes/Students.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

$session = $_SESSION['email'];
$student_id = substr($session, -4);
require_once __DIR__ . "/../../classes/Encode.php";
$encode = new Encode();
// echo $_SESSION['id'];
$class = $encode->getStudentAssignedSubjects($_SESSION['id']);
// print_r($class);

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
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="bg-white text-center p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Left: Logo + Title -->
            <a class="d-flex align-items-center nav-link" href="index.php">
                <img src="../img/tcc_logo.jpg" alt="Logo" style="height: 50px; margin-right: 10px;">
                <h1 class="h4 mb-0 text-decoration-none hover-none" style="text-decoration: none; ">e-Portal</h1>
            </a>

            <!-- Center: Navigation -->
            <nav>
                <ul class="nav justify-content-center mb-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="schedules.php">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Grades</a>
                    </li>
                </ul>
            </nav>

            <!-- Right: Logout Button -->
            <div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </header>
    <main class=""></main>