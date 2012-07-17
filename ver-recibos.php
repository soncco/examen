<?php
/**
 * Muestra Recibos
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$saved = isset($_GET['saved']);
	
	if($saved) :
		$msg = "La información se guardó correctamente";
	endif;
	
	$id = $_GET['ID'];
	
	$recibo = get_recibo($id);
	
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
<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * Imprime el recibo
		 * 
		 */
		$('#print').bind('click', function(){
			ID = $('#id').val();
			window.open('print-recibo.php?ID='+ID, 'print', 'location=0, status=0, width=800, height=600');
		}); 
		
		// Anula un recibo
		$('#anular').bind('click', function(){
			if(window.confirm('¿Estás seguro de anular este recibo? Esta acción no se puede deshacer.')) {
				ID = $('#id').val();
				$.ajax({
				   type: "POST",
				   url: "anular-recibo.php",
				   data: "ID=" + ID,
				   success: function(msg){
					 $('#frmrecibo').prepend('<p class="error">Este recibo ha sido anulado.</p>');
					 $('#anular').attr('disabled', 'disabled');
					 $('#anular').css('display', 'none');
					 $('#monto').text('0.00');
				   },
				});
			}
		}); 
	});
</script>
<title>Recibos | Sistema de exámenes</title>
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
        	<p class="align-center"><img src="images/report.png" alt="Recibos" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Recibos</h1>
            <?php if (isset($msg)): ?>
            	<p class="msg"><?php print $msg; ?></p>
            <?php endif; ?>
            <?php if ($recibo['factura']) : ?>
                <p class="war">
                    Para este recibo se ha emitido una factura.
                </p>
            <?php endif; ?>
            <?php if ($recibo['anulado']) : ?>
                <p class="error">
                    Este recibo está anulado.
                </p>
            <?php endif; ?>
            <form name="frmrecibo" id="frmrecibo" method="post" action="recibos.php">
            	<fieldset>
                	<legend>Recibo Nro. <?php print $recibo['nro_recibo']; ?></legend>
                    <p>
                    	<label for="fecha">Fecha:</label>
                        <strong><?php print $recibo['fecha']; ?></strong>
                    </p>
                    <p>
                    	<label for="monto">Monto pagado S/.:</label>
                        <strong id="monto"><?php print nuevos_soles($recibo['monto']); ?></strong>
                    </p>
                    <p>
                    	<label for="pagador">Recibí de:</label>
                        <strong><?php print $recibo['nombres']; ?></strong>
                        <label for="monto">La cantidad de:</label>
                        <strong><?php print convertir($recibo['monto']); ?></strong>
                        <label for="id_rubro">por concepto de:</label>
                        <strong><?php print $recibo['descripcion']; ?> (<?php print $recibo['codigo']; ?>)</strong> 
                    </p>
                    <?php if ($recibo['id_rubro'] == get_option('rubro_maquinaria')) : ?>
                    <p class="maquinarias">
                    	Se alquiló la máquina <strong><?php print $recibo['maquina']; ?></strong>
                        por <strong><?php print horas_minutos($recibo['horas']); ?></strong>.
                    </p>
                    <?php endif; ?>
                    <p>
                    	
                    </p>
                    <p>
                    	<label for="observaciones">Observaciones:</label><br />&nbsp;
                        <strong><?php print $recibo['observaciones']; ?></strong>
                    </p>
                    <p class="align-center">
                        <?php if (!$recibo['factura']) : ?>
                        <button type="button" name="print" id="print">Imprimir</button>
                        <?php endif; ?>
                        <?php if (!$recibo['anulado']) : ?>
                        <button type="button" name="anular" id="anular">Anular</button>
                        <?php endif; ?>
                        <input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
                    </p>
                </fieldset>
            </form>
            
            
        </div><div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>