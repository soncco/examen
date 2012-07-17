<?php
/**
 * Imprime recibos individuales
 */
require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
	
	function LoadData($id) {
		global $bcdb;
		$data = get_recibo($id);
		return $data;
	}
	

	//Una tabla más completa
	function recibo($header, $data) {
		
		$hl = 7; // Espacio a la izquierda
		
		$this->Ln(7);
		
		$this->SetFont('Times', 'B', 14);
		$this->Cell(185, 8, utf8_decode('N° ') . $data['nro_recibo'], 0, 0, 'R');
		$this->Ln();
	
		$this->SetFont('Arial', '' ,12);
		$this->Cell(185, 4, strftime('Fecha: %d/%m/%Y', strtotime($data['fecha'])), 0, 0, 'R');
		$this->Ln(7);
		
		$this->Cell($hl);
		$this->SetFont('Arial', '' ,16);
		$this->Cell(85, 7, "COMPROBANTE DE CAJA");
		$this->SetFont('Arial', '' ,11);
		$this->Cell(25, 7, "RUC: 20164370349");
		$this->SetFont('Arial', '' ,11);
		$this->Cell(40, 7, "Importe: ", 0, 0, 'R');
		$this->Cell(35, 7, "S/." . nuevos_soles($data['monto']), 1, 0, 'R');
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(30, 7, "NOMBRES: ", 0 ,0);
		$this->Cell(155, 7, utf8_decode($data['nombres']), 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(36, 7, "CLASIFICADOR: ", 0 ,0);
		$this->Cell(34, 7, $data['codigo'], 1);
		$this->Cell(80, 7, "DNI/RUC: ", 0 ,0, 'R');
		$this->Cell(35, 7, $data['documento'], 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(28, 7, "CONCEPTO: ", 0 ,0);
		$this->Cell(157, 7, utf8_decode($data['descripcion']), 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(28, 7, "ANEXO: ", 0 ,0);
		$this->Cell(157, 7, utf8_decode($data['observaciones']), 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(28, 7, "SON: ", 0 ,0);
		$this->Cell(157, 7, convertir($data['monto']), 1);
		$this->Ln(9);
		
		$cod_hora = strftime("%H:%M:%S", time()-3600) . "(". $_SESSION['loginuser']['ID'].")";
		
		$this->Cell($hl);
		$this->SetFont('', '', 10);
		$this->SetFillColor(225,225,225);
	    $this->Cell(10, 7, "", 0, 0, 0, true);
		$this->Cell(50, 8, strftime('%Y%m%d', strtotime($data['fecha'])). " " . $data['nro_recibo'] . " - UR", 0, 0, 'C');
		$this->Cell(20, 7, "", 0, 0, 0, true);
		$this->Cell(30, 8, $cod_hora, 0, 0, 'C');
		$this->Cell(20, 7, "", 0, 0, 0, true);
		$this->Cell(30, 8, nuevos_soles($data['monto']), 0, 0, 'C');
		$this->Cell(25, 7, "", 0, 0, 0, true);
	}
}

$pdf = new PDF();
//Títulos de las columnas
$header = array(utf8_decode('Código'), utf8_decode('Descripción'), 'Monto');
//Carga de datos
$data = $pdf->LoadData($_GET['ID']);
$pdf->AddPage();
$pdf->Recibo($header, $data);
$pdf->Output();
?>