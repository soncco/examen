<?php
/**
 * Registra Máquinas
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$maquina = array(
			'descripcion' => $_POST['descripcion'],
			'idoperador' => $_POST['idoperador']);
		
		// Verificación
		if (empty($maquina['descripcion']) || empty($maquina['idoperador'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
		
			$maquina = array_map('strip_tags', $maquina);
			// Guarda la máquina
			$id = save_item(0, $maquina, $bcdb->maquina);
			
			if($id) :
				$msg = "La información se guardó correctamente.";
			else:
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
	endif;
	
  // Trae los operadores
  $pager = false;
  $operadores = get_items($bcdb->operador);
  
	// Trae las máquinas
	$pager = true;
	$maquinas = get_items($bcdb->maquina);
	
	// Paginación
	
	$results = @$bcrs->get_navigation();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>layout.css" /> 
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".click").editable("/datos-maquina.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
    $(".clicks").editable("/datos-maquina.php", { 
      indicator : "Guardando...",
      loadurl   : "<?php print SCRIPTS_URL; ?>operadores.php",
      type   : "select",
      submit : "OK",
      style  : "inherit",
      submitdata : function() {
        return {op : true};
      }
    });
		$('#descripcion').focus();
//		$("#frmmaquina").validate();
	});
</script>
<title>Máquinas | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/maquina.png" alt="Máquinas" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Máquinas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmmaquina" id="frmmaquina" method="post" action="maquinas.php">
      <fieldset class="collapsible">
      <legend>Información de la máquina</legend>
      <p>
        <label for="descripcion">Descripción <span class="required">*</span>:</label>
        <input type="text" name="descripcion" id="descripcion" maxlength="45" size="45" />
      </p>
      <p>
        <label for="idoperador">Operador <span class="required">*</span>:</label>
        <select name="idoperador" id="idlugar">
          <option value="" selected="selected">Seleccione un operador</option>
          <?php foreach ($operadores as $operador) : ?>
          <option value="<?php print $operador['id']; ?>"><?php print $operador['nombres']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
      </fieldset>
    </form>
    <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
      <legend>Máquinas existentes</legend>
      <p class="war">Las máquinas se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
      <table>
        <thead>
          <tr>
          <th>Máquina</th>
          <th>Operador</th>
          <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($maquinas): ?>
          <?php $alt = "even"; ?>
          <?php foreach($maquinas as $k=> $maquina): ?>
          <tr class="<?php print $alt ?>">
            <th><span class="click" id="descripcion-<?php print $maquina['id']; ?>"><?php print $maquina['descripcion']; ?></span></td>
            <td><span class="clicks" id="idoperador-<?php print $maquina['id']; ?>"><?php print get_var_from_item('nombres', $maquina['idoperador'], $bcdb->operador); ?></span></td>
            <td><a href="ver-usomaquina.php?id_maquina=<?php print $maquina['id']; ?>">Ver reporte</a></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <td colspan="5">No existen datos</th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php include "pager.php"; ?>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>