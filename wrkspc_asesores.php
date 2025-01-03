<?php
// Consulta para obtener los tipos de prospección
$queryTiposProspeccion = "SELECT ID_tipo_Prospeccion, Nombre_Tipo_Prosp  FROM t_tipo_prospeccion";
$resultTiposProspeccion = $conn->query($queryTiposProspeccion);

$tiposProspeccion = [];
if ($resultTiposProspeccion->num_rows > 0) {
    while ($row = $resultTiposProspeccion->fetch_assoc()) {
        $tiposProspeccion[] = $row; // Guardar los resultados en un array
    }
}

// Consulta para obtener las etapas de prospección
$queryEtapasProspeccion = "SELECT ID_etapa_seguimiento_Inter, Nombre_Etapa_Seg_Inter FROM t_etapa_seguimiento_inter";
$resultEtapasProspeccion = $conn->query($queryEtapasProspeccion);

$etapasProspeccion = [];
if ($resultEtapasProspeccion->num_rows > 0) {
    while ($row = $resultEtapasProspeccion->fetch_assoc()) {
        $etapasProspeccion[] = $row;
    }
}

// Convertir el array de tipos a JSON para usar en JavaScript
$tiposProspeccionJSON = json_encode($tiposProspeccion);
$etapasProspeccionJSON = json_encode($etapasProspeccion);

// Manejar la solicitud para obtener datos de trazabilidad
if (isset($_GET['action']) && $_GET['action'] === 'getTrazabilidad' && isset($_GET['idInteresado'])) {
    $idInteresado = $_GET['idInteresado'];

    // Consulta a la tabla t_prospecto basada en el ID del interesado
    $queryTrazabilidad = "SELECT ID_Prospecto, ID_Dat_Inter, ID_tipo_Prospeccion, Detalle, FOT_EVE_NAME, FOT_EVE_TMPNAME , IDS_User, COD_EST_OBJ, DATE(FCHS_REG) AS Fecha, TIME(FCHS_REG) AS Hora FROM t_prospecto WHERE ID_Dat_Inter = ?";
    $stmt = $conn->prepare($queryTrazabilidad);
    $stmt->bind_param("i", $idInteresado);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'fecha' => $row['Fecha'],
            'hora' => $row['Hora'],
            'detalle' => $row['Detalle'],
            'archivo' => $row['FOT_EVE_NAME'], // Nombre original del archivo
            'id' => $row['ID_Prospecto'], // ID único para descargar el archivo
        ];
    }
    // Devolver los datos como JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Manejar la solicitud para descargar un archivo
