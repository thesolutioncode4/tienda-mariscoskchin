<?php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'clases/clientesFunciones.php';

$db = new Database();
$con = $db->conectar();

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug == '') {
    echo 'Error al procesar la peticion';
    exit;
} 
else {

        $sql = $con->prepare("SELECT count(id) FROM productos WHERE slug=? AND activo=1");
        $sql->execute([$slug]);
        if($sql->fetchColumn() > 0) {

            $sql = $con->prepare("SELECT id, nombre, descripcion, precio, descuento, stock FROM productos WHERE slug=? AND activo=1 LIMIT 1");
            $sql->execute([$slug]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $dir_img = 'asset/images/productos/'. $id . '/';
            $ruta_img = $dir_img .'/principal.jpg';
            if(!file_exists($ruta_img)){
                $ruta_img = "asset/images/no-photo.jpg";
            } 

            $imagenes = array();
            $dir = dir($dir_img);

            while(($archivo = $dir->read()) != false){
                if($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))){
                    $imagenes[] = $dir_img . $archivo;
                }
            }
            $dir->close();
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
    <base href="<?php echo SITE_URL; ?>">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a48d55bdce.js" crossorigin="anonymous"></script>

    <style>
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        height: 50px;
        width: 100px;
        outline: black;
        background-color: rgba(0, 0, 0, 0.7);
        background-size: 100%, 100%;
        border-radius: 50%;
        border: 1px solid black;
        z-index: 0;
    }

    main{
        z-index: 0;
    }

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
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <main>
        <div class="container"></div>
        <div class="container">
            <div class="row">
                <div class="d-flex justify-content-center align-items-center col-md-6 order-md-2">
                    <div id="carouselImages" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo $ruta_img; ?>" class="d-block w-100" height="280px">
                            </div>
                            <?php foreach($imagenes as $img) {?>
                            <div class="carousel-item">
                                <img src="<?php echo $img; ?>" class="d-block w-100" height="280px">
                            </div>

                            <?php } ?>

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="prev" style="z-index: 0;">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="next" style="z-index: 0;">
                            <span class="carousel-control-next-icon " aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 order-md-2">
                    <h2><?php echo $nombre; ?></h2>
                    <?php if($descuento > 0) {?>
                    <p><del><?php echo moneda . number_format($precio, 2, '.', ',');?></del> </p>
                    <h2><?php echo moneda . number_format($precio_desc, 2, '.', ',');?> <small class="text-success">
                            <?php echo $descuento; ?>% de descuento</small></h2>

                    <?php } else { ?>
                    <h2><?php echo moneda . number_format($precio, 2, '.', ',');?></h2>
                    <?php } ?>
                    <p></p>
                    <p class="lead"><b>Descripcion</b></p>
                    <p class="lead"><?php echo $descripcion;?></p>
                    <div class="col-4 my-3" >
                        Cantidad: <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" placeholder="Minimo 1 K/g"
                            >
                    </div>
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-outline-primary" type="button"
                            onclick="addProducto(<?php echo $id; ?>, cantidad.value)">Agregar
                            al
                            carrito</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script>
    function addProducto(id, cantidad, token = 0) {

        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('cantidad', cantidad)
        formData.append('token', token)
        formData.append('action', 'agregar')

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
    </script>
</body>

</html>