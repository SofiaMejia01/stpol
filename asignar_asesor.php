<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Asesores</title>

</head>

<body>

    <div class="mt-4">
    <h1 class="text-center">Asignacion de Asesores</h1>
    <form id="asignarForm" class="p-3 border rounded">
    <div class="container mt-4">
        <div class="row">
            <!-- Interesados sin Asesor -->
            <div class="col-md-5">
                <h5 class="text-center">Interesados sin Asesor</h5>
                <div class="table-responsive border rounded p-2 bg-light">
                    <table id="tablaInteresadosAsign" class="display table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre y Apellido</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Asesores Disponibles -->
            <div class="col-md-5 offset-md-2">
                <h5 class="text-center">Asesores Disponibles</h5>
                <div class="table-responsive border rounded p-2 bg-light">
                    <table id="tablaAsesoresAsign" class="display table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre y Apellido</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botón Asignar -->
        <div class="text-center mt-4">
            <button id="btnAsignar" class="btn btn-success px-4">Asignar Asesor</button>
        </div>
    </div>
    </form>

            <!-------------------------------------------------------------------------------- TOASTS ---------------------------------------------------------------------------------->
             <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarAsignacion class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Asesor asignado correctamente.
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

        var tablaInteresadosSA;
        var tablaAsesores


        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInteresadosSA = $('#tablaInteresadosAsign').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_asignar_asesores.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Inter',
                        render: function(data, type, row) {
                            return `Inter-${data}`; // Formato con prefijo PROD-
                        }
                    },
                    {
                        data: 'Nombre_Completo_Inter'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `    
                                <input type='checkbox' name='interesados[]' value='${row.ID_Inter}'>
                             `;
                        }
                    }
                ]
            });

            tablaAsesores = $('#tablaAsesoresAsign').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_asignar_asesores_s.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Colab',
                        render: function(data, type, row) {
                            return `Colab-${data}`; // Formato con prefijo PROD-
                        }
                    },
                    {
                        data: 'Nombre_Completo_Colab'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `    
                                <input type='radio' name='asesores[]' value='${row.ID_Colab}'>
                             `;
                        }
                    }
                ]
            });


            //aca se agrega o modifica un almacen
            $('#asignarForm').on('submit', function(e) {

                e.preventDefault(); // Evita el envío tradicional del formulario

                // Serializa los datos del formulario
                var formData = $(this).serialize();
                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'asignacion_asesores.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            $('#toastAgregarAsignacion').toast('show');
                            console.log("Asesor asignado");                                
                            limpiarFormCurso();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormCurso();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });

        });

        function limpiarFormCurso() {
            $('#id_curso').val("");
            $('#nom_curso').val("");
            $('#desc_curso').val("");
          
            $('#formCursoTitulo').html('>Agregar Nuevo Curso');
            $('#btn-curso').html('Agregar');

        }
    </script>

</body>

</html>