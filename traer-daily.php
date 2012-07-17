<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$fecha = $_POST['fecha'];
	
	$data = get_recibos_dia($fecha);
	
?>
<table>
<caption>Informe del <?php print utf8_encode(strftime("%a, %d de %B del %Y", strtotime($fecha))); ?></caption>
<thead>
    <tr>
        <th>Nro.</th>
        <th>Tipo</th>
        <th>Recibo</th>
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
      <td colspan="7">No se ha registrado ningún pago en esta fecha</th>
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