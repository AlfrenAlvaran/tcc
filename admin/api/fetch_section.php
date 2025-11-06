<?php
require_once __DIR__ . '/../../config/PDOConnection.php';

try {
    $conn = PDOConnection::getInstance()->getConnection();
    $stmt = $conn->query("SELECT id, section_name, year_level FROM section_tb ORDER BY id DESC");
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $sections]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
