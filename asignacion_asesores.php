<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

if (isset($_POST['interesados']) && isset($_POST['asesor'])) {
    $interesadosSeleccionados = $_POST['interesados'];
    $asesorSeleccionado = $_POST['asesor'];
    
    // Asignar el asesor a los interesados seleccionados
    try {
        $conn->begin_transaction();
        
        foreach ($interesadosSeleccionados as $idInteresado) {
            $sqlUpdateInteresado = "UPDATE t_interesados SET ID_Colab = ? WHERE ID_Inter = ?";
            $stmt = $conn->prepare($sqlUpdateInteresado);
            $stmt->bind_param("ii", $asesorSeleccionado, $idInteresado);
            $stmt->execute();
        }
        
        $sqlUpdateAsesor = "UPDATE t_colaborador SET Disp_Colab = 0 WHERE ID_Colab = ?";
        $stmt = $conn->prepare($sqlUpdateAsesor);
        $stmt->bind_param("i", $asesorSeleccionado);
        $stmt->execute();

        $conn->commit();

        echo json_encode(['status' => 'success', 'message' => 'Los interesados han sido asignados al asesor correctamente.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Error al realizar la asignación: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar al menos un interesado y un asesor.']);
}
