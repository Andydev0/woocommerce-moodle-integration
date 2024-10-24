<?php
function moodle_integration_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['save_moodle_settings'])) {
        update_option('moodle_token', sanitize_text_field($_POST['moodle_token']));
        update_option('moodle_domain', sanitize_text_field($_POST['moodle_domain']));
        update_option('moodle_ssl_verify', sanitize_text_field($_POST['moodle_ssl_verify']));
        update_option('moodle_email_subject', sanitize_text_field($_POST['moodle_email_subject']));
        update_option('moodle_email_body', wp_kses_post($_POST['moodle_email_body']));
        echo '<div class="updated"><p>Configurações salvas.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Configurações do Moodle Integration</h1>
        <form method="POST">
            <table class="form-table">
                <tr>
                    <th scope="row">Moodle Token</th>
                    <td><input type="text" name="moodle_token" value="<?php echo esc_attr(get_option('moodle_token')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Moodle Domain</th>
                    <td><input type="text" name="moodle_domain" value="<?php echo esc_attr(get_option('moodle_domain')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">SSL Verificação</th>
                    <td>
                        <select name="moodle_ssl_verify">
                            <option value="yes" <?php selected(get_option('moodle_ssl_verify'), 'yes'); ?>>Sim</option>
                            <option value="no" <?php selected(get_option('moodle_ssl_verify'), 'no'); ?>>Não</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Título do E-mail</th>
                    <td><input type="text" name="moodle_email_subject" value="<?php echo esc_attr(get_option('moodle_email_subject', 'Bem-vindo ao Moodle - Acesso ao curso')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Corpo do E-mail</th>
                    <td>
                        <textarea name="moodle_email_body" rows="10" class="large-text"><?php echo esc_textarea(get_option('moodle_email_body', 'Olá {first_name},\n\nEsses são seus acessos ao portal do aluno:\n\nURL: {login_url}\nUsername: {user_email}\nPassword: {password}\n\nPor favor, altere sua senha após o primeiro login.\n\nObrigado!')); ?></textarea>
                    </td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="save_moodle_settings" class="button-primary" value="Salvar Configurações"></p>
        </form>
    </div>
    <?php
}

function moodle_integration_test_api_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['test_moodle_api'])) {
        $domain = get_option('moodle_domain');
        $token = get_option('moodle_token');
        $ssl_verify = get_option('moodle_ssl_verify') == 'yes';

        $test_response = call_moodle_api($domain, $token, 'core_webservice_get_site_info', [], $ssl_verify);

        if (!empty($test_response) && !isset($test_response['exception'])) {
            echo '<div class="updated"><p>Conexão com a API do Moodle bem-sucedida.</p></div>';
        } else {
            echo '<div class="error"><p>Erro ao conectar-se à API do Moodle: ' . esc_html(print_r($test_response, true)) . '</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1>Testar Conexão com a API do Moodle</h1>
        <form method="POST">
            <p class="submit"><input type="submit" name="test_moodle_api" class="button-primary" value="Testar API"></p>
        </form>
    </div>
    <?php
}
?>