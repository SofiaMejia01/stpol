<?php
include 'connection.php';

// El script comprueba si el parámetro id está presente en la URL (por ejemplo, pagina.php?id=123). Si no está, detiene la ejecución y muestra un mensaje de error.
if (!isset($_POST['id'])) {
    echo "ID de asesores por  marca  no proporcionado.";
    exit();
}

$id = $_POST['id'];

//Consulta SQL que selecciona todos los datos (pp.*) del diamante con un ID_PP específico. También trae el Nombre_Estado asociado desde la tabla estado_pp mediante un JOIN.
$query = "SELECT t_producto_servicio_marca.*, t_marca.Nombre_Marca FROM t_producto_servicio_marca 
    JOIN t_marca ON t_producto_servicio_marca.ID_Marca = t_marca.ID_Marca 
    WHERE t_producto_servicio_marca.ID_Prod_Serv_Marca = ?";

//Crea una consulta preparada. Esto mejora la seguridad al prevenir ataques de inyección SQL al separar los datos dinámicos (proporcionados por el usuario) de la lógica de la consulta.
$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id);

//Ejecuta la consulta preparada con los parámetros vinculados. En este punto, la base de datos procesa la consulta y devuelve los resultados.
$stmt->execute();

//Recupera el resultado de la consulta ejecutada en forma de un objeto que puede ser iterado o procesado para extraer los datos.
$result = $stmt->get_result();


//Si no se encuentran registros para ese ID:Detiene la ejecución y muestra un mensaje de error.
if ($result->num_rows == 0) {
    echo "Error: Producto/Servicio por marca no encontrado.";
    exit();
}

// Fetch the data of the selected diamond
echo json_encode($result->fetch_assoc());

?>