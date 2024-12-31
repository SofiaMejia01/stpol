<?php
include 'connection.php';

// El script comprueba si el parámetro id está presente en la URL (por ejemplo, pagina.php?id=123). Si no está, detiene la ejecución y muestra un mensaje de error.
if (!isset($_POST['id'])) {
    echo "ID de Interesado no proporcionado.";
    exit();
}

$id = $_POST['id'];

$query = "SELECT * from t_interesados WHERE ID_Inter = ?";

//Crea una consulta preparada. Esto mejora la seguridad al prevenir ataques de inyección SQL al separar los datos dinámicos (proporcionados por el usuario) de la lógica de la consulta.
$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id);

//Ejecuta la consulta preparada con los parámetros vinculados. En este punto, la base de datos procesa la consulta y devuelve los resultados.
$stmt->execute();

//Recupera el resultado de la consulta ejecutada en forma de un objeto que puede ser iterado o procesado para extraer los datos.
$result = $stmt->get_result();


//Si no se encuentran registros para ese ID:Detiene la ejecución y muestra un mensaje de error.
if ($result->num_rows == 0) {
    echo "Error: Interesado no encontrado.";
    exit();
}

// Fetch the data of the selected diamond
echo json_encode($result->fetch_assoc());

?>