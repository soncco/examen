<?php

require_once('home.php');
//require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codTema';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
  $tema = array(
    'nombre' => $_POST['nombre'],
    'codCurso' => $_POST['codCurso'],
    'codDocente' => $_SESSION['loginuser']['codDocente'] 
  );

  // Verificación
  if (empty($tema['nombre'])) :
    $error = true;
    $msg = "Ingrese la información obligatoria.";
  else :

    $tema = array_map('strip_tags', $tema);
  
    // Guarda el tema
    $id = save_item(0, $tema, $bcdb->tema);

    if($id) :
      $msg = "La información se guardó correctamente.";
    else:
      $error = true;
      $msg = "Hubo un error al guardar la información, intente nuevamente.";
    endif;
  endif;
endif;

$temas = get_items($bcdb->tema);
$cursos = get_cursos_docente($_SESSION['loginuser']['codDocente']);

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
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#nombre').focus();
		$('#frmtema').validate();
	});
</script>
<title>Temas | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <div id="sidebar">
      <h3>Temas</h3>
      <ul>
        <li><a href="/temas.php">Crear temas</a></li>
        <li><a href="/lista-temas.php">Lista de temas</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Temas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmtema" id="frmtema" method="post" action="temas.php">
      <fieldset class="collapsible">
      <legend>Crear tema</legend>
      <p>
        <label for="nombre">Nombre <span class="required">*</span>:</label>
        <input type="text" name="nombre" id="nombre" maxlength="60" size="45" class="required" />
      </p>
      <p>
        <label for="codCurso">Curso <span class="required">*</span>:</label>
        <select name="codCurso" id="codCurso" class="required">
          <option value="" selected="selected">Seleccione un curso</option>
          <?php foreach ($cursos as $k => $curso) : ?>
          <option value="<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
      </fieldset>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>