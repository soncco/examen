<?php
	require_once('../home.php');
	header("Content-type: text/javascript");
	$clientes = get_items($bcdb->cliente, 'apaterno');
?>
var clientes = [
<?php
  $str = "";
  if ($clientes) :
    foreach($clientes as $k => $cliente) {
      $str .= sprintf ("{id: '%s', nombres: '%s %s %s'},",
              $cliente['id'], addslashes($cliente['nombres']), addslashes($cliente['apaterno']), addslashes($cliente['amaterno']));
    }
  endif;
  //$str .= "{id: '0', nombres: 'Agregar nuevo'}";
  $str = substr($str, 0, -1);
  echo $str;
?>
];