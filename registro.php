<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!empty($_POST)){

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = $_POST['direccion'];
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if(esNulo([$nombres, $apellidos, $email, $telefono, $direccion, $usuario, $password, $repassword])){
        $errors[] = "Debe llenar todos los campos";
    }

    if(!esEmail($email)){
        $errors[] = "La direccion de correo no es valida";
    }

    if(!validaPassword($password, $repassword)){
        $errors[] = "Las contraseñas no coinciden";
    }

    if(usuarioExiste($usuario, $con)){
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if(emailExiste($email, $con)){
        $errors[] = "El correo $email ya existe";
    }

    if(count($errors) == 0){
    
        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $direccion], $con);
        if($id > 0){
            require 'clases/mailer.php';
            $mailer = new Mailer();
            $token = generarToken();   
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);
            if($idUsuario > 0){

                $url = SITE_URL . 'activa_cliente.php?id='.$idUsuario .'&token='. $token;
                $asunto = "Activar cuenta Mariscos Kchin";
                $cuerpo = "Estimados $nombres: <br> Para continuar con el proceso de registro por favor confirme su cuenta <a href='$url'>Activar cuenta</a>";

                if($mailer->enviarEmail($email, $asunto, $cuerpo)){
                    echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado a la direccion de correo electronico $email. <a href='index.php'>Volver al inicio</a>";
                    exit;
                } 
            } else {
                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mariscos Kchin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a48d55bdce.js" crossorigin="anonymous"></script>
    <style>
    #carrito {
        display: none;
    }

    @media (max-width: 990px) {
        #carrito {
            display: block;
            align-items: end;
            align-content: end;
            float: right;
        }
    }

    div {
        word-wrap: break-word;
    }

    .card{
        box-shadow: inset 0 0 10px #02186b;
    }
    </style>

</head>

<body>
<?php include 'menu.php'; ?>

    <main>
        <div class="container"></div>
        <div class="container">
            <h2>Datos del cliente</h2>
            <?php mostrarMensajes($errors); ?>
            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <!-- nombres -->
                    <label for="nombres"><span class="text-danger">*</span> Nombre</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <!-- apellidos -->
                    <label for="apellidos"><span class="text-danger">*</span> Apellido</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <!-- email -->
                    <label for="email"><span class="text-danger">*</span> Email</label>
                    <input type="email" name="email" id="email" class="form-control" require>
                    <span id="validaEmail" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <!-- telefono -->
                    <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <!-- direccion -->
                    <label for="direccion"><span class="text-danger">*</span> Direccion</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <!-- usuario -->
                    <label for="usuario"><span class="text-danger">*</span> Nombre de usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" require>
                    <span id="validaUsuario" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <!-- password -->
                    <label for="password"><span class="text-danger">*</span> Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <!-- repassword -->
                    <label for="repassword"><span class="text-danger">*</span> Repetir contraseña</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" require>
                </div>
                <i><b>Nota: </b>Los campos con asterisco (*) son obligatorios</i>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>

        </div>
        </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script>
    let txtUsuario = document.getElementById('usuario')
    txtUsuario.addEventListener("blur", function() {
        existeUsuario(txtUsuario.value)
    }, false)

    let txtEmail = document.getElementById('email')
    txtEmail.addEventListener("blur", function() {
        existeEmail(txtEmail.value)
    }, false)

    function existeUsuario() {

        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "existeUsuario")
        formData.append("usuario", usuario)

        fetch(url, {
                method: 'POST',
                body: formData,
            }).then(response => response.json())
            .then(data => {

                if (data.ok) {
                    document.getElementById('usuario').value = ''
                    document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                } else {
                    document.getElementById('validaUsuario').innerHTML = ''
                }
            })
    }

    function existeEmail() {

        let url = "clases/clienteAjax.php"
        let formData = new FormData()
        formData.append("action", "existeEmail")
        formData.append("email", email)

        fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {

                if (data.ok) {
                    document.getElementById('email').value = ''
                    document.getElementById('validaEmail').innerHTML = 'El correo ya se encuentra registrado'
                } else {
                    document.getElementById('validaEmail').innerHTML = ''
                }
            })
    }
    </script>
</body>

</html>