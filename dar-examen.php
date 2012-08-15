<?php

require_once('home.php');
require_once('redirect.php');

$codExamen = isset($_GET['codExamen']) ? trim($_GET['codExamen']) : '';

if(empty($codExamen)) {
  safe_redirect('mis-cursos.php');
}

$examen = get_examen($codExamen);

$preguntas = get_preguntas_de_examen($codExamen);
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
	function timeLeft() {
		$.ajax({
			type: 'POST',
			url: 'traer-countdown.php',
      data: 'codExamen=<?php print $codExamen; ?>',
			success: function(response){
				$('#countdown strong').html(response);
			},
			error: function(){
				$('#simg').remove();
			},
			timeout: 5000
		});
	};
	
	$(document).ready(function() {
		timeLeft();
		
		setInterval(function(){
			timeLeft();
		}, 1000);
	});
</script>
<title>Exámenes | Sistema de exámenes</title>
</head>
<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="content" class="grid_16">
    <h1>Rendir examen</h1>
    <fieldset>
      <legend><?php print $examen['nombre']; ?></legend>
      <ol>
      <?php $alt = "even"; ?>
      <?php foreach($preguntas as $k => $pregunta) : ?>
        <li class="enunciado <?php print $alt ?>"><?php print $pregunta['enunciado']; ?>
          <ol>
          <?php
            $alternativas = get_alternativas_de_pregunta($pregunta['codPregunta']);
            foreach ($alternativas as $j => $alternativa):
          ?>
          <li class="alternativa">
            <input type="radio" name="alternativa<?php print $pregunta['codPregunta']; ?>" id="alternativa<?php print $alternativa['codAlternativa']; ?>" />
            <label for="alternativa<?php print $alternativa['codAlternativa']; ?>"><?php print $alternativa['detalle']; ?></label>
          </li>
          <?php endforeach; ?>
          </ol>
        </li>
      <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
      <?php endforeach; ?>
      </ol>
    </fieldset>
    <div id="countdown">
      Faltan <strong></strong>
    </div>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>