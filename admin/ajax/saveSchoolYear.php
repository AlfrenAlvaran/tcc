<?php
require_once __DIR__ . '/../../classes/Settings.php';

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] === "POST") {
    $sy =$_POST['sy'];
    $added_at = date('Y-m-d');
    $settings = new Settings();
    $data = [
        'sy' => $sy,
        'created_at' => $added_at
    ];
    $result = $settings->saveSchoolYear($data);
    if($result) {
        echo json_encode(['status' => 'success', 'message' => 'School Year saved successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save School Year.']);
    }

    exit;
}