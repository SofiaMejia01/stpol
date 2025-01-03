<?php
include 'connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}



// si encuentra un id, se hara el modificar curso
$prod_serv_marca_id = intval($_POST['id_prodServMarca']);
$id_marca = $_POST['id_marca'];
$nombreProdServMarca = $_POST['nombre_prod_serv_marca'];
$descripProdServMarca = $_POST['desc_prod_serv_marca'];


if ($prod_serv_marca_id) {

    $update_query = "UPDATE t_producto_servicio_marca SET ID_Marca = ?, Nombre_Prod_Serv_Marca = ?, Descripcion_Prod_Serv_Marca = ?  WHERE ID_Prod_Serv_Marca = ?";

    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("issi", $id_marca , $nombreProdServMarca, $descripProdServMarca,  $prod_serv_marca_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Producto/Servicio modificado exitosamente.']);
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

    $insert_query = "INSERT INTO t_producto_servicio_marca (ID_Marca, Nombre_Prod_Serv_Marca, Descripcion_Prod_Serv_Marca) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("iss", $id_marca, $nombreProdServMarca, $descripProdServMarca );
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => ' Producto/Servicio por marca agregado exitosamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo crear el registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación del SQL (INSERT)']);
    }
}