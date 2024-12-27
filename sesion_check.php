<?php
session_start(); // Inicia la sesión al acceder a esta página
include 'connection.php';

if (!isset($_SESSION['Nombre_Usuario'])) {
    header('Location: login.php'); // Si no hay sesión, redirige al login
    exit();
}

// Verificar tiempo de duración de la sesión
if (isset($_SESSION['inicio'])) {
    $tiempo_transcurrido = time() - $_SESSION['inicio'];
    if ($tiempo_transcurrido > $_SESSION['duracion']) {
        // Destruir la sesión y redirigir al login
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    } else {
        // Actualizar tiempo de inicio si la sesión sigue activa
        $_SESSION['inicio'] = time();
    }
}

?>