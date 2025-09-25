<?php
	session_start();
	include("../../config/database.php");
	
	$status = $_GET['status'];
	if($status==1){
	$sy = $_POST['sy'];
	$stid = $_POST['stid'];
	$lname = $_POST['lname'];
	$fname = $_POST['fname'];
	$mname = $_POST['mname'];
	$pid = $_POST['pid'];
	$compadd = $_POST['compadd'];
	$prov = $_POST['prov'];
	$city = $_POST['city'];
	$brgy = $_POST['brgy'];
	$dt = $_POST['dt'];
	$age = $_POST['age'];
	$gender = $_POST['gender'];
	
	$sql = "INSERT INTO students VALUES(null, '$sy','$stid','$lname','$fname','$mname','$pid','$compadd','$prov','$city','$brgy','$dt','$age','$gender')";
	$database->submit($sql,"students.php");
	
	$sql1 = "INSERT INTO accounts VALUES(null, '$fname','$lname','$sy$stid','$dt','3','0',NOW())";
	$database->submit($sql1,"students.php");
	
	}else{
		echo "failed";
	}
?>
