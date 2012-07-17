<?php
/**
 * Opciones del Sistema
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$sql = "INSERT INTO  `caja`.`alquileres` (
`ID` ,
`id_maquina` ,
`id_recibo` ,
`horas`
)
VALUES (
NULL ,  '11',  '1912',  '240'
);";

	$bcdb->query($sql);
	
	
	
?>