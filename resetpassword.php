<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if($user_id == '' || $token == '') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!verificaTokenRequest($user_id, $token, $con)){
    echo "No se pudo verificar la informacion";
    exit;
}

if(!empty($_POST)){

    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if(esNulo([$user_id, $token, $password, $repassword])){
        $errors[] = "Debe llenar todos los campos";
    }

    if(!validaPassword($password, $repassword)){
        $errors[] = "Las contraseñas no coinciden";
    }

    if(count($errors) == 0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if(actualizaPassword($user_id, $pass_hash, $con)){
            echo "Contraseña modificada. <br><a href='login.php'>Iniciar sesion</a>";
            exit;
        } else {
            $errors[] = "Error al modificar la contraseña. Intentalo nuevamente";
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
        <h3>Cambiar contraseña</h3>
        <?php mostrarMensajes($errors); ?>
        <form action="resetpassword.php" method="post" class="row g3" autocomplete="off">
            <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
            <input type="hidden" name="token" id="token" value="<?= $token; ?>">
            <div class="form-floating">
                <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña"
                    required>
                <label for="password">Nueva contraseña</label>
            </div>
            <div class="form-floating">
                <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña"
                    required>
                <label for="repassword">Confirmar contraseña</label>
            </div>
            <div class="d-grid gap-3 col-12">
                <button class="btn btn-primary" type="submit">Continuar</button>
            </div>
            <div class="col-12">
                <a href="login.php">Iniciar sesion</a>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>