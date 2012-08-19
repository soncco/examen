<?php

  require_once('home.php');
  require_once('redirect.php');
  
  $error = false;
  
  $postback = isset($_POST['submit']);

  if ($postback) :
    
    $bcdb->current_field = 'codExamen';
  
    $preguntas = $_POST['codPregunta'];
    $puntajes = $_POST['puntaje'];
  
    $examen = array(
      'nombre' => $_POST['nombre'],
    );
  
    $examen = array_map('strip_tags', $examen);
    $id = save_item(0, $examen, $bcdb->examen);
    
    if ($id > 0) :
      foreach ($preguntas as $k => $pregunta) {
        $examen_pregunta = array(
          'codExamen' => $id,
          'codPregunta' => $pregunta,
          'puntaje' => $puntajes[$k],
        );
        save_examen_pregunta($examen_pregunta);
      }
      $msg = "El examen se guardó correctamente.";
    else :
      $error = true;
    $msg = "No se pudo guardar el examen.";
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
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('codCurso').focus();
    
    simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
    
    // Combos dependientes.
		$('#codCurso').change(function () {
      codCurso = $(this).val();
      if (codCurso != '') {
        $(this).after(simg);
        $.ajax({
          type: 'POST',
          url: 'traer-temas-pregunta.php',
          data: 'complete=true&codCurso=' + codCurso,
          success: function(response){
            $('#temas').fadeIn().html(response);
            $('#traer').fadeIn();
            $('#simg').remove();
          }
        });
      } else {
        $('#temas').html('');
        $('#traer').hide();
      }
    });
    
    $('#button-traer').click(function() {
      // Recogemos los temas y niveles.
      temas = [];
      niveles = [];
      k = 0;
      $('#codTema option:selected').each(function(){ k++; temas.push($(this).val()); });
      $('#nivel option:selected').each(function(){ niveles.push($(this).val()); });
      
      if (k == 0) {
        alert('Escoje al menos un tema.');
        return;
      }
      
      // Juntamos los temas y niveles.
      temas = temas.join(',');
      niveles = niveles.join(',');      

      $(this).after(simg);
      $.ajax({
        type: 'POST',
        url: 'traer-preguntas.php',
        data: 'temas='+ temas +'&niveles=' + niveles,
        success: function(response){
          $('#preguntas').fadeIn();
          $('#preguntas-content').fadeIn().html(response);
          $('#simg').remove();
          $('#frmexamen').validate();
          // Habilita el textbox relativo al checkbox.
          $('#preguntas-content input[type="checkbox"]').click(function () {
            inp = $(this).parent().parent().find('input[type="text"]');
            inp.attr('disabled', !$(this).attr('checked'));
            $('#frmexamen').validate();
          })
        }
      });
    });    
    
	});
</script>
<title>Exámenes | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <div id="sidebar">
      <h3>Exámenes</h3>
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
    <form name="frmexamen" id="frmexamen" method="post" action="examenes.php">
      <fieldset class="collapsible">
        <legend>Crear Examen</legend>
        <p class="help">Primero escoja un curso y se le mostrarán la lista de temas disponibles. Puede escoger más de un tema para crear el examen.</p>
        <p>
          <label for="codCurso">Curso <span class="required">*</span>:</label>
          <select name="codCurso" id="codCurso">
            <option value="" selected="selected">Seleccione un curso</option>
            <?php foreach ($cursos as $k => $curso) : ?>
            <option value="<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></option>
            <?php endforeach; ?>
          </select>
        </p>
        <div id="temas" style="display: none;"></div>
        <p id="traer" class="align-center" style="display: none;">
          <button id="button-traer" name="button-traer" type="button">Traer preguntas</button>
        </p>
      </fieldset>
      <fieldset id="preguntas" style="display: none;">
        <legend>Preguntas</legend>
        <p>
          <label for="nombre">Nombre del examen <span class="required">*</span>:</label>
          <input type="text" name="nombre" id="nombre" maxlength="60" size="40" value="" class="required" />        	
        </p>
        <div id="preguntas-content">
          
        </div>
        <p class="align-center">
          <button type="submit" name="submit" id="submit">Crear Examen</button>
        </p>
      </fieldset>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>