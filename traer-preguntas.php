<?php
/**
 * Trae preguntas por temas y nivel.
 */
	require_once('home.php');
	require_once('redirect.php');
  
  $temas = explode(',', $_POST['temas']);
  $niveles = (empty($niveles)) ? array_keys($pniveles): explode(',', $_POST['niveles']);
  
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
      <th><!--input type="checkbox" name="all" id="all" /--></th>
      <th>Pregunta</th>
      <th>Nivel</th>
      <th>Tema</th>
      <th>Puntaje</th>
    </tr>
  </thead>
  <tbody>
    <?php $alt = "even"; ?>
    <?php if($preguntas) : ?>
    <?php foreach($preguntas as $k => $pregunta) : ?>
    <tr class="<?php print $alt ?>" title="<?php print $pregunta['enunciado']; ?>">
      <th><input type="checkbox" name="codPregunta[]" id="codPregunta" value="<?php print $pregunta['codPregunta']; ?>" /></th>
      <th><?php print substr($pregunta['enunciado'], 0, 50) . "..."; ?></th>
      <td><?php print $pniveles[$pregunta['nivel']]; ?></td>
      <td><?php print get_var_from_field('nombre', 'codTema', $pregunta['codTema'], $bcdb->tema); ?></td>
      <td><input type="text" name="puntaje[]" id="puntaje<?php print $pregunta['codPregunta']; ?>" size="2" maxlength="5" disabled="disabled" class="required number" /></td>
      <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <?php endforeach; ?>
    <?php else : ?>
    <tr class="<?php print $alt ?>">
      <th colspan="5">Todavía no existen preguntas. <a href="/preguntas.php">Crear preguntas</a>.</th>
    </tr>
    <?php endif; ?>
  </tbody>
</table>