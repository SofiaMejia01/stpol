<?php
include 'connection.php';


// header('Content-Type: multipart/form-data');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Recibe los datos del formulario
$colaborador_id = intval($_POST['id_colaborador']);

$nombreColab = $_POST['nom_colaborador'];
$apellidoColab = $_POST['ape_colaborador'];
$numDocColab = $_POST['num_doc_colaborador'];
$generoColab = $_POST['genero_colaborador'];
$fechaNacColab = $_POST['fecha_nac_colaborador'];
$edadColab = $_POST['edad_colaborador'];
$direccionColab = $_POST['direc_colaborador'];
$telefonoColab = $_POST['telef_colaborador'];
$correoColab = $_POST['correo_colaborador'];

if(isset($_FILES['archivo_cv'])) {
    $archivoCV = $_FILES['archivo_cv'];
    $nombre_archivoCV = uniqid('', true).".".$archivoCV['name'];
    $tipo_archivoCV = $archivoCV['type'];
    $tamano_archivoCV = $archivoCV['size'];
    $ruta_temporalCV = $archivoCV['tmp_name'];
    $ruta_destinoCV = 'curriculumVitae/' . basename($nombre_archivoCV);
}

$puestoColab = $_POST['puesto_colaborador'];
$modalidadColab = $_POST['modalidad_colaborador'];
$sueldoColab = $_POST['sueldo_colaborador'];
$fechaPagColab = $_POST['fech_pag_colaborador'];

if(isset($_FILES['archivo_contrato'])) {
    $archivoContrato = $_FILES['archivo_contrato'];
    $nombre_archivoContrato = uniqid('', true).".".$archivoContrato['name'];
    $tipo_archivoCContrato = $archivoContrato['type'];
    $tamano_archivoContrato = $archivoContrato['size'];
    $ruta_temporalContrato = $archivoContrato['tmp_name'];
    $ruta_destinoContrato = 'contratos/' . basename($nombre_archivoContrato);
}

$horarioTrabColab = $_POST['hor_trab_colaborador'];
$horarioRefriColab = $_POST['hor_refri_colaborador'];



if ($colaborador_id) {
    
    // Consultar el nombre del archivo actual asociado al registro
    $query = "SELECT FOT_EVE_NAME FROM t_gasto_interno WHERE ID_Gasto = ?";
    $stmtSelect = $conn->prepare($query);
    $stmtSelect->bind_param("i", $gasto_id);
    $stmtSelect->execute();
    $stmtSelect->bind_result($currentFileName);
    $stmtSelect->fetch();
    $stmtSelect->close();
    

    if ($archivo && $tamano_archivo > 0 && move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName != '') {
            $filePath = 'servicios/' . $currentFileName;
            if (file_exists($filePath)) {
                unlink($filePath); // Eliminar archivo anterior
            }
        }

        // Preparar la consulta SQL para insertar datos
        $sql = "UPDATE t_gasto_interno SET 
                        Nom_Gasto = ?, 
                        Monto_Gasto = ?, 
                        Fech_Pag_Gasto = ?, 
                        FOT_EVE_NAME = ?, 
                        FOT_EVE_TYPE = ?, 
                        FOT_EVE_SIZE = ?, 
                        FOT_EVE_TMPNAME = ? 
                        WHERE ID_Gasto = ?";
        
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssssi", $nombreGasto, $montoGasto, $fechaPagoGasto, 
            $nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino, $gasto_id);
       
    } else {
        $sql = "UPDATE t_gasto_interno SET 
                    Nom_Gasto = ?, 
                    Monto_Gasto = ?, 
                    Fech_Pag_Gasto = ? 
                    WHERE ID_Gasto = ?";
        // Si no se sube un archivo, solo actualizamos los campos sin los relacionados al archivo
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $nombreGasto, $montoGasto, $fechaPagoGasto,  $gasto_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    } 
    
    $stmt->close();
   
}
else {

    if($archivo && $tamano_archivo > 0 && move_uploaded_file($ruta_temporal, $ruta_destino)){
        $insert_query = "INSERT INTO t_gasto_interno 
            (Nom_Gasto, Monto_Gasto, Fech_Pag_Gasto, FOT_EVE_NAME, FOT_EVE_TYPE, FOT_EVE_SIZE, FOT_EVE_TMPNAME) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sdsssss", $nombreGasto, $montoGasto, $fechaPagoGasto, 
                $nombre_archivo, $tipo_archivo, $tamano_archivo,  $ruta_destino);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Servicio agregado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo crear el registro']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error en la preparación del SQL (INSERT 1)']);
        }  
    }else{
        $insert_query = "INSERT INTO t_gasto_interno 
        (Nom_Gasto, Monto_Gasto, Fech_Pag_Gasto) 
        VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sds", $nombreGasto, $montoGasto, $fechaPagoGasto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Servicio agregado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo crear el registro']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error en la preparación del SQL (INSERT 2)']);
        } 
    }

}
  



?>