<?php
session_start();

unset($_SESSION['USERNAME']);
unset($_SESSION['EMAIL']);
unset($_SESSION['PHOTO']);
setcookie("TOKEN", "", time() - 600, "/");

session_destroy();
header("Location: ../index.php");
?>