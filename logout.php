<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
unset($_SESSION['Email']);
unset($_SESSION['Password']);
unset($_SESSION['UserID']);
session_destroy();

header("Location: index.php");
exit;
?>