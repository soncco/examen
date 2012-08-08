<?php

require_once('home.php');
require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codSemestre';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
  $semestre = array(
  	'codSemestre' => $_POST['codSemestre'],
    'fechaInicio' => strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fechaInicio'])),
    'fechaFin' => strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fechaFin'])),
  );

  // Verificación
  if (empty($semestre['codSemestre']) || empty($semestre['fechaInicio'])  || empty($semestre['fechaFin'])) :
    $error = true;
    $msg = "Ingrese la información obligatoria.";
  else :

    $semestre = array_map('strip_tags', $semestre);
  
    // Guarda el semestre
    $id = save_item($_POST['codSemestre'], $curso, $bcdb->semestre);

    if($id) :
      $msg = "La información se guardó correctamente.";
    else:
      $error = true;
      $msg = "Hubo un error al guardar la información, intente nuevamente.";
    endif;
  endif;
endif;

$semestres = get_items($bcdb->semestre, 'codSemestre');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>layout.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>theme/ui.all.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.calendar.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.ui.all.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $('#frmsemestre').validate();
    $('#codSemestre').focus();
	});
</script>
<title>Semestres | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Semestres</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmsemestre" id="frmsemestre" method="post" action="semestres.php">
      <fieldset class="collapsible">
      <legend>Información del Semestre</legend>
      <p>
        <label for="codSemestre">Nombre del Semestre <span class="required">*</span>:</label>
        <input type="text" name="codSemestre" id="codSemestre" maxlength="7" size="10" class="required" />
      </p>
      <p>
        <label for="fechaInicio">Fecha Inicio <span class="required">*</span>:</label>
        <input type="text" name="fechaInicio" class="date" id="fechaInicio" maxlength="20" size="20" class="required" />
      </p>
      <p>
        <label for="fechaFin">Fecha Fin <span class="required">*</span>:</label>
        <input type="text" name="fechaFin" class="date" id="fechaFin" maxlength="20" size="20" class="required" />
      </p>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
      </fieldset>
    </form>
    <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
      <legend>Semestres existentes</legend>
      <table>
        <thead>
          <tr>
          <th>Semestre</th>
          <th>Fecha Inicio</th>
          <th>Fecha Fin</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($semestres): ?>
          <?php $alt = "even"; ?>
          <?php foreach($semestres as $k => $semestre): ?>
          <tr class="<?php print $alt ?>">
            <th><?php print $semestre['codSemestre']; ?></td>
            <th><?php print $semestre['fechaInicio']; ?></td>
            <th><?php print $semestre['fechaFin']; ?></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <td colspan="3">No existen datos</th>
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