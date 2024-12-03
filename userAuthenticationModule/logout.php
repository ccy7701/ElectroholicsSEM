<?php
    session_start();
    if (isset($_SESSION["accountID"])) {
        unset($_SESSION["accountID"]);
        unset($_SESSION["accountEmail"]);
        unset($_SESSION["username"]);
        unset($_SESSION["accountRole"]);
        header("location: ../index.php");
    }
?>