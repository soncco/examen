<?php

require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
		
	var $font;
	var $curso;
	var $examen;
	var $preguntas;
	var $alternativas;
	
	function Header() {
	}
	
	function Footer() {
		$this->SetY(-13);
		$this->SetFont($this->font,'I',7);
		$this->Cell(0, 10, utf8_decode('Pág. ') . $this->PageNo() . '/{nb}' , 0, 0, 'C');
	}
	
	function Informe() {
		$this->SetFont($this->font, 'B' ,11);	
		$title = utf8_decode("UNIVERSIDAD NACIONAL DE SAN ANTONIO ABAD DEL CUSCO");
		$w = $this->GetStringWidth($title)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,0,$title);
		$this->Ln(4);
		
		$this->SetFont($this->font, 'B' ,10);
		$subtitle = utf8_decode("DEPARTAMENTO DE INFORMÁTICA");
		$w = $this->GetStringWidth($subtitle)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,0,$subtitle);
		$this->Ln(7);
		
		$this->SetFont($this->font, 'B' ,9);
		$this->curso = utf8_decode($this->curso);
		$w = $this->GetStringWidth($this->curso)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,0,$this->curso);
		$this->Ln(4);
		
		$this->examen = utf8_decode($this->examen);
		$w = $this->GetStringWidth($this->examen)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,0,$this->examen);
		$this->Ln(8);		
		
		$this->SetFont($this->font, '' ,7.5);
		
		$apellidosynombres = utf8_decode("APELLIDOS Y NOMBRES:");
		$this->Cell(0, 0, $apellidosynombres);	
		
		$this->SetX(160);
		$codigo = utf8_decode("CÓDIGO:");
		$this->Cell(10, 0, $codigo);
		
		$this->Line($this->GetStringWidth($apellidosynombres)+12, $this->GetY()+1.5, 160, $this->GetY()+1.5);
		$this->Line(174, $this->GetY()+1.5, $this->GetStringWidth($codigo)+188, $this->GetY()+1.5);	
		
		$this->Ln(8);
		
		$i = 1;
		$j_b = 65;
		$j = $j_b;
		$n = sizeof($this->preguntas);
		
		for ($i = 1; $i <= $n; $i++) {
			$this->Cell(0, 3.5, "$i)");
			$this->SetX(16);
			$this->MultiCell(0, 3.5, utf8_decode($this->preguntas[$i-1]['enunciado']));
			$this->Ln();
			foreach ($this->alternativas[$i-1] as $k => $alt) {
        if(!empty($this->preguntas[$i-1]['imagen'])) {
          if ($k == 0) {
            $y = $this->getY();
            $this->Image('archivo/' . $this->preguntas[$i-1]['imagen'], 150, $y, 20);
          }
        }
				$this->SetX(16);
				$this->Cell(0, 3.5, chr($j).")");
				$this->SetX(22);
				$this->MultiCell(0, 3.5, utf8_decode($alt['detalle']));
				
				$j++;
			}
			
			$this->Ln();
			$j = $j_b;
		}
	}
}

global $bcdb;
$pdf = new PDF('P','mm','A4');

$pdf->font = 'Arial';
$codExamen = $_GET['id']; /*VALIDAR QUE SEA ENTERO Y QUE TENGA PERMISOS PARA VER EXAMEN*/

$arr_tmp = get_curso_de_examen($codExamen);
$pdf->curso = $arr_tmp[0]['nombre'] . " (" . $arr_tmp[0]['codCurso'] . ")";

$arr_tmp = get_examen($codExamen);
$pdf->examen = $arr_tmp['nombre'];

$pdf->preguntas = get_preguntas_de_examen($codExamen);

$n = sizeof($pdf->preguntas);
$pdf->alternativas = array();
for ($i = 0; $i < $n; $i++)
	array_push($pdf->alternativas, get_alternativas_de_pregunta($pdf->preguntas[$i]['codPregunta']));

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Informe();
$pdf->Output();

?>
