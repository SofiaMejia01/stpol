<?php
include 'connection.php';
$asesores = [];

//llena toda la tabla con los datos de los productos

$sql = "SELECT ID_Colab, CONCAT(Nombre_Colab, ' ', Apellido_Colab) AS Nombre_Completo_Colab
        FROM t_colaborador
        WHERE ID_Puesto = 2 AND COD_EST_OBJ <> 0 AND Disp_Colab = 1 AND ID_Colab <> 550";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
        $asesores = $result->fetch_all(MYSQLI_ASSOC); // Convertir el resultado en un array
}

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>