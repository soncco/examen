<?php

require_once('home.php');
//require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codTema';

$postback = isset($_POST['submit']);
$error = false;

// Si es que el formulario se ha enviado
if($postback) :
  $tema = array(
    'nombre' => $_POST['nombre'],
    'codCurso' => $_POST['codCurso'] 
  );

  // Verificación
  if (empty($tema['nombre'])) :
    $error = true;
    $msg = "Ingrese la información obligatoria.";
  else :

    $tema = array_map('strip_tags', $tema);
  
    // Guarda el tema
    $id = save_item(0, $tema, $bcdb->tema);

    if($id) :
      $msg = "La información se guardó correctamente.";
    else:
      $error = true;
      $msg = "Hubo un error al guardar la información, intente nuevamente.";
    endif;
  endif;
endif;

$temas = get_items($bcdb->tema);
$cursos = get_items($bcdb->curso, 'codCurso');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" /> 
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".click").editable("/datos-maquina.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
    $(".clicks").editable("/datos-maquina.php", { 
      indicator : "Guardando...",
      loadurl   : "/scripts/operadores.php",
      type   : "select",
      submit : "OK",
      style  : "inherit",
      submitdata : function() {
        return {op : true};
      }
    });
    
		$('#nombre').focus();
	});
</script>
<title>Temas | Sistema de exámenes</title>
</head>

<body>
<div class="container_16">
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Sistema de exámenes</span></a> </h1>
    <?php include "menutop.php"; ?>
    <?php if(isset($_SESSION['loginuser'])) : ?>
    <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
    <?php endif; ?>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Temas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmtema" id="frmtema" method="post" action="temas.php">
      <fieldset class="collapsible">
      <legend>Información del tema</legend>
      <p>
        <label for="nombre">Nombre <span class="required">*</span>:</label>
        <input type="text" name="nombre" id="nombre" maxlength="60" size="45" />
      </p>
      <p>
        <label for="codCurso">Curso <span class="required">*</span>:</label>
        <select name="codCurso" id="codCurso">
          <option value="" selected="selected">Seleccione un curso</option>
          <?php foreach ($cursos as $k => $curso) : ?>
          <option value="<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
      <p class="align-center">
        <button type="submit" name="submit" id="submit">Guardar</button>
      </p>
      </fieldset>
    </form>
    <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
      <legend>Temas existentes</legend>
      <p class="war">Los temas se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
      <table>
        <thead>
          <tr>
          <th>Tema</th>
          <th>Curso</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($temas): ?>
          <?php $alt = "even"; ?>
          <?php foreach($temas as $k => $tema): ?>
          <tr class="<?php print $alt ?>">
            <th><span class="click" id="nombre-<?php print $tema['codTema']; ?>"><?php print $tema['nombre']; ?></span></td>
            <td><span class="clicks" id="codCurso-<?php print $tema['codCurso']; ?>"><?php print get_var_from_field('nombre', 'codCurso', $tema['codCurso'], $bcdb->curso) ?></span></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <td colspan="2">No existen datos</th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php include "pager.php"; ?>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>