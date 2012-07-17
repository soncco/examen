<?php
/**
 * Registra Clientes
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	
	// Trae los clientes.
	
	$buscar = isset($_GET['buscar']);
	if($buscar):
		$palabra = trim($_GET['s']);
		$clientes = search_clientes($palabra);
	else:
		$pager = true;
		$clientes = get_items($bcdb->cliente, "apaterno");
		// Paginación
		$results = @$bcrs->get_navigation();
	endif;
	
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
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".click").editable("/datos-cliente.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
	});
</script>
<title>Pagadores | Sistema de exámenes</title>
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
    <p class="align-center"><img src="images/clients.png" alt="Pagadores" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Pagadores</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="search" id="search" method="get" action="clientes.php">
      <fieldset <?php if(!$buscar): ?>class="collapsibleClosed"<?php endif; ?>>
        <legend>Buscar pagador</legend>
        <p>
          <label for="s">Buscar por nombres o apellidos:</label>
          <input type="text" name="s" id="s" <?php if($buscar): ?>value="<?php print $palabra; ?>"<?php endif; ?> />
          <button name="buscar" id="buscar" type="submit">Buscar</button>
        </p>
      </fieldset>
    </form>
    <fieldset>
      <legend>Lista de clientes</legend>
      <p class="war">Los clientes se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
      <table>
        <?php if($buscar): ?>
        <caption>
        Mostrando resultados con: "<?php print $palabra; ?>"
        </caption>
        <?php endif; ?>
        <thead>
          <tr>
            <th>Documento</th>
            <th>Nombres</th>
            <th>Paterno</th>
            <th>Materno</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($clientes): ?>
          <?php $alt = "even"; ?>
          <?php foreach($clientes as $k=> $cliente): ?>
          <tr class="<?php print $alt ?>">
            <th><span class="click" id="dni-<?php print $cliente['id']; ?>"><?php print $cliente['dni']; ?></span></th>
            <td><span class="click" id="nombres-<?php print $cliente['id']; ?>"><?php print $cliente['nombres']; ?></span></td>
            <td><span class="click" id="apaterno-<?php print $cliente['id']; ?>"><?php print $cliente['apaterno']; ?></span></td>
            <td><span class="click" id="amaterno-<?php print $cliente['id']; ?>"><?php print $cliente['amaterno']; ?></span></td>
            <td><a href="ver-pagos.php?idcliente=<?php print $cliente['id']; ?>">Ver pagos</a></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <th colspan="5">No existen datos</th>
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