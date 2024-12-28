<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}



// si encuentra un id, se hara el modificar curso
$prog_curso_id = intval($_POST['id_progCurso']);
$id_curso = $_POST['id_curso'];
// $nom_curso = $_POST['nom_curso'];
$fecha_inicio_curso = $_POST['fecha_inicio_curso'];
$fecha_fin_curso = $_POST['fecha_fin_curso'];
$promo_curso = $_POST['promo_curso'];

if ($prog_curso_id) {

    $update_query = "UPDATE t_prog_curso SET ID_Curso = ?, Fecha_Inicio_Curso = ?, Fecha_Fin_Curso = ?, Promo_Curso = ? WHERE ID_Prog_Curso = ?";

    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("isssi", $id_curso , $fecha_inicio_curso, $fecha_fin_curso, $promo_curso, $prog_curso_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Programacion modificado exitosamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo actualizar el registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (UPDATE)']);
    }
}
// sino encuentra un id, se hara el agregar curso
else {

    $insert_query = "INSERT INTO t_prog_curso (ID_Curso, Fecha_Inicio_Curso, Fecha_Fin_Curso, Promo_Curso) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("isss", $id_curso, $fecha_inicio_curso, $fecha_fin_curso , $promo_curso);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => ' Programacion agregado exitosamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
    }
}
