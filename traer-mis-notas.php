<?php

require_once('home.php');
require_once('redirect.php');

$cursos = get_cursos_con_examenes_rendidos($_SESSION['loginuser']['codAlumno'], get_option('semestre_actual'));

?>
<table>
  <thead>
    <tr>
      <th style="width: 50%;">Nombre</th>
      <th style="width: 20%;">Fecha y Hora</th>
      <th style="width: 5%;">Correctas</th>
      <th style="width: 5%;">Nota</th>
    </tr>
  </thead>
  <tbody>
	<? if($cursos) : ?>
		<? foreach($cursos as $k => $curso) : ?>
		<tr class="odd">
			<th colspan="5"><?= $curso[nombre]; ?> (<?= $curso[codCurso]; ?>)</th>
		</tr>
		<? $examenes = get_examenes_rendidos_de_alumno($_SESSION['loginuser']['codAlumno'], $curso['codCurso'], get_option('semestre_actual')); ?>
	    <? if($examenes) : ?>
		    <? foreach($examenes as $k => $examen) : ?>
		    <tr title="<?= $examen['examen']; ?>">
		    	<? $notas = get_nota_examen($_SESSION['loginuser']['codAlumno'], $examen['codExamen'], $examen['fecha']) ?>
		      <th style="text-indent: 0.5cm;"><?= $examen['examen'] ?></th>
		      <td class="align-center"><?= $examen['fechaF']; ?></td>
		      <td class="align-center"><?= $notas[0]['correctas'] ?></td>
		      <td class="align-center"><?= $notas[0]['nota'] ?></td>
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