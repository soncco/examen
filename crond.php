<?php

// Este archivo se ejecuta con crond, cada minuto, revisa y actualiza el parametro 'rendido' de la tabla tExamenPrograma
// esto se coloca en crontab -e: */1 * * * * wget -O /dev/null http://localhost/crond.php
// con eso se ejecuta el script cada minuto, en localhost ponga el dominio que haga accesible el script

	require_once('home.php');
	
	//revisa atributo 'rendido', y lo actualiza de ser necesario
	$sql = "UPDATE tExamenPrograma SET rendido = 'S' ";
	$sql .= "WHERE TIME_TO_SEC((TIMEDIFF(DATE_ADD(fecha, INTERVAL duracion SECOND), CURRENT_TIMESTAMP))) <= 0;";
	$bcdb->query($sql);

?> 