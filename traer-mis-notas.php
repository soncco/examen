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
      <th style="width: 5%;">Nota</th>
      <th style="width: 5%;">Solución</th>
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
		    <tr title="<?= $curso[nombre]; ?> (<?= $curso[codCurso]; ?>) - <?= $examen['examen']; ?>">
		    	<? $notas = get_nota_examen($_SESSION['loginuser']['codAlumno'], $examen['codExamen'], $examen['fecha']) ?>
		      <th style="text-indent: 0.5cm;"><?= $examen['examen'] ?></th>
		      <td class="align-center"><?= $examen['fechaF']; ?></td>
		      <td class="align-center"><?= str_pad($notas[0]['nota'], 2, '0', STR_PAD_LEFT) ?></td>
		      <td class="align-center"><a href="#">Ver</a></td>
		    </tr>
			<? endforeach; ?>
	    <? else : ?>
	    <tr>
	      <th colspan="4">No existen exámenes concluidos.</th>
	    </tr>
	    <? endif; ?>
	  <? endforeach; ?>
    <? else : ?>
    <tr>
      <th colspan="4">No existen cursos con exámenes concluidos.</th>
    </tr>
    <? endif; ?>
  </tbody>
</table>