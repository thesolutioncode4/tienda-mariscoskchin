<header data-bs-theme="ligth" style="display:inline">
    <div class="navbar navbar-expand-lg navbar-ligth bg-ligth"
        style="z-index: 1; position: fixed; display: block; width:100%; background-color:white">
        <div class="container">
            <a href="<?php echo SITE_URL; ?>index.php" class="navbar-brand" style="color: #02186b;">
                <img src="asset/images/solo logo.png" width="60px" style="padding-right: 20px;" alt="">
                <strong><?php echo NAME_PAGE; ?></strong>
            </a>

            <a href="<?php echo SITE_URL; ?>checkout.php" class="btn btn-sm me-2" id="carrito" style="background-color: #02186b">
                <i class="fa-solid fa-cart-shopping" style="color:white;"></i>&nbsp;<span id="num_cart"
                    class="badge bg-secondary"><?php echo $num_cart; ?>&nbsp;</span>
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation" style="color: #02186b;">
                <span class="navbar-toggler-icon" style="color: #02186b;"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>index.php" class="nav-link active"
                            style="color: black;">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>catalogo.php" class="nav-link active"
                            style="color: black;">Catalogo</a>
                    </li>
                </ul>

                <form action="catalogo.php" method="get" autocomplete="off">
                    <div class="input-group pe-3">
                        <input type="text" name="q" id="q" class="form-control form-control-sm" placeholder="Buscar..."
                            aria-describedby="icon-buscar">
                        <button type="submit" id="icon-buscar" class="btn btn-sm" style="background-color: #02186b;">
                            <i class="fas fa-search" style="color: white;"></i>
                        </button>
                    </div>
                </form>

                <a href="<?php echo SITE_URL; ?>checkout.php" class="btn btn-sm me-2" style="background-color: #02186b; color: white;">
                    <i class="fa-solid fa-cart-shopping" ></i>&nbsp; Carrito&nbsp; <span id="num_cart"
                        class="badge bg-secondary"><?php echo $num_cart; ?></span>
                </a>

                <?php if(isset($_SESSION['user_id'])){ ?>
                <div class="dropdown" style="z-index: 1;">
                    <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session"
                        data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #02186b; color: white;">
                        <i class="fa-regular fa-user"></i> &nbsp; <?php echo $_SESSION['user_name']; ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btn_session">
                        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>compras.php" >Mis compras</a></li>
                        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>logout.php" >Cerrar sesion</a></li>
                    </ul>
                </div>
                <?php } else { ?>
                <a href="<?php echo SITE_URL; ?>login.php" class="btn btn-sm" style="background-color: #02186b; color: white;">
                    <i class="fa-regular fa-user"></i> Ingresar</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>