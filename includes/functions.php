<?php

function send_headers($mime = "text/html") {
	static $sent = false;
	if ( headers_sent() || $sent) return;
	
	$sent = true;
	session_start();

	header("Content-Type: $mime; charset=" . CHARSET);
	header("Vary: Accept");
}

function site_url() {
	$site_schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
	$site_server =  $_SERVER['HTTP_HOST'] . ( $_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT'] );

	return $site_schema . $site_server . '/';
	//return $site_schema . $site_server . site_path();
}

function site_path() {
	static $site_path;
	
	if ( empty($site_path) ) {
		$site_path = str_replace(
			str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', 
			str_replace('\\', '/', BASE_PATH)
		);
	}
	return $site_path;
}

function safe_redirect($url, $type = NULL) {
	$url = strip_tags(preg_replace('#[\r\n]#', '', $url));
	if(!is_null($type)) :
		print '<script type="text/javascript">';
		print 'parent.location.href = \'' . $url . '\'';
		print '</script>';
	else :
		header('Location: ' . $url);
	endif;
	exit();
}

function insert_query ($table, $params) {
	if ( ($insert = process_params_for_insert($params)) !== false )	{
		$query = @sprintf('INSERT INTO %s(%s) VALUES (%s)', $table, $insert['fields'], $insert['values']);
		
		if (!empty($query))
			return $query;
	}
	return false;
}

function update_query($table, $params, $condition) {
	if ( ($update = process_params_for_update($params)) !== false )	{
		$query_fmt = 'UPDATE %s SET %s';
		if (!empty($condition))
			$query_fmt .= ' WHERE %s';
		elseif ($condition !== false)		
			die("No hay una condición para hacer el update");
			
		$query = @sprintf($query_fmt, $table, $update, $condition);
		
		if (!empty($query))
			return $query;
	}
	return false;
}

function insert_update_query ($table, $params) {
	$insert = process_params_for_insert($params);
	$update = process_params_for_update($params);
	
	if (!empty($insert) && !empty($update)) {
		$query_fmt = 'INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s';
		
		$query = sprintf(
			$query_fmt, 
			$table, 
			$insert['fields'],
			$insert['values'],
			$update
			);
		
		if (!empty($query))
			return $query;
	}
	return false;
}

function process_params_for_insert($array) {
	if (!is_array($array)) return false;

	$returnData['fields'] = implode(',', array_keys($array));
	foreach ($array as $k => $v) {
		if (is_array($v)) return false;

		$array[$k] = ($v == 'now()') ? $v : "'" . trim($v) . "'";
	}
	$returnData['values'] = implode(',', array_values($array));

	return $returnData;
}

function process_params_for_update($array) {
	if (!is_array($array)) return false;

	foreach ($array as $k => $v) {
		if (is_array($v)) return false;
		$array[$k] = "$k = " . ( ($v == 'now()') ? $v : "'" . trim($v) . "'" );
	}

	return implode(',', array_values($array));
}

function process_params_for_search($array) {
	if (!is_array($array)) return false;

	foreach ($array as $k => $v) {
		if (is_array($v)) return false;
		$array[$k] = is_numeric($v) ? "$k = $v" : "$k like '%$v%'";
	}

	return implode(' AND ', array_values($array));
}

function validate_required($values) {
	global $msg;
	$errors = false;
	$msg = '<ul>';
	foreach ($values as $k => $val) {
		if (empty($val)) {
			$msg .= '<li>' . sprintf('%s es requerido', $k) . "</li>\n";
			$errors = true;
		}
	}
	$msg .= '</ul>';
	if (!$errors)
		$msg = null;
	return ! $errors;
}

function fecha_to_db($fecha) {
	$f = split("/", $fecha);
	return ($fecha) ? $f[2] . "-" . $f[1] . "-" . $f[0] : "";
}

function fecha_to_page($fecha) {
	$f = split("-", $fecha);
	return ($fecha) ? $f[2] . "/" . $f[1] . "/" . $f[0] : "";
}

function deb($a) {
	echo "<pre>";
	print_r($a);
	echo "</pre>";
	exit();
}
?>