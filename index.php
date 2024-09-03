<?php

require 'config/config.php';


$db = new Database();
$con = $db->conectar();

$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';

$orders = [
    'asc' => 'nombre ASD',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio DESC',
    'precio_bajo' => 'precio ASC',
];

$order = $orders[$orden] ?? '';

if(!empty($order)){
    $order = " ORDER BY $order";
}

if(!empty($idCategoria)) {
    $sql = $con->prepare("SELECT id, nombre, precio, stock FROM productos WHERE activo=1 AND id_categoria = ? $order");
    $sql->execute([$idCategoria]);
} else {
    $sql = $con->prepare("SELECT id, nombre, precio, stock FROM productos WHERE activo = 1 $order");
    $sql->execute();
}

$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $con->prepare("SELECT id, nombre FROM categoria WHERE activo = 1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);

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
    <link href="<?php echo SITE_URL; ?>css/style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a48d55bdce.js" crossorigin="anonymous"></script>
    <style>
    #carrito {
        display: none;
    }

    .card1{
            width: 250px;
            height: 450px;
        }

    @media (max-width: 990px) {

        #carrito {
            display: block;
            align-items: end;
            align-content: end;
            float: right;
        }

        .card1{
            width: 180px;
            height: 250px;
        }
        
    }

    div {
        word-wrap: break-word;
    }

    .card {
        box-shadow: inset 0 0 10px #02186b;
    }
    .carousel-item_ {
    background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('asset/images/background1.jpg');
    width: 100%;
    height: 350px;
    background-position: top center;
    background-size: cover;
    background-color: rgb(0, 0, 0, 0.5);
    }
    </style>

</head>

<body>

    <?php include 'menu.php'; ?>

    <div class="container"><br><br></div>
    <div class="container"></div>
    <div id="myCarousel" class="carousel slide mb-6 " data-bs-ride="carousel">

        <div class="carousel-inner" >

            <div class="carousel-item_ active" >
                <div class="container-carrusel">
                    <div class="carousel-caption" style="color: white;">
                        <h1>Mariscos Kchin</h1>
                        <p>La pescadería online del pescado fresco</p>
                        <p><a class="btn btn-lg" href="catalogo.php"
                                style="background-color: #02186b; color: white; ">Productos</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <main>
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <h2>¿Como trabajamos?</h2>
            </div>

        </div>
        <div class="container">
            <div
                class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3 d-flex justify-content-center align-items-center">
                <div class="col">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="inicio" src="asset/images/whatsapp.svg" style="width: 120px;" alt="">
                    </div>

                    <p>Enviamos los precios y cogemos pedidos a través de WhatsApp.</p>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="inicio" src="asset/images/fish.svg" style="width: 120px;" alt="">
                    </div>
                    <p>Cada dia seleccionamos el mejor pescado y marisco.</p>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="inicio" src="asset/images/shipping.svg" style="width: 100px; margin-bottom: 5px;" alt="">
                    </div>
                    <p>Lo preparamos en nuestras instalaciones, y llega a casa en 24/48h.</p>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="inicio" src="asset/images/cold.jpeg"
                            style="width: 120px;" alt="">
                    </div>
                    <p>Garantizamos la cadena de frío durante el transporte.</p>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="inicio" src="asset/images/ecos.png" style="width: 120px; background-color: none;" alt="">
                    </div>
                    <p>Ayudamos con el medio ambiente</p>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-center align-items-center">
                        <img class="inicio" src="asset/images/list.jpeg" style="width: 120px;" alt="">
                    </div>
                    <p>Enviamos los precios y cogemos pedidos a través de WhatsApp.</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <h2>¿Que te gustaria?</h2>
            </div>
        </div>
        
        <div class="container">
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 d-flex justify-content-center align-items-center">
                <div class="card1">
                    <div class="card d-flex justify-content-center align-items-center pt-3 pb-3">
                        <img src="asset/images/WhatsApp_icon.png" class="d-block" width="80px">
                        <div class="card-body">
                            <p class="card-text" style="text-align: center;">¿Tienes alguna duda o quieres hacer un
                                pedido?</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn" type="button" style="background-color: #02186b;"><a href="https://wa.link/8jntwq"
                            target="_blank"
                                    style="text-decoration: none; text-decoration-line: none; color: white;">¡Hablemos!</a></button>
                        </div>
                    </div>
                </div>
                <div class="card1">
                    <div class="card d-flex justify-content-center align-items-center pt-3 pb-3">
                        <img src="asset/images/306470.png" class="d-block" width="80px">
                        <div class="card-body ">
                            <p class="card-text" style="text-align: center;">¿Quieres ver nuestra lista de productos y
                                precios?</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn " type="button" style="background-color: #02186b;"><a href="catalogo.php"
                                    style="text-decoration: none; text-decoration-line: none; color: white;">Ver
                                    lista</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script>
    function addProducto(id, token) {

        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('token', token)

        fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if (data.ok) {
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero
                } else {
                    alert("No hay sufucientes existencias")
                }
            })
    }

    function submitOrder() {
        document.getElementById('orderForm').submit();
    }

    function submitCat() {
        document.getElementById('catForm').submit();
    }
    </script>

    <?php require 'footer.php' ?>
</body>

</html>