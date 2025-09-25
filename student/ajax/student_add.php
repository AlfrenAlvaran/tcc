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
	
	$sql = "INSERT INTO students VALUES(null, '$sy','$stid','$lname','$fname','$mname','$pid')";
	$database->submit($sql,"students.php");
	
	$sql1 = "INSERT INTO accounts VALUES(null, '$fname','$lname','$sy$stid','$lname','3','0',NOW())";
	$database->submit($sql1,"students.php");
	
	}else{
		echo "failed";
	}
?>
