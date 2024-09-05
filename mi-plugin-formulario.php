<?php

/**
 * Plugin Name: Mi Plugin de Formulario
 * Description: Plugin que crea un bloque de Gutenberg con un formulario y un shortcode para visualizar los datos.
 * Version: 1.0
 * Author: Tu Nombre
 * Text Domain: mi-plugin-formulario
 * Domain Path: /languages
 */

// Asegurarse de que WordPress esté cargado
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('MI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MI_PLUGIN_URL', plugin_dir_url(__FILE__));

// Incluir archivos necesarios
include_once MI_PLUGIN_DIR . 'shortcodes.php';
require_once MI_PLUGIN_DIR . 'includes/class-form-handler.php';
require_once MI_PLUGIN_DIR . 'includes/class-shortcode-handler.php';
require_once MI_PLUGIN_DIR . 'includes/class-gutenberg-block.php';

// Iniciar clases del plugin
new MiPlugin_Form_Handler();
new MiPlugin_Shortcode_Handler();
new MiPlugin_Gutenberg_Block();

// Encolar scripts y estilos
function mi_plugin_enqueue_assets()
{
    wp_enqueue_style('mi-plugin-styles', MI_PLUGIN_URL . 'assets/css/styles.css');
    wp_enqueue_script('mi-plugin-scripts', MI_PLUGIN_URL . 'assets/js/scripts.js', array('jquery'), null, true);

    wp_localize_script('mi-plugin-scripts', 'mi_plugin_ajax', array(
        'url' => admin_url('admin-ajax.php') // URL para el manejo de AJAX
    ));
}
add_action('wp_enqueue_scripts', 'mi_plugin_enqueue_assets');

// Registrar script del bloque Gutenberg
function mi_plugin_registrar_bloques()
{
    wp_enqueue_script(
        'mi-plugin-bloque-formulario',
        MI_PLUGIN_URL . 'assets/js/block.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(MI_PLUGIN_DIR . 'assets/js/block.js')
    );
}
add_action('enqueue_block_editor_assets', 'mi_plugin_registrar_bloques');

// Añadir regla de reescritura para la página de edición
function mi_plugin_rewrite_rules()
{
    add_rewrite_rule(
        '^([0-9]+)/mi_plugin_formulario/?',
        'index.php?post_type=mi_plugin_formulario&edit_id=$matches[1]',
        'top'
    );
}
add_action('init', 'mi_plugin_rewrite_rules');

// Añadir el Query Variable
function mi_plugin_query_vars($vars)
{
    $vars[] = 'edit_id';
    return $vars;
}
add_filter('query_vars', 'mi_plugin_query_vars');

// Registro del Custom Post Type
function mi_plugin_registrar_cpt()
{
    $labels = array(
        'name' => 'Formularios de Sugerencias',
        'singular_name' => 'Formulario de Sugerencia',
        'add_new' => 'Añadir Nuevo',
        'add_new_item' => 'Añadir Nuevo Formulario',
        'edit_item' => 'Editar Formulario',
        'new_item' => 'Nuevo Formulario',
        'view_item' => 'Ver Formulario',
        'search_items' => 'Buscar Formularios',
        'not_found' => 'No se encontraron formularios',
        'not_found_in_trash' => 'No se encontraron formularios en la papelera',
        'parent_item_colon' => '',
        'menu_name' => 'Formularios de Sugerencias'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'),
        'show_in_rest' => true, // Habilita Gutenberg
        'menu_icon' => 'dashicons-feedback',
    );

    register_post_type('mi_plugin_formulario', $args);
}
add_action('init', 'mi_plugin_registrar_cpt');

// Manejar la creación del formulario
function mi_plugin_create_form()
{
    // Verificar que todos los campos necesarios estén presentes
    if (!isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['sugerencias']) || !isset($_POST['pais'])) {
        wp_send_json_error(array('message' => 'Todos los campos son necesarios.'));
    }

    // Sanitizar y validar los datos
    $nombre = sanitize_text_field($_POST['nombre']);
    $apellido = sanitize_text_field($_POST['apellido']);
    $email = sanitize_email($_POST['email']);
    $sugerencias = sanitize_textarea_field($_POST['sugerencias']);
    $pais = sanitize_text_field($_POST['pais']);

    // Crear el nuevo Custom Post Type
    $post_data = array(
        'post_title'   => $nombre,
        'post_content' => $sugerencias,
        'post_status'  => 'publish', // Puedes cambiar el estado si es necesario
        'post_type'    => 'mi_plugin_formulario', // Asegúrate de que coincida con el nombre del CPT
    );
    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Error al crear el post.'));
    }

    // Actualizar campos personalizados usando ACF
    update_field('nombre', $nombre, $post_id);
    update_field('apellido', $apellido, $post_id);
    update_field('email', $email, $post_id);
    update_field('pais', $pais, $post_id);
    update_field('sugerencia', $sugerencias, $post_id);

    // Enviar respuesta
    wp_send_json_success(array('message' => 'Formulario creado correctamente.'));
}
add_action('wp_ajax_mi_plugin_create_form', 'mi_plugin_create_form');
add_action('wp_ajax_nopriv_mi_plugin_create_form', 'mi_plugin_create_form');
