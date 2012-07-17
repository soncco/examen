<?php
/**
 * Funciones para crear, modificar o eliminar items
 * Los items se refieren a cualquier dato de las tablas
 */
 
/**
* Devuelve una fila de una tabla de acuerdo a un id
*
* @param int $id Id del item
* @param string $table Tabla
* @return array
*/
function get_item ($id, $table) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $table WHERE $bcdb->current_field = '$id'");
}

/**
* Devuelve una fila de una tabla de acuerdo al valor un campo determinado
*
* @param string $field Campo de la tabla
* @param string $param Valor del campo
* @param string $table Tabla
* @return array
*/
function get_item_by_field ($field, $param, $table) {
	global $bcdb;
	return $bcdb->get_row("SELECT * FROM $table WHERE $field = '$param'");
}

/**
* Devuelve una valor de una tabla de acuerdo al id
*
* @param string $var Campo de la tabla
* @param int $id ID
* @param string $table Tabla
* @return string
*/
function get_var_from_item ($var, $id, $table) {
	global $bcdb;
	return $bcdb->get_var("SELECT $var FROM $table WHERE $bcdb->current_field = '$id'");
}

/**
* Devuelve una valor de una tabla de acuerdo al un campo
*
* @param string $var Campo de la tabla
* @param int $field ID
* @param int $value valor
* @param string $table Tabla
* @return string
*/
function get_var_from_field ($var, $field, $value, $table) {
	global $bcdb;
	return $bcdb->get_var("SELECT $var FROM $table WHERE $field = '$value'");
}

/**
* Devuelve todos los datos de una tabla
*
* @param string $table Tabla
* @param string $order Campo de la tabla para ordenar
* @param string $mode Modo de ordenación
* @return array
*/
function get_items ($table, $order = NULL, $mode = "ASC") {
	global $bcdb, $bcrs, $pager;
	
	if(is_null($order)) :
		$sql = "SELECT * 
				FROM $table 
				ORDER BY $bcdb->current_field $mode";
	else :
		$sql = "SELECT * 
				FROM $table 
				ORDER BY $order $mode";
	endif;

	$items = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	return $items;
}

/**
* Devuelve todos los datos de una tabla de acuerdo a un campo
*
* @param string $field Campo de la tabla
* @param string $value Valor del campo
* @param string $table Tabla
* @param string $order Campo por el que se debe ordenar
* @return array
*/
function get_items_by_field ($field, $value, $table, $order = NULL) {
	global $bcdb, $bcrs, $pager;
	
	if(is_null($order)) :
		$sql = "SELECT * 
			FROM $table 
			WHERE $field = '$value' 
			ORDER BY $bcdb->current_field";
	else :
		$sql = "SELECT * 
			FROM $table 
			WHERE $field = '$value' 
			ORDER BY $order";
	endif;

	$items = $bcdb->get_results($sql);
	return $items;
}

/**
* Guarda un item a una tabla
*
* @param int $id ID
* @param array $item_values Valores a guardar
* @param string $table Tabla
* @return boolean
*/
function save_item($id, $item_values, $table) {
	global $bcdb, $msg;

	$item_values[$bcdb->current_field] = $id;

	if ( ($query = insert_update_query($table, $item_values)) &&
		$bcdb->query($query) ) {
		if (empty($id))	
			$id = $bcdb->insert_id;
		
		$msg = "Los datos han sido guardados satisfactoriamente.";
		
		return $id;
	}
	$msg = "Hubo un problema al guardar.";
	return false;
}

/**
* Borra un item
*
* @param int $id ID
* @param string $table Tabla
*/
function remove_item ($id, $table) {
	global $bcdb;
	$bcdb->query("DELETE FROM $table WHERE $bcdb->current_field = $id");
}

?>