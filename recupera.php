<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';
$db = new Database();
$con = $db->conectar();

$errors = [];

if(!empty($_POST)){

    $email = trim($_POST['email']);

    if(esNulo([$email])){
        $errors[] = "Debe llenar todos los campos";
    }

    if(count($errors) == 0){
        if(emailExiste($email, $con)){
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios 
            INNER JOIN clientes ON usuarios.id_cliente=clientes.id 
            WHERE clientes.email LIKE ? LIMIT 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

            $token = solicitaPassword($user_id, $con);

            if($token !== null){
                require 'clases/mailer.php';
                $mailer = new Mailer();
                $url = SITE_URL . 'resetpassword.php?id='.$user_id .'&token='. $token;
                $asunto = "Recuperar contraseña Mariscos Kchin";
                $cuerpo = "Estimados $nombres: <br> Si has solicitado el cambio de tu contraseña da click en el siguiente enlace <a href='$url'>Restablecer contraseña</a>";
                $cuerpo.= "<br><br> Si no hiciste esta solicitud puedes ignorar este correo";

                if($mailer->enviarEmail($email, $asunto, $cuerpo)){
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p>Hemos enviado un correo a la direccion $email para restablecer la contraseña. <a href='index.php'>Volver al inicio</a></p>";
                    exit;
                } 
            }
        } else {
            $errors[] = "No existe una cuenta asociada a esta direccion de correo";
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

    <main class="form-login m-auto pt-4" style="max-width: 350px;">
        <div class="container"></div>
        <h3>Recuperar contraseña</h3>
        <?php mostrarMensajes($errors); ?>
        <form action="recupera.php" method="post" class="row g3" autocomplete="off">
            <div class="form-floating">
                <input class="form-control" type="email" name="email" id="email" placeholder="Correo electronico"
                    required>
                <label for="email">Correo electronico</label>
            </div>
            <div class="d-grid gap-3 col-12">
                <button class="btn btn-primary" type="submit">Continuar</button>
            </div>
            <div class="col-12">
                ¿No tienes cuenta? <a href="registro.php">Registrate aqui</a>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>