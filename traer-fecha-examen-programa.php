<?

	require_once('home.php');
	require_once('redirect.php');
  
  $codCurso = $_POST['codCurso'];
	
  $examenes = get_examenes_curso($codCurso);
  
?>
<? if (count($examenes) > 0) : ?>
<? foreach($examenes as $examen) : ?>
<option value="<?php print $examen['codExamen']?>"><?php print $examen['nombre']?></option>
<? endforeach; ?>
<? endif; ?>