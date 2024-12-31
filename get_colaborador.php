<?php
include 'connection.php';

// El script comprueba si el parámetro id está presente en la URL (por ejemplo, pagina.php?id=123). Si no está, detiene la ejecución y muestra un mensaje de error.
if (!isset($_POST['id'])) {
    echo "ID de colaborador no proporcionado.";
    exit();
}

$id = $_POST['id'];

//Consulta SQL que selecciona todos los datos (pp.*) del diamante con un ID_PP específico. También trae el Nombre_Estado asociado desde la tabla estado_pp mediante un JOIN.
// $query = "SELECT t_prog_curso.*, t_curso.Nom_Curso FROM t_prog_curso 
//     JOIN t_curso ON t_prog_curso.ID_Curso = t_curso.ID_Curso 
//     WHERE t_prog_curso.ID_Prog_Curso = ?";

$query = "SELECT 
    t_colaborador.ID_Colab, t_colaborador.Nombre_Colab,  t_colaborador.Apellido_Colab, t_colaborador.Numero_Doc_Colab, 
    t_colaborador.Genero_Colab, t_colaborador.Fecha_Nac_Colab, t_colaborador.Edad_Colab, t_colaborador.Direccion_Colab, 
    t_colaborador.Telefono_Colab, t_colaborador.Correo_Colab, t_colaborador.FOT_CV_NAME, t_colaborador.FOT_CV_TYPE, 
    t_colaborador.FOT_CV_SIZE, t_colaborador.FOT_CV_TMPNAME, t_colaborador.Modalidad_Colab, t_colaborador.Sueldo_Colab, 
    t_colaborador.Fecha_Pago_Colab, t_colaborador.FOT_REG_CONTRATO_NAME, t_colaborador.FOT_REG_CONTRATO_TYPE, 
    t_colaborador.FOT_REG_CONTRATO_SIZE, t_colaborador.FOT_REG_CONTRATO_TMPNAME, t_colaborador.Horario_Trabajo_Colab, 
    t_colaborador.Horario_Refrigerio_Colab, t_puesto.iD_Puesto, t_puesto.Nombre_Puesto
FROM t_colaborador JOIN t_puesto ON t_colaborador.ID_Puesto = t_puesto.iD_Puesto
WHERE t_colaborador.ID_Colab = ?";    

//Crea una consulta preparada. Esto mejora la seguridad al prevenir ataques de inyección SQL al separar los datos dinámicos (proporcionados por el usuario) de la lógica de la consulta.
$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id);

//Ejecuta la consulta preparada con los parámetros vinculados. En este punto, la base de datos procesa la consulta y devuelve los resultados.
$stmt->execute();

//Recupera el resultado de la consulta ejecutada en forma de un objeto que puede ser iterado o procesado para extraer los datos.
$result = $stmt->get_result();


//Si no se encuentran registros para ese ID:Detiene la ejecución y muestra un mensaje de error.
if ($result->num_rows == 0) {
    echo "Error: Colaborador no encontrado.";
    exit();
}

// Fetch the data of the selected diamond
echo json_encode($result->fetch_assoc());

?>