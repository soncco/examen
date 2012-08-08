<?php

require_once('home.php');
require_once "includes/spyc.php";
require_once('redirect.php');

$postback = isset($_POST['submit']);
$error = false;

if($postback){
	$pregunta = array(
		'codTema' => $_POST['codTema'],
	);
	
	if (empty($pregunta['codTema']) || (filesize($_FILES['archivo']['tmp_name']) == 0)) {
		$error = true;
		$msg = "Ingrese la información obligatoria.";
	} else {
		$preguntas = spyc_load_file($_FILES['archivo']['tmp_name']);
		
		/*
		 * TODO: VALIDAR QUE ARCHIVO ESTE EN EL FORMATO CORRECTO
		 */
		
		foreach ($preguntas as $preg) {
			$pregunta_i = array(
				'codTema' => $pregunta['codTema'],
				'enunciado' => $preg['enunciado'],
				'nivel' => $preg['nivel']
			);
			
			if (!$pregunta_i['nivel']) $pregunta_i['nivel'] = 'N';
			
			$bcdb->current_field = 'codPregunta';
			save_item(0, $pregunta_i, $bcdb->pregunta);
			$id_pregunta =  $bcdb->insert_id;
			$correcta = $preg['correcta'];
			
			foreach ($preg['alternativas'] as $alt) {
				$alternativa_i = array(
					'codPregunta' => $id_pregunta,
					'correcta' => 'N',
					'detalle' => $alt,
				);
				
				if ($preg['alternativas'][$correcta] == $alt) $alternativa_i['correcta'] = 'S';
				
				$bcdb->current_field = 'codAlternativa';
				save_item(0, $alternativa_i, $bcdb->alternativa);
			}
		}
	}
}

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
		$('#codCurso').change(function () {
      codCurso = $(this).val();
      if (codCurso != '') {
        $(this).after(simg);
        $.ajax({
          type: 'POST',
          url: 'traer-temas-pregunta.php',
          data: 'codCurso=' + codCurso,
          success: function(response){
            $('#codTema').html(response);
            $('#simg').remove();
          }
        });
      } else {
        $('#codTema').html($('<option value="">Escoge un curso</option>'));
      }
    });

		$('codCurso').focus();
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
        <li><a href="/preguntas.php">Crear preguntas</a></li>
        <li><a href="/lista-preguntas.php">Lista de preguntas</a></li>
        <li><a href="/preguntas-importar.php">Importar preguntas</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Preguntas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmpregunta" id="frmpregunta" enctype="multipart/form-data" method="post" action="preguntas-importar.php">
      <fieldset class="collapsible">
        <legend>Importar Preguntas</legend>
        <p>
          <label for="codCurso">Curso <span class="required">*</span>:</label>
          <select name="codCurso" id="codCurso">
            <option value="" selected="selected">Seleccione un curso</option>
            <?php foreach ($cursos as $k => $curso) : ?>
            <option value="<?php print $curso['codCurso']; ?>">
                  <?php print $curso['nombre']; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </p>
        <p>
          <label for="codTema">Tema <span class="required">*</span>:</label>
          <select name="codTema" id="codTema">
            <option value="" selected="selected">Seleccione un curso</option>
          </select>
        </p>
        <p>
          <label for="archivo">Archivo <span class="required">*</span>:</label>
           <input name="archivo" type="file" id="archivo" /> 
        </p>
      </fieldset>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Importar</button>
      </p>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>
