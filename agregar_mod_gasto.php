<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Obtener los datos del formulario
$id_gasto = intval($_POST['id_gasto']); // ID para distinguir entre insertar o actualizar
$nom_gasto = $_POST['nom_gasto'];
$monto_gasto = $_POST['monto_gasto'];
$fech_pag_gasto = $_POST['fech_pag_gasto'];
$archivo = isset($_FILES['archivo']) ? $_FILES['archivo'] : null;

$nombre_archivo = $archivo ? $archivo['name'] : null;
$tipo_archivo = $archivo ? $archivo['type'] : null;
$tamano_archivo = $archivo ? $archivo['size'] : null;
$ruta_temporal = $archivo ? $archivo['tmp_name'] : null;

if ($id_gasto) {
    // Si se proporciona un ID, se realizará la actualización
    if ($archivo && $archivo['error'] === 0) {
        // Si se sube un archivo, manejar la carga y actualizar también los campos relacionados al archivo
        $directorio_destino = 'servicios/';
        $ruta_destino = $directorio_destino . $nombre_archivo;

        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            $update_query = "UPDATE t_gasto_interno SET 
                                Nom_Gasto = ?, 
                                Monto_Gasto = ?, 
                                Fech_Pag_Gasto = ?, 
                                FOT_EVE_NAME = ?, 
                                FOT_EVE_TYPE = ?, 
                                FOT_EVE_SIZE = ?, 
                                FOT_EVE_TMPNAME = ?,
                             WHERE ID_Gasto = ?";
            $stmt = $conn->prepare($update_query);

            if ($stmt) {
                $stmt->bind_param("sdsssisi", $nom_gasto, $monto_gasto, $fech_pag_gasto, $nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino, $id_gasto);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Gasto actualizado exitosamente con archivo.']);
                } else {
                    echo json_encode(['error' => 'No se pudo actualizar el registro.']);
                }
                $stmt->close();
            } else {
                echo json_encode(['error' => 'Error en la preparación del SQL (UPDATE con archivo).']);
            }
        } else {
            echo json_encode(['error' => 'Error al mover el archivo.']);
        }
    } else {
        // Si no se sube un archivo, solo actualizar los campos principales
        $update_query = "UPDATE t_gasto_interno SET 
                            Nom_Gasto = ?, 
                            Monto_Gasto = ?, 
                            Fech_Pag_Gasto = ? 
                         WHERE ID_Gasto = ?";
        $stmt = $conn->prepare($update_query);

        if ($stmt) {
            $stmt->bind_param("sdsi", $nom_gasto, $monto_gasto, $fech_pag_gasto, $id_gasto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Gasto actualizado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo actualizar el registro.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error en la preparación del SQL (UPDATE).']);
        }
    }
} else {
    // Si no se proporciona un ID, se realizará la inserción
    $insert_query = "INSERT INTO t_gasto_interno (ID_Gasto, Nom_Gasto, Monto_Gasto, Fech_Pag_Gasto, FOT_EVE_NAME, FOT_EVE_TYPE, FOT_EVE_SIZE, FOT_EVE_TMPNAME) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        if ($archivo && $archivo['error'] === 0) {
            $directorio_destino = 'servicios/';
            $ruta_destino = $directorio_destino . $nombre_archivo;

            if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                $stmt->bind_param("isdsssss",$id_gasto, $nom_gasto, $monto_gasto, $fech_pag_gasto, $nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Gasto agregado exitosamente con archivo.']);
                } else {
                    echo json_encode(['error' => 'No se pudo crear el registro.']);
                }
            } else {
                echo json_encode(['error' => 'Error al mover el archivo.']);
            }
        } else {
            // Si no se sube un archivo, solo agregar los campos principales
            $insert_query = "INSERT INTO t_gasto_interno (ID_Gasto, Nom_Gasto, Monto_Gasto, Fech_Pag_Gasto) 
                             VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isds",$id_gasto, $nom_gasto, $monto_gasto, $fech_pag_gasto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Gasto agregado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo crear el registro.']);
            }
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT).']);
    }
}
?>