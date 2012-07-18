<?php
/**
 * Lista de Temas
 */
	require_once('home.php');
	require_once('redirect.php');
  
  $cursos = get_cursos_docente($_SESSION['loginuser']['codDocente ']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/theme/ui.all.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    
    simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
    
    $('#codCurso').change(function(){
      $(this).after(simg);
      codCurso = $(this).val();
			$.ajax({
			   type: 'POST',
			   url: 'traer-temas.php',
			   data: 'codCurso=' + codCurso,
			   success: function(response){
          $('#temas-results').empty();
          $('#temas-results').append(response);
          $('#simg').remove();
			   }
			 });
		});
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
    <p class="align-center"><img src="images/report.png" alt="Informes" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Lista de temas</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <fieldset class="collapsible">
      <legend>Escoge el curso</legend>
      <p>
	      <label for="codCurso">Curso <span class="required">*</span>:</label>
        <select name="codCurso" id="codCurso">
          <option value="" selected="selected">Seleccione un curso</option>
          <?php foreach ($cursos as $k => $curso) : ?>
          <option value="<?php print $curso['codCurso']; ?>"><?php print $curso['nombre']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
    </fieldset>
    <div id="temas-results">
    </div>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>