jQuery(document).ready(function($) {
    // Llenar el selector de países
    $.ajax({
        url: 'https://restcountries.com/v3.1/all',
        method: 'GET',
        success: function(data) {
            var countrySelect = $('#pais');
            $.each(data, function(index, country) {
                countrySelect.append('<option value="' + country.name.common + '">' + country.name.common + '</option>');
            });
        }
    });

    // Manejo del envío del formulario por AJAX
    $('#mi-plugin-formulario').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize() + '&action=mi_plugin_create_form';

        $.ajax({
            url: mi_plugin_ajax.url, // URL de admin-ajax.php pasada desde PHP
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Mostrar mensaje de éxito
                    $('#form-response').html('<p>Gracias por su sugerencia</p>')
                                       .removeClass('error')
                                       .addClass('success')
                                       .fadeIn(); // Asegurarse de que se muestre el mensaje

                    // Vaciar los campos del formulario
                    $('#mi-plugin-formulario')[0].reset();
                    
                    // Ocultar el mensaje después de 5 segundos
                    setTimeout(function() {
                        $('#form-response').fadeOut();
                    }, 5000);
                } else {
                    // Mostrar mensaje de error en el envío
                    $('#form-response').html('<p>Error al enviar la sugerencia. Por favor, inténtalo de nuevo.</p>')
                                       .removeClass('success')
                                       .addClass('error')
                                       .fadeIn(); // Asegurarse de que se muestre el mensaje

                    // Ocultar el mensaje después de 5 segundos
                    setTimeout(function() {
                        $('#form-response').fadeOut();
                    }, 5000);
                }
            },
            error: function(xhr, status, error) {
                // Mostrar mensaje de error en la conexión
                $('#form-response').html('<p>Ocurrió un error. Por favor, intenta nuevamente.</p>')
                                   .removeClass('success')
                                   .addClass('error')
                                   .fadeIn(); // Asegurarse de que se muestre el mensaje

                // Ocultar el mensaje después de 5 segundos
                setTimeout(function() {
                    $('#form-response').fadeOut();
                }, 5000);

                console.log('Error: ' + error); // Mostrar error en consola
            }
        });
    });
});
