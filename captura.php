<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';

$payment = $_GET['payment_id'];
$status = $_GET['status'];
$payment_type = $_GET['payment_type'];
$order_id = $_GET['merchant_order_id'];

echo "<h3>Pago exitoso</h3>";

echo $payment.'<br>';
echo $status.'<br>';
echo $order_id.'<br>';

unset($_SESSION['carrito']);
exit;