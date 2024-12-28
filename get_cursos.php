<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT ID_Curso, Nom_Curso, Desc_Curso FROM t_curso WHERE Cod_Est_Curso = 1";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>