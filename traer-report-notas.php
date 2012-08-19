<?php

require_once('home.php');
require_once('redirect.php');

$codCurso = $_POST['codCurso'];
$codExamen = $_POST['codExamen'];
$fecha = base64_decode($_POST['fecha']);

$notas = get_notas_examen($codCurso, get_option('semestre_actual'), $codExamen, $fecha, '0');

?>
<table>
  <thead>
    <tr>
      <th style="width: 15%;">Código</th>
      <th style="width: 80%;">Apellidos y Nombres</th>
      <th style="width: 15%;">Nota</th>
    </tr>
  </thead>
  <tbody>
	<? if($notas) : ?>
		<? foreach($notas as $k => $nota) : ?>
	    <tr>
	      <th class="align-center"><?= $nota['codAlumno'] ?></th>
	      <td class="align-left"><? print $nota['apellidoP'] . "-" . $nota['apellidoM'] . "-" . $nota['nombres']; ?></td>
	      <td class="align-center"><?= $nota['nota'] ?></td>
	    </tr>
	  <? endforeach; ?>
    <? else : ?>
    <tr>
      <th colspan="4">No existen datos para los parámetros indicados. "<?= $codCurso ?>" "<?= $codExamen ?>" "<?= $fecha ?>"</th>
    </tr>
    <? endif; ?>
  </tbody>
</table>