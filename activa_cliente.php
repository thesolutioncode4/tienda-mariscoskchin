<?php 

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';


$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';
$activada = isset($_GET['activacion']) ? $_GET['activacion'] : '';

if($id == '' || $token == '') {

    header("Location: index.php");
    exit;
}

if($token == ''){

    header("Location: index.php");
    exit;
}


$db = new Database();
$con = $db->conectar();

echo validaToken($id, $token, $con);

?>