<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';
$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if(!empty($_POST)){
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';

    if(esNulo([$usuario, $password])){
        $errors[] = "Debe llenar todos los campos";
    }
    if(count($errors) == 0){
        $errors[] = login($usuario, $password, $con, $proceso);
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
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    <div class="container"><br></div>
    <div class="container"><br></div>
    <div class="container"><br></div>
    <div class="container">
        <main class="form-login m-auto pt-4" style="max-width: 350px;">
            <h2>Iniciar sesion</h2>


            <?php mostrarMensajes($errors); ?>
            <form class="row g-3" action="login.php" method="post" autocomplete="off">
                <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
                <div class="form-floating">
                    <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario">
                    <label for="usuario">Usuario</label>
                </div>
                <div class="form-floating">
                    <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña">
                    <label for="password">Contraseña</label>
                </div>
                <div class="col-12">
                    <a href="recupera.php">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="d-grid gap-3 col-12">
                    <button class="btn btn-primary" type="submit">Ingresar</button>
                </div>
                <hr>
                <div class="col-12">
                    ¿No tienes cuenta? <a href="registro.php">Registrate aqui</a>
                </div>
            </form>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>