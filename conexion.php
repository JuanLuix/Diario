<?php
// Carga la configuración 
$config = parse_ini_file('config.ini');

// Conexión con los datos del 'config.ini'
$enlace = mysqli_connect($config['hostname'],$config['username'],$config['password'],$config['dbname']);

?>