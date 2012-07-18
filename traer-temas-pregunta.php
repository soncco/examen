<?php
/**
 * Trae exámenes.
 */
  require_once('home.php');
  require_once('redirect.php');

  $codCurso = $_POST['codCurso'];

  $temas = get_temas_curso($codCurso, $_SESSION['loginuser']['codDocente']);
?>
<?php if (count($temas) > 0) : ?>
<?php foreach($temas as $tema) : ?>
<option value="<?php print $tema['codTema']?>"><?php print $tema['nombre']?></option>
<?php endforeach; ?>
<?php endif; ?>
