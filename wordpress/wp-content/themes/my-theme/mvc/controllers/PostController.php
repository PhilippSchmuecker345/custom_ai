<?php
class PostController {

    public function handleRequest() {
        // Überprüfe, ob eine Aktion übergeben wurde
        if (isset($_GET['action']) && $_GET['action'] == 'show_ollama_response') {
            $userMessage = isset($_GET['message']) ? $_GET['message'] : 'Hallo!';
            
            // Instanziiere das Ollama Model
            $ollamaModel = new OllamaModel();
            $ollamaResponse = $ollamaModel->getResponseFromOllama($userMessage); // Korrektur: getResponseFromOllama()

            // Antwort als JSON ausgeben (für den AJAX-Request)
            header('Content-Type: application/json');
            echo json_encode(['response' => $ollamaResponse]);
            exit;
        } else {
            // Standardansicht anzeigen
            $this->displayPosts();
        }
    }

    public function displayPosts() {
        // Hier könntest du echte Beiträge abrufen und anzeigen
        echo "<p>Hier sollten die Beiträge angezeigt werden.</p>";
    }

    private function renderView($view, $data) {
        // Lade die View-Datei und übergebe die Daten
        include plugin_dir_path(__FILE__) . 'views/' . $view . '.php';
    }
}

