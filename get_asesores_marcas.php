<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT 
        mc.ID_Marca_Colaborador,
        m.Nombre_Marca,
        CONCAT(c.Nombre_Colab, ' ', c.Apellido_Colab) AS Asesor
       
        FROM 
            t_marca m
        JOIN 
            t_marca_colaborador mc ON m.ID_Marca = mc.ID_Marca
        JOIN 
            t_colaborador c ON mc.ID_Colab = c.ID_Colab
        WHERE 
            m.COD_EST_OBJ = 1 
            AND c.COD_EST_OBJ = 1 AND mc.COD_EST_OBJ = 1";

        $result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
