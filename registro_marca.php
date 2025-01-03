<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Marca</title>

</head>

<body>


    <div class="mt-4">
        <h1 class="text-center">Registro de Marcas</h1>
        <div class="row mt-5 mb-5">

            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formMarcaTitulo>Agregar Nueva Marca</h5>
                    <form id="formAgregarModificarMarca" class="p-3 border rounded" enctype="multipart/form-data">
                        <input type="hidden" id="id_marca" name="id_marca">

                        <div class="form-group mb-3">
                            <label for="nom_marca" class="form-label">Nombre de la marca:</label>
                            <input type="text" id="nom_marca" name="nom_marca" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="desc_marca" class="form-label">Descripción:</label>
                            <input type="text" id="desc_marca" name="desc_marca" class="form-control" required>
                        </div>
                        

                        <div  class="form-group mb-3">
                            <label for="archivo" class="form-label ">Adjuntar archivo manual de marca:</label>
                            <input type="file" id="archivo" name="archivo"  class="form-control" >

                            <div id="mostrarNombreArchivo">
                            </div>
                        </div>

                        <button id="btn-marca" type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Marcas</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="marcaTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Marca</th>
                                    <th>Nombre Marca</th>
                                    <th>Descripción</th>
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
                <div id="toastModificarMarca" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Marca modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarMarca" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Marca agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarMarca" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Marca eliminado correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar esta Marca?");
        }

        var tablaInventario;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInventario = $('#marcaTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_marcas.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Marca'
                    },
                    {
                        data: 'Nombre_Marca'
                    },
                    {
                        data: 'Descripcion_Marca'
                    },
                  
                    {
                        data: 'FOT_MARCA_TMPNAME',
                        render: function(data, type, row) {
                            if(data) {
                                return `<a href='${data}' target='_blank'>Ver Archivo</a>`;
                            }

                            return '<span class="badge rounded-pill text-bg-info">Archivo no<br>cargado</span>';
                            
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Marca}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Marca}">Eliminar</button>
                               

        
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarMarca').on('submit', function(e) {

                // console.log("entrando a submit");

                e.preventDefault(); // Evita el envío tradicional del formulario

                var form = document.querySelector('#formAgregarModificarMarca');
                // Serializa los datos del formulario
                var formData = new FormData(form);

                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_mod_marca.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    processData: false, // IMPORTANTE: Evita que jQuery procese los datos
                    contentType: false, // IMPORTANTE: Evita que jQuery establezca un encabezado incorrecto

                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_marca').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarMarca').toast('show');
                                console.log("Marca modificado");
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarMarca').toast('show');
                                console.log("Marca agregado");
                            }
                            limpiarFormMarca();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormMarca();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });



            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#marcaTable tbody').on('click', '.tablaModificar', function() {
                // console.log("hola sofia");
                // e.preventDefault();
                $("#formMarcaTitulo").html("Modificar Marca");
                $("#btn-marca").html("Modificar");

                const id = $(this).data('id');
                // console.log("id 11 =>", id)
                $.ajax({
                    url: 'get_marca.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_marca').val(data.ID_Marca);
                        $('#nom_marca').val(data.Nombre_Marca);
                        $('#desc_marca').val(data.Descripcion_Marca);
                     
                    
                        if(data.FOT_MARCA_NAME){
                            const htmlArchivo = `
                                <small>
                                    <br>Archivo actual:
                                    ${data.FOT_MARCA_NAME}
                                </small>`;
                            $("#mostrarNombreArchivo").html(htmlArchivo);
                        }else{
                            $("#mostrarNombreArchivo").html("")
                        }

                        

                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#marcaTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_marca.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarMarca').toast('show');
                        // alert('Registro eliminado exitosamente');
                        tablaInventario.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormMarca();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el registro: ' + error);
                    }
                });

            });


            // console.log("#formModificarDiamante =>", $('#formModificarDiamante'));




        });

        function limpiarFormMarca() {
            $('#id_marca').val("");
            $('#nom_marca').val("");
            $('#desc_marca').val("");
            $('#archivo').val("");
            $('#mostrarNombreArchivo').html("");

            // reiniciar título del formulario y texto de botón
            $('#formMarcaTitulo').html('Agregar Nueva Marca');
            $('#btn-marca').html('Agregar');
        }
    </script>

</body>

</html>