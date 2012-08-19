<?php

require_once('home.php');
require_once('redirect.php');

$codExamen = isset($_GET['codExamen']) ? trim($_GET['codExamen']) : '';

$examen = get_examen($codExamen);
$preguntas = get_preguntas_de_examen($codExamen);
$curso = get_curso_de_examen($codExamen);

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
</script>
<title>Exámenes | Sistema de exámenes</title>
</head>
<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="content" class="grid_16">
    <h1>Ver examen</h1>
    <p class="help"><a href="examenes.php">&larr; Regresar a la lista de exámenes</a></p>
    <fieldset>
      <legend><?php print $examen['nombre']; ?> - <?php print $curso[0]['nombre']; ?></legend>
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