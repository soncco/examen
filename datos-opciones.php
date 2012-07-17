<?php
/**
 * Modifica los valores de opciones
 */
	require_once('home.php');
	require_once('redirect.php');
	
	// Campo
	$field = $_POST['id'];
	
	$value = $_POST['value'];
	
	// Actualizamos
	$bcdb->query("UPDATE $bcdb->opciones SET descripcion = '$value' WHERE nombre = '$field'");
	
	// Escribimos
	print $bcdb->get_var("SELECT descripcion FROM $bcdb->opciones WHERE nombre = '$field'");
?>