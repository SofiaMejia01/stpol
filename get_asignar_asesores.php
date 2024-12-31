<?php
include 'connection.php';
$interesados = [];

//llena toda la tabla con los datos de los productos

$sql = "SELECT ID_Inter, CONCAT(Nombre_Inter, ' ', Apellido_Inter) AS Nombre_Completo_Inter
        FROM t_interesados 
        WHERE ID_Colab = 550 AND COD_EST_OBJ <> 0";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>