<?php
session_start();

if (isset($_SESSION['isLogged'])) {
    //destroying the session and directing to login.php
    session_unset();
    session_destroy();
}
header("Location: index.php");
die;
?>