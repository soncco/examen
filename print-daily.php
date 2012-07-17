<?php

/**
 * Imprime recibos individuales
 */
require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
	
	
	function LoadData() {
		global $bcdb;
		$data = get_recibos_dia($this->fecha);
		return $data;
	}
	
	function Header() {
		$this->Ln(30);
		$this->SetFont('Arial', '' ,12);
		$this->Cell(6);
		$this->Cell(1, 4, strftime('Fecha: %d de %B del %Y', strtotime($this->fecha)));
		$this->Ln(10);
	}
	
	function Footer() {
		//Posición: a 1,5 cm del final
		$this->SetY(-13);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0, 10, utf8_decode('Pág. ') . $this->PageNo() . '/{nb}' , 0, 0, 'C');
	}
	
	// Resultados
	function Informe($header, $data) {
		$hl = 6;
		//Anchuras de las columnas
		$w = array(10, 13, 20, 25, 88, 25);
		$h = 7; // Alto de las columnas
		//Cabeceras
		$this->SetFont('', 'B', '12');
		$this->Cell($hl);
		for($i=0; $i<count($header); $i++)
			$this->Cell($w[$i], $h, $header[$i], 1, 0, 'C');
		$this->Ln();
		//Datos
		$this->SetFont('', '', '12');
		if($data['recibos']) :
			foreach($data['recibos'] as $k =>$v) :
				
				if($v['factura']):
					$tipo = "FAC.";
				elseif ($v['tipo']):
					$tipo = "R/N";
				else:
					$tipo = "R/C";
				endif;
				
				$this->Cell($hl);
				$this->Cell($w[0], $h, $k+1, 'LRB' ,0 , 'R');
				$this->Cell($w[1], $h, $tipo, 'LRB');
				$this->Cell($w[2], $h, "0" . $v['nro_recibo'], 'LRB', 0, 'R');
				$this->Cell($w[3], $h, $v['codigo'], 'LRB', 0);
				$this->Cell($w[4], $h, ($v['anulado']) ? "ANULADO" : utf8_decode($v['nombres']), 'LRB', 0);
				$this->Cell($w[5], $h, nuevos_soles($v['monto']),'LRB', 0, 'R');
				$this->Ln();
			endforeach;
			
			//Línea de cierre
			$this->Cell($hl);
			$this->Cell(array_sum($w),0,'','T');
			$this->Ln(0);
			$this->Cell($hl);
			$this->SetFont('', 'B', 13);
			$this->Cell(156,8,'Total', 0, 0, 'R');
			$this->Cell(25,8, nuevos_soles($data['total']), '1', 0, 'R');
			$this->Ln();
		else:
			$this->Cell(array_sum($w),8, utf8_decode('No se ha registrado ningún pago en esta fecha'));
		endif;
	}
}

$pdf = new PDF();
$pdf->AliasNbPages();
//Títulos de las columnas
$header = array('Nro', 'Tipo', 'Recibo', utf8_decode('Código'), 'Pagado por', 'Monto');
//Carga de datos
$fecha = $_GET['fecha'];
$pdf->fecha = $fecha;
$data = $pdf->LoadData();
$pdf->AddPage();

$pdf->Informe($header, $data);

$pdf->Output();
?>