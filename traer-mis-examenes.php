<?php

require_once('home.php');
require_once('redirect.php');

$codCurso = $_POST['codCurso'];
$examenes = get_examenes_pendientes_de_alumno($_SESSION['loginuser']['codAlumno'], $codCurso, get_option('semestre_actual'));

?>
<table>
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Fecha y Hora</th>
      <th>Duración</th>
      <th>Comienza en</th>
    </tr>
  </thead>
  <tbody>
    <? $alt = "even"; ?>
    <? foreach($examenes as $k => $examen) : ?>
    <tr class="<?= $alt ?>" title="<?= $examen['examen']; ?>">
      <th><?= $examen['examen'] ?></th>
      <td><?= $examen['fecha']; ?></td>
      <td><?= $examen['duracion'] ?></td>
      <td><?= $examen['comienzo'] ?></td>
      <? $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <? endforeach; ?>
  </tbody>
</table>