<?php

require_once('home.php');
//require_once('redirect.php');

$id = ! empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
// Clave principal
$bcdb->current_field = 'codPregunta';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
  $pregunta = array(
    'codPregunta' => $id,
    'enunciado' => $_POST['enunciado'],
    'nivel' => $_POST['nivel'],
  );

  // Verificación
  if (empty($pregunta['enunciado'])) :
    $error = true;
    $msg = "Ingrese la información obligatoria.";
  else :
    $pregunta = array_map('strip_tags', $pregunta);
  
    // Guarda la pregunta
    save_item($id, $pregunta, $bcdb->pregunta);

    if ($id) :
      $bcdb->current_field = 'codAlternativa';
      $alternativas = $_POST['detalle'];
      foreach($alternativas as $k => $detalle) {
        $alternativa = array();
        $alternativa['detalle'] = $detalle;
        $alternativa['codPregunta'] = $id;
        $alternativa['codAlternativa'] = $_POST['codAlternativa'][$k];
        $alternativa['correcta'] = 'N';
        if ((int)$_POST['correcta'] == $_POST['codAlternativa'][$k]) $alternativa['correcta'] = 'S';
        $alternativa = array_map('strip_tags', $alternativa);
        if (!empty($alternativa['detalle'])) :
          save_item($_POST['codAlternativa'][$k], $alternativa, $bcdb->alternativa);
        endif;
      }
    endif;

    if($id) :
      $msg = "La información se guardó correctamente.";
      $id = 0;
      header("Location: preguntas.php?saved=1");
      exit();
    else:
      $error = true;
      $msg = "Hubo un error al guardar la información, intente nuevamente.";
    endif;
  endif;
endif;

$pregunta = array();
if($id) {
  $pregunta = get_pregunta($id);
} else {
  header('Location: preguntas.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>text.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>960.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php print STYLES_URL; ?>layout.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.validate.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $('#frmpregunta').validate();
		$('enunciado').focus();
	});
</script>
<title>Preguntas | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <?php include "header.php"; ?>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <div id="sidebar">
      <h3>Preguntas</h3>
      <ul>
        <li><a href="/preguntas.php">Crear preguntas</a></li>
        <li><a href="/lista-preguntas.php">Lista de preguntas</a></li>
        <li><a href="/preguntas-importar.php">Importar preguntas</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Preguntas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmpregunta" id="frmpregunta" method="post" action="editar-preguntas.php">
      <fieldset class="collapsible">
        <legend>Información de la pregunta</legend>
        <p>
          <label for="codCurso">Curso:</label>
          <strong><?php print $pregunta['curso']['nombre']; ?></strong>
        </p>
        <p>
          <label for="codTema">Tema:</label>
          <strong><?php print $pregunta['tema']['nombre']; ?></strong>
        </p>
        <p>
          <label for="enunciado">Enunciado <span class="required">*</span>:</label><br/>
          <textarea name="enunciado" id="enunciado" class="required" cols="100"><?php print $pregunta['enunciado']; ?></textarea>
        </p>
        <p>
          <label for="nivel">Nivel <span class="required">*</span>:</label>
          <?php foreach($pniveles as $k => $nivel) : ?>
          <input type="radio" name="nivel" id="nivel<?php print $k; ?>" value="<?php print $k; ?>" <?php if ($k == $pregunta['nivel']) print 'checked="checked"'?> />
          <label for="nivel<?php print $k; ?>"><?php print $nivel; ?></label>
          <?php endforeach; ?>
          <input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
        </p>
      </fieldset>
      <fieldset class="collapsible">
        <legend>Alternativas</legend>
        <table>
          <caption>
          Alternativas
          </caption>
          <thead>
            <tr>
              <th>Opción</th>
              <th>¿Correcta?</th>
            </tr>
          </thead>
          <tbody id="alternativas">
            <?php $alt = "even"; ?>
              <?php $i = 0; ?>
              <?php foreach($pregunta['alternativas'] as $k => $alternativa) : ?>
              <tr class="<?php print $alt ?>">
                <td><input type="text" name="detalle[]" id="detalle<?php print $alternativa['codAlternativa']; ?>" size="60" tabindex="<?php print $i+5; ?>" value="<?php print $alternativa['detalle']; ?>" /></td>
                <th>
                  <input type="radio" name="correcta" id="correcta<?php print $alternativa['codAlternativa']; ?>" value="<?php print $alternativa['codAlternativa']; ?>" <?php if ($alternativa['correcta'] == 'S') print 'checked="checked"'?> />
                  <input type="hidden" name="codAlternativa[]" id="codAlternativa" value="<?php print $alternativa['codAlternativa']; ?>" />
                </th>
                <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
              </tr>
              <?php $i++; ?>
              <?php endforeach; ?>
          </tbody>
        </table>
      </fieldset>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>