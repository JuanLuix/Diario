<?php

session_start();

$error="";
$mensajeExito="";
$ponerEmail="";
global $enlace;

if (array_key_exists("Logout",$_GET)){//VIENE DE LA PAGINA SESION INICIADA

    session_unset();
    setcookie("user", "", time()-60*60);
    $_COOKIE['user']="";//no haría falta con la linea de arriba
    
} else if((array_key_exists("user",$_SESSION) AND $_SESSION['user']) OR (array_key_exists("user",$_COOKIE) AND $_COOKIE['user'])){

    header ("Location: sesionIniciada.php");
}



// if($_POST){
if(array_key_exists("submit",$_POST)){
        header("Content-type: text/html;charset:'utf-8'");

        function prevenirInyeccionSql($cadena) {
            $nopermitidos = array("'",'\\','<','>',"\"",";","#","--");
            $cadena = str_replace($nopermitidos, "", $cadena);
            return $cadena;
        };
        
        $email = prevenirInyeccionSql($_POST["txtEmail"]); //limpio la entrada POST  
        $pass = prevenirInyeccionSql($_POST["txtPassword"]); //limpio la entrada POST  

        if ((!$email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $error .= "Cuenta de correo errónea<br>";
        }

        if (strlen($pass) < 8){
            $error .= "Password obligatoria y al menos de 8 caracteres<br>";    
        }

        if ($error !=""){
            $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>Se encontraron los siguientes errores: <br>" .$error. "</strong></div>";
        } else{

            require('conexion.php');

            if (!$enlace) {
                $error ="Error: No se pudo conectar a MySQL." .PHP_EOL. "Errno de depuración: " . mysqli_connect_errno() . PHP_EOL. " Error de depuración: " . mysqli_connect_error() . PHP_EOL;
                $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>" .$error. "</strong></div>";
                //exit;
            } else{
                
                if ($_POST["registro"]==1){//SI ESTAMOS DANDO DE ALTA UN NUEVO USUARIO
                
                    $query="SELECT * FROM tab_usuarios WHERE usuario = '".$email."'";
                    
                    if($resultado=mysqli_query($enlace,$query)){
                        
                        if (mysqli_num_rows($resultado)==0){//no existe email en BB.DD se procede al alta
                        
                            //insertamos el registro y encriptamos la clave la funcion hash --> md5
                            
                            $query="INSERT INTO tab_usuarios (usuario, password, timestamp) VALUES ('".$email."','".md5($pass)."','".date('Y-m-d H:i:s')."')";
                            
                            if(mysqli_query($enlace,$query)){

                                $_SESSION['user']=$email;

                                if ($_POST['check-iniciada']=='1'){
                                    
                                    setcookie("user", $email, time()+60*60*24*365); //un año se guarda la cookie
                                }
                                header("location: sesionIniciada.php");
                                /*$mensajeExito= "Se ha dado de alta el email: ".$email;
                                $mensajeExito="<div class=\"alert alert-success\" role=\"alert\"><strong>".$mensajeExito."</strong></div>";*/
                                
                            } else{

                                $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>Error al intentar el alta del email en la BB.DD. Inténtelo más tarde.</strong></div>";

                            }

                        } else{//en caso de que exista el usuario: mensaje de error

                            $error= "Email no válido!!! Ya existe en BB.DD.: ".$email;
                            $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>".$error."</strong></div>";
                            $ponerEmail="value=".$email;
                            
                            /*while($fila=mysqli_fetch_array($resultado)){
                            echo "<br>Clave de registro es: " .$fila['id']."<br>";
                            echo "Tu nombre de usuario es: " .$fila['username']."<br>";
                            echo "Tu contraseña es: " .$fila["password"]."<br>";
                            echo "-------------------------------<br>";

                            }*/
                    
                        }
                        
                    } else{

                        $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>Error en tabla usuarios de la BB.DD. Inténtelo más tarde.</strong></div>";
                    }
                
                } else{//SI ESTAMOS COMPROBANDO QUE EL USUARIO REGISTRADO QUIERE ACCEDER
                    // alert("usuario registrado");
                    $query="SELECT * FROM tab_usuarios WHERE usuario = '".$email."'";
                    
                    $resultado=mysqli_query($enlace,$query);

                    $fila=mysqli_fetch_array($resultado);

                    if (isset($fila)){//isset devuelve TRUE si no es null

                        $passwordHasheada=md5($pass);

                        if($passwordHasheada==$fila['password']){//usuario autenticado

                            $_SESSION['user']=$email;

                            if ($_POST['check-iniciada']=='1'){
                                    
                                setcookie("user", $email, time()+60*60*24*365); //un año se guarda la cookie
                            }
                            
                            header("location: sesionIniciada.php");

                        } else{

                            $error="<div class=\"alert alert-danger\" role=\"alert\"><strong>Email/Password incorrecta!!!</strong></div>";
                            $ponerEmail="value=".$email;
                        }

                    }
                }

                mysqli_close($enlace);   
                
            }

        }
    }


?>



<!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <title>Diario Secreto</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> 

        <style type=text/css>
             html {
                background: url(img/escritorio.jpeg) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            } 
            
            body {
                background: none;
            } 
            
            .container {
                text-align: center;
                margin-top: 100px;
                color: black;
                /* width: 600px; */
            }

            #mensaje{
                margin-top: 25px;
 
            }

            #subtitulo2{

                display: none;
            }

            #cambiarFormulario{

                text-decoration: underline;

            }

        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-4"></div>

                    <div class="col-4">
                        <h1 id="titulo"><strong>Diario Secreto</strong></h1>
                        <h6><strong>Guarda tus pensamientos para siempre</strong></h6>
                        <p id="subtitulo1">¿Estás interesad@? ¡Regístrate ahora!</p>
                        <p id="subtitulo2">Inicia sesión con tu email/contraseña</p>
                    
                        <form id="formulario" method="POST">
                            <fieldset class="form-group">
                                <input type="email"  maxlength="50" size="50" class="form-control" id="txtEmail" name="txtEmail" placeholder="Tu email" required <?php echo $ponerEmail?> >
                            </fieldset>
                            <fieldset class="form-group">
                                <input type="password"  maxlength="15" size="15" class="form-control" id="txtPassword" name="txtPassword" placeholder="Contraseña" required>
                            </fieldset>
                            <div class="form-check has-warning">
                                <label class="form-check-label">
                                <input type="checkbox" name="check-iniciada" class="form-check-input"> Recordar sesión</label>
                            </div>
                            <fieldset class="form-group">
                                <input type="hidden" id="registro" name="registro" value=1>
                                <button type="submit" name="submit" class="btn btn-success" id="botonEnviar">Regístrate!</button>
                            </fieldset>
                        </form>

                         <p><a id="cambiarFormulario">Iniciar Sesión</a></p> 
                        <!-- <p><button type="button" id="cambiarFormulario">Iniciar Sesión</button></p> -->

                        <div id="mensaje" class="alert" role="alert">
                            <?php echo $mensajeExito.$error; ?>
                        </div>

                    <div>

                <div class="col-4"></div>
            <div>

        </div>



        <!-- jQuery first, then Tether, then Bootstrap JS. -->
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
		 <script type="text/javascript">
            $(document).ready(function() {

                 $("#cambiarFormulario").mouseover(function(){
                    $("#cambiarFormulario").css('cursor','pointer');
                    $("#cambiarFormulario").css('color','red');

                });
                
                $("#cambiarFormulario").mouseout(function(){
                    $("#cambiarFormulario").css('color','black');
                });

                $("#cambiarFormulario").on("click",function(){
                    $("#subtitulo2").toggle();
                    $("#subtitulo1").toggle();
                    if ($("#cambiarFormulario").text()=="Iniciar Sesión"){
                        $("#cambiarFormulario").text("Registrarse");
                        $("#botonEnviar").text("Accede!");
                        $("#registro").attr("value",0);

                    }else{
                        $("#cambiarFormulario").text("Iniciar Sesión");
                        $("#botonEnviar").text("Regístrate!");
                        $("#registro").attr("value",1);
                    }
                });

                $("#formulario").submit(function() {
                    var error = "";

                    if ($("#txtEmail").val() == "") {
                        error += "campo email vacío.<br>";
                    }

                    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
                    if ((caract.test($("#txtEmail").val()) == false) && (error=="")){
                        error += "campo email. Formato inválido.<br>";
                    }
                    
                    if (($("#txtPassword").val().length < 8) && (error=="")){
                        error += "campo password. Debe tener al menos 8 carácteres.<br>";
                    }
            
                    if (error != "") {
                        $("#mensaje").addClass("alert-danger");
                        $("#mensaje").html("Error en " + error);

                        return false;
                        
                    } else {
                        return true;
                        /*if ($("#mensaje").hasClass("alert-danger")) {
                            $("#mensaje").removeClass("alert-danger");
                        }
                        $("#mensaje").addClass("alert-success");
                        $("#mensaje").html("Mensaje enviado con éxito");*/
                    }
                });
            });
                
        </script>
    </body>

    </html>