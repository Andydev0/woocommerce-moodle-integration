<?php
function write_log($message) {
    $log_file = plugin_dir_path(__FILE__) . '../logs/moodle_integration_log.txt';
    if (!file_exists(dirname($log_file))) {
        mkdir(dirname($log_file), 0755, true);
    }
    $timestamp = date("Y-m-d H:i:s");
    $message = "[{$timestamp}] " . $message . PHP_EOL;
    file_put_contents($log_file, $message, FILE_APPEND);
}

function moodle_integration_logs_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $log_file = plugin_dir_path(__FILE__) . '../logs/moodle_integration_log.txt';
    if (file_exists($log_file)) {
        $logs = file_get_contents($log_file);
        echo '<div class="wrap"><h1>Logs do Moodle Integration</h1><pre>' . esc_html($logs) . '</pre></div>';
    } else {
        echo '<div class="wrap"><h1>Logs do Moodle Integration</h1><p>Não há logs disponíveis.</p></div>';
    }
}
?>