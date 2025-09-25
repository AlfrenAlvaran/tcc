<?php
	include("../../config/database.php");
	
	$code = $_POST['subcode'];
	$des = $_POST['description'];
	$units = $_POST['units'];
	$withlab = $_POST['withlab'];
	if($code==NULL || $des==NULL || $units==NULL){
		echo 422;
	}else{
		$sql = "INSERT INTO subjects(sub_code, sub_name, units, withLab) 
				VALUES('$code','$des','$units','$withlab')";
		$database->save($sql);
		echo 200;
	}
	
?>