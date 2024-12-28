<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}



// si encuentra un id, se hara el modificar curso
$curso_id = intval($_POST['id_curso']);

$nom_curso = $_POST['nom_curso'];
$desc_curso = $_POST['desc_curso'];


if ($curso_id) {

    $update_query = "UPDATE t_curso SET nom_curso = ?, desc_curso = ? WHERE id_curso = ?";

    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("ssi", $nom_curso, $desc_curso, $curso_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Curso modificado exitosamente.']);
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

    $insert_query = "INSERT INTO t_curso (nom_curso, desc_curso) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $nom_curso, $desc_curso);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => ' Curso agregado exitosamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
    }
}
