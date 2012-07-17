<?php
	require_once('../home.php');
	header("Content-type: text/javascript");
	$operadores = get_items($bcdb->operador);
?>
{<?php
  $str = "";
  if ($operadores) :
    foreach($operadores as $k => $operador) {
      $str .= sprintf('"%s":"%s",',
              $operador['id'],
              $operador['nombres']);
    }
  endif;
  //$str .= "{id: '0', nombres: 'Agregar nuevo'}";
  $str = substr($str, 0, -1);
  echo $str;
?>}