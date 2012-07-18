<?php
/**
 * Funciones varias
 */

function get_pregunta($codPregunta) {
  global $bcdb;
  
  $q = sprintf("SELECT * FROM $bcdb->pregunta WHERE codPregunta = '%s'", $codPregunta);
  $pregunta = $bcdb->get_row($q);
  
  $q2 = sprintf("SELECT * FROM $bcdb->alternativa WHERE codPregunta = '%s'", $codPregunta); 
  $alternativas = $bcdb->get_results($q2);
  
  foreach ($alternativas as $alternativa) {
    $pregunta['alternativas'][] = $alternativa;
  }
  return $pregunta;
}

function get_cursos_docente($codDocente) {
  global $bcdb;
  
  $q = sprintf("SELECT * 
    FROM %s CA
    INNER JOIN %s C
    ON CA.codCurso = C.codCurso
    WHERE CA.codDocente = '%s'",
      $bcdb->cargaacademica,
      $bcdb->curso,
      $codDocente);
  
  $cursos = $bcdb->get_results($q);
}

/**
* Guarda un usuario
*
* @param int $idusuario El id del usuario
* @return boolean
*/
function save_user($idusuario, $user_values) {
	global $bcdb, $msg;

	if ( $idusuario && get_item($idusuario, $bcdb->admin) ) {
		unset($user_values['usuario']); // We don't want someone 'accidentally' update usuario
	}		
	
	//$user_values['codUsuario'] = $idusuario;
	if ( ($query = insert_update_query($bcdb->usuario, $user_values)) &&
		$bcdb->query($query) ) {
		if (empty($idusuario))	
			$idusuario = $bcdb->insert_id;
		return $idusuario;
	}
	return false;
}

/**
* Es Administrador
*
* @param int $idusuario El id del usuario
* @return boolean
*/
function is_admin ($idusuario) {
	return true;
}

?>