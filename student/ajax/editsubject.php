<?php
	include("../../config/database.php");
	
	$code = $_POST['subcode'];
	$des = $_POST['description'];
	$units= $_POST['units'];
	if($code==NULL || $des==NULL || $units==NULL){
		echo 422;
	}else{
		$sql = "UPDATE subjects SET sub_code='$code', sub_name='$des',units='$units' 
			WHERE sub_id=".$_POST['id'];
		$results=$database->update($sql,"");
		//echo 200;
	}
	
?>