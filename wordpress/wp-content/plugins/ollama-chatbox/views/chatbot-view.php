<div id="chat-container">
    <div id="chat-output">
        <?php echo do_shortcode('[ollama_chatbot_history]'); ?>
    </div>

    <input type="text" id="chat-input" placeholder="Schreibe eine Nachricht...">
    <button onclick="sendMessage()">Senden</button>
</div>

<script>
    function sendMessage() {
        const userInput = document.getElementById('chat-input').value;
        const chatOutput = document.getElementById('chat-output');

        // API-Aufruf zum Senden der Nachricht an den Chatbot
        fetch('http://localhost:8080/wp-json/ollama/v1/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                prompt: userInput
            })
        })
        .then(response => response.json())
        .then(data => {
            // Antwort vom Bot
            chatOutput.innerHTML += `<p><strong>Du:</strong> ${userInput}</p>`;
            chatOutput.innerHTML += `<p><strong>Bot:</strong> ${data.response}</p>`;
        })
        .catch(error => console.error('Error:', error));
    }
</script>

