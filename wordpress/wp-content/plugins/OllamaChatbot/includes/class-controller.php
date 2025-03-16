<?php
class AI_Chatbot_Controller {
    private $model;

    public function __construct() {
        $this->model = new AI_Chatbot_Model();
        
        // AJAX-Handler registrieren
        add_action('wp_ajax_ai_chatbot_send', [$this, 'handle_chat']);
        add_action('wp_ajax_nopriv_ai_chatbot_send', [$this, 'handle_chat']);
    }

    public function handle_chat() {
        if (!isset($_POST['message'])) {
            wp_send_json_error(['message' => 'Keine Nachricht erhalten.']);
        }

        $user_message = sanitize_text_field($_POST['message']);
        
        // Anfrage an Ollama API senden
        $bot_response = $this->query_ollama($user_message);

        // In der DB speichern
        $this->model->save_message($user_message, $bot_response);

        wp_send_json_success(['bot_response' => $bot_response]);
    }

    private function query_ollama($message) {
        $ollama_url = "http://ollama:11434/api/generate"; // Docker-interne URL

        $data = json_encode([
            "model" => "llama3:8b",
            "prompt" => $message,
            "stream" => false

        ]);

        $args = [
            'body'    => $data,
            'headers' => ['Content-Type' => 'application/json'],
            'timeout' => 60
        ];

        $response = wp_remote_post($ollama_url, $args);
        
        if (is_wp_error($response)) {
            return "Fehler bei der API-Anfrage: " . $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);

        return $json['response'] ?? "Keine Antwort erhalten.";
    }
}

