<?php

require_once ('home.php');
require_once ('redirect.php');

// Clave principal
$bcdb -> current_field = 'codExamen';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if ($postback) :
	$hora = explode(":", $_POST['inicio']);
	$time_i = ($hora[0] * 3600) + ($hora[1] * 60);
	
	$hora = explode(":", $_POST['fin']);
	$time_f = ($hora[0] * 3600) + ($hora[1] * 60);
	
	$time = $time_f - $time_i;

	$examenprograma = array(
		'codExamen' => $_POST['codExamen'],
		'fecha' => strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fecha']) + $time_i),
		'rendido' => 'N',
		'duracion' => $time
		);

	// Verificación
	if (empty($_POST['codExamen']) || empty($_POST['fecha']) || empty($_POST['inicio']) || empty($_POST['fin'])) :
		$error = true;
		$msg = "Ingrese la información obligatoria.";
	else :
		if ($_POST['inicio'] == $_POST['fin']) :
			$error = true;
			$msg = "Hora de inicio y hora de fin no pueden ser las mismas";
		else:
			$examenprograma = array_map('strip_tags', $examenprograma);
			// Guarda
			$id = save_item($examenprograma['codExamen'], $examenprograma, $bcdb -> examenprograma);
	
			if ($id) :
				$msg = "La información se guardó correctamente.";
			else :
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
	endif;
endif;

$cursos = get_cursos_docente($_SESSION['loginuser']['codDocente']);
$examenes_programados = get_examenes_programados_docente($_SESSION['loginuser']['codDocente']);
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
			'timeFormat' : 'H:i',
			'minTime' : '5:00am',
			'maxTime' : '10:00pm'
		});

		$('#inicio').blur(function() {
			inicio = $(this).val();
			$('#fin').val('').timepicker({
				'timeFormat' : 'H:i',
				'minTime' : inicio,
				'maxTime' : '11:00pm'
			});
		})
		// Combos dependientes.
		simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
		$('#codCurso').change(function() {
			codCurso = $(this).val();
			if (codCurso != '') {
				$(this).after(simg);
				$.ajax({
					type : 'POST',
					url : 'traer-examenes.php',
					data : 'codCurso=' + codCurso,
					success : function(response) {
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
<title>Exámenes | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php
	include "header.php";
 ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <div id="sidebar">
      <h3>Examen</h3>
      <ul>
        <li><a href="<?php print BASE_URL; ?>examenes.php">Crear exámen</a></li>
        <li><a href="<?php print BASE_URL; ?>ver-examenes.php">Lista de examenes</a></li>
        <li><a href="<?php print BASE_URL; ?>examen-programa.php">Programar Examen</a></li>
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
        <p class="align-center">
          <button type="submit" name="submit" id="submit">Guardar</button>
        </p>
      </fieldset>
    </form>
    <fieldset class="collapsible">
      <legend>Examenes programados</legend>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Curso</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Duración</th>
            <th>Rendido</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($examenes_programados) : ?>
          <?php foreach ($examenes_programados as $k => $examen) : ?>
          <tr>
            <th><?php print $examen['nombre']; ?></th>
            <th><?php print $examen['curso'][0]['nombre']; ?></th>
            <td><?php print strftime('%d %b %Y', strtotime($examen['fecha'])); ?></td>
            <td><?php print strftime('%H:%m', strtotime($examen['fecha'])); ?></td>
            <td><?php print $examen['duracion']/60; ?> minutos</td>
            <td><?php print ($examen['rendido'] == 'S') ? 'Si' : 'No' ; ?></td>
          </tr>
          <?php endforeach; ?>
          <?php else : ?>
          <tr>
            <th colspan="4"></th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </fieldset>
      
  </div>
  <div class="clear"></div>
  <?php
    include "footer.php";
  ?>
</div>
</body>
</html>