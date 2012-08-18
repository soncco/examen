<?php

require_once('home.php');
require_once('redirect.php');

$cursos = get_cursos_con_examenes_pendientes($_SESSION['loginuser']['codAlumno'], get_option('semestre_actual'));

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
	<? if($cursos) : ?>
		<? foreach($cursos as $k => $curso) : ?>
		<tr class="odd">
			<th colspan="5"><?= $curso[nombre]; ?> (<?= $curso[codCurso]; ?>)</th>
		</tr>
		<? $examenes = $cursos = get_examenes_pendientes_de_alumno($_SESSION['loginuser']['codAlumno'], $curso['codCurso'], get_option('semestre_actual')); ?>
	    <? if($examenes) : ?>
		    <? foreach($examenes as $k => $examen) : ?>
		    <tr title="<?= $curso[nombre]; ?> (<?= $curso[codCurso]; ?>) - <?= $examen['examen']; ?>">
		      <th style="text-indent: 0.5cm;"><?= $examen['examen'] ?></th>
		      <td class="align-center"><?= $examen['fecha']; ?></td>
		      <td class="align-center"><?= $examen['duracion'] ?></td>
		      <td class="align-center">
		        <?
		          if (substr($examen['comienzo'], 0, 1) == "-") {
		          	?><a href="dar-examen.php?codExamen=<?php print $examen['codExamen']; ?>&ts=<?php print strtotime($examen['fecha']); ?>"><?= 'Dar examen' ?></a><?
		          } else {
		          	echo $examen['comienzo'];
		          }
		        ?>
		      </td>
		    </tr>
			<? endforeach; ?>
	    <? else : ?>
	    <tr>
	      <th colspan="4">No existen exámenes pendientes.</th>
	    </tr>
	    <? endif; ?>
	  <? endforeach; ?>
    <? else : ?>
    <tr>
      <th colspan="4">No existen cursos con exámenes pendientes.</th>
    </tr>
    <? endif; ?>
  </tbody>
</table>