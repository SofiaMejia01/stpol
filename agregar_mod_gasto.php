<?php
include 'connection.php';


// header('Content-Type: multipart/form-data');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Recibe los datos del formulario
$gasto_id = intval($_POST['id_gasto']);

$nombreGasto = $_POST['nom_gasto'];
$montoGasto = $_POST['monto_gasto'];
$fechaPagoGasto = $_POST['fech_pag_gasto'];

$archivo = $_FILES['archivo'];
$nombre_archivo = $archivo['name'];
$tipo_archivo = $archivo['type'];
$tamano_archivo = $archivo['size'];
$ruta_temporal = $archivo['tmp_name'];
$ruta_destino = 'servicios/' . basename($nombre_archivo);

if ($gasto_id) {
    
    // Definir la ruta donde se guardará el archivo
    

    if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // Preparar la consulta SQL para insertar datos
        $sql = "INSERT INTO t_gasto_interno (Nom_Gasto, Monto_Gasto, Fech_Pag_Gasto, FOT_EVE_NAME, FOT_EVE_TYPE, FOT_EVE_SIZE, FOT_EVE_TMPNAME) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssss", $nombreGasto, $montoGasto, $fechaPagoGasto, $nombre_archivo, $tipo_archivo, $tamano_archivo,  $ruta_destino);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // echo "Gasto registrado exitosamente.";

          


           
        } else {
            // echo "Error al registrar el gasto: " . $stmt->error;
            $response = ['status' => 'error', 'message' => $stmt->error];
        }

       
    } else {
        echo "Error al mover el archivo.";
    }

    echo json_encode($response);
    exit;   
   
}
else {

    if(move_uploaded_file($ruta_temporal, $ruta_destino)){
        $insert_query = "INSERT INTO t_gasto_interno (Nom_Gasto, Monto_Gasto, Fech_Pag_Gasto, FOT_EVE_NAME, FOT_EVE_TYPE, FOT_EVE_SIZE, FOT_EVE_TMPNAME) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sdsssss", $nombreGasto, $montoGasto, $fechaPagoGasto, $nombre_archivo, $tipo_archivo, $tamano_archivo,  $ruta_destino);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Servicio agregado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo crear el registro']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
        }  
    }else{
        echo json_encode(['error' => 'No se pudo mover el archivo de la ruta temporal a la ruta destino']);  
    }

}
  



?>