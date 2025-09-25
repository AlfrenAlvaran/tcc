<?php
	session_start();
	include("../../config/database.php");
	
	$status = $_GET['status'];
	if($status==1){
	$stid = $_POST['stid'];
	$cname = $_POST['cname'];
	$course = $_POST['course'];
	$cur = $_POST['cur'];
	
	$sql = "INSERT INTO enrolled_students VALUES(null,'$stid','$cname','$course','$cur')";
	$database->enroll($sql,"enrolled_students.php");
	}else{
		echo "failed";
	}
?>
