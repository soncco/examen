<?php
/**
 * Informes
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$now = time();
	$fecha = strftime("%Y-%m-%d", $now);
	
	$data = get_recibos_dia($fecha);
	
	// Trae las rubros
	$rubros = get_items($bcdb->rubros);

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
<script type="text/javascript" src="/scripts/tabs.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * Funciones de impresión
		 * 
		 */
		 
		 // Imprime los reportes diarios
		$('#print-daily').bind('click', function(){
			fecha = $('#fecha').val();
			window.open('print-daily.php?fecha='+fecha, 'print', 'location=0, status=0, width=800, height=600');
		});
		
		// Imprime los reportes por fecha
		$('#print-per').bind('click', function() {
			var fecha_inicio = $("#fecha-inicio").val();
			var fecha_fin = $("#fecha-fin").val();
			window.open('print-per.php?fecha-inicio='+fecha_inicio+'&fecha-fin='+fecha_fin, 'print', 'location=0, status=0, width=800, height=600');
		});
		
		simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
		
		// Muestra reportes diarios según fecha
		$('#change-daily').bind('click', function(){
			$(this).after(simg);
			var fecha = $("#fecha-daily").val();
			$('#fecha').val(fecha);
			$.ajax({
			   type: "POST",
			   url: "traer-daily.php",
			   data: "fecha=" + fecha,
			   success: function(msg){
				 $('#daily-results').empty();
				 $('#daily-results').append(msg);
				 $('#simg').remove();
				 
			   },
			 });

		});
		
		// Muestra reportes entre fechas
		$('#show-per').bind('click', function(){
			$('#print-per').css('display', 'block');
			$(this).after(simg);
			var fecha_inicio = $("#fecha-inicio").val();
			var fecha_fin = $("#fecha-fin").val();
			$.ajax({
			   type: "POST",
			   url: "traer-per.php",
			   data: "fecha-inicio=" + fecha_inicio + "&fecha-fin=" + fecha_fin,
			   success: function(msg){
				 $('#per-results').empty();
				 $('#per-results').append(msg);
				 $('#simg').remove();
			   },
			 });
		});
		
		// Muestra reportes entre fechas y rubros
		$('#show-rub').bind('click', function(){
			var id_rubro = $("#id_rubro").val();
			
			if(id_rubro != "") {
				var fecha_inicio = $("#fecha-inicio-r").val();
				var fecha_fin = $("#fecha-fin-r").val();
				var pagadores = ($("#pagadores").is(":checked")) ? 'si' : 'no';
				
				$(this).after(simg);
				$.ajax({
				   type: "POST",
				   url: "traer-rubro.php",
				   data: "fecha-inicio=" + fecha_inicio + "&fecha-fin=" + fecha_fin + "&id_rubro=" + id_rubro + "&pagadores=" + pagadores,
				   success: function(msg){
					 $('#rubro-results').empty();
					 $('#rubro-results').append(msg);
					 $('#simg').remove();
				   },
				 });
			}
		});
		
	});
</script>
<title>Informes | Sistema de exámenes</title>
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
        	<h1>Informes</h1>
            <?php if (isset($msg)): ?>
            	<p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
            <?php endif; ?>
            <ul class="i-tabs"> 
                <li><a href="#i-diario">Diario</a></li>
                <li><a href="#i-fecha">Por fecha</a></li> 
                <li><a href="#i-rubro">Por rubro</a></li> 
            </ul>
            <div class="i-tab-container">
                <div id="i-diario" class="i-tab-content">
                	<fieldset class="collapsibleClosed">
                    	<legend>Cambiar fecha</legend>
                        <p>
                        	<label for="fecha-daily">Escoja la fecha del informe:</label>
                            <input type="text" name="fecha-daily" id="fecha-daily" class="date" value="<?php print $fecha; ?>" />
                            <button type="button" name="change-daily" id="change-daily" class="small">Cambiar</button>
                        </p>
                    </fieldset>
                    <div id="daily-results">
                        <table>
                            <caption>Informe del <?php print utf8_encode(strftime("%a, %d de %B del %Y", $now)); ?></caption>
                            <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th>Tipo</th>
                                    <th>Recibo</th>
                                    <th>Código</th>
                                    <th>Pagador por</th>
                                    <th>Monto <abbr title="Nuevos Soles">S/.</abbr></th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($data['recibos']): ?>
                                <?php $alt = "even"; ?>
                                <?php foreach($data['recibos'] as $k=> $recibo): ?>
                                <?php
                                    if($recibo['factura']):
                                        $tipo = "FAC.";
                                    elseif ($recibo['tipo']):
                                        $tipo = "R/N";
                                    else:
                                        $tipo = "R/C";
                                    endif;
									
									if($recibo['anulado']) $alt = "error";
                                ?>
                                <tr class="<?php print $alt ?>">
                                    <th><?php print $k+1; ?></th>
                                    <th><?php print $tipo; ?></th>
                                    <td><?php print $recibo['nro_recibo']; ?></td>
                                    <td><?php print $recibo['codigo']; ?></td>
                                    <td><?php print ($recibo['anulado']) ? "ANULADO" : $recibo['nombres']; ?></td>
                                    <td class="align-right"><?php print nuevos_soles($recibo['monto']); ?></td>
                                    <td><a href="ver-recibos.php?ID=<?php print $recibo['ID']; ?>">Detalles</a></td>
                                    <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr class="<?php print $alt; ?>">
                                  <th colspan="7">No se ha registrado ningún pago en esta fecha</th>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <?php if ($data['recibos']): ?>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="align-right no-border">Total:</th>
                                    <th class="align-right"><?php print nuevos_soles($data['total']); ?></th>
                                </tr>
                            </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                    <p class="align-center">
                    	<button type="button" name="print-daily" id="print-daily">Imprimir</button>
                        <input type="hidden" name="fecha" id="fecha" value="<?php print $fecha; ?>" />
                    </p>
                </div>
                <div id="i-fecha" class="i-tab-content">
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
                    </div>
                    <p class="align-center">
                        <button type="button" name="print-per" id="print-per" style="display: none;">Imprimir</button>
                    </p>
                </div>
                <div id="i-rubro" class="i-tab-content">
                	<fieldset class="collapsible">
                    	<legend>Datos</legend>
                        <p>
                            <label for="id_rubro">Rubro:</label>
                            <select name="id_rubro" id="id_rubro">
                                <option value="">Seleccione un rubro</option>
                                <?php foreach ($rubros as $rubro) : ?>
                                <option value="<?php print $rubro['ID']; ?>"><?php print $rubro['descripcion']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <p>
                        	<label for="fecha-inicio">Inicio:</label>
                            <input type="text" name="fecha-inicio-r" id="fecha-inicio-r" class="date" value="<?php print $fecha; ?>" />
                            <label for="fecha-fin">Fin:</label>
                            <input type="text" name="fecha-fin-r" id="fecha-fin-r" class="date" value="<?php print $fecha; ?>" />
                        </p>
                        <p>
                        	<input type="checkbox" name="pagadores" id="pagadores" />
                            <label for="pagadores">Mostrar sólo nombres de pagadores</label>
                        </p>
                        <p class="align-center">
                        	<button type="button" name="show-rub" id="show-rub" class="small">Mostrar</button>
                        </p>

                    </fieldset>
                    <div id="rubro-results">
                    </div>
                </div>
            </div>
            
        </div>
        <div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>