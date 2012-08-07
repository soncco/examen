<?php
// Include ezSQL core
include_once "ez_sql/shared/ez_sql_core.php";
// Include ezSQL database specific component
include_once "ez_sql/mysql/ez_sql_mysql.php";
  
if ( ! isset($bcdb) ) {
	$bcdb = new ezSQL_mysql($db_params['db_user'], $db_params['db_pass'], $db_params['db_name'], $db_params['db_host']);
	$bcdb->show_errors = true;
}
?>