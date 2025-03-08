<?php
class OllamaModel {
    // API-URL von Ollama
    private $apiUrl = 'http://localhost:8091/v1/chat/completions'; // Stelle sicher, dass die URL korrekt ist

    // Funktion, um die Antwort von Ollama zu erhalten
    public function getResponseFromOllama($userMessage) {
        // Daten, die an Ollama gesendet werden (Nachricht des Benutzers und das Modell)
        $data = [
            'model' => 'llama4:8b',  // Modellbezeichnung (hier llama3:8b)
            'messages' => [
                ['role' => 'user', 'content' => $userMessage]  // Die Nachricht des Benutzers
            ]
        ];

        // Initialisiere cURL
        $ch = curl_init();

        // Setze cURL Optionen
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl); // API-URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Rückgabe der Antwort als String
        curl_setopt($ch, CURLOPT_POST, true); // POST-Anfrage
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // JSON-Daten an Ollama senden
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', // Setze den Content-Type auf JSON
        ]);

        // Führe die cURL-Anfrage aus
        $response = curl_exec($ch);

        // Fehlerbehandlung: Wenn die Anfrage fehlschlägt
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return 'Fehler bei der Kommunikation mit der API: ' . $error_msg;  // Fehlernachricht zurückgeben
        }

        // Schließe cURL
        curl_close($ch);

        // Dekodiere die JSON-Antwort
        $responseData = json_decode($response, true);

        // Überprüfen, ob die Antwort von Ollama die erwarteten Daten enthält
        if (isset($responseData['choices'][0]['message']['content'])) {
            // Rückgabe der Antwort von Ollama
            return $responseData['choices'][0]['message']['content'];
        } else {
            // Wenn keine Antwort von Ollama empfangen wurde
            return 'Keine Antwort von Ollama erhalten. Antwort: ' . var_export($responseData, true);
        }
    }
}
?>

