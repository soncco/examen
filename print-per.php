<?php

/**
 * Imprime recibos por rango de fecha
 */
require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
	
	
	function LoadData() {
		global $bcdb;
		$data = get_recibos_per($this->fecha_inicio, $this->fecha_fin);
		return $data;
	}
	
	function Header() {
		$this->SetFont('Times', '' ,12);
		$this->Cell(0, 4, 'MUNICIPALIDAD DISTRITAL DE CHINCHERO', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Arial', '' ,12);
		$this->Cell(0, 10, 'PROV. URUBAMBA - DPTO. CUSCO', 0, 0, 'C');
		$this->Ln(10);
		
		$this->SetFont('', 'B' ,12);
		$this->Cell(0, 4, "Ingresos recaudados desde el " . strftime("%d %b %Y", strtotime($this->fecha_inicio)) . " hasta el " . strftime("%d %b %Y", strtotime($this->fecha_fin)), 0, 0, 'C');
		$this->Ln(10);
		
		$this->SetFont('', '' ,10);
		$this->Cell(190, 7, "Fecha: " . strftime("%d %b %Y"), 0, 0, 'R');
		$this->Ln();

	}
	
	function Footer() {
		$this->SetFont('Arial', 'B' ,13);
		$this->Cell(190, 7, "SON: " . convertir($this->total));
		$this->Ln();
		//Posición: a 1,5 cm del final
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0, 10, utf8_decode('Pág. ') . $this->PageNo() . '/{nb}' , 0, 0, 'C');
	}
	
	// Resultados
	function Informe($header, $data) {
		//Anchuras de las columnas
		$w = array(30, 118, 43);
		$h = 7; // Alto de las columnas
		//Cabeceras
		$this->SetFont('', 'B', '12');
		for($i=0; $i<count($header); $i++)
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
		$this->Ln();
		//Datos
		$this->SetFont('', '', '14');
		if($data['rubros']) :
		foreach($data['rubros'] as $k =>$v) :
			$this->Cell($w[0], $h, $v['codigo'], 'LRB', 0);
			$this->Cell($w[1], $h, utf8_decode($v['descripcion']), 'LRB', 0);
			$this->Cell($w[2], $h, nuevos_soles($v['subtotal']),'LRB', 0, 'R');
			$this->Ln();
		endforeach;
		
		//Línea de cierre
		$this->Cell(array_sum($w),0,'','T');
		$this->Ln(0);
		$this->SetFont('', 'B', 16);
		$this->Cell(148,8,'Total', 0, 0, 'R');
		$this->Cell(43,8, nuevos_soles($data['total']), '1', 0, 'R');
		else:
			$this->Cell(array_sum($w),8, utf8_decode('No se ha registrado ningún pago en este rango de fechas'));
		endif;
		$this->Ln(20);
	}
}

$pdf = new PDF();
$pdf->AliasNbPages();
//Títulos de las columnas
$header = array(utf8_decode('Código'), 'Concepto', 'Monto');
//Carga de datos
$fecha_inicio = $_GET['fecha-inicio'];
$fecha_fin = $_GET['fecha-fin'];

$pdf->fecha_inicio = $fecha_inicio;
$pdf->fecha_fin = $fecha_fin;
$data = $pdf->LoadData();
$pdf->total = $data['total'];
$pdf->AddPage();
$pdf->Informe($header, $data);

$pdf->Output();
?>