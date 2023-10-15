<?php
session_start();
$code = $_GET['code'];
include('../includes/microsoftGraph.php'); 

$accessToken = getToken($code);
$username = getUsername($accessToken);
$email = getEmail($accessToken);
$photo = getPhoto($accessToken);

setcookie("TOKEN", $accessToken, time() + 600, "/", "", false, true);
$_SESSION['USERNAME'] = $username;
$_SESSION['EMAIL'] = $email;
$_SESSION['PHOTO'] = $photo;

header("Location: dashboard.php");
exit;