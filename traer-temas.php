<?php
/**
 * Trae temas por Curso.
 */
	require_once('home.php');
	require_once('redirect.php');
  
  $codCurso = $_POST['codCurso'];
  
  // El Curso.
  $bcdb->current_field = 'codCurso';
  $curso = get_item($codCurso, $bcdb->curso);
  
  // Los temas.
  $temas = get_temas_curso($codCurso);
?>
<table>
  <caption>Temas del Curso <?php print $curso['nombre']; ?></caption>
  <thead>
      <tr>
        <th>Tema</th>
      </tr>
  </thead>
  <tbody>
      <?php if ($temas): ?>
      <?php $alt = "even"; ?>
      <?php foreach($temas as $k => $tema): ?>
        <tr class="<?php print $alt ?>">
          <td><?php print $tema['nombre']; ?></td>
        </tr>
      <?php endforeach; ?>
      <?php else: ?>
        <tr class="<?php print $alt; ?>">
          <th>No se han creado temas para este curso. <a href="temas.php">Agregar temas</a>.</th>
        </tr>
      <?php endif; ?>
  </tbody>
</table>