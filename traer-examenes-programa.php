<?php
/**
 * Trae exámenes.
 */
	require_once('home.php');
	require_once('redirect.php');
  
  $codCurso = $_POST['codCurso'];
	
  $examenes = get_examen_programa_rendidos($codCurso, get_option('semestre_actual'));
?>
<?php if (count($examenes) > 0) : ?>
<?php foreach($examenes as $examen) : ?>
<option value="<?php print $examen['codExamen']?>"><?php print $examen['nombre']?></option>
<?php endforeach; ?>
<?php endif; ?>