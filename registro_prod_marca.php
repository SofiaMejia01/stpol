<?php
 

// Obtiene todos los registros de la tabla t_curso, que serán usados para generar un menú desplegable en el formulario. 
$t_marca_result = $conn->query("SELECT * FROM t_marca WHERE COD_EST_OBJ = 1");

?>

    <div class="mt-4">
    <h1 class="text-center">Productos o Servicios por Marca</h1>
        <div class="row mt-5 mb-3">
        
            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formProdServMarcaTitulo>Agregar Nuevo Producto o Servicio por Marca</h5>
                    <form id="formAgregarModificarProdServMarca" class="p-3 border rounded">
                        <input type="hidden" id="id_prodServMarca" name="id_prodServMarca">
                           
                        <div class="form-group mb-3">
                            <label for="id_marca" class="form-label">Elejir una Marca:</label>
                            <select class="form-select" id="id_marca" name="id_marca" required>
                                <option value="" disabled selected>Seleccione</option>
                                <?php while ($row = $t_marca_result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['ID_Marca']; ?>"><?php echo $row['Nombre_Marca']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nombre_prod_serv_marca" class="form-label">Nombre del producto o servicio:</label>
                            <input type="text" name="nombre_prod_serv_marca"  id="nombre_prod_serv_marca" class="form-control"  required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="desc_prod_serv_marca">Descripción:</label>
                            <textarea class="form-control" id="desc_prod_serv_marca" name="desc_prod_serv_marca" required></textarea>
                        </div>

                        <button id="btn-prod-serv-marca" type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Programacion de cursos</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="servProdMarcaTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Marca</th>
                                    <th>Nombre Producto o Servicio</th>    
                                    <th>Descripcion</th>
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
                <div id="toastModificarProdServMarca" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Producto/Servicio modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarProdServMarca" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Producto/Servicio agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarProdServMarca" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Producto/Servicio eliminado correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar este Producto/Servicio?");
        }

        var tablaInventario;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInventario = $('#servProdMarcaTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_prod_serv_marcas.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Prod_Serv_Marca',
                        render: function(data, type, row) {
                            return `Prog-${data}`; // Formato con prefijo PROD-
                        }
                    },
                    {
                        data: 'Nombre_Marca'
                    },
                    {
                        data: 'Nombre_Prod_Serv_Marca'
                    },
                    {
                        data: 'Descripcion_Prod_Serv_Marca'
                    },
                 
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Prod_Serv_Marca}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Prod_Serv_Marca}">Eliminar</button>
                               

        
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarProdServMarca').on('submit', function(e) {

                // console.log("entrando a submit");

                e.preventDefault(); // Evita el envío tradicional del formulario

                // Serializa los datos del formulario
                var formData = $(this).serialize();


                // Validación manual opcional
                // if (!formData.includes('tipo_certificado') || !formData.includes('numero_certificado')) {
                //     alert('Todos los campos obligatorios deben completarse.');
                //     return;
                // }

                console.log('Datos enviados:', formData); // Verifica qué datos se están enviando

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_mod_prod_serv_marca.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_prodServMarca').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarProdServMarca').toast('show');
                                console.log("Producto/Servicio modificado");
                                
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarProdServMarca').toast('show');
                                console.log("Producto/Servicio agregado");
                                
                            }
                            limpiarFormProdServMarca();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormProdServMarca();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });



            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#servProdMarcaTable tbody').on('click', '.tablaModificar', function() {
                // console.log("hola sofia");
                // e.preventDefault();
                $("#formProdServMarcaTitulo").html("Modificar Producto o Servicio por Marca");
                $("#btn-prod-serv-marca").html("Modificar");

                const id = $(this).data('id');
                // console.log("id 11 =>", id)
                $.ajax({
                    url: 'get_prod_serv_marca.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_prodServMarca').val(data.ID_Prod_Serv_Marca);
                        $('#id_marca').val(data.ID_Marca);
                        $('#nombre_prod_serv_marca').val(data.Nombre_Prod_Serv_Marca);
                        $('#desc_prod_serv_marca').val(data.Descripcion_Prod_Serv_Marca);



                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#servProdMarcaTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_prod_serv_marca.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarProdServMarca').toast('show');
                        // alert('Registro eliminado exitosamente');
                        tablaInventario.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormProdServMarca();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el producto o servicio por marca: ' + error);
                    }
                });

            });


            // console.log("#formModificarDiamante =>", $('#formModificarDiamante'));




        });

        function limpiarFormProdServMarca() {
            $('#id_marca').val("");
           
            $('#nombre_prod_serv_marca').val("");
            $('#desc_prod_serv_marca').val("");
         
            // reiniciar título del formulario y texto de botón
            $('#formProdServMarcaTitulo').html('Agregar Nuevo Producto o Servicio por Marca');
            $('#btn-prod-serv-marca').html('Agregar');

        }
    </script>