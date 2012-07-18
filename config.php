<?php
/**
 * Definición de constantes usadas en el sitio, AAAaaaa
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

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

setlocale(LC_ALL, '');

/**
 * Parámetros de la base de datos
 */
$db_params = array(
  'db_host' => '96.126.121.49',
  'db_name' => 'examen',
  'db_user' => 'examen',
  'db_pass' => 'magnificos123'
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
