<?php
include 'connection.php';


//llena toda la tabla con los datos de los colaboradores


$sql = "SELECT 
    t_colaborador.ID_Colab, t_colaborador.Nombre_Colab,  t_colaborador.Apellido_Colab, t_colaborador.Numero_Doc_Colab, 
    t_colaborador.Genero_Colab, t_colaborador.Fecha_Nac_Colab, t_colaborador.Edad_Colab, t_colaborador.Direccion_Colab, 
    t_colaborador.Telefono_Colab, t_colaborador.Correo_Colab, t_colaborador.FOT_CV_NAME, t_colaborador.FOT_CV_TYPE, 
    t_colaborador.FOT_CV_SIZE, t_colaborador.FOT_CV_TMPNAME, t_colaborador.Modalidad_Colab, t_colaborador.Sueldo_Colab, 
    t_colaborador.Fecha_Pago_Colab, t_colaborador.FOT_REG_CONTRATO_NAME, t_colaborador.FOT_REG_CONTRATO_TYPE, 
    t_colaborador.FOT_REG_CONTRATO_SIZE, t_colaborador.FOT_REG_CONTRATO_TMPNAME, t_colaborador.Horario_Trabajo_Colab, 
    t_colaborador.Horario_Refrigerio_Colab, t_puesto.Nombre_Puesto
FROM t_colaborador
JOIN t_puesto ON t_colaborador.ID_Puesto = t_puesto.iD_Puesto
WHERE t_colaborador.COD_EST_OBJ = 1";



$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>