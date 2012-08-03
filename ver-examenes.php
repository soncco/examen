<?php

require_once('home.php');
require_once('redirect.php');

// Trae los exámenes
$examenes = get_examenes_docente($_SESSION['loginuser']['codDocente']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/theme/ui.all.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.validate.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript" src="/scripts/jquery.calendar.js"></script>
<script type="text/javascript" src="/scripts/jquery.ui.all.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	});
</script>
<title>Exámenes | Sistema de exámenes</title>
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
      <h3>Exámenes</h3>
      <ul>
        <li><a href="/examenes.php">Crear exámen</a></li>
        <li><a href="/ver-examenes.php">Lista de examenes</a></li>
        <li><a href="/examen-programa.php">Programar Examen</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Exámenes</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <fieldset>
      <legend>Exámenes existentes</legend>
      <table>
        <thead>
          <tr>
            <th>Nombre del examen</th>
            <th>Curso</th>
            <th colspan="2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($examenes): ?>
          <?php $alt = "even"; ?>
          <?php foreach($examenes as $k => $examen): ?>
          <tr class="<?php print $alt ?>">
            <th><?php print $examen['nombre']; ?></th>
            <td><?php print get_var_from_field('nombre', 'codCurso', $examen['codCurso'], $bcdb->curso); ?></td>
            <td><a href="ver-examen.php?id=<?php print $examen['codExamen']; ?>">Ver</a></td>
            <td><a href="print-examen.php?id=<?php print $examen['codExamen']; ?>">Imprimir</a></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <th colspan="3">No existen datos</th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php include "pager.php"; ?>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>