<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}


// Recuperar los datos enviados desde el modal
$idInteresado = $_POST['idInteresado']; // ID del interesado
$fechaProximaInteraccion = $_POST['fechaProximaInteraccion'];
$detalleObservaciones = 'Voucher'; // Detalle fijo para esta acción

// Recuperar el último tipo de prospección asociado al interesado
$selectTipoQuery = "
    SELECT ID_tipo_prospeccion 
    FROM t_prospecto 
    WHERE ID_Dat_Inter = ? 
    ORDER BY ID_Prospecto DESC 
    LIMIT 1
";
$stmtSelect = $conn->prepare($selectTipoQuery);
$stmtSelect->bind_param('i', $idInteresado);
$stmtSelect->execute();
$result = $stmtSelect->get_result();

// Verificar si se encontró un tipo de prospección
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tipoProspeccion = $row['ID_tipo_prospeccion'];
} else {
    // Asignar un valor por defecto si no se encontró ningún registro
    $tipoProspeccion = null;
}

// Manejo del archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $FOT_EVE_NAME = $_FILES['archivo']['name'];
    $FOT_EVE_TYPE = $_FILES['archivo']['type'];
    $FOT_EVE_SIZE = $_FILES['archivo']['size'];
    $FOT_EVE_TMPNAME = uniqid('tmp_', true); // Nombre único temporal

    $uploadDir = __DIR__ . '/documentos_de_prospeccion/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadPath = $uploadDir . $FOT_EVE_TMPNAME;

    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadPath)) {
        $FOT_EVE_NAME = $FOT_EVE_TYPE = $FOT_EVE_SIZE = $FOT_EVE_TMPNAME = null;
    }
} else {
    $FOT_EVE_NAME = $FOT_EVE_TYPE = $FOT_EVE_SIZE = $FOT_EVE_TMPNAME = null;
}

// Preparar las consultas
$insertQuery = "
    INSERT INTO t_prospecto (
        ID_Dat_Inter,
        ID_tipo_prospeccion,
        Detalle,
        FOT_EVE_NAME,
        FOT_EVE_TYPE,
        FOT_EVE_SIZE,
        FOT_EVE_TMPNAME
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
";

// Etapa fija "Matriculado" (ID=3)
$etapaProspeccion = 3;
$updateQuery = "
    UPDATE t_data_interesado 
    SET ID_etapa_seguimiento_Inter = ?, Fecha_prox_interaccion = ?, ID_estado_interesado=1
    WHERE ID_Inter = ?
";

// Transacción para asegurar consistencia
$conn->begin_transaction();
try {
    // Insertar registro en t_prospecto
    $stmtInsert = $conn->prepare($insertQuery);
    $stmtInsert->bind_param(
        'iisssss',
        $idInteresado,
        $tipoProspeccion,
        $detalleObservaciones,
        $FOT_EVE_NAME,
        $FOT_EVE_TYPE,
        $FOT_EVE_SIZE,
        $FOT_EVE_TMPNAME
    );
    $stmtInsert->execute();

    // Actualizar la etapa de seguimiento
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param(
        'isi',
        $etapaProspeccion,
        $fechaProximaInteraccion,
        $idInteresado
    );
    $stmtUpdate->execute();

    // Confirmar transacción
    $conn->commit();
} catch (Exception $e) {
    // Revertir cambios en caso de error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}