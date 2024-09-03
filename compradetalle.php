<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if($orden == null || $orden == null || $token != $token_session) {
    header('Location: compras.php');
    exit;
}

$db = new Database();
$con = $db->conectar();


$sqlCompra = $con->prepare("SELECT id, id_transaccion, fecha, total, medio_pago FROM compra WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idCompra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d/m/Y H:i:s');
$sqlDetalle = $con->prepare("SELECT id, id_compra, id_producto, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ? ");
$sqlDetalle->execute([$idCompra]);

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
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Detalle de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong><?php echo $fecha; ?></p>
                            <p><strong>Orden: </strong><?php echo $rowCompra['id_transaccion']; ?></p>
                            <p><strong>Total:
                                </strong><?php echo moneda . ' ' . number_format($rowCompra['total'], 2, '.', ','); ?>
                            </p>
                            <p><strong>Metodo de pago: </strong><?php echo $rowCompra['medio_pago']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)){
                                    $precio = $row['precio']; 
                                    $cantidad = $row['cantidad'];                                    
                                    $subtotal = $precio * $cantidad; 
                                    ?>
                                <tr>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo moneda . ' ' . number_format($precio, 2, '.', ','); ?></td>
                                    <td><?php echo $cantidad; ?></td>
                                    <td><?php echo moneda . ' ' . number_format($subtotal, 2, '.', ','); ?></td>
                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>