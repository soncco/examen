<?php

require_once('home.php');
require_once('redirect.php');

// Clave principal
$bcdb->current_field = 'codPregunta';

$preguntas = get_items($bcdb->pregunta, 'codPregunta');

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
	});
</script>
<title>Preguntas | Sistema de exámenes</title>
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
    <div id="sidebar">
      <h3>Preguntas</h3>
      <ul>
        <li><a href="/preguntas.php">Crear preguntas</a></li>
        <li><a href="/lista-preguntas.php">Lista de preguntas</a></li>
      </ul>
    </div>
    <p class="align-center"><img src="images/opciones.png" alt="Opciones" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Preguntas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <fieldset class="collapsible">
      <legend>Banco de Preguntas</legend>
      <table>
        <thead>
          <tr>
            <th>Pregunta</th>
            <th>Nivel</th>
            <th>Tema</th>
            <th>Operaciones</th>
          </tr>
        </thead>
        <tbody>
          <?php $alt = "even"; ?>
          <?php foreach($preguntas as $k => $pregunta) : ?>
          <tr class="<?php print $alt ?>">
            <th><?php print $pregunta['enunciado']; ?></th>
            <td><?php print $pniveles[$pregunta['nivel']]; ?></td>
            <td><?php print get_var_from_field('nombre', 'codTema', $pregunta['codTema'], $bcdb->tema); ?></td>
            <td><a href="preguntas.php?id=<?php print $pregunta['codPregunta'] ?>">Ver o Editar</a></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>