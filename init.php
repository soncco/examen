<?php
/**
 * Inicialización de variables e inclusión de archivos
 */
include_once('config.php');

include_once(INCLUDE_PATH . 'ez_sql.php');

# Tablas
$table_prefix = "t";
$bcdb->tema           = $table_prefix . 'Tema';
$bcdb->curso          = $table_prefix . 'Curso';
$bcdb->pregunta       = $table_prefix . 'Pregunta';
$bcdb->alternativa    = $table_prefix . 'Alternativa';
$bcdb->opciones       = $table_prefix . 'Opciones';
$bcdb->docente        = $table_prefix . 'Docente';
$bcdb->admin          = $table_prefix . 'Administrador';
$bcdb->alumno         = $table_prefix . 'Alumno';
$bcdb->docente        = $table_prefix . 'Docente';
$bcdb->cargaacademica = $table_prefix . 'CargaAcademica';

# Funciones independientes
include_once(INCLUDE_PATH . 'formatting-functions.php');
include_once(INCLUDE_PATH . 'pager.class.php');
include_once(INCLUDE_PATH . 'user-functions.php');
include_once(INCLUDE_PATH . 'item-functions.php');
include_once(INCLUDE_PATH . 'various-functions.php');
include_once(INCLUDE_PATH . 'numbertotext.php');
include_once(INCLUDE_PATH . 'krumo/class.krumo.php');

# Iniciamos
send_headers();

global_sanitize();

$pager = false;

$_SERVER['PHP_SELF'] = htmlspecialchars(preg_replace('`(\.php).*$`', '$1', $_SERVER['PHP_SELF']), ENT_QUOTES, 'utf-8');
$self = $_SERVER['PHP_SELF'];

?>