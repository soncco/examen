<?php

require_once('home.php');
require_once('redirect.php');

$codCurso = $_POST['codCurso'];
$examenes = get_examenes_pendientes_de_alumno($_SESSION['loginuser']['codAlumno'], $codCurso, get_option('semestre_actual'));

?>
<table>
  <thead>
    <tr>
      <th style="width: 52%;">Nombre</th>
      <th style="width: 23%;">Fecha y Hora</th>
      <th style="width: 10%;">Duración</th>
      <th style="width: 15%;">Comienza en</th>
    </tr>
  </thead>
  <tbody>
    <? $alt = "even"; ?>
    <? if($examenes) : ?>
    <? foreach($examenes as $k => $examen) : ?>
    <tr class="<?= $alt ?>" title="<?= $examen['examen']; ?>">
      <th><?= $examen['examen'] ?></th>
      <td style=" text-align: center;"><?= $examen['fecha']; ?></td>
      <td style=" text-align: center;"><?= $examen['duracion'] ?></td>
      <td style=" text-align: center;">
        <?
          if (substr($examen['comienzo'], 0, 1) == "-") {
          	?><a href="#"><?= 'Dar examen' ?></a><?
          } else {
          	echo $examen['comienzo'];
          }
        ?>
      </td>
      <? $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <? endforeach; ?>
    <? else : ?>
    <tr class="<?= $alt ?>">
      <th colspan="4">No existen exámenes pendientes.</th>
    </tr>
    <? endif; ?>    
  </tbody>
</table>