<?php
function enrol_user($domain, $token, $email, $first_name, $last_name, $password, $course_id, $ssl_verify) {
    $existing_user = get_moodle_user($domain, $token, $email, $ssl_verify);
    if ($existing_user) {
        $created_user_id = $existing_user['id'];
    } else {
        $user_data = [
            'username' => $email,
            'password' => $password,
            'firstname' => $first_name,
            'lastname' => $last_name,
            'email' => $email,
        ];
        $create_user_response = call_moodle_api($domain, $token, 'core_user_create_users', ['users' => [$user_data]], $ssl_verify);
        if (!empty($create_user_response) && isset($create_user_response[0]['id'])) {
            $created_user_id = $create_user_response[0]['id'];
            send_moodle_credentials_email($first_name, $email, $password);
        } else {
            write_log('Erro ao criar o usuário no Moodle.');
            return;
        }
    }

    if (isset($created_user_id)) {
        $timestart = time();
        $timeend = strtotime('+1 year', $timestart);
        $enrol_data = [
            'enrolments' => [
                [
                    'roleid' => 5,
                    'userid' => $created_user_id,
                    'courseid' => $course_id,
                    'timestart' => $timestart,
                    'timeend' => $timeend,
                ],
            ],
        ];
        $enrol_response = call_moodle_api($domain, $token, 'enrol_manual_enrol_users', $enrol_data, $ssl_verify);
        if (!empty($enrol_response) && !isset($enrol_response['exception'])) {
            write_log("Usuário $created_user_id matriculado no curso $course_id com sucesso.");
        } else {
            write_log("Erro ao matricular o usuário $created_user_id no curso $course_id. Resposta: " . print_r($enrol_response, true));
        }
    }
}

function send_moodle_credentials_email($first_name, $user_email, $password) {
    $login_url = get_option('moodle_domain') . '/login/';
    $subject = get_option('moodle_email_subject', 'Bem-vindo ao Moodle - Acesso ao curso');
    $body_template = get_option('moodle_email_body', 'Olá {first_name},\n\nEsses são seus acessos ao portal do aluno:\n\nURL: {login_url}\nUsername: {user_email}\nPassword: {password}\n\nPor favor, altere sua senha após o primeiro login.\n\nObrigado!');
    $message = str_replace(['{first_name}', '{login_url}', '{user_email}', '{password}'], [$first_name, $login_url, $user_email, $password], $body_template);
    wp_mail($user_email, $subject, $message);
}

function call_moodle_api($domain, $token, $function, $data, $ssl_verify) {
    $url = $domain . '/webservice/rest/server.php?wstoken=' . $token . '&wsfunction=' . $function . '&moodlewsrestformat=json';
    $response = wp_remote_post($url, [
        'body' => $data,
        'sslverify' => $ssl_verify,
    ]);

    if (is_wp_error($response)) {
        write_log('Erro ao fazer chamada para API do Moodle: ' . $response->get_error_message());
        return [];
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

function get_moodle_user($domain, $token, $email, $ssl_verify) {
    $function = 'core_user_get_users';
    $criteria = [
        'criteria' => [
            [
                'key' => 'email',
                'value' => $email,
            ],
        ],
    ];
    $response = call_moodle_api($domain, $token, $function, $criteria, $ssl_verify);
    if (!empty($response['users'])) {
        return $response['users'][0];
    }
    return false;
}
?>