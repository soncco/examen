<?php
	$menutop = array(
		"/temas.php" => "Temas",
		"/opciones.php" => "Opciones",
		"/usuarios.php" => "Usuarios"
	);
	
?>
<div id="menutop">
  <ul>
    <li><a href="temas.php" class="<?php if($self=='/temas.php') print "active"; ?>">Temas</a></li>
    <li><a href="cursos.php" class="<?php if($self=='/cursos.php') print "active"; ?>">Cursos</a></li>
    <li><a href="preguntas.php" class="<?php if($self=='/preguntas.php') print "active"; ?>">Preguntas</a></li>
    <li><a href="opciones.php" class="<?php if($self=='/opciones.php') print "active"; ?>">Opciones</a></li>
    <li><a href="usuarios.php" class="<?php if($self=='/usuarios.php') print "active"; ?>">Usuarios</a></li>
  </ul>
</div>