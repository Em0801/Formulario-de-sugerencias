<?php
class MiPlugin_Form_Handler {
    public function __construct() {
        add_action('wp_ajax_mi_plugin_save_form', array($this, 'mi_plugin_save_form_handler'));
        add_action('wp_ajax_nopriv_mi_plugin_save_form', array($this, 'mi_plugin_save_form_handler'));
    }

    public function mi_plugin_save_form_handler() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mi_plugin_form_nonce')) {
            wp_send_json_error(array('message' => 'Nonce inválido.'));
        }

        $nombre = sanitize_text_field($_POST['nombre']);
        $apellido = sanitize_text_field($_POST['apellido']);
        $email = sanitize_email($_POST['email']);
        $sugerencias = sanitize_textarea_field($_POST['sugerencias']);
        $pais = sanitize_text_field($_POST['pais']);

        $post_id = wp_insert_post(array(
            'post_title' => $nombre . ' ' . $apellido,
            'post_content' => $sugerencias,
            'post_status' => 'publish',
            'post_type' => 'mi_plugin_formulario',
        ));

        if ($post_id) {
            update_field('nombre', $nombre, $post_id);
            update_field('apellido', $apellido, $post_id);
            update_field('sugerencia', $sugerencias, $post_id);
            update_field('email', $email, $post_id);
            update_field('pais', $pais, $post_id);

            wp_send_json_success(array('message' => 'Gracias por su sugerencia.'));
        } else {
            wp_send_json_error(array('message' => 'No se pudo guardar la sugerencia.'));
        }

        wp_die();
    }
}

?>
