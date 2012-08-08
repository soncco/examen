<div id="header">
  <h1 id="logo"> <a href="/"><span>Sistema de Exámenes</span></a> </h1>
	<?php
  	include "menutop.php";
  ?>
  <?php if(isset($_SESSION['loginuser'])) : ?>
  <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
  <?php endif; ?>
</div>