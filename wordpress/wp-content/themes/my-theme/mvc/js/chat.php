<?php
include_once 'models/OllamaModel.php'; // Modell einbinden
header('Content-Type: application/json');

// JSON-Daten aus der POST-Anfrage empfangen
$data = json_decode(file_get_contents('php://input'), true);

// Überprüfen, ob die Nachricht empfangen wurde
if (!$data || !isset($data['message']) || empty($data['message'])) {
    // Fehlerprotokollierung zur Prüfung der Daten
    error_log('Fehlerhafte Anfrage: ' . print_r($data, true));
    echo json_encode(['response' => 'Bitte gib eine Nachricht ein.']);
    exit;
}

// Benutzerinput absichern
$userMessage = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');

try {
    // Das Ollama-Modell initialisieren
    $ollamaModel = new OllamaModel();
    
    // Antwort von Ollama basierend auf der Nachricht erhalten
    $ollamaResponse = $ollamaModel->getResponseFromOllama($userMessage);
    
    // Die Antwort von Ollama als JSON zurückgeben
    echo json_encode(['response' => $ollamaResponse]);
} catch (Exception $e) {
    // Fehlerantwort, wenn etwas schief geht
    echo json_encode(['response' => 'Fehler bei der Verarbeitung deiner Anfrage.']);
}
?>