if (isset($_GET['action']) && $_GET['action'] === 'downloadFile' && isset($_GET['id'])) {
    $idProspecto = $_GET['id'];

    // Obtener datos del archivo basado en el ID
    $queryArchivo = "
        SELECT 
            FOT_EVE_NAME, 
            FOT_EVE_TMPNAME 
        FROM t_prospecto 
        WHERE ID_Prospecto = ?
    ";
    $stmt = $conn->prepare($queryArchivo);
    $stmt->bind_param("i", $idProspecto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $fileData = $result->fetch_assoc();
        $fileName = $fileData['FOT_EVE_NAME'];
        $fileTmpName = $fileData['FOT_EVE_TMPNAME'];
        $filePath = __DIR__ . '/documentos_de_prospeccion/' . $fileTmpName;

        if (file_exists($filePath)) {
            // Forzar la descarga del archivo
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            echo "Archivo no encontrado.";
            exit;
        }
    } else {
        http_response_code(404);
        echo "No se encontró el archivo.";
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpace de Asesores</title>

</head>

<body>


    <div class="mt-4">
    <h1 class="text-center">WorkSpace de Asesores</h1>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-center mb-3">
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Cliente')">Cliente</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Cliente Potencial')">Cliente Potencial</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Prospecto')">Prospecto</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('No Prospecto')">No Prospecto</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Ex-Cliente')">Ex-Cliente</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('No Activo')">No Activo</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('')">Interesado (General)</button>
            </div>


            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-12">
                    <div class="table-responsive">
                        <table id="tablaWorkSpaceAsesores" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Interesado</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Etapa</th>
                                    <th>Prospecto</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="trazabilidadModal" tabindex="-1" aria-labelledby="trazabilidadModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="trazabilidadModalLabel">Trazabilidad del Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="trazabilidadContent">Cargando...</div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="modalProspeccion" tabindex="-1" aria-labelledby="modalProspeccionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    </div>
                </div>
            </div>

            <!-------------------------------------------------------------------------------- TOASTS ---------------------------------------------------------------------------------->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <!-- Toast para éxito al modificar un producto -->
                

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarProspeccion" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Prospectado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para error -->
                <div id="toastErrorModify" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            Ocurrió un error al intentar procesar la solicitud.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script>
        document.querySelector('.btn-close').addEventListener('click', function() {
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide(); // Forzar el cierre del modal
        });
    </script>
    <script>
        const tiposProspeccion = <?= $tiposProspeccionJSON ?>;
        const etapasProspeccion = <?= $etapasProspeccionJSON ?>;

        function setProspeccionData(idInteresado, nombreInteresado, etapaSeguimiento) {
            // Generar dinámicamente el contenido del modal
            let modalContent;
            if (etapaSeguimiento === "En Compromiso de Pago") {
            // Modal simplificado para "En Compromiso de Pago"
            modalContent = `
                <form id="formProspeccion2" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProspeccionLabel">Registro de Compromiso de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idInteresado" id="idInteresado" value="${idInteresado}">
                        <div class="mb-3">
                            <label for="nombreCliente" class="form-label fw-bold">Nombre Cliente:</label>
                            <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="${nombreInteresado}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="fechaProximaInteraccion" class="form-label fw-bold">Fecha de Pago:</label>
                            <input type="datetime-local" class="form-control" id="fechaProximaInteraccion" name="fechaProximaInteraccion" required>
                        </div>
                        <div class="mb-3">
                            <label for="archivo" class="form-label fw-bold">Archivo:</label>
                            <input type="file" class="form-control" id="archivo" name="archivo" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="registrarCompromisoPago">Registrar</button>
                    </div>
                </form>
            `;
        } else {
            // Modal completo para otras etapas
            modalContent = `
                <form id="formProspeccion1" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProspeccionLabel">Información de Prospección</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idInteresado" id="idInteresado" value="${idInteresado}">
                        <div class="mb-3">
                            <label for="nombreCliente" class="form-label fw-bold">Nombre Cliente:</label>
                            <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="${nombreInteresado}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tipoProspeccion" class="form-label fw-bold">Tipo Prospección:</label>
                            <select class="form-select" id="tipoProspeccion" name="tipoProspeccion" required>
                                ${tiposProspeccion.map(tipo => `<option value="${tipo.ID_tipo_Prospeccion}">${tipo.Nombre_Tipo_Prosp}</option>`).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="archivo" class="form-label fw-bold">Archivo:</label>
                            <input type="file" class="form-control" id="archivo" name="archivo">
                        </div>
                        <div class="mb-3">
                            <label for="detalleObservaciones" class="form-label fw-bold">Detalle y Observaciones de Contacto:</label>
                            <textarea class="form-control" id="detalleObservaciones" name="detalleObservaciones" rows="6" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="etapaProspeccion" class="form-label fw-bold">Etapa Prospección:</label>
                            <select class="form-select" id="etapaProspeccion" name="etapaProspeccion" required>
                                ${etapasProspeccion.map(etapa => `<option value="${etapa.ID_etapa_seguimiento_Inter}">${etapa.Nombre_Etapa_Seg_Inter}</option>`).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fechaProximaInteraccion" class="form-label fw-bold">Fecha Próxima Interacción:</label>
                            <input type="datetime-local" class="form-control" id="fechaProximaInteraccion" name="fechaProximaInteraccion" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="registrarProspeccion">Registrar</button>
                    </div>
                </form>
            `;
        }
            // Insertar el contenido dinámico en el modal
            const modalElement = document.getElementById('modalProspeccion');
            modalElement.querySelector('.modal-content').innerHTML = modalContent;

            // Mostrar el modal
            const modalInstance = new bootstrap.Modal(modalElement);
            modalInstance.show();
            

            modalElement.addEventListener('hidden.bs.modal', function () {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove(); // Eliminar el fondo oscuro manualmente
                }
            });
        }
    </script>

    <script>

        function setTrazabilidad(idInteresado, nombreInteresado, contactoCliente, fechaProxInter) {
            // Generar dinámicamente el contenido del modal
            const modalContent = `
                <div class="modal-header">
                    <h5 class="modal-title" id="trazabilidadModalLabel">Trazabilidad del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idInteresado" id="idInteresado" value="${idInteresado}">
                    <div class="mb-3">
                        <label for="nombreCliente" class="form-label fw-bold">Nombre del Cliente:</label>
                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="${nombreInteresado}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="contactoCliente" class="form-label fw-bold">Contacto del Cliente:</label>
                        <input type="text" class="form-control" id="contactoCliente" name="contactoCliente" value="${contactoCliente}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fechaProximoContacto" class="form-label fw-bold">Fecha de Próximo Contacto:</label>
                        <input type="text" class="form-control" id="fechaProximoContacto" name="fechaProximoContacto" value="${fechaProxInter}" readonly>
                    </div>
                    <div class="table-responsive">
                        <table id="trazabilidadTable" class="display table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Detalle</th>
                                    <th>Archivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            // Insertar el contenido dinámico en el modal
            const modalElement1 = document.getElementById('trazabilidadModal');
            modalElement1.querySelector('.modal-content').innerHTML = modalContent;

            // Mostrar el modal
            const modalInstance2 = new bootstrap.Modal(modalElement1);
            modalInstance2.show();

            modalElement1.addEventListener('hidden.bs.modal', function () {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove(); // Eliminar el fondo oscuro manualmente
                }
            });

            // Hacer una solicitud AJAX para obtener los datos de trazabilidad
        fetch(`wrkspc_asesores.php?action=getTrazabilidad&idInteresado=${idInteresado}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = modalElement1.querySelector('#trazabilidadTable tbody');
                tableBody.innerHTML = ''; // Limpiar contenido previo

                data.forEach(row => {
                    const newRow = `
                        <tr>
                            <td>${row.fecha}</td>
                            <td>${row.hora}</td>
                            <td>${row.detalle}</td>
                            <td>${row.archivo ? `<a href="wrkspc_asesores.php?action=downloadFile&id=${row.id}" target="_blank">Descargar archivo</a>` : 'N/A'}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += newRow;
                });
            })
            .catch(error => {
                console.error('Error al obtener los datos de trazabilidad:', error);
            });


        }

    </script>


    <script>

        var tablaWorkSpace
        tablaWorkSpace = $('#tablaWorkSpaceAsesores').DataTable({
        ajax: {
            url: 'get_wrkspc.php', // El archivo que obtiene los registros
            dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
        },
        columns: [{
                data: 'ID_Interesado',
                render: function(data, type, row) {
                    return `INTER-${data}`; // Formato con prefijo PROD-
                }
            },
            {
                data: 'Nombre_Completo'
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                            <a href="https://wa.me/51${row.Celular}" 
                                target="_blank" 
                                class="text-success d-flex align-items-center">
                                <i class="fab fa-whatsapp fa-lg me-1"></i>
                                ${row.Celular}
                            </a>
                        `;
                }
            },
            {
                data: 'Estado_Seguimiento'
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                            <button 
                                class="btn btn-info btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#trazabilidadModal" 
                                onclick="setTrazabilidad(
                                       ${row.ID_Interesado}, 
                                        '${row.Nombre_Completo}', 
                                        '${row.Celular}',
                                        '${row.Fecha_Prox}'
                                    )">
                                ${row.Etapa_Seguimiento}
                            </button>
                        `;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                            <button 
                                    class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalProspeccion" 
                                    onclick="setProspeccionData(
                                        ${row.ID_Interesado}, 
                                        '${row.Nombre_Completo}', 
                                        '${row.Etapa_Seguimiento}'
                                    )">
                                    Prospectar
                            </button>
                        `;
                }
            }
        ]
    });


    $('#formProspeccion1').on('submit', function(e) {

        e.preventDefault(); 
        var formData = $(this).serialize();

        console.log('Datos enviados:', formData); // Verifica qué datos se están enviando

        // Realiza la solicitud AJAX
        $.ajax({
            url: 'agregar_prospeccion.php', // Dirección del servidor
            method: 'POST',
            data: formData,
            dataType: 'json', // Esperamos una respuesta JSON
            success: function(response) {
                tablaWorkSpace.ajax.reload();
                if (response.status === 'success') {
                    $('#toastAgregarProspeccion').toast('show');
                    console.log("prospectado");
                } else {
                    // alert('Error: ' + response.message);
                    $('#toastErrorModify').toast('show');
                    console.log("producto error  error")
                }
            },
            error: function(xhr, status, error) {
                // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                $('#toastErrorModify').toast('show');
                console.error('Detalles del error:', error);
            }
        });
    });


    $('#formProspeccion2').on('submit', function(e) {

        e.preventDefault(); 
        var formData = $(this).serialize();

        console.log('Datos enviados:', formData); // Verifica qué datos se están enviando

        // Realiza la solicitud AJAX
        $.ajax({
            url: 'agregar_prospeccion_pago.php', // Dirección del servidor
            method: 'POST',
            data: formData,
            dataType: 'json', // Esperamos una respuesta JSON
            success: function(response) {
                tablaWorkSpace.ajax.reload();
                if (response.status === 'success') {
                    $('#toastAgregarProspeccion').toast('show');
                    console.log("prospectado");
                } else {
                    // alert('Error: ' + response.message);
                    $('#toastErrorModify').toast('show');
                    console.log("producto error  error")
                }
            },
            error: function(xhr, status, error) {
                // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                $('#toastErrorModify').toast('show');
                console.error('Detalles del error:', error);
            }
        });
    });

    

    </script>
    <script>
        $(document).ready(function () {
            window.filterByEstado = function (estado) {
                if (estado) {
                // Usa ^ y $ para buscar coincidencias exactas
                table
                    .column(3)
                    .search("^" + estado + "$", true, false)
                    .draw();
                } else {
                table.search("").columns().search("").draw(); // Limpia los filtros
                }
            };
        });
    </script>
</body>

</html>