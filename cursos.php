<?php

require_once('home.php');
require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codCurso';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
  $curso = array(
    'codCurso' => $_POST['codCurso'],
    'nombre' => $_POST['nombre'],
    'creditos' => $_POST['creditos'],
    'activo' => $_POST['activo'],
  );

  // Verificación
  if (empty($curso['codCurso']) || empty($curso['nombre'])) :
    $error = true;
    $msg = "Ingrese la información obligatoria.";
  else :

    $curso = array_map('strip_tags', $curso);
  
    // Guarda el curso
    $id = save_item($_POST['codCurso'], $curso, $bcdb->curso);

    if($id) :
      $msg = "La información se guardó correctamente.";
    else:
      $error = true;
      $msg = "Hubo un error al guardar la información, intente nuevamente.";
    endif;
  endif;
endif;

$pager = true;
$cursos = get_items($bcdb->curso, 'codCurso');

// Paginación.
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
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $('#frmcurso').validate();
    
		$(".click").editable("/datos-curso.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
    $(".clicks").editable("/datos-curso.php", { 
      indicator : "Guardando...",
      loadurl   : "<?php print SCRIPTS_URL; ?>operadores.php",
      type   : "select",
      submit : "OK",
      style  : "inherit",
      submitdata : function() {
        return {op : true};
      }
    });

  $('#codCurso').focus();
	});
</script>
<title>Cursos | Sistema de exámenes</title>
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
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Cursos</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmcurso" id="frmcurso" method="post" action="cursos.php">
      <fieldset class="collapsible">
      <legend>Información del Curso</legend>
      <p>
        <label for="codCurso">Código <span class="required">*</span>:</label>
        <input type="text" name="codCurso" id="codCurso" maxlength="8" size="10" class="required" />
      </p>
      <p>
        <label for="nombre">Nombre <span class="required">*</span>:</label>
        <input type="text" name="nombre" id="nombre" maxlength="60" size="45" class="required" />
      </p>
      <p>
        <label for="creditos">Créditos <span class="required">*</span>:</label>
        <input type="text" name="creditos" id="creditos" maxlength="1" size="2" class="required number" />
      </p>
        <label for="activo">Activo <span class="required">*</span>:</label>
        <select name="activo" id="activo">
          <option value="S" selected="selected">SI</option>
		  <option value="N" >NO</option>
        </select>
      </p>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
      </fieldset>
    </form>
    <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
      <legend>Cursos existentes</legend>
      <p class="war">Las cursos se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
      <table>
        <thead>
          <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Créditos</th>
          <th>Activo</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($cursos): ?>
          <?php $alt = "even"; ?>
          <?php foreach($cursos as $k => $curso): ?>
          <tr class="<?php print $alt ?>">
            <th><span class="click" id="codCurso-<?php print $curso['codCurso']; ?>"><?php print $curso['codCurso']; ?></span></td>
            <th><span class="click" id="nombre-<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></span></td>
            <th><span class="click" id="creditos-<?php print $curso['codCurso']; ?>"><?php print $curso['creditos']; ?></td>
            <td><?php print $curso['activo']; ?></span></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <td colspan="2">No existen datos</th>
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