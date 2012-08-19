<?php

require_once('home.php');
require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codPregunta';

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
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
    $('#codCurso').change(function(){
      codCurso = $(this).val();
      if (codCurso != '') {
        $(this).after(simg);
        $.ajax({
          type: 'POST',
          url: 'traer-preguntas.php',
          data: 'codCurso=' + codCurso + '&op=lista&niveles=',
          success: function(response){
            $('#temas-results').empty();
            $('#temas-results').append(response);
            $('#simg').remove();
          }
        });
      }
		});
	});
</script>
<title>Preguntas | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <div id="sidebar">
      <h3>Preguntas</h3>
      <ul>
        <li><a href="<?php print BASE_URL; ?>preguntas.php">Crear preguntas</a></li>
        <li><a href="<?php print BASE_URL; ?>lista-preguntas.php">Lista de preguntas</a></li>
        <li><a href="<?php print BASE_URL; ?>preguntas-importar.php">Importar preguntas</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="<?php print IMAGES_URL; ?>/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Preguntas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <fieldset>
    <legend>Escoge el curso</legend>
      <p class="help">Escoge el curso para ver las preguntas.</p>
      <p>
	      <label for="codCurso">Curso <span class="required">*</span>:</label>
        <select name="codCurso" id="codCurso">
          <option value="" selected="selected">Seleccione un curso</option>
          <?php foreach ($cursos as $k => $curso) : ?>
          <option value="<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
    </fieldset>
    <fieldset class="collapsible">
      <legend>Banco de Preguntas</legend>
      <div id="temas-results"></div>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>