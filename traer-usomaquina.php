<?php
/**
 * Muestra el uso de una máquina
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$id_maquina = $_POST['id_maquina'];
	$fecha_inicio = $_POST['fecha-inicio'];
	$fecha_fin = $_POST['fecha-fin'];
	
	$data = get_alquileres($id_maquina, $fecha_inicio, $fecha_fin);

	
?>
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