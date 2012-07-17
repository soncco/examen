<?php
/**
 * Muestra los pagos de un determinado pagador
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$id_pagador = $_GET['id_pagador'];
	
	$id_rubro = isset($_GET['id_rubro']) ? trim($_GET['id_rubro']) : 0;
	
	if ($id_rubro > 0) :
		$data = get_recibos_pagador_rubro($id_pagador, $id_rubro);
	else :
		$data = get_recibos_pagador($id_pagador);
	endif;
	
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
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * Funciones de Filtro
		 * 
		 */
		 
		 // Carga una página con datos del pagador de acuerdo a un rubro
		$('#filter').bind('click', function(){
			id_pagador = <?php print $id_pagador; ?>;
			id_rubro = $('#id_rubro').val();
			if(id_rubro != '')
				location.href = '<?php print $self; ?>?id_pagador=' + id_pagador + '&id_rubro=' + id_rubro;
		});
		
		
	});
</script>
<title>Ver Pagos | Sistema de exámenes</title>
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
        	<p class="align-center"><img src="images/clients.png" alt="Pagos" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Informes</h1>
            <form>
            	<fieldset <?php if($id_rubro == 0) : ?>class="collapsibleClosed"<?php else: ?>class="collapsible"<?php endif; ?>>
                    <legend>Filtrar por rubro</legend>
                    <p>
                    	<label for="id_rubro">Rubro:</label>
                        <select name="id_rubro" id="id_rubro">
                        	<option value="">Seleccione un rubro</option>
                        	<?php foreach ($rubros as $rubro) : ?>
                            <option value="<?php print $rubro['ID']; ?>" <?php if($id_rubro == $rubro['ID']) : ?>selected="selected"<?php endif; ?>><?php print $rubro['descripcion']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" name="filter" id="filter">Filtrar</button>
                    </p>
                </fieldset>
            </form>
            <table>
                <caption>Pagos realizados por <strong><?php print get_var_from_item("nombres", $id_pagador, $bcdb->pagadores); ?></strong><?php if ($id_rubro > 0) :?> en el rubro <?php print get_var_from_item('descripcion', $id_rubro, $bcdb->rubros); ?><?php endif; ?></caption>
                <thead>
                    <tr>
                        <th>Nro.</th>
                        <th>Tipo</th>
                        <th>Recibo</th>
                        <th>Fecha</th>
                        <th>Código</th>
                        <th>Concepto</th>
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
                            $tipo = "fac.";
                        elseif ($recibo['tipo']):
                            $tipo = "r/n";
                        else:
                            $tipo = "r/c";
                        endif;
                    ?>
                    <tr class="<?php print $alt ?>">
                        <th><?php print $k+1; ?></th>
                        <th><?php print $tipo; ?></th>
                        <td><?php print $recibo['nro_recibo']; ?></td>
                        <td><?php print strftime("%d %b %Y", strtotime($recibo['fecha'])); ?></td>
                        <td><?php print $recibo['codigo']; ?></td>
                        <td><?php print $recibo['descripcion']; ?></td>
                        <td class="align-right"><?php print nuevos_soles($recibo['monto']); ?></td>
                        <td><a href="ver-recibos.php?ID=<?php print $recibo['ID']; ?>">detalles</a></td>
                        <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr class="<?php print $alt; ?>">
                      <td colspan="8">No se ha registrado ningún pago en esta fecha</th>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <?php if ($data['recibos']): ?>
                <tfoot>
                    <tr>
                        <th colspan="6" class="align-right no-border">Total:</th>
                        <th class="align-right"><?php print nuevos_soles($data['total']); ?></th>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div><div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>