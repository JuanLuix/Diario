<?php

session_start();

if (array_key_exists("user",$_COOKIE) && $_COOKIE['user']){

    $_SESSION['user']=$_COOKIE['user'];
}

if (array_key_exists("user",$_SESSION) && $_SESSION['user']){

    // echo "<p>Sesión iniciada. <a href='index.php?Logout=1'>Cerrar Sesión</a></p>";
    require('conexion.php');

}else{

    header ("Location: index.php");
}


?>