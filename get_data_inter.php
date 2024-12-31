<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT 
                    t_interesados.ID_Inter,
                    t_data_interesado.ID_Dat_Inter,
                    CONCAT(t_interesados.Nombre_Inter, ' ', t_interesados.Apellido_Inter) AS Nombre_Completo,
                    t_interesados.Celular_Inter,
                    t_data_interesado.Lugar_Inter,
                    t_data_interesado.Detalle_Observacion_Inter,
                    t_interesados.FCHS_REG
                FROM 
                    t_interesados
                INNER JOIN 
                    t_data_interesado 
                ON 
                    t_interesados.ID_Inter = t_data_interesado.ID_Inter
                WHERE 
                    t_interesados.COD_EST_OBJ <> 0 
                    AND t_data_interesado.COD_EST_OBJ <> 0
                    AND t_interesados.Tipo_Registro_Inter = 'Manual'";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>