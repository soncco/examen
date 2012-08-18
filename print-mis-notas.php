<?php

require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
		
	var $font;
	var $semestre;
	var $alumno;
	var $codAlumno;
	var $cursos;
	var $examenes;
	
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
		$subtitle2 = utf8_decode("CONSTANCIA DE NOTAS");
		$w = $this->GetStringWidth($subtitle2)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,0,$subtitle2);
		$this->Ln(4);
		
		$this->SetFont($this->font, 'B' , 8);
		$this->semestre = utf8_decode("SEMESTRE $this->semestre");
		$w = $this->GetStringWidth($this->semestre)+6;
		$this->SetX((210-$w)/2);
		$this->Cell($w,0,$this->semestre);	
		$this->Ln(8);
		
		$this->SetFont($this->font, '' ,7.5);
		$this->Cell(0, 0, utf8_decode("APELLIDOS Y NOMBRES: $this->alumno ($this->codAlumno)"));
		$this->Ln(8);
		
		$this->SetX(22);
		$this->SetFont($this->font, 'B' ,7.5);
		$this->Cell(100,6, utf8_decode('ASIGNATURA / EXAMEN'), 'LRTB');
		$this->Cell(45,6, utf8_decode('FECHA Y HORA'), 'LRTB');
		$this->Cell(15,6, utf8_decode('NOTA'), 'LRTB');
		$this->Ln(6);
		
		$this->SetFont($this->font, '' ,7.5);
		foreach ($this->cursos as $k => $curso) {
			$this->SetX(22);
			$this->Cell(160,6, utf8_decode("   " . $curso['nombre'] . " (" . $curso['codCurso'] . ")"), 'LRTB');
			$this->Ln(6);
			
			$this->examenes = get_examenes_rendidos_de_alumno($_SESSION['loginuser']['codAlumno'], $curso['codCurso'], get_option('semestre_actual'));
			foreach ($this->examenes as $k => $examen) {
				$nota = get_nota_examen($_SESSION['loginuser']['codAlumno'], $examen['codExamen'], $examen['fecha']);
				$this->SetX(22);
				$this->Cell(100,6, utf8_decode("      " .$examen['examen']), 'LRTB');
				$this->Cell(45,6, utf8_decode($examen['fechaF']), 'LRTB');
				$this->Cell(15,6, utf8_decode(str_pad($nota[0]['nota'], 2, '0', STR_PAD_LEFT)), 'LRTB');
				$this->Ln(6);				
			}
		}
	}
}

global $bcdb;
$pdf = new PDF('P','mm','A4');
$pdf->font = 'Arial';

$pdf->semestre = get_option('semestre_actual');
$pdf->codAlumno = $_SESSION['loginuser']['codAlumno'];
$pdf->alumno  = $_SESSION['loginuser']['apellidoP'] . "-" . $_SESSION['loginuser']['apellidoM'] . "-" .$_SESSION['loginuser']['nombres'];
$pdf->cursos = $cursos = get_cursos_con_examenes_rendidos($_SESSION['loginuser']['codAlumno'], get_option('semestre_actual'));

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Informe();
$pdf->Output();

?>
