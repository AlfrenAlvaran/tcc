<?php
require_once __DIR__ . '/../../classes/Curriculum.php';
$title = $_POST['title'];

$date = date('Y-m-d');
$time = date('h:i:sa');
$by = "admin";

$curr = new Curriculum();
$curr->createAnnouncement($title, $date, $time, $by);
header("Location: ../announcement.php");
exit();
