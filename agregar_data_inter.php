<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}



// si encuentra un id, se hara el modificar curso
$data_inter_id = intval($_POST['id_dat_inter']);

$id_interesado = $_POST['id_interesado'];
$lugar = $_POST['lugar_inter'];
$detalle = $_POST['detalle_inter'];


if ($data_inter_id) {
   echo json_encode(['error' => 'Error en la preparación del SQL (UPDATE)']);
}
// sino encuentra un id, se hara el agregar curso
else {

    $insert_query = "INSERT INTO t_data_interesado (ID_Inter, Lugar_Inter, Detalle_Observacion_Inter) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        // Bind parameters
         $stmt->bind_param("iss", $id_interesado, $lugar, $detalle);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => ' Data del Interesado agregado exitosamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
    }
}
