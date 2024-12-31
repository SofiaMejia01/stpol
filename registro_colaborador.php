
<?php
 
// Obtiene todos los registros de la tabla t_puesto, que serán usados para generar un menú desplegable en el formulario. 
$t_puesto_result = $conn->query("SELECT ID_Puesto, Nombre_Puesto FROM t_puesto");

?>

    <div class="mt-4">
        
        <div class="row mt-5 mb-5">

            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formGastoTitulo>Agregar Nuevo Colaborador</h5>
                    <form id="formAgregarModificarColaborador" class="p-3 border rounded" enctype="multipart/form-data">
                        <input type="hidden" id="id_colaborador" name="id_colaborador">

                        <div class="form-group mb-3">
                            <label for="nom_colaborador" class="form-label">Nombre:</label>
                            <input type="text" id="nom_colaborador" name="nom_colaborador" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="ape_colaborador" class="form-label">Apellido:</label>
                            <input type="text" id="ape_colaborador" name="ape_colaborador" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="num_doc_colaborador" class="form-label">N° Documento de Identidad:</label>
                            <input type="text" id="num_doc_colaborador" name="num_doc_colaborador" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="genero_colaborador" class="form-label">Género:</label>
                            <select class="form-select" id="genero_colaborador" name="genero_colaborador" required>
                                <option value="" disabled selected>Seleccione un Género</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="fecha_nac_colaborador" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" name="fecha_nac_colaborador" id="fecha_nac_colaborador" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edad_colaborador" class="form-label">Edad:</label>
                            <input type="number" id="edad_colaborador" name="edad_colaborador" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="direc_colaborador" class="form-label">Dirección:</label>
                            <textarea id="direc_colaborador" name="direc_colaborador" class="form-control" required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="telef_colaborador" class="form-label">Teléfono o Celular:</label>
                            <input type="text" id="telef_colaborador" name="telef_colaborador" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="correo_colaborador" class="form-label">Correo:</label>
                            <input type="email" id="correo_colaborador" name="correo_colaborador" class="form-control" required>
                        </div>

                        <div  class="form-group mb-3">
                            <label for="archivo_cv" class="form-label ">Adjuntar Curriculun Vitae:</label>
                            <input type="file" id="archivo_cv" name="archivo_cv"  class="form-control" >

                            <div id="mostrarNombreArchivoCV">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="puesto_colaborador" class="form-label">Puesto de Trabajo:</label>
                            <select class="form-select" id="puesto_colaborador" name="puesto_colaborador" required>
                                <option value="" disabled selected>Seleccione un puesto</option>
                                
                                <?php while ($row = $t_puesto_result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['ID_Puesto']; ?>"><?php echo $row['Nombre_Puesto']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="modalidad_colaborador" class="form-label">Modalidad de Trabajo:</label>
                            <select class="form-select" id="modalidad_colaborador" name="modalidad_colaborador" required>
                                <option value="" disabled selected>Seleccione una modalidad</option>
                                <option value="Planilla">Planilla</option>
                                <option value="Recibo por Honorarios">Recibo por Honorarios</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="sueldo_colaborador" class="form-label">Sueldo:</label>
                            <input type="number" step="0.01" id="sueldo_colaborador" name="sueldo_colaborador" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="fech_pag_colaborador" class="form-label">Fecha de Pago:</label>
                            <select class="form-select" id="fech_pag_colaborador" name="fech_pag_colaborador" required>
                                <option value="" disabled selected>Tipo de Pago</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Quincenal">Quincenal</option>
                            </select>
                        </div>
                        
                        <div  class="form-group mb-3">
                            <label for="archivo_contrato" class="form-label ">Adjuntar Contrato:</label>
                            <input type="file" id="archivo_contrato" name="archivo_contrato"  class="form-control" >

                            <div id="mostrarNombreArchivoContrato">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hor_trab_colaborador" class="form-label">Horario de Trabajo:</label>
                            <input type="text" id="hor_trab_colaborador" name="hor_trab_colaborador" class="form-control" required>
                        </div>   
                        
                        <div class="form-group mb-3">
                            <label for="hor_refri_colaborador" class="form-label">Horario de Refrigerio:</label>
                            <input type="text" id="hor_refri_colaborador" name="hor_refri_colaborador" class="form-control" required>
                        </div>


                        <button id="btn-colaborador" type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los colaboradores -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Colaboradores</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="colaboradorTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Colaborador</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>N° Doc.</th>
                                    <th>Género</th>
                                    <th>F.Nacimiento</th>
                                    <th>Edad</th>
                                    <th>Dirección</th>
                                    <th>Celular/Telefono</th>
                                    <th>Correo</th>
                                    <th>C.Vitae</th>
                                    <th>Puesto</th>
                                    <th>Modalidad</th>
                                    <th>Sueldo</th>
                                    <th>Fecha Pago</th>
                                    <th>Contrato</th>
                                    <th>H.Trabajo</th>
                                    <th>H.Refrigerio</th>
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
                <!-- Toast para éxito al modificar un colaborador -->
                <div id="toastModificarColaborador" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Colaborador modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un colaborador -->
                <div id="toastAgregarColaborador" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Colaborador agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un colaborador -->
                <div id="toastEliminarColaborador" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Colaborador eliminado correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar este Colaborador?");
        }

        var tablaInventario;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInventario = $('#colaboradorTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_colaboradores.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Colab'
                    },
                    {
                        data: 'Nombre_Colab'
                    },
                    {
                        data: 'Apellido_Colab'
                    },
                    {
                        data: 'Numero_Doc_Colab'
                    },
                    {
                        data: 'Genero_Colab'
                    },
                    {
                        data: 'Fecha_Nac_Colab'
                    },
                    {
                        data: 'Edad_Colab'
                    },
                    {
                        data: 'Direccion_Colab'
                    },
                    {
                        data: 'Telefono_Colab'
                    },
                    {
                        data: 'Correo_Colab'
                    },

                    {
                        data: 'FOT_CV_TMPNAME',
                        render: function(data, type, row) {
                            if(data) {
                                return `<a href='${data}' target='_blank'>Ver C.V</a>`;
                            }

                            return '<span class="badge rounded-pill text-bg-info">Archivo no<br>cargado</span>';
                            
                        }
                    },
                    {
                        data: 'Nombre_Puesto'
                    },
                    {
                        data: 'Modalidad_Colab'
                    },
                    {
                        data: 'Sueldo_Colab'
                    },
                    {
                        data: 'Fecha_Pago_Colab'
                    },
                    {
                        data: 'FOT_RG_CONTRATO_TMPNAME',
                        render: function(data, type, row) {
                            if(data) {
                                return `<a href='${data}' target='_blank'>Ver Contrato</a>`;
                            }

                            return '<span class="badge rounded-pill text-bg-info">Archivo no<br>cargado</span>';
                            
                        }
                    },

                    {
                        data: 'Horario_Trabajo_Colab'
                    },

                    {
                        data: 'Horario_Refrigerio_Colab'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Colab}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Colab}">Eliminar</button>
                               

        
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarColaborador').on('submit', function(e) {

                // console.log("entrando a submit");

                e.preventDefault(); // Evita el envío tradicional del formulario

                var form = document.querySelector('#formAgregarModificarColaborador');
                // Serializa los datos del formulario
                var formData = new FormData(form);

                // for (let pair of formData.entries()) {
                //     console.log(pair[0] + ': ' + pair[1]);
                // }

                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'agregar_mod_colaborador.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    processData: false, // IMPORTANTE: Evita que jQuery procese los datos
                    contentType: false, // IMPORTANTE: Evita que jQuery establezca un encabezado incorrecto

                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_colaborador').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarColaborador').toast('show');
                                console.log("Colaborador modificado");
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarColaborador').toast('show');
                                console.log("Colaborador agregado");
                            }
                            limpiarFormColaborador();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormColaborador();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });



            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#colaboradorTable tbody').on('click', '.tablaModificar', function() {
                // console.log("hola sofia");
                // e.preventDefault();
                $("#formColaboradorTitulo").html("Modificar Colaborador");
                $("#btn-colaborador").html("Modificar");

                const id = $(this).data('id');
                // console.log("id 11 =>", id)
                $.ajax({
                    url: 'get_colaborador.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_colaborador').val(data.ID_Colab);
                        $('#nom_colaborador').val(data.Nombre_Colab);
                        $('#ape_colaborador').val(data.Apellido_Colab);
                        $('#num_doc_colaborador').val(data.Numero_Doc_Colab);
                        $('#genero_colaborador').val(data.Genero_Colab);
                        $('#fecha_nac_colaborador').val(data.Fecha_Nac_Colab);
                        $('#edad_colaborador').val(data.Edad_Colab);
                        $('#direc_colaborador').val(data.Direccion_Colab);
                        $('#telef_colaborador').val(data.Telefono_Colab);
                        $('#correo_colaborador').val(data.Correo_Colab);
                        $('#correo_colaborador').val(data.Correo_Colab);

                        if(data.FOT_CV_NAME){
                            const htmlArchivo = `
                                <small>
                                    <br>Archivo actual:
                                    ${data.FOT_CV_NAME}
                                </small>`;
                            $("#mostrarNombreArchivoCV").html(htmlArchivo);
                        }else{
                            $("#mostrarNombreArchivoCV").html()
                        }

                        $('#puesto_colaborador').val(data.iD_Puesto);
                        $('#modalidad_colaborador').val(data.Modalidad_Colab);
                        $('#sueldo_colaborador').val(data.Sueldo_Colab);
                        $('#fech_pag_colaborador').val(data.Fecha_Pago_Colab);

                        if(data.FOT_REG_CONTRATO_NAME){
                            const htmlArchivo2 = `
                                <small>
                                    <br>Archivo actual:
                                    ${data.FOT_REG_CONTRATO_NAME}
                                </small>`;
                            $("#mostrarNombreArchivoContrato").html(htmlArchivo2);
                        }else{
                            $("#mostrarNombreArchivoContrato").html()
                        }

                        $('#hor_trab_colaborador').val(data.Horario_Trabajo_Colab);
                        $('#hor_refri_colaborador').val(data.Horario_Refrigerio_Colab);

                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#colaboradorTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_colaborador.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarColaborador').toast('show');
                        // alert('Registro eliminado exitosamente');
                        tablaInventario.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormColaborador();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar el registro: ' + error);
                    }
                });

            });


            // console.log("#formModificarDiamante =>", $('#formModificarDiamante'));




        });

        function limpiarFormColaborador() {
            $('#id_colaborador').val("");
            $('#nom_colaborador').val("");
            $('#ape_colaborador').val("");
            $('#num_doc_colaborador').val("");
            $('#genero_colaborador').val("");
            $('#fecha_nac_colaborador').val("");
            $('#edad_colaborador').val("");
            $('#direc_colaborador').val("");
            $('#telef_colaborador').val("");
            $('#correo_colaborador').val("");
            $('#correo_colaborador').val("");
            $('#mostrarNombreArchivoCV').html();
            $('#puesto_colaborador').val("");
            $('#modalidad_colaborador').val("");
            $('#sueldo_colaborador').val("");
            $('#fech_pag_colaborador').val("");
            $('#mostrarNombreArchivoContrato').html();
            $('#hor_trab_colaborador').val("");
            $('#hor_refri_colaborador').val("");

            // reiniciar título del formulario y texto de botón
            $('#formColaboradorTitulo').html('Agregar Nuevo Colaborador');
            $('#btn-colaborador').html('Agregar');
        }
    </script>

