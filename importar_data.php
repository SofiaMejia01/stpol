<?php
$sql_combo = "SELECT ID_Inter, CONCAT(Nombre_Inter, ' ', Apellido_Inter) AS Nombre_Completo 
              FROM t_interesados 
              WHERE COD_EST_OBJ <> 0 AND Tipo_Registro_Inter = 'Manual'";
$result_combo = $conn->query($sql_combo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importacion de Data</title>

</head>

<body>


    <div class="mt-4">
    <h1 class="text-center">Importar Data</h1>
        <div class="row mt-5 mb-5">
        
            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formDataInterTitulo>Agregar Datos de Interesado</h5>
                    <form id="formAgregarModificarDataInter" class="p-3 border rounded">
                        <input type="hidden" id="id_dat_inter" name="id_dat_inter">
                        <div class="form-group mb-3">
                            <label for="id_interesado">Seleccione un Interesado:</label>
                            <select name="id_interesado" id="id_interesado" class="form-select" required>
                                <option value="">Seleccione un interesado</option>
                                <?php
                                while ($row = $result_combo->fetch_assoc()) {
                                    echo "<option value='{$row['ID_Inter']}'>{$row['Nombre_Completo']}</option>";
                                }
                                ?> 
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="lugar_inter">Lugar Interesado:</label>
                            <input type="text" name="lugar_inter" id="lugar_inter" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="detalle_inter">Detalle y Observación Interesado:</label>
                            <input type="text" name="detalle_inter" id="detalle_inter" class="form-control" required>
                        </div>
                        <button id="btn-datInter" type="submit" class="btn btn-primary">Agregar Data de Interesado</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Interesados</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="dataInterTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Interesado</th>
                                    <th>Nombre Completo</th>
                                    <th>Celular</th>
                                    <th>Lugar</th>
                                    <th>Detalle</th>
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
                <!-- Toast para éxito al modificar un producto -->
                <div id="toastModificarDataInter" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Datos del Interesado modificados correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarDataInter" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Datos del Interesado agregados correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarDataInter" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Datos del Interesado eliminados correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar este Curso?");
        }

        var tablaDataInter;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaDataInter = $('#dataInterTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_data_inter.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Inter',
                        render: function(data, type, row) {
                            return `DAT_INTER-${data}`; // Formato con prefijo PROD-
                        }
                    },
                    {
                        data: 'Nombre_Completo'
                    },
                    {
                        data: 'Celular_Inter'
                    },
                     {
                        data: 'Lugar_Inter'
                    },
                     {
                        data: 'Detalle_Observacion_Inter'
                    },
                     {
                        data: 'FCHS_REG'
                    },                    
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Dat_Inter}">Eliminar</button>
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarDataInter').on('submit', function(e) {
                e.preventDefault(); // Evita el envío tradicional del formulario
                // Serializa los datos del formulario
                var formData = $(this).serialize();

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_data_inter.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    success: function(response) {
                        tablaDataInter.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_dat_inter').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarDataInter').toast('show');
                                console.log("Data del Interesado modificado");
                                
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarDataInter').toast('show');
                                console.log("Data del Interesado agregado");
                               
                            }
                            limpiarFormDataInter();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("Data Interesado error  error")
                        }
                        limpiarFormDataInter();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });
            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#dataInterTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_data_inter.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarDataInter').toast('show');
                        // alert('Registro eliminado exitosamente');
                        tablaDataInter.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormDataInter();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el curso: ' + error);
                    }
                });

            });

        });

        function limpiarFormDataInter() {
            $('#id_interesado').val("");
            $('#lugar_inter').val("");
            $('#detalle_inter').val("");
          
            $('#formDataInterTitulo').html('>Agregar Datos de Interesado');
            $('#btn-datInter').html('Agregar Data de Interesado');

        }
    </script>

</body>

</html>