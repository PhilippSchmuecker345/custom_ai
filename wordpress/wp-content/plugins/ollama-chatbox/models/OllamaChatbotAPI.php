<?php
class OllamaChatbotAPI {
    private $api_url = 'http://localhost:11434/api/generate';

    public function send_message($prompt) {
        $data = json_encode(array('model' => 'llama3', 'prompt' => $prompt));

        $response = wp_remote_post($this->api_url, array(
            'method'    => 'POST',
            'body'      => $data,
            'headers'   => array('Content-Type' => 'application/json')
        ));

        if (is_wp_error($response)) {
            return 'Fehler beim Abrufen der Antwort.';
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['response'] ?? 'Keine Antwort erhalten.';
    }
}

