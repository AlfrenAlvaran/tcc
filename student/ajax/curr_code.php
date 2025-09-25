<?php
	include("../../config/database.php");
	
	$cyear = $_POST['cyear'];
	$pid = $_POST['pid'];
		
	$sql = "INSERT INTO curriculum VALUES(null, '$cyear','$pid',NOW())";
	$results = $database->save($sql);
	
?>