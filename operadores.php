<?php
/**
 * Registra Rubros
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$operador = array(
			'nombres' => $_POST['nombres'],
    );
		
		// Verificación
		if (empty($operador['nombres'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
		
			$operador = array_map('strip_tags', $operador);
			// Guarda el rubro
			$id = save_item(0, $operador, $bcdb->operador);
			
			if($id) :
				$msg = "La información se guardó correctamente.";
			else:
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
	endif;
	
	// Trae las operadores
	$pager = true;
	$operadores = get_items($bcdb->operador);
	
	// Paginación
	
	$results = @$bcrs->get_navigation();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#nombres').focus();		
		$(".click").editable("/datos-operadores.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
	});
</script>
<title>Operadores | Sistema de exámenes</title>
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
    <p class="align-center"><img src="images/rubros.png" alt="Rubros" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Operadores</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmoperador" id="frmoperador" method="post" action="operadores.php">
      <fieldset class="collapsible">
        <legend>Información del operador</legend>
        <p>
          <label for="nombres">Nombres: <span class="required">*</span>:</label>
          <input type="text" name="nombres" id="codigo" maxlength="45" size="40" />
        </p>
        <p class="align-center">
          <button type="submit" name="submit" id="submit">Guardar</button>
        </p>
      </fieldset>
    </form>
    <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
      <legend>Operadores existentes</legend>
      <p class="war">Los operadores se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
      <table>
        <thead>
          <tr>
            <th>Identificador</th>
            <th>Nombres</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($operadores): ?>
          <?php $alt = "even"; ?>
          <?php foreach($operadores as $k=> $operador): ?>
          <tr class="<?php print $alt ?>">
            <th><?php print $operador['id']; ?></th>
            <th><span class="click" id="nombres-<?php print $operador['id']; ?>"><?php print $operador['nombres']; ?></span></th>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <th>No existen datos</th>
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