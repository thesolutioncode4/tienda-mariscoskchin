<?php

require_once 'config/config.php';


$db = new Database();
$con = $db->conectar();

$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = $_GET['q'] ?? '';

$filtro = '';

$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio DESC',
    'precio_bajo' => 'precio ASC',
];

$order = $orders[$orden] ?? '';

if(!empty($order)){
    $order = " ORDER BY $order";
}

$params = [];

$sql = $con->prepare("SELECT id, slug, nombre, precio, descuento, descripcion, stock FROM productos WHERE activo = 1 $filtro $order");

if($buscar != ''){
    $filtro = "AND (nombre LIKE '%$buscar%' || descripcion LIKE '%$buscar%')";
}

if(!empty($idCategoria)) {
    $sql = $con->prepare("SELECT id, slug, nombre, precio, descuento, descripcion, stock FROM productos WHERE activo = 1 $filtro AND id_categoria = ? $order");
    $sql->execute([$idCategoria]);
    
} else {
    $sql = $con->prepare("SELECT id, slug, nombre, precio, descuento, descripcion, stock FROM productos WHERE activo = 1 $filtro $order");
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

    <main class="flex-shrink-0">
        <div class="container"></div>
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <h2>Productos</h2>
            </div>

        </div>
        <div class="container">
            <div class="mb-2">
                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 justify-content-end g-4">
                    <div class="col mb-2">
                        <form action="catalogo.php" id="catForm" method="get">
                            <label for="categorias" class="form-label">Categoria</label>
                            <select name="cat" id="cat" class="form-select form-select-sm" onchange="submitCat()">
                                <option value="catalogo.php"
                                    class="list-group-item list-group-item-action <?php if($idCategoria == '') echo 'active'?>">
                                    Seleccione
                                </option>
                                <option value="">
                                    <a href="catalogo.php"
                                        class="list-group-item list-group-item-action <?php if($idCategoria == '') echo 'active'?>">
                                        Todos
                                    </a>
                                </option>
                                <?php foreach($categorias as $categoria) { ?>
                                <option value="<?php echo $categoria['id']; ?>"><a
                                        href="catalogo.php?cat=<?php echo $categoria['id']; ?>"
                                        class="list-group-item list-group-item-action <?php if($idCategoria == $categoria['id']) echo 'active'?>">
                                        <?php echo $categoria['nombre']; ?>
                                    </a>
                                </option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>

                    <!-- ordenar por precios -->

                    <div class="col mb-2">
                        <form action="catalogo.php" id="orderForm" method="get">
                            <input type="hidden" name="cat" id="cat" value="<?php echo $idCategoria; ?>">
                            <label for="categoria" class="form-label">Por orden</label>
                            <select name="orden" id="orden" class="form-select form-select-sm" onchange="submitOrder()">
                                <option value="">
                                    Seleccione</option>
                                <option value="precio_alto" <?php echo ($orden === 'precio_alto') ? 'selected' : ''; ?>>
                                    Precios mas altos</option>
                                <option value="precio_bajo" <?php echo ($orden === 'precio_bajo') ? 'selected' : ''; ?>>
                                    Precios mas bajos</option>
                                <option value="asc" <?php echo ($orden === 'acs') ? 'selected' : ''; ?>>Nombre A-Z
                                </option>
                                <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>Nombre Z-A
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row ">

                <!-- ordenar por precios -->

                <div class="col-12">

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        <?php foreach($resultado as $row) { ?>
                        <div class="col">
                            <div class="card">
                                <?php
                                $id = $row['id'];
                                $imagen = "asset/images/productos/" . $id . "/principal.jpg" ;
                                if(!file_exists($imagen)){
                                    $imagen = "asset/images/no-photo.jpg";
                                } 
                                ?>
                                <div class="mx-auto" style="width: 265px; height: 240px; margin-top:15px; box-shadow: 0 0 10 rgb(2, 24, 107, 0.3);">
                                    <a href="detalles/<?php echo $row['slug']; ?>">
                                        <img src="<?php echo $imagen ?>" class="d-block" height="235px" width="260px"
                                            style="border-radius: 20px; ">
                                    </a>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['nombre'] ?></h5>
                                    <div class="container">
                                        <?php if($row['descuento'] > 0) {?>
                                        <p style="margin-bottom: 0;">
                                            <del><?php echo moneda . number_format($row['precio'], 2, '.', ',');?></del><small
                                                class="text-success">
                                                <?php echo $row['descuento']; ?>% de descuento</small></p>
                                        <h4 style="margin-top: 0;">
                                            <?php echo moneda . number_format($row['precio']-(($row['precio']*$row['descuento'])/100), 2, '.', ',');?>
                                        </h4>

                                        <?php } else { ?>
                                        <h4><?php echo moneda . number_format($row['precio'], 2, '.', ',');?></h4>
                                        <?php } ?>
                                        <p><small>*Las cantidades son en K/g</small></p>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="detalles/<?php echo $row['slug']; ?>" class="btn "
                                                style="background-color: #02186b; color:white;">Detalles</a>
                                        </div>

                                        <?php if ($row['stock'] == 0) : ?>

                                        <button class="btn" type="button" style="background-color: #e86131; color:white">Agotado</button>

                                        <?php else  : ?>
                                        <button class="btn " type="button"
                                            style="background-color: #02186b; color:white;"
                                            onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha256', $row['id'], KEY_TOKEN);?>')">Agregar</button>

                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
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