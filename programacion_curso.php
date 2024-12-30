<?php
 

// Obtiene todos los registros de la tabla t_curso, que serán usados para generar un menú desplegable en el formulario. 
$t_curso_result = $conn->query("SELECT * FROM t_curso WHERE Cod_Est_Curso = 1");

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programacion de Cursos</title>

</head>

<body>


    <div class="mt-4">
    <h1 class="text-center">Programacion de Cursos</h1>
        <div class="row mt-5 mb-5">
        
            <!-- Formulario para agregar una nuevo almacen-->
            <div class="col-12 col-xl-4">
                <div class="container mt-3">
                    <h5 id=formProgCursoTitulo>Agregar Nueva Programacion de Curso</h5>
                    <form id="formAgregarModificarProgCurso" class="p-3 border rounded">
                        <input type="hidden" id="id_progCurso" name="id_progCurso">
                           
                        <div class="form-group mb-3">
                            <label for="id_curso" class="form-label">Elejir un Curso:</label>
                            <select class="form-select" id="id_curso" name="id_curso" required>
                                <option value="" disabled selected>Seleccione un Curso</option>
                                <?php while ($row = $t_curso_result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['ID_Curso']; ?>"><?php echo $row['Nom_Curso']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="fecha_inicio_curso" class="form-label">Fecha inicio del curso:</label>
                            <input type="date" name="fecha_inicio_curso" id="fecha_inicio_curso" class="form-control" required>
                        </div>
                         
                        <div class="form-group mb-3">
                            <label for="fecha_fin_curso" class="form-label">Fecha fin del curso:</label>
                            <input type="date" name="fecha_fin_curso" id="fecha_fin_curso" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="promo_curso" class="form-label">Promocion:</label>
                            <input type="text" name="promo_curso"  id="promo_curso" class="form-control"  required>
                        </div>

                        <button id="btn-prog-curso" type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-8">
                <div class="table-section bg-white p-3">
                    <h5>Lista de Programacion de cursos</h5>
                    <br>
                    <div class="table-responsive">
                        <table id="cursoProgTable" class="display table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID Programacion</th>
                                    <th>Nombre Curso</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Promocion</th>
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
                <div id="toastModificarProgCurso" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-pencil-fill me-2"></i>
                            Programacion modificado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarProgCurso" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Programacion agregado correctamente.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>


                <!-- Toast para eliminar un producto -->
                <div id="toastEliminarProgCurso" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Programacion eliminado correctamente.
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
            return confirm("¿Estás seguro de que deseas eliminar esta Programacion?");
        }

        var tablaInventario;



        $(document).ready(function() {

            // new DataTable("#prodTable");

            // Inicializar DataTables
            tablaInventario = $('#cursoProgTable').DataTable({
                // scrollX: true, // Permitir scroll horizontal


                ajax: {
                    url: 'get_prog_cursos.php', // El archivo que obtiene los registros
                    dataSrc: '' // Los datos provienen directamente del JSON (sin necesidad de envolver en otra propiedad)
                },
                columns: [{
                        data: 'ID_Prog_Curso',
                        render: function(data, type, row) {
                            return `Prog-${data}`; // Formato con prefijo PROD-
                        }
                    },
                    {
                        data: 'Nom_Curso'
                    },
                    {
                        data: 'Fecha_Inicio_Curso'
                    },
                    {
                        data: 'Fecha_Fin_Curso'
                    },
                    {
                        data: 'Promo_Curso'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
    
                                <button class="btn btn-secondary tablaModificar" data-id="${row.ID_Prog_Curso}">Modificar</button>
                                <button class="btn btn-danger tablaEliminar" data-id="${row.ID_Prog_Curso}">Eliminar</button>
                               

        
                             `;
                        }
                    }
                ]
            });

            //aca se agrega o modifica un almacen
            $('#formAgregarModificarProgCurso').on('submit', function(e) {

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
                    url: 'agregar_mod_prog_curso.php', // Dirección del servidor
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Esperamos una respuesta JSON
                    success: function(response) {
                        tablaInventario.ajax.reload();
                        if (response.status === 'success') {
                            // alert(response.message);
                            if ($('#id_progCurso').val()) {
                                // Si el ID del diamante está vacío, es una acción de agregar
                                $('#toastModificarProgCurso').toast('show');
                                console.log("Programacion de curso modificado");
                                
                            } else {
                                // Si el ID del diamante no está vacío, es una acción de modificar
                                $('#toastAgregarProgCurso').toast('show');
                                console.log("Programacion de curso agregado");
                                
                            }
                            limpiarFormProgCurso();

                        } else {
                            // alert('Error: ' + response.message);
                            $('#toastErrorModify').toast('show');
                            console.log("producto error  error")
                        }
                        limpiarFormProgCurso();
                    },
                    error: function(xhr, status, error) {
                        // alert('Ocurrió un error al procesar la solicitud (modificar_diamante.php).');
                        $('#toastErrorModify').toast('show');
                        console.error('Detalles del error:', error);
                    }
                });
            });



            //------------------- BOTON O ENLACE MODIFICAR, AL HACERLE CLICK-------------------------------------------
            $('#cursoProgTable tbody').on('click', '.tablaModificar', function() {
                // console.log("hola sofia");
                // e.preventDefault();
                $("#formProgCursoTitulo").html("Modificar Programacion de Curso");
                $("#btn-prog-curso").html("Modificar");

                const id = $(this).data('id');
                // console.log("id 11 =>", id)
                $.ajax({
                    url: 'get_prog_curso.php',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'json',
                    success: function(data) {

                        console.log("data22 =>", data)
                        $('#id_progCurso').val(data.ID_Prog_Curso);
                        $('#id_curso').val(data.ID_Curso);
                        $('#fecha_inicio_curso').val(data.Fecha_Inicio_Curso);
                        $('#fecha_fin_curso').val(data.Fecha_Inicio_Curso);
                        $('#promo_curso').val(data.Promo_Curso);



                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error);
                        console.error('Respuesta completa:', xhr.responseText);
                    }
                });
            })


            //------------------- BOTON O ENLACE ELIMINAR, AL HACERLE CLICK-------------------------------------------

            $('#cursoProgTable tbody').on('click', '.tablaEliminar', function() {
                const id = $(this).data('id');

                // Aquí puedes realizar una solicitud AJAX para eliminar el registro
                $.ajax({
                    url: 'delete_prog_curso.php', // Ruta del archivo que manejará la eliminación
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        $('#toastEliminarProgCurso').toast('show');
                        // alert('Registro eliminado exitosamente');
                        tablaInventario.ajax.reload(); // Recargar la tabla para reflejar los cambios
                        limpiarFormProgCurso();
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar la programacion: ' + error);
                    }
                });

            });


            // console.log("#formModificarDiamante =>", $('#formModificarDiamante'));




        });

        function limpiarFormProgCurso() {
            $('#id_curso').val("");
           
            $('#fecha_inicio_curso').val("");
            $('#fecha_fin_curso').val("");
            $('#promo_curso').val("");
            // reiniciar título del formulario y texto de botón
            $('#formProgCursoTitulo').html('Agregar Nueva Programacion de Curso');
            $('#btn-prog-curso').html('Agregar');

        }
    </script>

</body>

</html>