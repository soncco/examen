<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$fecha_inicio = $_POST['fecha-inicio'];
	$fecha_fin = $_POST['fecha-fin'];
	$id_rubro = $_POST['id_rubro'];
	$pagadores = $_POST['pagadores'];
	
	$rubro = get_item($id_rubro, $bcdb->rubros);
	
	if($pagadores == 'si') :
		$data = get_pagadores_rubro($fecha_inicio, $fecha_fin, $id_rubro);
	else :
		$data = get_recibos_rubro($fecha_inicio, $fecha_fin, $id_rubro);	
	endif;
	
?>
<?php if($pagadores == 'no') : ?>
<table>
<caption>Ingresos recaudados desde el <?php print strftime("%d %b %Y", strtotime($fecha_inicio)); ?> hasta el <?php print strftime("%d %b %Y", strtotime($fecha_fin)); ?> en el rubro <?php print $rubro['descripcion']; ?></caption>
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
    <tr class="<?php print $alt ?>">
        <td><?php print $rubro['codigo']; ?></td>
        <td><?php print $rubro['descripcion']; ?></td>
        <td class="align-right"><?php print nuevos_soles($data['total']); ?></td>
        <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <?php else: ?>
    <tr class="<?php print $alt; ?>">
      <td colspan="3">No existen datos</th>
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
<? else : ?>
<table>
<caption>Lista de pagadores desde el <?php print strftime("%d %b %Y", strtotime($fecha_inicio)); ?> hasta el <?php print strftime("%d %b %Y", strtotime($fecha_fin)); ?> en el rubro <?php print $rubro['descripcion']; ?></caption>
<thead>
    <tr>
    	<th>Documento</th>
        <th>Nombres</th>
        <th>Fecha</th>
        <th>Detalle</th>
    </tr>
</thead>
<tbody>
    <?php if ($data): ?>
    <?php foreach($data as $da): ?>
    <?php $alt = "even"; ?>
    <tr class="<?php print $alt ?>">
        <td><?php print $da['documento']; ?></td>
        <td><?php print $da['nombres']; ?></td>
        <td><?php print fecha_to_page($da['fecha']); ?></td>
        <td><a href="ver-recibos.php?ID=<?php print $da['ID']; ?>">Ver detalles</a></td>
        <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr class="<?php print $alt; ?>">
      <td colspan="4">No existen datos</th>
    </tr>
    <?php endif; ?>
</tbody>
</table>
<?php endif; ?>