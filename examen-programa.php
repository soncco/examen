<?php

require_once('home.php');
require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codExamen';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
  
endif;

$cursos = get_cursos_docente($_SESSION['loginuser']['codDocente']);
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
    // Combos dependientes.
		$('#codCurso').change(function () {
      codCurso = $(this).val();
      if (codCurso != '') {
        $.ajax({
          type: 'POST',
          url: 'traer-examenes.php',
          data: 'codCurso=' + codCurso,
          success: function(response){
            console.log(response);
            $('#codExamen').html(response);
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
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Sistema de exámenes</span></a> </h1>
    <?php include "menutop.php"; ?>
    <?php if(isset($_SESSION['loginuser'])) : ?>
    <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
    <?php endif; ?>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <div id="sidebar">
      <h3>Preguntas</h3>
      <ul>
        <li><a href="/examenes.php">Crear exámen</a></li>
        <li><a href="/pregunta-examen.php">Agregar pregunta a exámen</a></li>
        <li><a href="/ver-examenes.php">Lista de examenes</a></li>
        <li><a href="/examen-programa.php">Programar Examen</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Exámenes</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmexamen" id="frmexamen" method="post" action="examenes.php">
      <fieldset class="collapsible">
        <legend>Programar Examen</legend>  
	      <p>
	        <label for="codCurso">Curso <span class="required">*</span>:</label>
	        <select name="codCurso" id="codCurso">
	          <option value="" selected="selected">Seleccione un curso</option>
	          <?php foreach ($cursos as $k => $curso) : ?>
	          <option value="<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></option>
	          <?php endforeach; ?>
	        </select>
	      </p>
        <p>
	        <label for="codExamen">Examenes <span class="required">*</span>:</label>
	        <select name="codExamen" id="codExamen">
	          <option value="" selected="selected">Seleccione un curso</option>
	        </select>
	      </p>
      </fieldset>
      <fieldset class="collapsible">
        <legend>Examenes</legend>
      </fieldset>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>