<?php

  require_once('home.php');
  require_once('redirect.php');
  
  $op = $_POST['op'];
  $codExamen = $_POST['codExamen'];
  $codAlternativa = $_POST['codAlternativa'];
  
  $examen_programado = get_examen_programado();
?>