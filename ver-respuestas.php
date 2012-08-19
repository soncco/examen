<?php

require_once('home.php');
require_once('redirect.php');

$codExamen = isset($_GET['codExamen']) ? trim($_GET['codExamen']) : '';
$timestamp = isset($_GET['ts']) ? trim($_GET['ts']) : '';

if(empty($codExamen) || empty($timestamp)) {
  safe_redirect('mis-notas.php');
}
$examen_programado = get_examen_programado($codExamen, strftime('%Y-%m-%d %H:%M:00', $timestamp));
if($examen_programado['rendido'] == 'N') {
  safe_redirect('mis-notas.php');
}

$examen = get_examen($codExamen);
$preguntas = get_preguntas_de_examen($codExamen);

// Control de respuestas marcadas.
$respuestas = get_respuestas_alumno($examen_programado, $_SESSION['loginuser']['codAlumno']);

$enunciados = array();
$marcadas = array(); 
if (count($respuestas) > 0) {
  foreach($respuestas as $k => $respuesta) {
    $enunciados[] = "alternativa" . $respuesta['codPregunta'];
    $marcadas[] = "alternativa" . $respuesta['codAlternativa'];
  }
}
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
<script type="text/javascript">
	
	$(document).ready(function() {
		
    <?php
      // Control de alternativas marcadas.
      if(count($marcadas) > 0) :
        foreach($marcadas as $j => $marcada) :
    ?>
        $('#<?php print $marcada; ?>').html('Marcada por alumno');
    <?php
        endforeach;
      endif;
    ?>
	});
</script>
<title>Exámenes | Sistema de exámenes</title>
</head>
<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="content" class="grid_16">
    <h1>Respuestas de examen</h1>
    <fieldset>
      <legend><?php print $examen['nombre']; ?></legend>
      <ol>
      <?php $alt = "even"; ?>
      <?php foreach($preguntas as $k => $pregunta) : ?>
        <li class="enunciado <?php print $alt ?>" rel="<?php print $pregunta['codPregunta']; ?>"><?php print $pregunta['enunciado']; ?>
          <ol>
          <?php
            $alternativas = get_alternativas_de_pregunta($pregunta['codPregunta']);
            foreach ($alternativas as $j => $alternativa):
          ?>
          <li class="alternativa">
            <label for="alternativa<?php print $alternativa['codAlternativa']; ?>">
              <?php print $alternativa['detalle']; ?>
              <?php if($alternativa['correcta'] == 'S') : ?>
              <strong class="correcta">(Respuesta correcta)</strong>
              <?php endif; ?>
            </label>
            <strong id="alternativa<?php print $alternativa['codAlternativa']; ?>" class="marcada"></strong>
          </li>
          <?php endforeach; ?>
          </ol>
        </li>
      <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
      <?php endforeach; ?>
      </ol>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>