<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}



// si encuentra un id, se hara el modificar curso
$asesor_marca_id = intval($_POST['id_asesorMarca']);
$id_marca = $_POST['id_marca'];
$id_asesores = $_POST['id_asesores'];
// echo "IDs seleccionados: " . implode(", ", $id_asesores);


if ($asesor_marca_id) {

    $update_query = "UPDATE t_marca_colaborador SET ID_Marca = ?, ID_Colab = ? WHERE ID_Marca_Colaborador = ?";

    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("iii", $id_marca , $id_asesores,  $asesor_marca_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Registro modificado exitosamente.']);
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
    $insert_query = "INSERT INTO t_marca_colaborador (ID_Marca, ID_Colab) VALUES (?, ?)";

    $stmt = $conn->prepare($insert_query);

    if (!$stmt) {
        //die("Error al preparar la consulta: " . $mysqli->error);
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
    }

    // Asociar parámetros y ejecutar para cada ID
    foreach ($id_asesores as $id_asesor) {
        $stmt->bind_param("ii", $id_marca, $id_asesor); // "ii" indica dos enteros
        $stmt->execute();
    }

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => ' registro agregado exitosamente.']);
    } else {
        echo json_encode(['error' => 'No se pudo crear el registro']);
    }

    $stmt->close();
}
