<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_inter'])) {
    // Recibe los datos del formulario
    $id_colab = 550;
    $nombreInter = $_POST['nombre_inter'];
    $apellidoInter = $_POST['apellido_inter'];
    $numeroDocInter = $_POST['numero_doc_inter'];
    $correoInter = $_POST['correo_inter'];
    $celularInter = $_POST['celular_inter'];
    $tipoRegInter = 'Manual';

    // Inserta un nuevo registro en la tabla t_colaborador
    $stmt = $conn->prepare("INSERT INTO t_interesados (ID_Colab, Nombre_Inter, Apellido_Inter, Numero_Doc_Inter, Correo_Inter, Celular_Inter, Tipo_Registro_Inter) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $id_colab, $nombreInter, $apellidoInter, $numeroDocInter, $correoInter, $celularInter, $tipoRegInter);

    if ($stmt->execute()) {
        echo "Registro insertado exitosamente.";
    } else {
        echo "Error al insertar el registro: " . $stmt->error;
    }

    $stmt->close();
}

// Consulta los datos para la tabla (excluye Disp_Colab)
$sql = "SELECT * from t_interesados WHERE COD_EST_OBJ <> 0;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interesados</title>

</head>
<body>
    
<!-- Contenedor principal -->
    <div class="mt-4">
        <h1 class="text-center">Registro de Interesados</h1>
        <div class="row mt-5 mb-5">            
            <!-- Sección para agregar un nuevo interesado (columna izquierda) -->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formInterTitulo>Agregar Nuevo Interesado</h5>
                    <form id="formAgregarModificarInter" class="p-3 border rounded">
                        <input type="hidden" id="id_inter" name="id_inter">
                        <div class="form-group mb-3">
                            <label for="nombre_inter">Nombre Interesado:</label>
                            <input type="text" name="nombre_inter" id="nombre_inter" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="apellido_inter">Apellido Interesado:</label>
                            <input type="text" name="apellido_inter" id="apellido_inter" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="numero_doc_inter">Número de Documento Interesado:</label>
                            <input type="text" name="numero_doc_inter" id="numero_doc_inter" class="form-control" required>
                        </div>                        
                        <div class="form-group mb-3">
                            <label for="correo_inter">Correo Interesado:</label>
                            <input type="email" name="correo_inter" id="correo_inter" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="celular_inter">Celular Interesado:</label>
                            <input type="text" name="celular_inter" id="celular_inter" class="form-control" required>
                        </div>
                        <button id="btn-interesados" type="submit" class="btn btn-primary">Agregar Interesado</button>
                    </form>
                </div>
            </div>

            <!-- Sección para listar los colaboradores (columna derecha) -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Listado de Interesados</h5>
                    <br>
                    <!-- Agregamos table-responsive para hacer la tabla desplazable en pantallas pequeñas -->
                    <div class="table-responsive">
                        <table id="interesadoTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Interesado</th>
                                    <th>Nombre Interesado</th>
                                    <th>Apellido Interesado</th>
                                    <th>Numero de Documento</th>
                                    <th>Correo</th>
                                    <th>Celular del Interesado</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>                         
                        </table>
                    </div>
                </div>
            </div>
             <!-------------------------------------------------------------------------------- TOASTS ---------------------------------------------------------------------------------->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div id="toastModificarInter" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Interesado modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarInter" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Interesado agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarInter" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Interesado eliminado correctamente.
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
        function confirmDelete() {
            return confirm("¿Estás seguro de que deseas eliminar este Interesado?");
        }

        var tablaInteresado;

        $(document).ready(function() {
            // Inicializar DataTables
            tablaInteresado = $('#interesadoTable').DataTable({
                ajax: {
                    url: 'get_interesados.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Inter',
                        render: function(data, type, row) {
                            return `Inter-${data}`; // Formato con prefijo PROD-
                        }
                    },
                    {
                        data: 'Nombre_Inter'
                    },
                    {
                        data: 'Apellido_Inter'
                    },
                    {
                        data: 'Numero_Doc_Inter'
                    },
                    {
                        data: 'Correo_Inter'
                    },
                    {
                        data: 'Celular_Inter'
                    },
                    {
                        data: 'FCHS_REG'
                    },


                    {
                        data: null,
                        render: function(data, type, row) {
                            return `    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Inter}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Inter}">Eliminar</button>
                             `;
                        }
                    }
                ]
            });

            $('#formAgregarModificarInter').on('submit', function(e) {
                e.preventDefault(); // Evita el envío tradicional del formulario

                // Serializa los datos del formulario
                var formData = $(this).serialize();

                console.log('Datos enviados:', formData); // Verifica qué datos se están enviando

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_mod_interesado.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    success: function(response) {
                        tablaInteresado.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_inter').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarInter').toast('show');
                                console.log("Interesado modificado");
                                
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarInter').toast('show');
                                console.log("Interesado agregado");
                               
                            }
                            limpiarFormInteresado();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("Interesado error  error")
                        }
                        limpiarFormInteresado();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });

            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#interesadoTable tbody').on('click', '.tablaModificar', function() {
                $("#formInterTitulo").html("Modificar Interesado");
                $("#btn-interesados").html("Modificar Interesado");

                const id = $(this).data('id');
                $.ajax({
                    url: 'get_interesado.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_inter').val(data.ID_Inter);
                        $('#nombre_inter').val(data.Nombre_Inter);
                        $('#apellido_inter').val(data.Apellido_Inter);
                        $('#numero_doc_inter').val(data.Numero_Doc_Inter);
                        $('#correo_inter').val(data.Correo_Inter);
                        $('#celular_inter').val(data.Celular_Inter);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#interesadoTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_interesado.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarInter').toast('show');
                        tablaInteresado.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormInteresado();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el curso: ' + error);
                    }
                });

            });

        });

        function limpiarFormInteresado() {
            $('#id_inter').val("");
            $('#nombre_inter').val("");
            $('#apellido_inter').val("");
            $('#numero_doc_inter').val("");
            $('#correo_inter').val("");
            $('#celular_inter').val("");
          
            $('#formInterTitulo').html('>Agregar Nuevo Interesado');
            $('#btn-interesados').html('Agregar');

        }
</script>

</body>
</html>
