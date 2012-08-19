<?php

require_once('home.php');
require_once('redirect.php');

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
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
	
	function notasExamen() {
		$('#referencia').after(simg);
		$.ajax({
			type: 'GET',
			url: 'traer-report-notas.php',
			success: function(response){
				$('#notas').html(response);
				$('#simg').remove();
			},
			error: function(){
				$('#simg').remove();
			},
			timeout: 5000
		});
	};
	
	function examenDeCurso() {
		codCurso = $('#codCurso').val();
		if (codCurso != '') {
			$('#codCurso').after(simg);
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
			$('#codExamen').html($('<option value="">Escoge un curso</option>'));
			$('#fecha').html($('<option value="">Escoge un exámen</option>'));  
		}
	}
	
	$(document).ready(function() {
		$('#codCurso').change(function() {
			examenDeCurso();
		});
	});
</script>
<title>Reportes | Sistema de exámenes</title>
</head>
<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="<?php print IMAGES_URL; ?>/misnotas.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Reportes</h1>
    <fieldset> 	
      <legend>Calificaciones por exámen</legend>
      	<p class="help"><span id="referencia">Tiempos en hh:mm:ss, se actualiza automáticamente cada 20 segundos.</span></p>
        <p>
          <label for="codCurso">Curso <span class="required">*</span>:</label>
          <select name="codCurso" id="codCurso" class="required">
            <option value="" selected="selected">Seleccione un curso</option>
            <? foreach ($cursos as $k => $curso) : ?>
            <option value="<? print $curso['codCurso']; ?>">
                  <? print $curso['nombre']; ?>
            </option>
            <? endforeach; ?>
          </select>
        </p>
        <p>
          <label for="codExamen">Exámen <span class="required">*</span>:</label>
          <select name="codExamen" id="codExamen" class="required">
            <option value="" selected="selected">Seleccione un curso</option>
          </select>
        </p>
        <p>
          <label for="fecha">Fecha <span class="required">*</span>:</label>
          <select name="fecha" id="fecha" class="required">
            <option value="" selected="selected">Seleccione un exámen</option>
          </select>
        </p>	
	    <p id="notas">
	    </p>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>