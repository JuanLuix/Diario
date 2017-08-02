<?php

session_start();

if (array_key_exists("user",$_COOKIE) && $_COOKIE['user']){

    $_SESSION['user']=$_COOKIE['user'];
}

if (array_key_exists("user",$_SESSION) && $_SESSION['user']){

    // echo "<p>Sesión iniciada. <a href='index.php?Logout=1'>Cerrar Sesión</a></p>";
    
    require('conexion.php');//CONEXION A LA BASE DE DATOS

    if (!$enlace) {
        $error ="Error MySQL. Sin conexión!!!" .PHP_EOL. "Error de depuración: " . mysqli_connect_errno() . PHP_EOL. " Error de depuración: " . mysqli_connect_error() . PHP_EOL;
        $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>" .$error. "</strong></div>";
        //exit;

    } else{

        $query="SELECT texto FROM tab_usuarios WHERE usuario = '".$_SESSION['user']."'";

        $resultado=mysqli_query($enlace,$query);

        $fila=mysqli_fetch_array($resultado);

        $textoDiario=$fila['texto'];

    }

}else{

    header ("Location: index.php");
}


include("header.php");

?>

<nav class="navbar navbar-toggleable-md navbar-light bg-faded">

  <a class="navbar-brand" href="">Diario Secreto</a>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-md-0">
    </ul>
    <div class="form-inline mt-2 mt-md-0">
      <a href="index.php?Logout=1"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cerrar Sesión</button></a>
    </div>
  </div>
</nav>

<div class="container-fluid" id="contenedorSesionIniciada">
    <textarea rows="" cols="" id="diario" class="form-control"><?php echo $textoDiario; ?></textarea>
</div>


<?php include("footer.php"); ?>
