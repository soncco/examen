<?php
/**
 * Si la sesión existe envía al inicio
 */
require_once('init.php');

if(isset($_SESSION['loginuser'])) {
	$session_active = true;
	if(basename($_SERVER['PHP_SELF'])=='login.php')
		header("Location: " . BASE_URL . "index.php");
}
?>