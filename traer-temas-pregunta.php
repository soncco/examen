<?php
/**
 * Trae exámenes.
 */
  require_once('home.php');
  require_once('redirect.php');

  $codCurso = $_POST['codCurso'];  
  $complete = isset($_POST['complete']);

  $temas = get_temas_curso($codCurso, $_SESSION['loginuser']['codDocente']);
?>
<?php if (count($temas) > 0) : ?>
  <?php if ($complete) : ?>
    <p>
      <select name="codTema[]" id="codTema" multiple="multiple" size="5">
      <?php foreach($temas as $tema) : ?>
      <option value="<?php print $tema['codTema']?>"><?php print $tema['nombre']?></option>
      <?php endforeach; ?>
      </select>
      <select name="nivel[]" id="nivel" multiple="multiple" size="5">
      <?php foreach($pniveles as $k => $nivel) : ?>
      <option value="<?php print $k; ?>"><?php print $nivel; ?></option>
      <?php endforeach; ?>
      </select>
    </p>
  <?php else : ?>
    <?php foreach($temas as $tema) : ?>
    <option value="<?php print $tema['codTema']?>"><?php print $tema['nombre']?></option>
    <?php endforeach; ?>
  <?php endif; ?>
<?php endif; ?>
