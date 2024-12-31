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
    $tipo_archivoContrato = $archivoContrato['type'];
    $tamano_archivoContrato = $archivoContrato['size'];
    $ruta_temporalContrato = $archivoContrato['tmp_name'];
    $ruta_destinoContrato = 'contratos/' . basename($nombre_archivoContrato);
}

$horarioTrabColab = $_POST['hor_trab_colaborador'];
$horarioRefriColab = $_POST['hor_refri_colaborador'];



if ($colaborador_id) {

    $sqlUpdate = "UPDATE t_gasto_interno SET 
                    Nom_Gasto = ?, 
                    Monto_Gasto = ?, 
                    Fech_Pag_Gasto = ?";

    $tipoDatos = "sdsssssi";
    $parametros = [ $nombreGasto, $montoGasto, $fechaPagoGasto ];

    // 1er archivo
    if ($archivoCV && $tamano_archivoCV > 0 && move_uploaded_file($ruta_temporalCV, $ruta_destinoCV)) {

        // Consultar el nombre del archivo actual DEL CV asociado al registro
        $query = "SELECT FOT_CV_NAME FROM t_colaborador WHERE ID_Colab = ?";
        $stmtSelect = $conn->prepare($query);
        $stmtSelect->bind_param("i", $colaborador_id);
        $stmtSelect->execute();
        $stmtSelect->bind_result($currentFileName);
        $stmtSelect->fetch();
        $stmtSelect->close();

        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName != '') {
            $filePath = 'servicios/' . $currentFileName;
            if (file_exists($filePath)) {
                unlink($filePath); // Eliminar archivo anterior
            }
        }

        // Preparar la consulta SQL para insertar datos
        $sqlUpdate .= ",
            FOT_CV_NAME = ?, 
            FOT_CV_TYPE = ?, 
            FOT_CV_SIZE = ?, 
            FOT_CV_TMPNAME = ?";

        $tipoDatos .= "ssss";

        $parametros = array_merge($parametros, [$nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino]);

    }

    // 2do archivo
    if ($archivoContrato && $tamano_archivoContrato > 0 && move_uploaded_file($ruta_temporalContrato, $ruta_destinoContrato)) {

        // Consultar el nombre del archivo actual DEL CV asociado al registro
        $query = "SELECT FOT_REG_CONTRATO_NAME FROM t_colaborador WHERE ID_Colab = ?";
        $stmtSelect = $conn->prepare($query);
        $stmtSelect->bind_param("i", $colaborador_id);
        $stmtSelect->execute();
        $stmtSelect->bind_result($currentFileName2);
        $stmtSelect->fetch();
        $stmtSelect->close();

        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName2 != '') {
            $filePath2 = 'contratos/' . $currentFileName2;
            if (file_exists($filePath2)) {
                unlink($filePath2); // Eliminar archivo anterior
            }
        }

        // Preparar la consulta SQL para insertar datos
        $sqlUpdate .= ",
            FOT_REG_CONTRATO_NAME = ?, 
            FOT_REG_CONTRATO_TYPE = ?, 
            FOT_REG_CONTRATO_SIZE = ?, 
            FOT_REG_CONTRATO_TMPNAME = ?";

        $tipoDatos .= "ssss";

        $parametros = array_merge($parametros, [$nombre_archivo, $tipo_archivo, $tamano_archivo, $ruta_destino]);

    }

    $sqlUpdate .= " WHERE ID_Gasto = ?";
    $tipoDatos .= "i";
    $parametros[] = $gasto_id;

    // Preparar la declaración
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param($tipoDatos, ...$parametros);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    } 
    
    $stmt->close(); 
   
}
else {

    $sqlInsert = "INSERT INTO t_colaborador ";
            

    $campos = " (Nombre_Colab, Apellido_Colab, Numero_Doc_Colab, Genero_Colab, Fecha_Nac_Colab, Edad_Colab, Direccion_Colab, Telefono_Colab, Correo_Colab, ID_Puesto, Modalidad_Colab, Sueldo_Colab, Fecha_Pago_Colab, Horario_Trabajo_Colab, Horario_Refrigerio_Colab";
    $interrogaciones = " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

    $tipoDatos = "sssssisssssdsss";
    $parametros = [ $nombreColab, $apellidoColab, $numDocColab, $generoColab, $fechaNacColab, $edadColab, $direccionColab,$telefonoColab, 
    $correoColab, $puestoColab, $modalidadColab, $sueldoColab, $fechaPagColab, $horarioTrabColab, $horarioRefriColab ];

    // 1er archivo
    if ($archivoCV && $tamano_archivoCV > 0 && move_uploaded_file($ruta_temporalCV, $ruta_destinoCV)) {

        // Consultar el nombre del archivo actual DEL CV asociado al registro
        $query = "SELECT FOT_CV_NAME FROM t_colaborador WHERE ID_Colab = ?";
        $stmtSelect = $conn->prepare($query);
        $stmtSelect->bind_param("i", $colaborador_id);
        $stmtSelect->execute();
        $stmtSelect->bind_result($currentFileName);
        $stmtSelect->fetch();
        $stmtSelect->close();

        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName != '') {
            $filePath = 'curriculumVitae/' . $currentFileName;
            if (file_exists($filePath)) {
                unlink($filePath); // Eliminar archivo anterior
            }
        }

        $campos .= ",FOT_CV_NAME, FOT_CV_TYPE, FOT_CV_SIZE, FOT_CV_TMPNAME";
        $interrogaciones .= ", ?, ?, ?, ?";

        $tipoDatos .= "ssss";

        $parametros = array_merge($parametros, [$nombre_archivoCV, $tipo_archivoCV, $tamano_archivoCV, $ruta_destinoCV]);

    }

    // 2do archivo
    if ($archivoContrato && $tamano_archivoContrato > 0 && move_uploaded_file($ruta_temporalContrato, $ruta_destinoContrato)) {

        // Consultar el nombre del archivo actual DEL CV asociado al registro
        $query = "SELECT FOT_REG_CONTRATO_NAME FROM t_colaborador WHERE ID_Colab = ?";
        $stmtSelect = $conn->prepare($query);
        $stmtSelect->bind_param("i", $colaborador_id);
        $stmtSelect->execute();
        $stmtSelect->bind_result($currentFileName2);
        $stmtSelect->fetch();
        $stmtSelect->close();

        // Verificar si existe un archivo anterior y eliminarlo
        if ($currentFileName2 != '') {
            $filePath2 = 'contratos/' . $currentFileName2;
            if (file_exists($filePath2)) {
                unlink($filePath2); // Eliminar archivo anterior
            }
        }

        $campos .= ",FOT_REG_CONTRATO_NAME, FOT_REG_CONTRATO_TYPE, FOT_REG_CONTRATO_SIZE, FOT_REG_CONTRATO_TMPNAME";
        $interrogaciones .= ", ?, ?, ?, ?";

        $tipoDatos .= "ssss";

        $parametros = array_merge($parametros, [$nombre_archivoContrato, $tipo_archivoContrato, $tamano_archivoContrato, $ruta_destinoContrato]);

    }

    $campos .= ") ";
    $interrogaciones .= ") ";

    $sqlInsert = $sqlInsert . $campos . $interrogaciones;

    //echo "<pre>$sqlInsert</pre>"; 

    // Preparar la declaración
    $stmt = $conn->prepare($sqlInsert);

    if (!$stmt) {
        die("Error en prepare: " . $mysqli->error);
    }

    $stmt->bind_param($tipoDatos, ...$parametros);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    } 
    
    $stmt->close(); 


}
  



?>