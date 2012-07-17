<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$fecha_inicio = $_POST['fecha-inicio'];
	$fecha_fin = $_POST['fecha-fin'];
	
	$data = get_recibos_per($fecha_inicio, $fecha_fin);
	
?>
<table>
<caption>Ingresos recaudados desde el <?php print strftime("%d %b %Y", strtotime($fecha_inicio)); ?> hasta el <?php print strftime("%d %b %Y", strtotime($fecha_fin)); ?></caption>
<thead>
    <tr>
        <th>Código</th>
        <th>Concepto</th>
        <th>Monto <abbr title="Nuevos Soles">S/.</abbr></th>
    </tr>
</thead>
<tbody>
    <?php if ($data): ?>
    <?php $alt = "even"; ?>
    <?php foreach($data['rubros'] as $rubro): ?>
    <tr class="<?php print $alt ?>">
        <td><?php print $rubro['codigo']; ?></td>
        <td><?php print $rubro['descripcion']; ?></td>
        <td class="align-right"><?php print nuevos_soles($rubro['subtotal']); ?></td>
        <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr class="<?php print $alt; ?>">
      <td colspan="3">No existen datos en estas fecha</th>
    </tr>
    <?php endif; ?>
</tbody>
<?php if ($data): ?>
<tfoot>
    <tr>
        <th colspan="2" class="align-right no-border">Total:</th>
        <th class="align-right"><?php print nuevos_soles($data['total']); ?></th>
    </tr>
</tfoot>
<?php endif; ?>
</table>
<p>Son: <strong><?php print convertir($data['total']); ?></strong></p>