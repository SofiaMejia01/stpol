<?php
session_start();
include 'connection.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nombre_Usuario = $_POST['Nombre_Usuario'] ?? ''; // Entrada del usuario
    $Password_Usuario = $_POST['Password_Usuario'] ?? ''; // Entrada de contraseña

    // Verificar credenciales
    $stmt = $conn->prepare("SELECT ID_Usuario, ID_Perfil FROM t_usuario WHERE Nombre_Usuario = ? AND Password_Usuario = ?");
    $stmt->bind_param("ss", $Nombre_Usuario, $Password_Usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();


        $_SESSION['Nombre_Usuario'] = $Nombre_Usuario; // Guardar el nombre del usuario en la sesión
        $_SESSION['ID_Usuario'] = $user['ID_Usuario'];
         $_SESSION['ID_Perfil'] = $user['ID_Perfil'];
        $_SESSION['inicio'] = time(); // Establecer tiempo de inicio de sesión
        $_SESSION['duracion'] = 86400; // Tiempo de sesión en segundos (10 minutos)


        // // Redirigir según el usuario
        switch ($user['ID_Perfil']) {
            case 2: // Administrador
                header("Location: index.php");
                break;

            default:
                echo " usuario no reconocido.";
                break;
        }
    } else {
        echo "Usuario o contraseña inválidos.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/estilos-login.css">
</head>

<body>
    <!-- <form method="POST" action="login.php">
        <input type="text" name="Nombre_Usuario" placeholder="Usuario" required>
        <input type="password" name="Password_Usuario" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form> -->

    <div class="login-page">
        <div class="form formulario">

            <form method="POST" class="login-form" action="login.php">

                <img src="img/logo_principal.png" alt="Logo" class="mb-5">
                <input type="text" name="Nombre_Usuario" placeholder="usuario"  required />
                <input type="password" name="Password_Usuario" placeholder="contraseña" required/>
                <button type="submit">login</button>
                <!-- <p class="message">Aun no estas registr? <a href="#">Create an account</a></p> -->

            </form>

        </div>
    </div>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>