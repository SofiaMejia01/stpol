<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT 
            t_pago.ID_Pago, 
            t_colaborador.ID_Colab,
            t_colaborador.Nombre_Colab, 
            t_pago.Monto_Pago,
            t_pago.FOT_PAGO_NAME,
            t_pago.FOT_PAGO_TYPE,
            t_pago.FOT_PAGO_SIZE,
            t_pago.FOT_PAGO_TMPNAME
              
        FROM 
            t_pago 
         JOIN 
            t_colaborador ON t_pago.ID_Colab = t_colaborador.ID_Colab
        WHERE 
            t_pago.Cod_EST_OBJ = 1";


$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>