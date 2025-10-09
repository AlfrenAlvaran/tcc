<?php
class Logout {
    public static function perform() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /tcc/index.php");
        exit();
    }
}