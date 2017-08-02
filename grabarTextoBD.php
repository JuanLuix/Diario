<?php

session_start();



if (array_key_exists('contenido',$_POST)){

    

    require('conexion.php');//CONEXION A LA BASE DE DATOS

    if (!$enlace) {
        $error ="Error MySQL. Sin conexión!!!" .PHP_EOL. "Error de depuración: " . mysqli_connect_errno() . PHP_EOL. " Error de depuración: " . mysqli_connect_error() . PHP_EOL;
        $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>" .$error. "</strong></div>";
        //exit;

    } else{

        $query="UPDATE tab_usuarios SET texto = '".$_POST['contenido']."' WHERE usuario = '".$_SESSION['user']."'";

        mysqli_query($enlace,$query);

    }
}

?>