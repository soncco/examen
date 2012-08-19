<?

	require_once('home.php');
	require_once('redirect.php');
	
	$codExamen = $_POST['codExamen'];
		
	$fechas = get_fecha_de_examen_programa($codExamen);

?>
<? if (count($fechas) > 0) : ?>
<? foreach($fechas as $fecha) : ?>
<option value="<?= base64_encode($fecha['fecha']) ?>"><?= $fecha['fechaF']?></option>
<? endforeach; ?>
<? endif; ?>