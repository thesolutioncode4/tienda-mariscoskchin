<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';


$error = '';
if($id_transaccion == ''){
  $error = 'Error al procesar la peticion';
} else {
  $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
        $sql->execute([$id_transaccion, 'COMPLETED']);
        if($sql->fetchColumn() > 0) {

            $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
            $sql->execute([$id_transaccion, 'COMPLETED']);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $idCompra = $row['id'];
            $total = $row['total'];
            $fecha = $row['fecha'];

            $id_client = $_SESSION['user_cliente'];
            $sql = $con->prepare("SELECT email, nombres, apellidos, telefono, direccion FROM clientes WHERE id=? AND estatus=1");
            $sql->execute([$id_client]);
            $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);
            $email = $row_cliente['email'];
            $nombres = $row_cliente['nombres'];
            $apellidos = $row_cliente['apellidos'];
            $telefono = $row_cliente['telefono'];
            $direccion = $row_cliente['direccion'];

            $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
            $sqlDet->execute([$idCompra]);
        } else {
          $error = 'Error al comprobar la compra';
        }
          
}

unset($_SESSION['carrito']);

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
            <?php if(strlen($error) > 0){ ?>
            <div class="row">
                <div class="col">
                    <h3><?php echo $error ?></h3>
                </div>
            </div>
            <?php } else { ?>
            <div class="row">
                <div class="col">
                    <b>Orden de compra: </b><?php echo $id_transaccion; ?><br>
                    <b>Nombre:  </b><?php echo '  ', $nombres,'  ', $apellidos; ?><br>
                    <b>Email: </b><?php echo $email; ?><br>
                    <b>Telefono: </b><?php echo $telefono; ?><br>
                    <b>Direccion: </b><?php echo $direccion; ?><br>
                    <b>Fecha de compra: </b><?php echo $fecha; ?><br>
                    <b>Total: </b><?php echo moneda . number_format($total, 2, '.', ','); ?><br>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) { 
                              $importe = $row_det['precio'] * $row_det['cantidad']; ?>
                            <tr>
                                <td><?php echo $row_det['cantidad']; ?>   K/g</td>
                                <td><?php echo $row_det['nombre']; ?></td>
                                <td><?php echo moneda.' '.$importe; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    
</body>

</html>