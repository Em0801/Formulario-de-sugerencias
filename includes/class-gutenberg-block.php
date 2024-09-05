<?php
if (!defined('ABSPATH')) {
    exit;
}

class MiPlugin_Gutenberg_Block {

    public function __construct() {
        add_action('init', array($this, 'register_block'));
    }

    public function register_block() {
        wp_register_script(
            'mi-plugin-block',
            MI_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components')
        );

        wp_register_style(
            'mi-plugin-block-editor',
            MI_PLUGIN_URL . 'assets/css/block-editor.css',
            array('wp-edit-blocks')
        );

        register_block_type('mi-plugin/formulario', array(
            'editor_script' => 'mi-plugin-block',
            'editor_style' => 'mi-plugin-block-editor',
            'render_callback' => array($this, 'render_form_block'),
        ));
    }

    public function render_form_block($attributes) {
        ob_start();
        ?>
        <form id="mi-plugin-formulario">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="pais">País</label>
            <select id="pais" name="pais" required></select>

            <label for="sugerencias">Sugerencias</label>
            <textarea id="sugerencias" name="sugerencias" required></textarea>

            <input type="hidden" name="action" value="mi_plugin_enviar_formulario">
            <?php wp_nonce_field('mi_plugin_nonce', 'security'); ?>
            <button type="submit">Enviar</button>
        </form>
        <div id="form-response"></div>
        <?php
        return ob_get_clean();
    }
}
