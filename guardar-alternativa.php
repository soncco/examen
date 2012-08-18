<?php

  require_once('home.php');
  require_once('redirect.php');
  
  $op = $_POST['op'];
  $codExamen = $_POST['codExamen'];
  $timestamp = strftime('%Y-%m-%d %H:%M:00', $_POST['ts']);
  $codAlternativa = $_POST['codAlternativa'];
  $codAlumno = (string)$_SESSION['loginuser']['codAlumno'];
  
  switch ($op) {
    case 'insert':
      $sql = sprintf("INSERT INTO %s(codAlumno, codExamen, fecha, codAlternativa) 
                VALUES ('%s', '%s', '%s', '%s')", 
              $bcdb->respuesta,
              $codAlumno,
              $codExamen,
              $timestamp,
              $codAlternativa);
      if(!$bcdb->query($sql)) ajax_error();
    break;
    case 'update':
      $sqlA = "SELECT * FROM $bcdb->alternativa
            WHERE codPregunta IN (
            SELECT codPregunta FROM $bcdb->alternativa WHERE codAlternativa = '$codAlternativa')";
      $alternativas = $bcdb->get_results($sqlA);
      $error = false;
      foreach ($alternativas as $k => $alternativa) {
        $sql = sprintf("UPDATE %s SET codAlternativa = '%s'
                      WHERE codAlumno = '%s'
                      AND codExamen = '%s'
                      AND fecha = '%s'
                      AND codAlternativa = '%s'", 
                $bcdb->respuesta, $codAlternativa, $codAlumno, $codExamen, $timestamp, $alternativa['codAlternativa']);
        $error = ($error && !$bcdb->query($sql));
      }      
      if ($error) ajax_error();
    break;
  }
?>