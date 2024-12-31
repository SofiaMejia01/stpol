<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT * from t_interesados WHERE COD_EST_OBJ <> 0;";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>