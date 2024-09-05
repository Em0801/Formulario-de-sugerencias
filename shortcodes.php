<?php
// Función para mostrar los registros del CPT "mi_plugin_formulario"
function mi_plugin_mostrar_sugerencias() {
    // Verificar si el usuario es administrador
    if (!current_user_can('administrator')) {
        return '<p><strong>Zona no autorizada</strong>. Lo siento, esta página es solo para administradores.</p>';
    }

    // Iniciar salida en buffer
    ob_start();

    // Crear consulta para recuperar los posts del CPT
    $args = array(
        'post_type'      => 'mi_plugin_formulario', // Nombre del CPT
        'posts_per_page' => -1, // Recuperar todos los posts
    );
    $query = new WP_Query($args);

    // Si hay registros
    if ($query->have_posts()) {
        // Crear la tabla para mostrar los registros
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Nombre</th>';
        echo '<th>Email</th>';
        echo '<th>Sugerencias</th>';
        echo '<th>Acción</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($query->have_posts()) {
            $query->the_post();

            $nombre = get_post_meta(get_the_ID(), 'nombre', true);
            $email = get_post_meta(get_the_ID(), 'email', true);
            $sugerencias = get_the_content();
            $edit_url = home_url('/detalle-de-sugerencia/?sugerencia_id=' . get_the_ID());

            echo '<tr>';
            echo '<td>' . esc_html($nombre) . '</td>';
            echo '<td>' . esc_html($email) . '</td>';
            echo '<td>' . esc_html($sugerencias) . '</td>';
            echo '<td><a target="_blank" href="' . esc_url($edit_url) . '">Ver</a></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

        // Restablecer datos de la consulta
        wp_reset_postdata();
    } else {
        echo 'No se encontraron sugerencias.';
    }

    // Devolver la salida en buffer
    return ob_get_clean();
}

// Registrar el shortcode
add_shortcode('mostrar_sugerencias', 'mi_plugin_mostrar_sugerencias');



// shortcode para mostrar el detalle de las sugerencias
function mi_plugin_detalle_sugerencia_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'id' => 0, // ID por defecto
    ), $atts, 'detalle_sugerencia');

    // Si no se especifica un ID en el shortcode, obtenerlo de la URL
    if (!$atts['id']) {
        if (isset($_GET['sugerencia_id'])) {
            $post_id = intval($_GET['sugerencia_id']);
        } else {
            return 'ID de sugerencia no proporcionado.';
        }
    } else {
        $post_id = intval($atts['id']);
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'mi_plugin_formulario') {
        return 'Sugerencia no encontrada.';
    }

    // Obtener campos personalizados
    $nombre = get_post_meta($post->ID, 'nombre', true);
    $email = get_post_meta($post->ID, 'email', true);
    $sugerencias = $post->post_content;
    $pais = get_post_meta($post->ID, 'pais', true);

    // Crear la salida HTML
    ob_start();
?>
    <div class="sugerencia-detalle">
        <h2><?php echo esc_html($nombre); ?></h2>
        <p><strong>Email:</strong> <?php echo esc_html($email); ?></p>
        <p><strong>País:</strong> <?php echo esc_html($pais); ?></p>
        <p><strong>Sugerencias:</strong></p>
        <p><?php echo esc_html($sugerencias); ?></p>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('detalle_sugerencia', 'mi_plugin_detalle_sugerencia_shortcode');

// Función para mostrar el formulario
function mi_plugin_formulario_shortcode() {
    // Obtener información del usuario logueado
    $current_user = wp_get_current_user();

    // Verificar si el usuario está logueado
    if (is_user_logged_in()) {
        $nombre = esc_attr($current_user->user_firstname);
        $apellido = esc_attr($current_user->user_lastname);
        $email = esc_attr($current_user->user_email);
    } else {
        $nombre = '';
        $apellido = '';
        $email = '';
    }

    ob_start();
    ?>
    <form id="mi-plugin-formulario" method="post" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        
        <label for="pais">País:</label>
        <select id="pais" name="pais" required></select>
        
        <label for="sugerencias">Sugerencias:</label>
        <textarea id="sugerencias" name="sugerencias" required></textarea>
        
        <input type="hidden" name="action" value="mi_plugin_enviar_formulario">
        <?php wp_nonce_field('mi_plugin_nonce', 'security'); ?>
        <button type="submit">Enviar</button>
    </form>
    <div id="form-response"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('mi_plugin_formulario', 'mi_plugin_formulario_shortcode');


