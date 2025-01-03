<?php
include 'connection.php';


// header('Content-Type: multipart/form-data');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Recibe los datos del formulario
$marca_id = intval($_POST['id_marca']);

$nombreMarca = $_POST['nom_marca'];
$descMarca = $_POST['desc_marca'];

if(isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo'];
    $nombre_archivo = uniqid('', true).".".$archivo['name'];
    $tipo_archivo = $archivo['type'];
    $tamano_archivo = $archivo['size'];
    $ruta_temporal = $archivo['tmp_name'];
    $ruta_destino = 'marcas/' . basename($nombre_archivo);
}

if ($marca_id) {
    
    // Consultar el nombre del archivo actual asociado al registro
    $query = "SELECT FOT_MARCA_NAME FROM t_marca WHERE ID_Marca = ?";
    $stmtSelect = $conn->prepare($query);
    $stmtSelect->bind_param("i", $marca_id);
    $stmtSelect->execute();
    $stmtSelect->bind_result($currentFileName);
    $stmtSelect->fetch();
    $stmtSelect->close();
    

    if ($archivo && $tamano_archivo > 0 && move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName != '') {
            $filePath = 'marcas/' . $currentFileName;
            if (file_exists($filePath)) {
                unlink($filePath); // Eliminar archivo anterior
            }
        }

        // Preparar la consulta SQL para insertar datos
        $sql = "UPDATE t_marca SET 
                        Nombre_Marca = ?, 
                        Descripcion_Marca = ?,  
                        FOT_MARCA_NAME = ?, 
                        FOT_MARCA_TYPE = ?, 
                        FOT_MARCA_SIZE = ?, 
                        FOT_MARCA_TMPNAME = ? 
                        WHERE ID_Marca = ?";
        
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nombreMarca, $descMarca, $nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino, $marca_id);
       
    } else {
        $sql = "UPDATE t_marca SET 
                    Nombre_Marca = ?, 
                    Descripcion_Marca = ? 
                    WHERE ID_Marca = ?";
        // Si no se sube un archivo, solo actualizamos los campos sin los relacionados al archivo
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombreMarca, $descMarca, $marca_id);
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
        $insert_query = "INSERT INTO t_marca 
            (Nombre_Marca, Descripcion_Marca, FOT_MARCA_NAME, FOT_MARCA_TYPE, FOT_MARCA_SIZE, FOT_MARCA_TMPNAME) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssssss", $nombreMarca, $descMarca, $nombre_archivo, $tipo_archivo, $tamano_archivo,  $ruta_destino);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Marca agregado exitosamente.']);
            } else {
                echo json_encode(['error' => 'No se pudo crear el registro']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error en la preparación del SQL (INSERT 1)']);
        }  
    }else{
        $insert_query = "INSERT INTO t_marca 
        (Nombre_Marca, Descripcion_Marca) 
        VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $nombreMarca, $descMarca);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => ' Marca agregado exitosamente.']);
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