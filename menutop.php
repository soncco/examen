<?php
  $menu = array(
      'admin' => array(
          'top' => array(
            'cursos.php' => 'Cursos',
            'semestres.php' => 'Semestres',
            'docentes.php' => 'Docentes',
            'asignar-docente.php' => 'Asignar docente',
            'alumnos.php' => 'Alumnos',
            'opciones.php' => 'Opciones',
          ),
          'side' => array(
          )
      ),
      'docente' => array(
          'top' => array(
            'temas.php' => 'Temas',
            'preguntas.php' => 'Preguntas',
            'examenes.php' => 'Exámenes',
            'report-notas.php' => 'Reportes',
            'mis-datos.php' => 'Mis datos',
          ),
          'side' => array(
            'preguntas.php' => 'Crear Preguntas',
            'lista-preguntas.php' => 'Lista de Preguntas'
          )
      ),
      'alumno' => array(
          'top' => array(
            'mis-cursos.php' => 'Cursos',
            'mis-examenes.php' => 'Exámenes',
            'mis-notas.php' => 'Notas',
            'mis-datos-a.php' => 'Mis datos',
          ),
          'side' => array(
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