<?php
include 'connection.php';


// header('Content-Type: multipart/form-data');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Recibe los datos del formulario
$pago_id = intval($_POST['id_pago']);
$id_cola_pago = $_POST['id_cola_pago'];
$montoPago = $_POST['monto_pago'];


if(isset($_FILES['archivo_pago'])) {
    $archivo = $_FILES['archivo_pago'];
    $nombre_archivo = uniqid('', true).".".$archivo['name'];
    $tipo_archivo = $archivo['type'];
    $tamano_archivo = $archivo['size'];
    $ruta_temporal = $archivo['tmp_name'];
    $ruta_destino = 'pagos/' . basename($nombre_archivo);
}

if ($pago_id) {
    
    // Consultar el nombre del archivo actual asociado al registro
    $query = "SELECT FOT_PAGO_NAME FROM t_pago WHERE ID_Pago = ?";
    $stmtSelect = $conn->prepare($query);
    $stmtSelect->bind_param("i", $pago_id);
    $stmtSelect->execute();
    $stmtSelect->bind_result($currentFileName);
    $stmtSelect->fetch();
    $stmtSelect->close();
    

    if ($archivo && $tamano_archivo > 0 && move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName != '') {
            $filePath = 'pagos/' . $currentFileName;
            if (file_exists($filePath)) {
                unlink($filePath); // Eliminar archivo anterior
            }
        }

        // Preparar la consulta SQL para insertar datos
        $sql = "UPDATE t_pago SET 
                        ID_Colab = ?, 
                        Monto_Pago = ?, 
                        FOT_PAGO_NAME = ?, 
                        FOT_PAGO_TYPE = ?, 
                        FOT_PAGO_SIZE = ?, 
                        FOT_PAGO_TMPNAME = ? 
                        WHERE ID_Pago = ?";
        
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idssssi",$id_cola_pago, $montoPago, $nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino, $pago_id);
       
    } else {

        $sql = "UPDATE t_pago SET 
                    ID_Colab = ?, 
                    Monto_Pago = ?
                    WHERE ID_Pago = ?";
        // Si no se sube un archivo, solo actualizamos los campos sin los relacionados al archivo
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idi", $id_cola_pago, $montoPago, $pago_id);
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
        $insert_query = "INSERT INTO t_pago 
            (ID_Colab, Monto_Pago, FOT_PAGO_NAME, FOT_PAGO_TYPE, FOT_PAGO_SIZE, FOT_PAGO_TMPNAME) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("idssss", $id_cola_pago, $montoPago, 
                $nombre_archivo, $tipo_archivo, $tamano_archivo,  $ruta_destino);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Pago agregado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo crear el registro']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error en la preparación del SQL (INSERT 1)']);
        }  
    }else{
        $insert_query = "INSERT INTO t_pago
        (ID_Pago, Monto_Pago) 
        VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("id", $id_cola_pago, $montoPago);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Pago agregado exitosamente.']);
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