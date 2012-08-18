<?php

// crontab -e: */1 * * * * wget -q -O /dev/null http://localhost/crond.php

	require_once('home.php');
	
	//revisa atributo 'rendido', y lo actualiza de ser necesario
	$sql = "UPDATE tExamenPrograma SET rendido = 'S' ";
	$sql .= "WHERE TIME_TO_SEC((TIMEDIFF(DATE_ADD(fecha, INTERVAL duracion SECOND), CURRENT_TIMESTAMP))) <= 0;";
	$bcdb->query($sql);

?> 