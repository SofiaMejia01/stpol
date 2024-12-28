<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Gasto Interno</title>

</head>

<body>


    <div class="mt-4">
        <h1 class="text-center">Registro de Gasto Interno</h1>
        <div class="row mt-5 mb-5">

            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formGastoTitulo>Agregar Nuevo Gasto Interno</h5>
                    <form id="formAgregarModificarGasto" class="p-3 border rounded" enctype="multipart/form-data">
                        <input type="hidden" id="id_gasto" name="id_gasto">

                        <div class="form-group mb-3">
                            <label for="nom_gasto" class="form-label">Nombre del Servicio:</label>
                            <input type="text" id="nom_gasto" name="nom_gasto" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="monto_gasto" class="form-label">Monto:</label>
                            <input type="number" step="0.01" id="monto_gasto" name="monto_gasto" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="fech_pag_gasto" class="form-label">Fecha de Pago:</label>
                            <input type="date" name="fech_pag_gasto" id="fech_pag_gasto" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="archivo" class="form-label ">Adjuntar Comprobante de Pago:</label>
                            <input type="file" class="form-control" id="archivo" name="archivo" required>

                            <div id="mostrarNombreArchivo">
                            </div>
                        </div>


                        <button id="btn-gasto" type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Gastos Internos</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="gastoTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Servicio</th>
                                    <th>Nombre Servicio</th>
                                    <th>Monto</th>
                                    <th>Fecha de Pago</th>
                                    <th>Archivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>

                                <!-- Los registros se llenarán aquí dinámicamente -->
                                <!-- <tr>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>

                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-------------------------------------------------------------------------------- TOASTS ---------------------------------------------------------------------------------->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <!-- Toast para éxito al modificar un producto -->
                <div id="toastModificarGasto" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Servicio modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarGasto" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Servicio agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarGasto" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Registro eliminado correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar este Almacen?");
        }

        var tablaInventario;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInventario = $('#gastoTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_gastos.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Gasto'
                    },
                    {
                        data: 'Nom_Gasto'
                    },
                    {
                        data: 'Monto_Gasto'
                    },
                    {
                        data: 'Fech_Pag_Gasto'
                    },

                    {
                        data: 'FOT_EVE_TMPNAME',
                        render: function(data, type, row) {
                            return `<a href='${data}' target='_blank'>Ver Archivo</a>`;
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Gasto}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Gasto}">Eliminar</button>
                               

        
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarGasto').on('submit', function(e) {

                // console.log("entrando a submit");

                e.preventDefault(); // Evita el envío tradicional del formulario

                var form = document.querySelector('#formAgregarModificarGasto');
                // Serializa los datos del formulario
                var formData = new FormData(form);

                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_mod_gasto.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    processData: false, // IMPORTANTE: Evita que jQuery procese los datos
                    contentType: false, // IMPORTANTE: Evita que jQuery establezca un encabezado incorrecto

                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_gasto').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarGasto').toast('show');
                                console.log("Gasto modificado");
                                limpiarFormAlmacen();
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarGasto').toast('show');
                                console.log("Gasto agregado");
                                limpiarFormGasto();
                            }

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormGasto();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });



            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#gastoTable tbody').on('click', '.tablaModificar', function() {
                // console.log("hola sofia");
                // e.preventDefault();
                $("#formGastoTitulo").html("Modificar Gasto");
                $("#btn-gasto").html("Modificar Gasto");

                const id = $(this).data('id');
                // console.log("id 11 =>", id)
                $.ajax({
                    url: 'get_gasto.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_gasto').val(data.ID_Gasto);
                        $('#nom_gasto').val(data.Nom_Gasto);
                        $('#monto_gasto').val(data.Monto_Gasto);
                        $('#fech_pag_gasto').val(data.Fech_Pag_Gasto);
                       

                        if(data.FOT_EVE_NAME){
                            const htmlArchivo = `
                                <small>
                                    <br>Archivo actual:
                                    ${data.FOT_EVE_NAME}
                                </small>`;
                            $("#mostrarNombreArchivo").html(htmlArchivo);
                        }else{
                            $("#mostrarNombreArchivo").html()
                        }

                        

                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#gastoTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_gasto.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarGasto').toast('show');
                        // alert('Registro eliminado exitosamente');
                        tablaInventario.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormGasto();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el registro: ' + error);
                    }
                });

            });


            // console.log("#formModificarDiamante =>", $('#formModificarDiamante'));




        });

        function limpiarFormGasto() {
            $('#id_gasto').val("");
            $('#nom_gasto').val("");
            $('#monto_gasto').val("");
            $('#archivo').val("");

        }
    </script>

</body>

</html>