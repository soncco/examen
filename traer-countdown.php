<?php

require_once('home.php');
require_once('redirect.php');

$codExamen = $_POST['codExamen'];
$timestamp = strftime('%Y-%m-%d %H:%M:00', $_POST['ts']);

print get_countdown($codExamen, $timestamp);
exit();
?>