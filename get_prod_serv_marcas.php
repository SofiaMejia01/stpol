<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT 
            t_producto_servicio_marca.ID_Prod_Serv_Marca, 
            -- t_marca.ID_Marca, 
            t_marca.Nombre_Marca, 
            t_producto_servicio_marca.Nombre_Prod_Serv_Marca, 
            t_producto_servicio_marca.Descripcion_Prod_Serv_Marca
           
        FROM 
            t_producto_servicio_marca 
        inner JOIN 
            t_marca ON t_producto_servicio_marca.ID_Marca = t_Marca.ID_Marca
        WHERE 
            t_producto_servicio_marca.COD_EST_OBJ = 1";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>