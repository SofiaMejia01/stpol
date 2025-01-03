<?php


// Obtiene todos los registros de la tabla t_curso, que serán usados para generar un menú desplegable en el formulario. 
// $t_marca_result = $conn->query("SELECT * FROM t_marca m WHERE m.COD_EST_OBJ = 1 
//     AND m.ID_Marca NOT IN (
//         SELECT mc.ID_Marca
//         FROM t_marca_colaborador mc);");

$t_marca_result = $conn->query("SELECT * FROM t_marca WHERE COD_EST_OBJ = 1");

$t_colaborador_result = $conn->query("SELECT * FROM t_colaborador c WHERE c.COD_EST_OBJ = 1");

?>

<div class="mt-4">
    <h1 class="text-center">Registro de Asesores por Marca</h1>
    <div class="row mt-5 mb-3">

        <!-- Formulario para agregar una nuevo almacen-->
        <div class="col-12 col-xl-4">
            <div class="container mt-3">
                <h5 id=formAsesorMarcaTitulo>Registrar Asesor(es) por marca:</h5>
                <form id="formAgregarModificarAsesorMarca" class="p-3 border rounded">
                    <input type="hidden" id="id_asesorMarca" name="id_asesorMarca">

                    <div class="form-group mb-3">
                        <label for="id_marca" class="form-label">Elegir Marca:</label>
                        <select class="form-select" id="id_marca" name="id_marca" required>
                            <option value="" disabled selected>Seleccione una Marca</option>
                            <?php while ($row = $t_marca_result->fetch_assoc()): ?>
                                <option value="<?php echo $row['ID_Marca']; ?>"><?php echo $row['Nombre_Marca']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>


                    <div class="form-group mb-3">
                        <label for="id_asesores" class="form-label">Elegir Asesor(es):</label>
                        <select class="form-select" id="id_asesores" name="id_asesores[]" multiple="multiple" required
                                 data-placeholder="Seleccione uno o más asesores"
                        >
                            <?php while ($row = $t_colaborador_result->fetch_assoc()): ?>
                                <option value="<?php echo $row['ID_Colab']; ?>"><?php echo $row['Nombre_Colab']. ' ' . $row['Apellido_Colab']; ?></option>
                    
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button id="btn-asesor-marca" type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>

        <!-- DataTable para listar los almacenes -->
        <div class="col-12 col-xl-8">
            <div class="table-section bg-white p-3">
                <h5>Lista de asesores por Marca</h5>
                <br>
                <div class="table-responsive">
                    <table id="asesormarcaTable" class="display table table-striped w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Marca</th>
                                <th>Nombre de Asesor</th>
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
            <div id="toastModificarAsesorMarca" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <!-- Ícono al costado del texto -->
                        <i class="bi bi-pencil-fill me-2"></i>
                        Registro modificado correctamente.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>

            <!-- Toast para éxito al agregar un producto -->
            <div id="toastAgregarAsesorMarca" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body  d-flex align-items-center">
                        <!-- Ícono al costado del texto -->
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Registro agregado correctamente.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>


            <!-- Toast para eliminar un producto -->
            <div id="toastEliminarAsesorMarca" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
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
        return confirm("¿Estás seguro de que deseas eliminar este registro?");
    }

    var tablaInventario;

    const $select = $('#id_asesores');

    $(document).ready(function() {

        $('#id_asesores').select2({
            placeholder: "Seleccione uno o más asesores",
            //width: '100%'
        });

        // Inicializar DataTables
        tablaInventario = $('#asesormarcaTable').DataTable({
            // scrollX: true, // Permitir scroll horizontal


            ajax: {
                url: 'get_asesores_marcas.php', // El archivo que obtiene los registros
                dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
            },
            columns: [{
                    data: 'ID_Marca_Colaborador'
            
                },
                {
                    data: 'Nombre_Marca'
                },
                {
                    data: 'Asesor'
                },
             

                {
                    data: null,
                    render: function(data, type, row) {
                        return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Marca_Colaborador}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Marca_Colaborador}">Eliminar</button>
                               

        
                             `;
                    }
                }
            ]
        });

        //aca se agrega o modifica un almacen
        $('#formAgregarModificarAsesorMarca').on('submit', function(e) {

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
                url: 'agregar_mod_marca_asesor.php', // Dirección del servidor
                method: 'POST',
                data: formData,
                dataType: 'json', // Esperamos una respuesta JSON
                success: function(response) {
                    tablaInventario.ajax.reload();
                    if (response.status === 'success') {
                        // alert(response.message);
                        if ($('#id_asesorMarca').val()) {
                            // Si el ID del diamante está vacío, es una acción de agregar
                            $('#toastModificarAsesorMarca').toast('show');
                            console.log("Registro modificado");

                        } else {
                            // Si el ID del diamante no está vacío, es una acción de modificar
                            $('#toastAgregarAsesorMarca').toast('show');
                            console.log("Registro agregado");

                        }
                        limpiarFormAsesorMarca();

                    } else {
                        // alert('Error: ' + response.message);
                        $('#toastErrorModify').toast('show');
                        console.log("producto error  error")
                    }
                    limpiarFormAsesorMarca();
                },
                error: function(xhr, status, error) {
                    // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                    $('#toastErrorModify').toast('show');
                    console.error('Detalles del error:', error);
                }
            });
        });



        //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
        $('#asesormarcaTable tbody').on('click', '.tablaModificar', function() {
            // console.log("hola sofia");
            // e.preventDefault();
            $("#formAsesorMarcaTitulo").html("Modificar Asesores por Marca");
            $("#btn-asesor-marca").html("Modificar");

            const id = $(this).data('id');
            // console.log("id 11 =>", id)
            $.ajax({
                url: 'get_asesor_marca.php',
                method: 'POST',
                data: {
                    id
                },
                dataType: 'json',
                success: function(data) {

                    console.log("data22 =>", data)
                    $('#id_asesorMarca').val(data.ID_Marca_Colaborador);
                    $('#id_marca').val(data.ID_Marca);
                    $('#id_asesores').val(data.ID_Colab);

                    cambiarTipoSelect();

                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', status, error);
                    console.error('Respuesta completa:', xhr.responseText);
                }
            });
        })

        function cambiarTipoSelect() {

            // Verificar si ya es múltiple
            if ($select.attr('multiple')) {
                $select.removeAttr('multiple'); // Remover el atributo 'multiple'
            } 

            // Volver a inicializar Select2 para que reconozca el cambio
            $select.select2();
        }


        //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

        $('#asesormarcaTable tbody').on('click', '.tablaEliminar', function() {
            const id = $(this).data('id');

            // Aquí puedes realizar una solicitud AJAX para eliminar el registro
            $.ajax({
                url: 'delete_asesor_marca.php', // Ruta del archivo que manejará la eliminación
                type: 'POST',
                data: {
                    id
                },
                success: function(response) {
                    $('#toastEliminarAsesorMarca').toast('show');
                    // alert('Registro eliminado exitosamente');
                    tablaInventario.ajax.reload(); // Recargar la tabla para reflejar los cambios
                    limpiarFormAsesorMarca();
                },
                error: function(xhr, status, error) {
                    alert('Error al eliminar la programacion: ' + error);
                }
            });

        });


        // console.log("#formModificarDiamante =>", $('#formModificarDiamante'));




    });

    function limpiarFormAsesorMarca() {
        $('#id_marca').val("");
        $('#id_asesores').val(null).trigger('change');

        // reiniciar título del formulario y texto de botón
        $('#formAsesorMarcaTitulo').html('Registrar Asesor(es) por marca:');
        $('#btn-asesor-marca').html('Agregar');

        // limpiar multiselect
        if (!$select.attr('multiple')){
            $select.attr('multiple', 'multiple'); // Agregar el atributo 'multiple'
        }

        // Volver a inicializar Select2 para que reconozca el cambio
        $select.select2();

    }
</script>