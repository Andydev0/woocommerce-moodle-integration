<?php
/*
Plugin Name: WooCommerce Moodle Integration
Description: Integração entre WooCommerce e Moodle, criando usuários e matriculando-os em cursos automaticamente após uma compra.
Version: 1.1
Author: Anderson Dev
*/

// Inclui arquivos necessários
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/enrolment-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/log-functions.php';

// Registra as configurações do plugin
add_action('admin_menu', 'moodle_integration_settings_menu');
function moodle_integration_settings_menu() {
    add_menu_page('Moodle Integration Settings', 'Moodle Integration', 'manage_options', 'moodle-integration', 'moodle_integration_settings_page', 'dashicons-welcome-learn-more');
    add_submenu_page('moodle-integration', 'Logs', 'Logs', 'manage_options', 'moodle-integration-logs', 'moodle_integration_logs_page');
    add_submenu_page('moodle-integration', 'Testar API', 'Testar API', 'manage_options', 'moodle-integration-test-api', 'moodle_integration_test_api_page');
}

// Função para adicionar campo personalizado na página de produto
add_action('woocommerce_product_options_general_product_data', 'add_moodle_course_id_field');
function add_moodle_course_id_field() {
    woocommerce_wp_text_input(array(
        'id' => '_moodle_course_id',
        'label' => 'Moodle Course ID',
        'placeholder' => 'Digite o ID do curso no Moodle',
        'desc_tip' => 'true',
        'description' => 'ID do curso correspondente no Moodle para este produto.'
    ));
}

// Salva o ID do curso ao salvar o produto
add_action('woocommerce_process_product_meta', 'save_moodle_course_id_field');
function save_moodle_course_id_field($post_id) {
    $course_id = isset($_POST['_moodle_course_id']) ? sanitize_text_field($_POST['_moodle_course_id']) : '';
    update_post_meta($post_id, '_moodle_course_id', $course_id);
}

// Hook para matricular o usuário após o pedido ser concluído
add_action('woocommerce_order_status_completed', 'enrol_user_in_moodle_course', 10, 1);
function enrol_user_in_moodle_course($order_id) {
    $order = wc_get_order($order_id);

    // Moodle API details
    $moodle_token = get_option('moodle_token');
    $moodle_domain = get_option('moodle_domain');
    $ssl_verify = get_option('moodle_ssl_verify') == 'yes';

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        $course_id = get_post_meta($product->get_id(), '_moodle_course_id', true);

        if ($course_id) {
            // Obtém as informações do usuário do pedido
            $user_email = $order->get_billing_email();
            $first_name = $order->get_billing_first_name();
            $last_name = $order->get_billing_last_name();
            $password = wp_generate_password();

            enrol_user($moodle_domain, $moodle_token, $user_email, $first_name, $last_name, $password, $course_id, $ssl_verify);
        }
    }
}

?>