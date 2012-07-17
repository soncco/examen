<?php
  require_once('home.php');
	//require_once('redirect.php');
  
  $consulta = "SELECT * FROM tusuario";
  
  $usuarios = $bcdb->get_results($consulta);
  
  $consulta = "SELECT * FROM tusuario WHERE codUsuario = 0";
  
  $usuariox = $bcdb->get_row($consulta);
  
  //krumo($usuarios);
  
  $q = "SELECT nombres FROm tusuario WHERE codUsuario = 0";
  
  $nombres = $bcdb->query($q);
  
  print $nombres;
  
?>
<table id="tablita">
  <?php foreach($usuarios as $k => $usuario) : ?>
  <tr>
    <td><?php print $usuario['nombres']; ?></td>
    <td><?php print $usuario['apellidoP']; ?></td>
  </tr>
  <?php endforeach; ?>
</table>
