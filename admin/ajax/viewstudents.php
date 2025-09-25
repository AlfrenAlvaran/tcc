<?php
	include("../../config/database.php");
	
	$sql = "SELECT * FROM students WHERE sub_id=".$_GET['id'];
	$results=$database->getdata($sql);
	echo json_encode($results);
	
?>