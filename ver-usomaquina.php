<?php
/**
 * Muestra el uso de una máquina
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$id_maquina = $_GET['id_maquina'];
	
	$now = time();
	$fecha = strftime("%Y-%m-%d", $now);
	
	$data = get_alquileres($id_maquina, $fecha, $fecha);	
	
	
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
<script type="text/javascript" src="/scripts/jquery.calendar.js"></script> 
<script type="text/javascript" src="/scripts/jquery.ui.all.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * Funciones de impresión
		 * 
		 */
		 
		// Imprime los reportes por fecha
		$('#print-maquina').bind('click', function() {
			var fecha_inicio = $("#fecha-inicio").val();
			var fecha_fin = $("#fecha-fin").val();
			var id_maquina = <?php print $id_maquina; ?>;
			window.open('print-usomaquina.php?id_maquina='+id_maquina+'&fecha-inicio='+fecha_inicio+'&fecha-fin='+fecha_fin, 'print', 'location=0, status=0, width=800, height=600');
		});
		
		simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
		
		// Muestra reportes entre fechas
		$('#show-per').bind('click', function(){
			$('#print-per').css('display', 'block');
			$(this).after(simg);
			var fecha_inicio = $("#fecha-inicio").val();
			var fecha_fin = $("#fecha-fin").val();
			var id_maquina = <?php print $id_maquina; ?>;
			$.ajax({
			   type: "POST",
			   url: "traer-usomaquina.php",
			   data: "id_maquina="+id_maquina+"&fecha-inicio=" + fecha_inicio + "&fecha-fin=" + fecha_fin,
			   success: function(msg){
				 $('#per-results').empty();
				 $('#per-results').append(msg);
				 $('#simg').remove();
			   },
			 });
		});
		
	});
</script>
<title>Ver uso de máquina | Sistema de exámenes</title>
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
        	<p class="align-center"><img src="images/maquina.png" alt="Máquinas" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Informes</h1>
            
            <fieldset class="collapsible">
                <legend>Escoger fecha</legend>
                <p>
                    <label for="fecha-inicio">Inicio:</label>
                    <input type="text" name="fecha-inicio" id="fecha-inicio" class="date" value="<?php print $fecha; ?>" />
                    <label for="fecha-fin">Fin:</label>
                    <input type="text" name="fecha-fin" id="fecha-fin" class="date" value="<?php print $fecha; ?>" />
                    <button type="button" name="show-per" id="show-per" class="small">Mostrar</button>
                </p>
            </fieldset>
            <div id="per-results">
                <table>
                    <caption>Reporte de la máquina <strong><?php print get_var_from_item("nombre", $id_maquina, $bcdb->maquinas); ?></strong></caption>
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Fecha</th>
                            <th>Alquilado por</th>
                            <th>Minutos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data['alquileres']): ?>
                        <?php $alt = "even"; ?>
                        <?php foreach($data['alquileres'] as $k=> $alquiler): ?>
						<?php if ($alquiler['horas'] == 0) $alt = "error"; ?>
                        <tr class="<?php print $alt ?>">
                            <th><?php print $k+1; ?></th>
                            <td><?php print strftime("%d %b %Y", strtotime($alquiler['fecha'])); ?></td>
                            <td><?php print $alquiler['nombres']; ?></td>
                            <td><?php print ($alquiler['horas'] > 0) ? horas_minutos($alquiler['horas']) : "ANULADO"; ?></td>
                            <td><a href="ver-recibos.php?ID=<?php print $alquiler['id_recibo']; ?>">detalles</a></td>
                            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="<?php print $alt; ?>">
                          <td colspan="7">No se han registrado alquileres en estas fechas.</th>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if ($data['alquileres']): ?>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="align-right no-border">Total:</th>
                            <th class="align-right"><?php print horas_minutos($data['total']); ?></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div><div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>