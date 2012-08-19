<?php

require_once ('home.php');
require_once ('redirect.php');

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') :
	$email = $_POST['email'];
	$codDocente = $_SESSION['loginuser']['codDocente'];
	
	if ($_POST['pwd'] != '' && ($_POST['pwd'] == $_POST['pwd2'])) {
		$pwd = md5($_POST['pwd']);
	} else {
		$pwd = 'false';
	}

	// Guarda
	global $bcdb;
	$sql = "UPDATE tDocente SET email = '$email' WHERE codDocente = '$codDocente';";
	$_SESSION['loginuser']['email'] = $email;
	$bcdb->query($sql);
	
	if ($pwd != 'false') {
		$sql = "UPDATE tDocente SET password = '$pwd' WHERE codDocente = '$codDocente';";
		$bcdb->query($sql);		
	}
	
	$msg = "<p class=\"msg\">La información se guardó correctamente.</p>";
endif;
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
				$("#frmusers").validate();
			}); 
		</script>
		<title>Mis Datos | Sistema de exámenes</title>
	</head>

	<body>
		<div class="container_16">
			<? include "header.php"; ?>
			<div class="clear"></div>
			<div id="icon" class="grid_3">
				<p class="align-center"><img src="<?php print IMAGES_URL; ?>/opciones.png" alt="Opciones" />
				</p>
			</div>
			<div id="content" class="grid_13">
				<h1>Mis datos</h1>
				<? if ($_SERVER['REQUEST_METHOD'] == 'POST') { echo $msg; } ?>
				<form name="frmusers" id="frmusers" method="post" action="mis-datos.php">
				<fieldset>
					<legend></legend>
					<p class="help">
						<span id="referencia">Si no desea cambiar su contraseña dejela en blanco.</span>
					</p>
					<p>
						<label>Código: <?= $_SESSION['loginuser']['codDocente']; ?></label>
					</p>
					<p>
						<label>Apellidos y Nombres: <?= $_SESSION['loginuser']['apellidoP']; ?>-<?= $_SESSION['loginuser']['apellidoM']; ?>-<?= $_SESSION['loginuser']['nombres']; ?></label>
					</p>	
					<p>
						<label for="email">E-mail:</label>
						<input id="email" class="required email" type="text" value="<?= $_SESSION['loginuser']['email']; ?>" size="60" maxlength="255" name="email">
					</p>
					<p>
						<label for="pwd">Contraseña:</label>
						<input id="pwd" type="password" title="Ingresa la contraseña" maxlength="100" name="pwd">
					</p>
					<p>
						<label for="pwd2">Otra vez:</label>
						<input id="pwd2" type="password" title="Ingresa la contraseña" maxlength="100" name="pwd2">
						<br>
					</p>
					<p>
						<button type="submit" name="submit" id="submit">Guardar</button>
					</p>
				</fieldset>
				</form>
			</div>
			<div class="clear"></div>
			<? include "footer.php"; ?>
		</div>
	</body>
</html>