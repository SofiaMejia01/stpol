<?php
include 'connection.php';

// Comprueba si el parámetro ID está presente en la solicitud POST.
if (!isset($_POST['id'])) {
    echo "ID de producto/servicio no proporcionado.";
    exit();
}

// Captura el ID enviado desde el formulario o la solicitud.
$id = $_POST['id'];

// Consulta SQL para eliminar el registro con el ID proporcionado.
$query = "UPDATE t_producto_servicio_marca SET COD_EST_OBJ = 0 WHERE ID_Prod_Serv_Marca = ?";

// Crea una consulta preparada para prevenir inyecciones SQL.
$stmt = $conn->prepare($query);

// Vincula el parámetro "id" como un entero (i).
$stmt->bind_param("i", $id);

// Ejecuta la consulta preparada.
if ($stmt->execute()) {
    echo "El registro con ID $id ha sido eliminado correctamente.";
} else {
    echo "Error al eliminar el registro: " . $conn->error;
}

// Cierra la declaración y la conexión para liberar recursos.
$stmt->close();
$conn->close();
?>