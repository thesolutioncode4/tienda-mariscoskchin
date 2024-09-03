<?php

require_once '../config/config.php';

$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

if(is_array($datos)){

    $id_client = $_SESSION['user_cliente'];
    $sql = $con->prepare("SELECT email, nombres, apellidos, telefono, direccion FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_client]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);

    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units']['0']['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $row_cliente['email'];
    $nombres = $row_cliente['nombres'];
    $apellidos = $row_cliente['apellidos'];
    $telefono = $row_cliente['telefono'];
    $direccion = $row_cliente['direccion'];
    $sql = $con->prepare("INSERT INTO compra(id_transaccion, fecha, status, email, id_cliente, total, medio_pago) VALUES (?,?,?,?,?,?,?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_client, $total, 'PayPal']);
    $id = $con->lastInsertId();

    if ($id > 0){
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if($productos != null){
            foreach($productos as $clave=> $cantidad ){
                $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);

                $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                if($sql_insert->execute([$id, $clave, $row_prod['nombre'], $precio_desc, $cantidad])){
                    restarStock($row_prod['id'], $cantidad, $con);
                }
            }

            require 'Mailer.php';

            $asunto = "Detalles de su pedido";
            $cuerpo = "<h4>Gracias por su compra</h4>";
            $cuerpo .= '<p>El id de su compra es: <b>' . $id_transaccion . '</b></p>';

            $mailer = new Mailer();
            $mailer->enviarEmail($email, $asunto, $cuerpo);
            unset($_SESSION['carrito']);
        }
        unset($_SESSION['carrito']);
    }
}

function restarStock($id, $cantidad, $con){
    $sql = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? LIMIT 1");
    $sql->execute([$cantidad, $id]);
}