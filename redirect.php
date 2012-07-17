<?php
/**
 * Revisa si es que la sesión es activa, si no es así, envía a la página de login
 */
 
// Redirección al login
if(!isset($session_active)) {
	header("Location: ". BASE_URL . "login.php?r=" . $_SERVER['PHP_SELF']);
	exit();
}
?>