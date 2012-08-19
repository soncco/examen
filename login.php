<?php
/**
 * Inicio de sesión para el sistema
 */
require_once('home.php');

if ( !empty($_SESSION['loginuser']) ) {
  switch ($_SESSION['loginuser']['rol']) :
    case 'docente':
      safe_redirect('temas.php');
    break;
    case 'alumno':
      safe_redirect('mis-cursos.php');
    break;
    default:
      safe_redirect('cursos.php');
  endswitch;
}

$postback = isset($_POST['username']);
$location = !empty($_REQUEST['r']) ? clean_html($_REQUEST['r']) : '';
$rol = !empty($_REQUEST['rol']) ? clean_html($_REQUEST['rol']) : 'admin';

// Verificación de login
if($postback){
  switch ($rol) :
    case 'docente':
      $user = get_item_by_field("codDocente", $_POST['username'], $bcdb->docente);	
      $user['rol'] = 'docente';
      $location = 'temas.php';
    break;
    case 'alumno':
      $user = get_item_by_field("codAlumno", $_POST['username'], $bcdb->alumno);	
      $user['rol'] = 'alumno';
      $location = 'mis-cursos.php';
    break;
    default:
      $user = get_item_by_field("usuario", $_POST['username'], $bcdb->admin);
      $user['rol'] = 'admin';
      $location = 'cursos.php';
  endswitch;
	if ( isset($user['password']) ) :
		if( $user['password'] == md5($_POST['pwd']) ) :
			session_regenerate_id();
			$_SESSION['loginuser'] = $user;
			safe_redirect($location);
			exit();
		else:
			$error = true;
			$msg = "La contrase&ntilde;a es incorrecta";
		endif;
	else:
		$error = true;
		$msg = "El usuario no existe";
	endif;
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
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.collapsible.js"></script>
<script type="text/javascript" src="<?php print SCRIPTS_URL; ?>jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#username").focus();
	});
</script>
<title>Login | Sistema de Exámenes</title>
</head>

<body>
<div class="container_16">
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Sistema de Exámenes</span></a> </h1>
    <?php include "menutop.php"; ?>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="<?php print IMAGES_URL; ?>/login.png" alt="Ingresar" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Bienvenido</h1>
    <?php if (isset($error)): ?>
    <p class="error"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmlogin" id="frmlogin" method="post" action="login.php">
      <fieldset>
        <legend>Iniciar Sesión (<?php print $rol; ?>)</legend>
        <p>
          <label for="username" accesskey="u"><span class="accesskey">U</span>suario:</label>
          <input type="text" name="username" id="username" maxlength="100" class="required" title="Ingresa el nombre de usuario" />
        </p>
        <p>
          <label for="pass" accesskey="c"><span class="accesskey">C</span>ontraseña:</label>
          <input type="password" name="pwd" id="pass" maxlength="100" class="required" title="Ingresa la contraseña" />
        </p>
        <p>
          <button type="submit" name="submit" id="submit">Entrar</button>
          <input type="hidden" name="r" id="r" value="<?php print $location ?>" />
          <input type="hidden" name="rol" id="rol" value="<?php print $rol ?>" />
        </p>
      </fieldset>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>