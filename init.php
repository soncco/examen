<?php
/**
 * Inicialización de variables e inclusión de archivos
 */
include_once('config.php');

include_once(INCLUDE_PATH . 'ez_sql.php');

# Tablas
$table_prefix = "t";
$bcdb->tema           = $table_prefix . 'tema';
$bcdb->curso          = $table_prefix . 'curso';
$bcdb->pregunta       = $table_prefix . 'pregunta';
$bcdb->alternativa    = $table_prefix . 'alternativa';
$bcdb->opciones       = $table_prefix . 'opciones';
$bcdb->usuario        = $table_prefix . 'usuario';

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