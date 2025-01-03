<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT 
        tdi.ID_Inter AS ID_Interesado,
        CONCAT(ti.Nombre_Inter, ' ', ti.Apellido_Inter) AS Nombre_Completo,
        ti.Celular_Inter AS Celular,
        tdi.Fecha_prox_interaccion as Fecha_Prox,
        tei.Nombre_Estado_Inter AS Estado_Seguimiento,
        tesi.Nombre_Etapa_Seg_Inter AS Etapa_Seguimiento
    FROM 
        t_data_interesado tdi
    INNER JOIN 
        t_interesados ti ON tdi.ID_Inter = ti.ID_Inter
    INNER JOIN 
        t_estado_interesado tei ON tdi.ID_estado_interesado = tei.ID_estado_interesado
    INNER JOIN 
        t_etapa_seguimiento_inter tesi ON tdi.ID_etapa_seguimiento_Inter = tesi.ID_etapa_seguimiento_Inter";


$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>