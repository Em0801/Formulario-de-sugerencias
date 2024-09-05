<?php

if (!defined('ABSPATH')) {
    exit;
}

class MiPlugin_Shortcode_Handler {
    public function __construct() {
        add_shortcode('mi_plugin_tabla', array($this, 'mi_plugin_tabla_shortcode'));
        add_shortcode('mostrar_sugerencias', array($this, 'mi_plugin_mostrar_sugerencias'));
        add_shortcode('detalle_sugerencia', array($this, 'mi_plugin_detalle_sugerencia_shortcode'));
    }

    public function mi_plugin_tabla_shortcode() {
        if (!current_user_can('manage_options')) {
            return 'No tienes permiso para ver esta tabla.';
        }

        $args = array(
            'post_type' => 'mi_plugin_formulario',
            'posts_per_page' => -1,
        );

        $query = new WP_Query($args);
        ob_start();
        ?>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Sugerencias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($query->have_posts()) : ?>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <tr>
                            <td><?php echo get_field('nombre'); ?></td>
                            <td><?php echo get_field('email'); ?></td>
                            <td><?php the_content(); ?></td>
                            <td><a href="<?php echo esc_url(home_url('/' . get_the_ID() . '/editar-ficha/')); ?>">Editar</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No hay registros.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }

    public function mi_plugin_mostrar_sugerencias() {
        if (!current_user_can('administrator')) {
            return '<p>Lo siento, esta página es solo para administradores.</p>';
        }

        ob_start();

        $args = array(
            'post_type'      => 'mi_plugin_formulario',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
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
                $edit_url = home_url('/detalle-de-sugerencia/?sugerencia_id=' . get_the_ID() . '/');

                echo '<tr>';
                echo '<td>' . esc_html($nombre) . '</td>';
                echo '<td>' . esc_html($email) . '</td>';
                echo '<td>' . esc_html($sugerencias) . '</td>';
                echo '<td><a target="_blank" href="' . esc_url($edit_url) . '">Ver</a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

            wp_reset_postdata();
        } else {
            echo 'No se encontraron sugerencias.';
        }

        return ob_get_clean();
    }

    public function mi_plugin_detalle_sugerencia_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, 'detalle_sugerencia');

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

        $nombre = get_post_meta($post->ID, 'nombre', true);
        $email = get_post_meta($post->ID, 'email', true);
        $sugerencias = $post->post_content;
        $pais = get_post_meta($post->ID, 'pais', true);

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
}
