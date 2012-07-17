<?php
/**
 * Registra alquileres
 */
 	
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	
	// Si es que el formulario se ha enviado
	if($postback) :
		$alquiler = array(
			'fecha' => strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fecha'])),
			'recibo' => $_POST['recibo'],
			'idcliente' => $_POST['idcliente'],
			'idlugar' => $_POST['idlugar'],
			'idmaquina' => $_POST['idmaquina'],
			'minutos' => $_POST['minutos'],
      'observaciones' => $_POST['observaciones'],
		);
		/**
		  * Verificación
		  */
		  
		// Todo
		
		if (empty($alquiler['fecha']) ||
            empty($alquiler['recibo']) ||
            empty($alquiler['idcliente']) ||
            empty($alquiler['idlugar']) ||
            empty($alquiler['idmaquina']) ||
            empty($alquiler['minutos']) ||
            empty($alquiler['observaciones'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
			if(!$error) :
				$alquiler = array_map('strip_tags', $alquiler);
				// Guarda el alquiler
				$id = save_item(0, $alquiler, $bcdb->alquiler);
				
				if($id) :
					$msg = "La información se guardó correctamente.";
					//safe_redirect("ver-recibos.php?ID=$id&saved=1");
					exit();
				else:
					$error = true;
					$msg = "Hubo un error al guardar la información, intente nuevamente.";
				endif;
			endif;
		endif;
	endif; // End Postback.
	
	// Trae las máquinas
	$maquinas = get_items($bcdb->maquina);
  
  // Trae los lugares
  $lugares = get_items($bcdb->lugar);
	
	$now = time();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/jquery.autocomplete.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/thickbox.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/theme/ui.all.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/localdata.php"></script>
<script type='text/javascript' src="/scripts/jquery.bgiframe.min.js"></script> 
<script type='text/javascript' src="/scripts/jquery.ajaxQueue.js"></script> 
<script type="text/javascript" src="/scripts/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/scripts/thickbox.js"></script>
<script type="text/javascript" src="/scripts/jquery.validate.js"></script>
<script type="text/javascript" src="/scripts/jquery.calendar.js"></script> 
<script type="text/javascript" src="/scripts/jquery.ui.all.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * 'clientes' está definido en /scripts/localdata.php
		 * 
		 * Funciones que generan el autocompletado.
		 * *****************************************
		 * Si es que se da click a algún resultado se escoge a esa persona
		 * como la que paga.
		 */
		$("#cliente").autocomplete(clientes, {
			matchContains: true,
			minChars: 0,				  
			formatItem: function(item) {
				return item.nombres;
			}
		}).result(function(event, item) {
			$("#idcliente").attr("value", item.id);
		});
		
		$("#frmalquiler").validate();
		
		/**
		 * Varios
		 */	
		$('#fecha').focus();
	});
</script>
<title>Alquileres | Sistema de exámenes</title>
</head>

<body>
	<div class="container_16">
    <div id="header">
      <h1 id="logo"> <a href="/"><span>Sistema de exámenes</span></a> </h1>
      <?php include "menutop.php"; ?>
      <?php if(isset($_SESSION['loginuser'])) : ?>
      <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
      <?php endif; ?>
    </div>
    <div class="clear"></div>
        
    <div id="icon" class="grid_3">
      <p class="align-center"><img src="images/coins.png" alt="Pagos" /></p>
    </div>
    <div id="content" class="grid_13">
    <h1>Alquileres</h1>
      <?php if (isset($msg)): ?>
        <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
      <?php endif; ?>
      <p class="eContainer"></p>
      <form name="frmalquiler" id="frmalquiler" method="post" action="alquiler.php">
        <fieldset>
          <legend>Datos del alquiler</legend>
          <p>
            <label for="fecha">Fecha: <span class="required">*</span>:</label>
            <input type="text" name="fecha" id="fecha" size="20" class="date" />
            <label for="recibo">Recibo de caja: <span class="required">*</span></label>
            <input type="text" name="recibo" id="recibo" size="10" maxlength="20" />
          </p>
          <p>
            <label for="cliente">Cliente: <span class="required">*</span>:</label>
            <input type="text" name="cliente" id="cliente" size="60" />
            <a href="cliente.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&width=480&height=320&modal=true" class="thickbox">Agregar Nuevo</a>
          </p>
          <p>
            <label for="idlugar">Sector o comunidad: <span class="required">*</span></label>
            <select name="idlugar" id="idlugar">
              <option value="" selected="selected">Seleccione un lugar</option>
              <?php foreach ($lugares as $lugar) : ?>
              <option value="<?php print $lugar['id']; ?>"><?php print $lugar['nombre']; ?></option>
              <?php endforeach; ?>
            </select>
          </p>
          <p>
            <label for="idmaquina">Máquina: <span class="required">*</span>:</label>
            <select name="idmaquina" id="id_maquina">
              <option value="" selected="selected">Seleccione una máquina</option>
              <?php foreach ($maquinas as $maquina) : ?>
              <option value="<?php print $maquina['id']; ?>"><?php print $maquina['descripcion']; ?></option>
              <?php endforeach; ?>
            </select>
            <label for="minutos">Minutos: <span class="required">*</span>:</label>
            <select name="minutos" id="minutos">
              <option value="" selected="selected">Escoge el tiempo</option>
              <?php $tiempo = convierte_horas(get_option('limite_dia')*60) ?>
              <?php foreach ($tiempo as $k=>$v) : ?>
              <option value="<?php print $k; ?>"><?php print $v; ?></option>
              <?php endforeach; ?>
            </select> 
          </p>
          <p>
            <label for="observaciones">Observaciones:</label><br />&nbsp;
            <textarea rows="8" cols="95" name="observaciones" id="observaciones"></textarea>
          </p>
          <p class="align-center">
            <button type="submit" name="submit" id="submit">Guardar</button>
            <input type="hidden" name="idcliente" id="idcliente" value="" />
            <input type="hidden" name="now" id="now" value="<?php print $now; ?>" />
          </p>
        </fieldset>
      </form>
    </div><div class="clear"></div>
    <?php include "footer.php"; ?>
    </div>
</body>
</html>