<?php
/* Cerrar Sesión */
require_once('home.php');

if(!isset($_SESSION['loginuser'])) {
	header('Location:' . BASE_URL);
	exit();
}

$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}

session_destroy();

@ header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
@ header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
@ header('Cache-Control: no-cache, must-revalidate, max-age=0');
@ header('Pragma: no-cache');
@ header('Refresh=5; location=' . BASE_URL);

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
<title>Sistema de Exámenes</title>
</head>

<body>
<div class="container_16">
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Sistema de Exámenes</span></a> </h1>
    <?php include "menutop.php"; ?>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="<?php print IMAGES_URL; ?>/login.png" alt="Login" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Hasta Pronto</h1>
    <p>Si deseas ingresar nuevamente <a href="/">usa el formulario de ingreso</a>.</p>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>