<?php
/**
 * Inicio
 */
	require_once('home.php');
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
		$("#username").focus();
	});
</script>
<title>Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Sistema de exámenes</span></a> </h1>
    <div id="menutop">
      <ul>
        <li><a href="login.php?rol=docente">Docentes</a></li>
        <li><a href="login.php?rol=alumno">Alumnos</a></li>
        <li><a href="login.php?rol=admin">Administrador</a></li>
      </ul>
    </div>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/login.png" alt="Ingresar" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Bienvenido</h1>
    <div id="welcome">
      <div class="welcome-icon">
        <a href="login.php?rol=docente">
          <img src="/images/teacher.png" alt="Docentes" width="180" />
          <span>Ingreso a Docentes</span>
        </a>
      </div>
      <div class="welcome-icon">
        <a href="login.php?rol=alumno">
          <img src="/images/student.png" alt="Estudiantes" width="180" />
          <span>Ingreso a Alumnos</span>
        </a>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>