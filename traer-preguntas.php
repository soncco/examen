<?php
/**
 * Trae preguntas por temas y nivel.
 */
	require_once('home.php');
	require_once('redirect.php');
  
  $temas = explode(',', $_POST['temas']);
  $niveles = (empty($niveles)) ? array_keys($pniveles): explode(',', $_POST['niveles']);
  $op = (isset($_POST['op'])) ? $_POST['op'] : '';
  
  if ($op == 'lista') {
    $codCurso = $_POST['codCurso'];
    $temasx = get_temas_curso($codCurso, $_SESSION['loginuser']['codDocente']);
    $temas = array();
    if ($temasx) {
      foreach ($temasx as $k => $v) {
        $temas[] = $v['codTema'];
      }
    }
    
    if (count($temas) == 0) :
?>
<p class="align-center">Todavía no existen preguntas. <a href="/preguntas.php">Crear preguntas</a>.</p>
<?php
    exit();
    endif;
  }
  
  $sql = sprintf("SELECT * FROM %s WHERE ", $bcdb->pregunta);
  
  if ($temas) :
    foreach ($temas as $tema)  :
      foreach ($niveles as $nivel) :
        $sql .= sprintf("(codTema = '%s' AND nivel = '%s') OR ", $tema, $nivel);
      endforeach;
    endforeach;
    $sql = substr($sql, 0, -4);
  endif;
  
  $preguntas = $bcdb->get_results($sql);
?>
<table>
  <thead>
    <tr>
      <?php if($op != 'lista') : ?>
      <th><!--input type="checkbox" name="all" id="all" /--></th>
      <?php endif; ?>
      <th>Pregunta</th>
      <th>Nivel</th>
      <th>Tema</th>
      <?php if($op != 'lista') : ?>
      <th>Puntaje</th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php $alt = "even"; ?>
    <?php if($preguntas) : ?>
    <?php foreach($preguntas as $k => $pregunta) : ?>
    <tr class="<?php print $alt ?>" title="<?php print $pregunta['enunciado']; ?>">
      <?php if($op != 'lista') : ?>
      <th><input type="checkbox" name="codPregunta[]" id="codPregunta" value="<?php print $pregunta['codPregunta']; ?>" /></th>
      <?php endif; ?>
      <th><?php print substr($pregunta['enunciado'], 0, 50) . "..."; ?></th>
      <td><?php print $pniveles[$pregunta['nivel']]; ?></td>
      <td><?php print get_var_from_field('nombre', 'codTema', $pregunta['codTema'], $bcdb->tema); ?></td>
      <?php if($op != 'lista') : ?>
      <td><input type="text" name="puntaje[]" id="puntaje<?php print $pregunta['codPregunta']; ?>" size="2" maxlength="5" disabled="disabled" class="required number" /></td>
      <?php endif; ?>
      <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <?php endforeach; ?>
    <?php else : ?>
    <tr class="<?php print $alt ?>">
      <th colspan="<?php print ($op == 'lista') ? 3 : 5; ?>">Todavía no existen preguntas. <a href="/preguntas.php">Crear preguntas</a>.</th>
    </tr>
    <?php endif; ?>
  </tbody>
</table>