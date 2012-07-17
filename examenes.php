<?php

require_once('home.php');
//require_once('redirect.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		//$('codTema').focus();
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
      <h3>Preguntas</h3>
      <ul>
        <li><a href="/preguntas.php">Crear exámenes</a></li>
        <li><a href="/ver-examenes.php">Lista de examenes</a></li>
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
        <legend>Información de la pregunta</legend>
        <p>
          <label for="codTema">Tema <span class="required">*</span>:</label><br />
          <select name="codTema[]" id="codTema" multiple="multiple">
            <option value="" selected="selected">Seleccione un tema</option>
            <?php foreach ($temas as $k => $tema) : ?>
            <option value="<?php print $tema['codTema']; ?>"
              <?php
              if (count($pregunta) > 0) {
                if($tema['codTema'] == $pregunta['codTema']) 
                  print 'selected="selected"';
              }
              ?>>
                  <?php print $tema['nombre']; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </p>
        <p>
          <label for="nivel">Nivel <span class="required">*</span>:</label>
          <?php foreach($pniveles as $k => $nivel) : ?>
          <input type="checkbox" name="nivel[]" id="nivel<?php print $k; ?>" value="<?php print $k; ?>" <?php if ($k == 'N') print 'checked="checked"'?> />
          <label for="nivel<?php print $k; ?>"><?php print $nivel; ?></label>
          <?php endforeach; ?>
        </p>
      </fieldset>
      <fieldset class="collapsible">
        <legend>Resultados</legend>
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