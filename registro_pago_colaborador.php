<?php
 

// Obtiene todos los registros de la tabla t_curso, que serán usados para generar un menú desplegable en el formulario. 
$t_pago_result = $conn->query("SELECT ID_Colab, Nombre_Colab FROM t_colaborador");

?>

    <div class="mt-4">
        <h1 class="text-center">Registro de Pagos</h1>
        <div class="row mt-5 mb-5">

            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formPagoTitulo>Agregar Nuevo Pago</h5>
                    <form id="formAgregarModificarPago" class="p-3 border rounded" enctype="multipart/form-data">
                        <input type="hidden" id="id_pago" name="id_pago">

                        <div class="form-group mb-3">
                            <label for="id_cola_pago" class="form-label">Elejir un Colaborador:</label>
                            <select class="form-select" id="id_cola_pago" name="id_cola_pago" required>
                                <option value="" disabled selected>Seleccione</option>
                                <?php while ($row = $t_pago_result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['ID_Colab']; ?>"><?php echo $row['Nombre_Colab']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="monto_pago" class="form-label">Monto a Pagar:</label>
                            <input type="number" step="0.01" id="monto_pago" name="monto_pago" class="form-control" required>
                        </div>
                       

                        <div  class="form-group mb-3">
                            <label for="archivo_pago" class="form-label ">Adjuntar Voucher de Pago:</label>
                            <input type="file" id="archivo_pago" name="archivo_pago"  class="form-control" >

                            <div id="mostrarNombreArchivoPago">
                            </div>
                        </div>




                        <button id="btn-pago" type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Pagos</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="pagoTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Pago</th>
                                    <th>Nombre Colaborador</th>
                                    <th>Monto Pago</th>
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
                <div id="toastModificarPago" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Pago modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarPago" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Pago agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarPago" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Pago eliminado correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar este Pago?");
        }

        var tablaInventario;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInventario = $('#pagoTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_pagos.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Pago'
                    },
                    {
                        data: 'Nombre_Colab'
                    },
                    {
                        data: 'Monto_Pago'
                    },
                   
                    {
                        data: 'FOT_PAGO_TMPNAME',
                        render: function(data, type, row) {
                            if(data) {
                                return `<a href='${data}' target='_blank'>Ver Voucher de Pago</a>`;
                            }

                            return '<span class="badge rounded-pill text-bg-info">Archivo no<br>cargado</span>';
                            
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Pago}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Pago}">Eliminar</button>
                               

        
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarPago').on('submit', function(e) {

                // console.log("entrando a submit");

                e.preventDefault(); // Evita el envío tradicional del formulario

                var form = document.querySelector('#formAgregarModificarPago');
                // Serializa los datos del formulario
                var formData = new FormData(form);

                // for (let pair of formData.entries()) {
                //     console.log(pair[0] + ': ' + pair[1]);
                // }

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_mod_pago.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    processData: false, // IMPORTANTE: Evita que jQuery procese los datos
                    contentType: false, // IMPORTANTE: Evita que jQuery establezca un encabezado incorrecto

                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_pago').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarPago').toast('show');
                                console.log("Pago modificado");
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarPago').toast('show');
                                console.log("Pago agregado");
                            }
                            limpiarFormPago();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormPago();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });



            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#pagoTable tbody').on('click', '.tablaModificar', function() {
                // console.log("hola sofia");
                // e.preventDefault();
                $("#formPagoTitulo").html("Modificar Pago");
                $("#btn-pago").html("Modificar Pago");

                const id = $(this).data('id');
                // console.log("id 11 =>", id)
                $.ajax({
                    url: 'get_pago.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_pago').val(data.ID_Pago);
                        $('#id_cola_pago').val(data.ID_Colab);
                        $('#monto_pago').val(data.Monto_Pago);
                       
                       

                        if(data.FOT_PAGO_NAME){
                            const htmlArchivo = `
                                <small>
                                    <br>Archivo actual:
                                    ${data.FOT_PAGO_NAME}
                                </small>`;
                            $("#mostrarNombreArchivoPago").html(htmlArchivo);
                        }else{
                            $("#mostrarNombreArchivoPago").html()
                        }

                        

                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#pagoTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_pago.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarPago').toast('show');
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

        function limpiarFormPago() {
            $('#id_cola_pago').val("");
            $('#monto_pago').val("");
            $('#archivo_pago').val("");
            $('#mostrarNombreArchivoPago').html("");

            // reiniciar título del formulario y texto de botón
            $('#formPagoTitulo').html('Agregar Nuevo Pago');
            $('#btn-pago').html('Agregar');
        }
    </script>

