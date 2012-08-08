<?php

require_once('home.php');
require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codExamen';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
	$hora = explode(":", $_POST['hora']);
	$time = ($hora[0] * 3600) + ($hora[1] * 60); 
	
	$examenprograma = array(
		'codExamen' => $_POST['codExamen'],
		'fecha' => strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fecha']) + $time),
		'rendido' => 'N',
		'duracion' => $_POST['duracion']
	);
	
	// Verificación
	if (empty($examenprograma['codExamen']) || empty($_POST['fecha']) || empty($_POST['hora'])  || empty($examenprograma['duracion'])) :
		$error = true;
		$msg = "Ingrese la información obligatoria.";
	else :
		$examenprograma = array_map('strip_tags', $examenprograma);
		// Guarda el semestre
		$id = save_item($examenprograma['codExamen'], $examenprograma, $bcdb->examenprograma);
	
		if($id) :
			$msg = "La información se guardó correctamente.";
		else:
			$error = true;
			$msg = "Hubo un error al guardar la información, intente nuevamente.";
		endif;
	endif;
endif;

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
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>theme/ui.all.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>jquery.timepicker.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.calendar.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.ui.all.min.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.timepicker.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    
    $('#frmexamenprograma').validate();
    // Timepicker.
    $('#inicio').timepicker({
      'minTime' : '5:00am', // Hora mínima.
      'maxTime' : '11:00pm' // Hora máxima.
    });
    
    $('#inicio').blur(function () {
      inicio = $(this).val();
      $('#fin').val('').timepicker({
        'minTime' : inicio,
        'maxTime' : '12:00pm'
      });
    })
    
    // Combos dependientes.
    simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
		$('#codCurso').change(function () {
      codCurso = $(this).val();      
      if (codCurso != '') {
        $(this).after(simg);
        $.ajax({
          type: 'POST',
          url: 'traer-examenes.php',
          data: 'codCurso=' + codCurso,
          success: function(response){
            $('#codExamen').html(response);
            $('#simg').remove();
          }
        });
      } else {
        $('#codCurso').html($('<option value="">Escoge un curso</option>'));
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
      <h3>Examen</h3>
      <ul>
        <li><a href="/examenes.php">Crear exámen</a></li>
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
    <form name="frmexamenprograma" id="frmexamenprograma" method="post" action="examen-programa.php">
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
	      <p>
	        <label for="fecha">Fecha <span class="required">*</span>:</label>
	        <input type="text" name="fecha" class="date" id="fecha" maxlength="20" size="20" />
	      </p>
        <p>
          <label for="inicio">Hora de inicio <span class="required">*</span>:</label>
          <input type="text" name="inicio" id="inicio" size="8" value="" class="required" />
          <label for="fin">Hora de fin <span class="required">*</span>:</label>
          <input type="text" name="fin" id="fin" size="8" value="" class="required" />
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