<?php
  $menu = array(
      'admin' => array(
          'top' => array(
            'cursos.php' => 'Cursos',
            'semestres.php' => 'Semestres',
            'usuarios.php?rol=docente' => 'Docentes',
            'asignar-docente.php' => 'Asignar docente',
            'opciones.php' => 'Opciones'
          ),
          'side' => array(
          )
      ),
      'docente' => array(
          'top' => array(
            'temas.php' => 'Temas',
            'preguntas.php' => 'Preguntas',
            'examenes.php' => 'Exámenes',
            'cursos-docente.php' => 'Cursos'
          ),
          'side' => array(
            'preguntas.php' => 'Crear Preguntas',
            'lista-preguntas.php' => 'Lista de Preguntas'
          )
      )
  );
          
  $session_active = isset($_SESSION['loginuser']);
?>
<div id="menutop">
  <ul>
    <?php if ($session_active) : ?>
    <?php foreach ($menu[$_SESSION['loginuser']['rol']]['top'] as $menu_url => $menu_name) : ?>
    <li><a href="<?php print $menu_url; ?>" class="<?php if($self == '/' . $menu_url) print "active"; ?>"><?php print $menu_name; ?></a></li>
    <?php endforeach; ?>
    <?php else: ?>
    <li><a href="login.php?rol=docente">Docentes</a></li>
    <li><a href="login.php?rol=alumno">Alumnos</a></li>
    <li><a href="login.php?rol=admin">Administrador</a></li>
    <?php endif; ?>
  </ul>
</div>