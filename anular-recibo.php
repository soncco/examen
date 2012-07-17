<?php
/**
 * Anula un recibos
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$ID = $_POST['ID'];
	
	print anular_recibo($ID);
	
?>