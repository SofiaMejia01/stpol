<?php
include 'sesion_check.php';

$Nombre_Usuario = $_SESSION['Nombre_Usuario'];


// Obtener el path base dinámico
$basePath = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$page = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 'home';

// Ruta base de las páginas
$pagesPath = __DIR__ . '/';

// Validar que el archivo exista en la carpeta `pages`
$filePath = $pagesPath . $page . '.php';
if (!file_exists($filePath)) {
    $filePath = __DIR__ . '/reg_gasto_interno.php'; // Página por defecto
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STPOL</title>
    <link rel="icon" href="img/favicon_stpol.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

    <!-- font-awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Rutas Absolutas para Estilos y Scripts -->
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/styles.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/estilos-layout.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Butttons de Datatables-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- librerias para multiples opciones select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <script src="<?php echo $basePath; ?>/scripts.js"></script>

</head>

<body>
    <header class="navbar navbar-dark bg-marmol fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-3" type="button" id="toggle-sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Logo -->
            <a href="index.php" class="">
                <img src="img/logo_STPOL.png" alt="Logo" class="d-none d-sm-block" style="height: 40px; object-fit: cover;">
            </a>
            <!-- botón "Cerrar Sesión" -->
            <div class="d-flex align-items-center ms-auto">

                <span class="navbar-text text-light me-3" style="font-size: 16px;">
                    Bienvenido <?php echo htmlspecialchars( $Nombre_Usuario); ?>
                </span>
                <img class="img-profile rounded-circle" src="img/admin.png" style="height: 30px;">

                <form action="cerrar_sesion.php" method="POST" class="d-none d-sm-inline-block ms-3">
                    <button type="submit" class="btn btn-danger btn-sm me-3">Cerrar Sesión&nbsp;<i class="fa-solid fa-power-off"></i></button>

                </form>
            </div>
        </div>
    </header>
    <div class="container-fluid sidebar-main p-0">
        <!-- Sidebar -->
        <nav class="bg-darkmarmol sidebar border-end" id="sidebar">
            <div class="sidebar-container">
                <div class="position-relative text-center">
                    <div class="me-4 mt-4">
                    <img src="img/logo_STPOL.png" alt="Logo" class="my-4 mx-5" style="height: 80px; object-fit: contain;">
                    </div>

               </div>
             
                <hr style="background-color: #aa6ecf; border-color: #aa6ecf; height: 2px;">
                <ul class="nav flex-column py-3">

        
                    <li class="nav-item mx-3">
                        <a  href="#administracion" class="nav-link text-white" data-bs-toggle="collapse" aria-expanded="false">
                        <i class="fa-solid fa-user-tie"></i> &nbsp;
                            Administración
                            <span class="arrow float-end">
                                <!-- Flecha hacia abajo -->
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                        </a>
                        <div class="collapse" id="administracion">
                            <ul class="submenu list-unstyled ps-3">
                                <li><a href="reg_gasto_interno" class="nav-link text-white"><i class="fa-solid fa-file-invoice-dollar"></i>&nbsp;Registro de Gastos Internos</a></li>
                                <li><a href="registro_curso" class="nav-link text-white"><i class="fa-solid fa-book"></i> &nbsp;Registro de Cursos</a></li>
                                <li><a href="programacion_curso" class="nav-link text-white"><i class="fa-solid fa-calendar-days"></i>&nbsp;Programación de Cursos</a></li>

                            </ul>
                        </div>
                    </li>
         
         
                </ul>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="px-3" id="main-content">
            <?php include $filePath; ?>
        </main>
    </div>

    <footer class="py-2 bg-dark color-footer mt-auto">
        <div class="container-fluid px-4 mb-1 d-flex align-items-center  justify-content-center">
            <div class="d-flex  small text-center">
                <div class="mx-2">&copy 2024 STPOL. Todos los derechos reservados. Versión 1.0. </div>
                <!-- <div><a class="mx-2 color-footer" href="#">Política de Privacidad</a></div>
                <div><a class="mx-1 color-footer" href="#">Términos &amp; Condiciones</a></div> -->
            </div>
        </div>
    </footer>


<script>
    // Seleccionar el botón de cierre


</script>


</body>

</html>