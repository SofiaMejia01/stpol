<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}



// si encuentra un id, se hara el modificar curso
$inter_id = intval($_POST['id_inter']);
$id_colab = 550;
$nombreInter = $_POST['nombre_inter'];
$apellidoInter = $_POST['apellido_inter'];
$numeroDocInter = $_POST['numero_doc_inter'];
$correoInter = $_POST['correo_inter'];
$celularInter = $_POST['celular_inter'];
$tipoRegInter = 'Manual';


if ($inter_id) {

    $update_query = "UPDATE t_interesados SET  Nombre_Inter = ?, Apellido_Inter = ?, Numero_Doc_Inter = ?, Correo_Inter = ?, Celular_Inter = ?, Tipo_Registro_Inter = ? WHERE ID_Inter = ?";

    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $nombreInter, $apellidoInter, $numeroDocInter, $correoInter, $celularInter, $tipoRegInter, $inter_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Interesado modificado exitosamente.']);
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

    $insert_query = "INSERT INTO t_interesados (ID_Colab, Nombre_Inter, Apellido_Inter, Numero_Doc_Inter, Correo_Inter, Celular_Inter, Tipo_Registro_Inter) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("issssss", $id_colab, $nombreInter, $apellidoInter, $numeroDocInter, $correoInter, $celularInter, $tipoRegInter);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => ' Interesado agregado exitosamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
    }
}
