
        <!-- jQuery first, then Tether, then Bootstrap JS. -->
        <!-- <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $("#cambiarFormulario").mouseover(function() {
                    $("#cambiarFormulario").css('cursor', 'pointer');
                    $("#cambiarFormulario").css('color', 'red');

                });

                $("#cambiarFormulario").mouseout(function() {
                    $("#cambiarFormulario").css('color', 'black');
                });

                $("#cambiarFormulario").on("click", function() {
                    $("#subtitulo2").toggle();
                    $("#subtitulo1").toggle();
                    if ($("#cambiarFormulario").text() == "Iniciar Sesión") {
                        $("#cambiarFormulario").text("Registrarse");
                        $("#botonEnviar").text("Accede!");
                        $("#registro").attr("value", 0);

                    } else {
                        $("#cambiarFormulario").text("Iniciar Sesión");
                        $("#botonEnviar").text("Regístrate!");
                        $("#registro").attr("value", 1);
                    }
                });

                $("#formulario").submit(function() {
                    var error = "";

                    if ($("#txtEmail").val() == "") {
                        error += "campo email vacío.<br>";
                    }

                    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
                    if ((caract.test($("#txtEmail").val()) == false) && (error == "")) {
                        error += "campo email. Formato inválido.<br>";
                    }

                    if (($("#txtPassword").val().length < 8) && (error == "")) {
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

            //EVENTO PARA CUANDO ESCRIBIMOS SE ACTUALICE AUTOMATICAMENTE EN MYSQL
            $("#diario").on('input propertychange', function() { //EVENTO ESCRIBIR
                
                // $.ajax({
                //     method: "POST",
                //     url: "grabarTextoBD.php",
                //     data: { contenido : $("#diario").val() }

                // })
                
               
                $.post("grabarTextoBD.php",
                
                    {contenido: $("#diario").val()}
                    
                    /*,function(data, status){
                        alert("Data: " + data + "\nStatus: " + status);
                    }*/
                    
                    );

            });



            });


            


        </script>
    </body>

    </html>