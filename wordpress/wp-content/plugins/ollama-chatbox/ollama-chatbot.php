<?php
/**
 * Plugin Name: Ollama Chatbot
 * Description: Ein Plugin, das den Ollama Chatbot verwendet, basierend auf dem MVC-Ansatz.
 * Version: 1.0
 * Author: Dein Name
 */

// Verhindern des direkten Zugriffs
if (!defined('ABSPATH')) {
    exit;
}

// Lade die Controller und Modelle
require_once plugin_dir_path(__FILE__) . 'controllers/ChatController.php';
require_once plugin_dir_path(__FILE__) . 'models/OllamaChatbotAPI.php';

// Erstellen der Tabelle bei Aktivierung des Plugins
function ollama_chatbot_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ollama_chat_history'; // Name der Tabelle
    $charset_collate = $wpdb->get_charset_collate();

    // SQL-Befehl zur Erstellung der Tabelle
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        prompt TEXT NOT NULL,
        response TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'ollama_chatbot_create_table');

// Frontend-Skripte und Stylesheets einbinden
function ollama_chatbot_enqueue_scripts() {
    wp_enqueue_script('ollama-chatbot-js', plugin_dir_url(__FILE__) . 'js/chatbot.js', array('jquery'), null, true);
    wp_enqueue_style('ollama-chatbot-css', plugin_dir_url(__FILE__) . 'css/chatbot.css');
}
add_action('wp_enqueue_scripts', 'ollama_chatbot_enqueue_scripts');

// API-Endpunkt für das Chatten registrieren
function ollama_chatbot_register_api() {
    register_rest_route('ollama/v1', '/chat', array(
        'methods' => 'POST',
        'callback' => 'ollama_chatbot_handle_request',
    ));
}
add_action('rest_api_init', 'ollama_chatbot_register_api');

// Callback für den API-Endpunkt
function ollama_chatbot_handle_request(WP_REST_Request $request) {
    $prompt = sanitize_text_field($request->get_param('prompt'));
    $chat_controller = new ChatController();
    return $chat_controller->generate_response($prompt);
}

// Shortcode für den Chatbot
function ollama_chatbot_shortcode() {
    $file = plugin_dir_path(__FILE__) . 'views/chatbot-view.php';

    // Ausgabe von Debug-Informationen in das Fehlerprotokoll
    error_log("Pfad zur View-Datei: " . $file);

    if (!file_exists($file)) {
        return '<p style="color:red;">Fehler: View-Datei nicht gefunden! Pfad: ' . $file . '</p>';
    }

    ob_start();
    include $file;
    return ob_get_clean();
}

add_shortcode('ollama_chatbot', 'ollama_chatbot_shortcode');

// Funktion, um den Chatverlauf aus der Datenbank zu holen
function ollama_chatbot_get_chat_history() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ollama_chat_history';

    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    return $results;
}

// Funktion, um den Chatverlauf im Frontend anzuzeigen
function ollama_chatbot_show_history() {
    $chats = ollama_chatbot_get_chat_history();
    $output = '<div class="chat-history">';
    
    foreach ($chats as $chat) {
        $output .= '<div class="chat-message">';
        $output .= '<p><strong>Du:</strong> ' . esc_html($chat->prompt) . '</p>';
        $output .= '<p><strong>Bot:</strong> ' . esc_html($chat->response) . '</p>';
        $output .= '<p><small>' . esc_html($chat->created_at) . '</small></p>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

// Shortcode für den Verlauf
function ollama_chatbot_history_shortcode() {
    return ollama_chatbot_show_history();
}
add_shortcode('ollama_chatbot_history', 'ollama_chatbot_history_shortcode');
?>

