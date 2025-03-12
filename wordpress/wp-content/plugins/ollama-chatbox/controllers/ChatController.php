<?php

class ChatController {
    public function generate_response($prompt) {
        $ollama_url = 'http://localhost:8090/api/generate'; // Ollama-Server-URL

        $body = json_encode([
            'model' => 'llama3:8b',
            'prompt' => $prompt
        ]);

        $response = wp_remote_post($ollama_url, [
            'method'    => 'POST',
            'headers'   => ['Content-Type' => 'application/json'],
            'body'      => $body
        ]);

        if (is_wp_error($response)) {
            return ['error' => 'Fehler bei der API-Verbindung: ' . $response->get_error_message()];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Chatverlauf in der Datenbank speichern
        if (isset($data['response'])) {
            $this->save_chat_history($prompt, $data['response']);
        }

        return $data;
    }

    // Funktion, um den Chatverlauf in der Datenbank zu speichern
    private function save_chat_history($prompt, $response) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ollama_chat_history';

        $wpdb->insert(
            $table_name,
            [
                'prompt'    => $prompt,
                'response'  => $response,
            ]
        );
    }
}
?>

