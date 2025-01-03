<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}


// Recuperar los datos enviados desde el modal
$idInteresado = $_POST['idInteresado']; // ID del interesado
$tipoProspeccion = $_POST['tipoProspeccion'];  // ID del tipo de prospección seleccionado
$detalleObservaciones = $_POST['detalleObservaciones']; // Detalles del textarea
$etapaProspeccion = $_POST['etapaProspeccion']; // Nueva etapa de prospección seleccionada
$fechaProximaInteraccion = $_POST['fechaProximaInteraccion'];

    // Manejo del archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
$FOT_EVE_NAME = $_FILES['archivo']['name'];
$FOT_EVE_TYPE = $_FILES['archivo']['type'];
$FOT_EVE_SIZE = $_FILES['archivo']['size'];

// Generar un nombre temporal único
$FOT_EVE_TMPNAME = uniqid('tmp_', true); // Ejemplo: tmp_64c1c54dcf5f6.123456

// Carpeta donde se guardará el archivo
$uploadDir = __DIR__ . '/documentos_de_prospeccion/';

// Crear la carpeta si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Ruta completa del archivo con el nombre temporal único
$uploadPath = $uploadDir . $FOT_EVE_TMPNAME;

// Mover el archivo a la carpeta especificada
if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadPath)) {
    // Error al mover el archivo
    $FOT_EVE_NAME = $FOT_EVE_TYPE = $FOT_EVE_SIZE = $FOT_EVE_TMPNAME = null;
}
} else {
    // Si no se subió un archivo válido
    $FOT_EVE_NAME = $FOT_EVE_TYPE = $FOT_EVE_SIZE = $FOT_EVE_TMPNAME = null;
}

// Preparar la consulta INSERT para la tabla prospecto
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

// Preparar la consulta UPDATE para actualizar la etapa de prospección
$updateQuery = "
    UPDATE t_data_interesado 
    SET ID_etapa_seguimiento_Inter = ? , Fecha_prox_interaccion = ? , ID_estado_interesado=3
    WHERE ID_Inter = ?
";

// Usar prepared statements para evitar inyecciones SQL
$conn->begin_transaction(); // Iniciar transacción
try {
    // Preparar y ejecutar la consulta INSERT
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

    // Preparar y ejecutar la consulta UPDATE
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param(
        'isi',
        $etapaProspeccion,
        $fechaProximaInteraccion,
        $idInteresado
    );
    $stmtUpdate->execute();

    // Confirmar la transacción
    $conn->commit();

} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    $response = ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
}




