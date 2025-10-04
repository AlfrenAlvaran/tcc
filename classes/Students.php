<?php
class Students {
    private $conn;
    public function __construct() {
        $this->conn=PDOConnection::getInstance()->getConnection();
    }


    // public function insert_students(int ) {

    // }
}