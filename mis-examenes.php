<?php

require_once('home.php');
require_once('redirect.php');

$cursos = get_cursos_con_examenes_pendientes($_SESSION['loginuser']['codAlumno'], get_option('semestre_actual'));

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
	$(document).ready(function() {
		$('#codCurso').change(function () {
			codCurso = $(this).val();
			if (codCurso != '') {
				$(this).after(simg);
				$.ajax({
					type: 'POST',
					url: 'traer-mis-examenes.php',
					data: 'codCurso=' + codCurso,
					success: function(response){
						$('#examenes').html(response);
						$('#simg').remove();
					}
				});
			} else {
				$('#examenes').html($('Escoge un curso'));
			}
		});
	});
</script>
<title>Cursos | Sistema de exámenes</title>
</head>
<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Exámenes</h1>
    <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsible<?php endif; ?>"> 	
      <legend>Programados</legend>
        <p>
          <label for="codCurso">Curso <span class="required">*</span>:</label>
          <select name="codCurso" id="codCurso" class="required">
            <option value="" selected="selected">Seleccione un curso</option>
            <?php foreach ($cursos as $k => $curso) : ?>
            <option value="<?php print $curso['codCurso']; ?>">
                  <?php print $curso['nombre']; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </p>
	    <p id="examenes">
	    </p>        
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>