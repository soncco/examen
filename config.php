<?php
/**
 * Por favor no pongas líneas de prueba aca.
 * Por favor.
 * Definición de constantes usadas en el sitio
 */
define("BASE_PATH", dirname(__FILE__) . "/");
define("INCLUDE_PATH", BASE_PATH . "includes/");

include_once(INCLUDE_PATH . 'functions.php');
define("BASE_URL", site_url());
define("IMAGES_URL", BASE_URL . "images/");
define("STYLES_URL", BASE_URL . "css/");
define("SCRIPTS_URL", BASE_URL . "scripts/");

define("CHARSET", "UTF-8");
define("NUM_ITEMS", 10); // Número de items mostrados en la paginación

error_reporting(E_ALL);

setlocale(LC_ALL, '');

/**
 * Parámetros de la base de datos
 */
$db_params = array(
  'db_host' => 'localhost',
  'db_name' => 'examen',
  'db_user' => 'root',
  'db_pass' => 'root'
);

/**
  * Tipo de usuario
  */
$autipos = array(
		'S'=>"Administrador",
		'D'=>"Docente",
    'A'=>"Alumno"
	);

/**
  * Niveles de preguntas
  */
$pniveles = array(
		'F'=>"F&aacute;cil",
		'D'=>"Dif&iacute;cil",
		'N'=>"Normal",
	);
?>
