<?php

require_once('home.php');
require_once('redirect.php');

$codExamen = isset($_GET['codExamen']) ? trim($_GET['codExamen']) : '';
$timestamp = isset($_GET['ts']) ? trim($_GET['ts']) : '';

if(empty($codExamen) || empty($timestamp)) {
  safe_redirect('mis-cursos.php');
}
$examen_programado = get_examen_programado($codExamen, strftime('%Y-%m-%d %H:%M:00', $timestamp));
if($examen_programado['rendido'] == 'S') {
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
  var codExamen = <?php print $codExamen; ?>;
  var ts = <?php print $timestamp; ?>;
  var d = <?php print $examen_programado['duracion']; ?>;
  var now = <?php print time(); ?>;  
  
  function finish() {
    location.href = '<?php print BASE_URL; ?>mis-notas.php';
  }
  
  function zero(n) {
    if(n.toString().length < 2) {
			return '0' + n;
		} else {
			return n;
		}
  }
  
	function timeLeft() {
    $.ajax({
      type: 'get',
      url: 'traer-countdown.php',
      success: function(response){
        now = response;
      }
    });
		// Total y restante.
    total = ts + d;
    //console.log(total);
    restante = total - now;
    if(restante <= 1) finish();
    segundos = zero(restante % 60);
    minutos = zero(Math.floor(restante / 60) % 60);
    horas = zero(Math.floor(restante / 3600));
    return horas + ':' + minutos + ':' + segundos;
    
	};
  
  // Implementation for "Index Of".
  if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 0) {
            n = Number(arguments[1]);
            if (n != n) { // shortcut for verifying if it's NaN
                n = 0;
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= len) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k;
            }
        }
        return -1;
    }
  }
	
	$(document).ready(function() {
		$('#countdown strong').html(timeLeft());
		
		setInterval(function(){
			$('#countdown strong').html(timeLeft());
		}, 1000);
    
    var simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
    var alternativas = [];
    <?php
      // Control de alternativas marcadas.
      if(count($marcadas) > 0) :
        foreach($marcadas as $j => $marcada) :
    ?>
        alternativas.push('<?php print $enunciados[$j]; ?>');
        $('#<?php print $marcada; ?>').attr('checked', true);
    <?php
        endforeach;
      endif;
    ?>
    $('.alternativa .radio').click(function () {
      // Opción que indica si actualizar o insertar.
      op = 'insert';
      // Tomamos la alternativa y su valor
      myRadio = $(this);
      codAlternativa = myRadio.val();
      codPregunta = myRadio.parent().parent().parent().attr('rel');
      // Verificamos si alguna alternativa de esa pregunta ha sido marcada.
      iO = alternativas.indexOf(myRadio.attr('name'));
      if (iO > -1) {
        op = 'update';
        delete alternativas[iO];
      }
      alternativas.push(myRadio.attr('name'));

      // Ajax loader
      $(this).next().after(simg);
      
      // Guardamos la respuesta
      $.ajax({
        type: 'POST',
        url: 'guardar-alternativa.php',
        data: 'codExamen=' + codExamen 
          + '&ts=' + ts 
          + '&codAlternativa=' + codAlternativa 
          + '&codPregunta=' + codPregunta
          + '&op=' + op,
        success: function(response){
          //console.log(response);
          $('#simg').remove();
        },
        error: function() {
          $('#simg').remove();
          alert('Se produjo un error al guardar la respuesta, intente otra vez.');
          myRadio.removeAttr('checked');
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
  <div id="content" class="grid_16">
    <h1>Rendir examen</h1>
    <fieldset>
      <legend><?php print $examen['nombre']; ?></legend>
      <ol>
      <?php $alt = "even"; ?>
      <?php foreach($preguntas as $k => $pregunta) : ?>
        <li class="enunciado <?php print $alt ?>" rel="<?php print $pregunta['codPregunta']; ?>"><?php print $pregunta['enunciado']; ?>
          <?php if(!empty($pregunta['imagen'])) : ?>
          <div class="c-imagen-pregunta">
            <img src="archivo/<?php print $pregunta['imagen']; ?>" alt="<?php print $pregunta['enunciado']; ?>" class="imagen-pregunta" />
          </div>
          <?php endif; ?>
          <ol>
          <?php
            $alternativas = get_alternativas_de_pregunta($pregunta['codPregunta']);
            foreach ($alternativas as $j => $alternativa):
          ?>
          <li class="alternativa">
            <input type="radio" name="alternativa<?php print $pregunta['codPregunta']; ?>" class="radio" id="alternativa<?php print $alternativa['codAlternativa']; ?>" value="<?php print $alternativa['codAlternativa']; ?>" />
            <label for="alternativa<?php print $alternativa['codAlternativa']; ?>"><?php print $alternativa['detalle']; ?></label>
          </li>
          <?php endforeach; ?>
          </ol>
        </li>
      <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
      <?php endforeach; ?>
      </ol>
      <p class="align-center"><button class="button" type="button" id="terminar" onclick="finish();">Terminar examen</button></p>
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