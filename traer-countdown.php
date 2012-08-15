<?php

require_once('home.php');
require_once('redirect.php');

$codExamen = $_POST['codExamen'];

print get_countdown($codExamen);
exit();
?>