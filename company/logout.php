<?php 
ob_start();
session_start();
include ('inc/config.php'); 
unset($_SESSION['company']);
header("location: login.php"); 
?>