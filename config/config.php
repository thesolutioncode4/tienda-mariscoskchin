<?php

$path = dirname(__FILE__);

require_once $path.'/database.php';

$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datosConfig = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];
foreach( $datosConfig as $datoConfig ){
    $config[$datoConfig['nombre']] = $datoConfig['valor'];
}



// configuracion del sistema
define("SITE_URL", "http://localhost/kchinrepositorio/");
define("KEY_TOKEN", "APR.qwe-354*");
define("moneda", "€");
define("NAME_PAGE", $config['tienda_nombre']);
// tienda_nombre

// configuracion para paypal
define("CLIENT_ID", $config['id_paypal']);
define("CURRENCY", "EUR");

// configuracion para envio de correo
define("MAIL_HOST", $config['correo_smtp']);
define("MAIL_USER", $config['correo_email']);
define("MAIL_PASS", $config['correo_pass']);
define("MAIL_PORT", $config['correo_puerto']);

session_name('ecommerce_session');
session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}
