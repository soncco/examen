<?php
/**
 * Modifica los valores de una máquina
 */
	require_once('home.php');
	require_once('redirect.php');

	// Campo
	$field = $_POST['id'];
	$field = explode("-", $field);

	// ID
	$id = $field[1];
	$field = $field[0];

	$value = $_POST['value'];

	// Actualizamos
	$bcdb->query("UPDATE $bcdb->maquina SET $field = '$value' WHERE id = '$id'");

  // Escribimos
  if(!isset($_POST['op'])) :
    print $bcdb->get_var("SELECT $field FROM $bcdb->maquina WHERE id = '$id'");
  else :
    print $bcdb->get_var("SELECT nombres FROM $bcdb->operador WHERE id = '$value'");
  endif;  
?>