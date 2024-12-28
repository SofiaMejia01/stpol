<?php
include 'connection.php';


//llena toda la tabla con los datos de los productos

$sql = "SELECT 
            t_prog_curso.ID_Prog_Curso, 
            t_curso.ID_Curso, 
            t_prog_curso.Fecha_Inicio_Curso, 
            t_prog_curso.Fecha_Fin_Curso, 
            t_prog_curso.Promo_Curso,  -- Estado de la promoción del curso
            t_curso.Nom_Curso, 
            t_curso.Cod_Est_Curso  -- Estado del curso
        FROM 
            t_prog_curso 
        inner JOIN 
            t_curso ON t_prog_curso.ID_Curso = t_curso.ID_Curso
        WHERE 
            t_prog_curso.Cod_Est_Prog_Curso = 1";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);

$conn->close();
?>