<?php

  require_once('home.php');
  require_once('redirect.php');
  
  $op = $_POST['op'];
  $codExamen = $_POST['codExamen'];
  $timestamp = strftime('%Y-%m-%d %H:%M:00', $_POST['ts']);
  $codPregunta = $_POST['codPregunta'];
  $codAlternativa = $_POST['codAlternativa'];
  $codAlumno = (string)$_SESSION['loginuser']['codAlumno'];
  
  switch ($op) {
    case 'insert':
      $sql = sprintf("INSERT INTO %s(codAlumno, codExamen, fecha, codAlternativa, codPregunta) 
                VALUES ('%s', '%s', '%s', '%s', '%s')", 
              $bcdb->respuesta,
              $codAlumno,
              $codExamen,
              $timestamp,
              $codAlternativa,
              $codPregunta);
      if(!$bcdb->query($sql)) ajax_error();
      //else echo "Guardado: $sql";
    break;
    case 'update':
        $sql = sprintf("UPDATE %s SET codAlternativa = '%s'
                      WHERE codAlumno = '%s'
                      AND codExamen = '%s'
                      AND fecha = '%s'
                      AND codPregunta = '%s'",
                $bcdb->respuesta, $codAlternativa, $codAlumno, $codExamen, $timestamp, $codPregunta);

      if(!$bcdb->query($sql)) ajax_error();
      //else echo "Actualizado: $sql";
    break;
  }
?>