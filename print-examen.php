<?php

require_once('home.php');
//require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
		
	var $font;
	var $curso;
	var $examen;
	var $preguntas;
	var $alternativas;
	
	function Header() {
		$this->SetFont($this->font, 'B' ,12);
				
		$title = utf8_decode("UNIVERSIDAD NACIONAL DE SAN ANTONIO ABAD DEL CUSCO");
		$w = $this->GetStringWidth($title)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,9,$title);
		$this->Ln(5);
		
		$subtitle = utf8_decode("DEPARTAMENTO DE INFORMÁTICA");
		$w = $this->GetStringWidth($subtitle)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,9,$subtitle);
		$this->Ln(10);
		
		$this->SetFont($this->font, 'B' ,10);
		
		$this->curso = utf8_decode($this->curso);
		$w = $this->GetStringWidth($this->curso)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,9,$this->curso);
		$this->Ln(4);
		
		$this->examen = utf8_decode($this->examen);
		$w = $this->GetStringWidth($this->examen)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,9,$this->examen);
		$this->Ln(10);
	}
	
	function Footer() {
		$this->SetY(-13);
		$this->SetFont($this->font,'I',8);
		$this->Cell(0, 10, utf8_decode('Pág. ') . $this->PageNo() . '/{nb}' , 0, 0, 'C');
	}
	
	function Informe() {
		$this->SetFont($this->font, '' ,9);
		
		$apellidosynombres = utf8_decode("APELLIDOS Y NOMBRES:");
		$this->Cell(0,9, $apellidosynombres);
		
		$this->SetX(160);
		$codigo = utf8_decode("CÓDIGO:");
		$this->Cell(10, 6, $codigo);
		
		$this->Ln(10);
					
		$i = 1;
		$j_b = 65;
		$j = $j_b;
		$n = sizeof($this->preguntas);
		
		for ($i = 1; $i <= $n; $i++) {
			$this->Cell(0, 4, "$i)");
			$this->SetX(16);
			$this->MultiCell(0, 4, utf8_decode($this->preguntas[$i-1][enunciado]));
			$this->Ln(5);
			
			foreach ($this->alternativas[$i-1] as $alt) {
				$this->SetX(16);
				$this->Cell(0, 4, chr($j).")");
				$this->SetX(22);
				$this->MultiCell(0, 4, utf8_decode($alt[detalle]));
				
				$j++;			
			}
			
			$this->Ln(5);
			$j = $j_b;
		}
	}
}

global $bcdb;
$pdf = new PDF();

$pdf->font = 'Arial';
$codExamen = $_GET['id']; /*VALIDAR QUE SEA ENTERO Y QUE TENGA PERMISOS PARA VER EXAMEN*/

$arr_tmp = get_curso_de_examen($codExamen);
$pdf->curso = $arr_tmp[0][nombre] . " (" . $arr_tmp[0][codCurso] . ")";

$arr_tmp = get_examen($codExamen);
$pdf->examen = $arr_tmp[0][nombre];

$pdf->preguntas = get_preguntas_de_examen($codExamen);

$n = sizeof($pdf->preguntas);
$pdf->alternativas = array();
for ($i = 0; $i < $n; $i++)
	array_push($pdf->alternativas, get_alternativas_de_pregunta($pdf->preguntas[$i][codPregunta]));

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Informe();
$pdf->Output();

?>