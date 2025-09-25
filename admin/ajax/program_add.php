<?php
	include("../../config/database.php");
	
	$pcode = $_POST['pcode'];
	$pdes = $_POST['pdes'];
	$pyear = $_POST['pyear'];
	
		
	$sql = "INSERT INTO programs(p_code, p_des, p_year) 
				VALUES('$pcode','$pdes','$pyear')";
	$results = $database->save($sql);
	
?>