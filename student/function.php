<?php
session_start();
include("../config/database.php");
$status = $_GET['status'];
if($status==1){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $level = $_POST['acc_level'];
    $password = $_POST['password'];

    $sql ="INSERT INTO accounts VALUES(null, '$fname','$lname','$email','$password','$level',0,NOW())";
    $database->add($sql,'users.php');
    echo "Hello ". $fname;
}else if($status==2){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $id=$_POST['id'];
    $sql = "UPDATE accounts SET fname='$fname', lname='$lname', email='$email' WHERE id=".$id;
    $database->update($sql,"users.php");
}else if($status==3){
	$year_level = $_POST['year_level'];
	$semester = $_POST['semester'];
	$course_id = $_POST['course_id'];
	$pre_req = $_POST['pre_req'];
	 //echo "Year Level: ".$year_level;
	 $sql = "INSERT INTO curriculum_content(curr_id, cc_year, cc_sem, cc_course_id)
			VALUES(".$_SESSION['curr_id'].", '$year_level','$semester','$course_id')";
	$results = $database->add($sql,"curriculum-edit.php");
}else if($status==4){
	//delete curriculum content
	$sql = "DELETE FROM curriculum_content WHERE cc_id=".$_GET['id'];
	$results = $database->delete($sql,"curriculum-create.php?id=".$_SESSION['curr_id']);
}