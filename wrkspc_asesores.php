<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpace de Asesores</title>

</head>

<body>


    <div class="mt-4">
    <h1 class="text-center">WorkSpace de Asesores</h1>
        <div class="row mt-5 mb-5">
            <div class="d-flex justify-content-center mb-3">
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Cliente')">Cliente</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Cliente Potencial')">Cliente Potencial</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Prospecto')">Prospecto</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('No Prospecto')">No Prospecto</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('Ex-Cliente')">Ex-Cliente</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('No Activo')">No Activo</button>
                <button class="btn btn-outline-primary mx-1" onclick="filterByEstado('')">Interesado (General)</button>
            </div>


            <!-- DataTable para listar los almacenes -->
            <div class="col-12 col-xl-12">
                    <div class="table-responsive">
                        <table id="tablaWorkSpaceAsesores" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Interesado</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Etapa</th>
                                    <th>Prospecto</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
            </div>

            <!-------------------------------------------------------------------------------- TOASTS ---------------------------------------------------------------------------------->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <!-- Toast para éxito al modificar un producto -->
                

                <!-- Toast para éxito al agregar un producto -->
                <div id="toastAgregarCurso" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body  d-flex align-items-center">
                            <!-- Ícono al costado del texto -->
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Curso agregado correctamente.
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
        $(document).ready(function () {
        const table = $("#tablaWorkSpaceAsesores").DataTable({
            pageLength: 10,
            searching: true,
            bDestroy: true,
        });

        window.filterByEstado = function (estado) {
            if (estado) {
            // Usa ^ y $ para buscar coincidencias exactas
            table
                .column(3)
                .search("^" + estado + "$", true, false)
                .draw();
            } else {
            table.search("").columns().search("").draw(); // Limpia los filtros
            }
        };
        });
    </script>

</body>

</html>