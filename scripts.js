$(document).ready(function () {
    // Botón Hamburguesa para mostrar/ocultar el Sidebar
    $('#toggle-sidebar').on('click', function () {
        const sidebar = $('#sidebar');

        // Alternar la clase de colapso del sidebar
        sidebar.toggleClass('sidebar-collapsed');

    });


    // $(document).ready(function () {
    //     // Seleccionar el elemento <nav> con id "sidebar"
    //     $('.btn-close').on('click', function () {
    //         const sidebar = $('#sidebar');
    
    //         // Alternar la clase de colapso del sidebar
    //         sidebar.toggleClass('sidebar-collapsed');
    
    //     });
    // });




 

    // Cargar contenido dinámico al hacer clic en los enlaces
    $('.nav-link:not([data-bs-toggle="collapse"])').on('click', function (e) {
        e.preventDefault(); // Evitar recarga

        const href = $(this).attr('href'); // Obtener la URL del archivo PHP
        const title = $(this).text(); // Obtener el título del enlace

        const arrow = $(this).find('.arrow');

        // Realizar la solicitud AJAX
        $.ajax({
            url: href,
            method: 'GET',
            success: function (response) {
                $('#main-content').html($(response).find('#main-content').html()); // Insertar contenido dinámico
                history.pushState({ page: href }, title, href); // Actualizar la URL
                document.title = title; // Cambiar el título
            },
            error: function () {
                $('#main-content').html('<p>Error loading content.</p>');
            }
        });

         // Cambia la dirección de la flecha dependiendo de su estado
         const isExpanded = $(this).attr('aria-expanded') === 'true';
         if (isExpanded) {
             arrow.css('transform', 'rotate(180deg)'); // Flecha hacia abajo
         } else {
             arrow.css('transform', 'rotate(0deg)'); // Flecha hacia arriba
         }
    });

    // Manejar el retroceso y avance del navegador
    window.onpopstate = function (event) {
        if (event.state) {
            const page = event.state.page;

            $.ajax({
                url: page,
                method: 'GET',
                success: function (response) {
                    $('#main-content').html($(response).find('#main-content').html());
                },
                error: function () {
                    $('#main-content').html('<p>Error loading content.</p>');
                }
            });
        }
    };
});
