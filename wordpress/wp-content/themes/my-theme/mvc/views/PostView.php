<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot mit Ollama</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        #chatContainer {
            width: 90%;
            max-width: 600px;
            height: 80vh;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        #chatBox {
            flex-grow: 1;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background: #ffffff;
        }
        .message {
            padding: 8px;
            border-radius: 5px;
            margin: 5px 0;
            max-width: 80%;
        }
        .user { background: #007bff; color: white; text-align: right; align-self: flex-end; }
        .bot { background: #f1f1f1; color: black; align-self: flex-start; }
        #inputContainer {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }
        #userMessage {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

    <div id="chatContainer">
        <h2 style="text-align: center;">Ollama Chatbot</h2>
        <div id="chatBox"></div>
        <div id="inputContainer">
            <input type="text" id="userMessage" placeholder="Schreib eine Nachricht..." />
            <button onclick="sendMessage()">Senden</button>
        </div>
    </div>

    <script>
        const chatUrl = "<?php echo get_template_directory_uri(); ?>/mvc/js/chat.php";

        function sendMessage() {
            const message = document.getElementById("userMessage").value.trim();
            const chatBox = document.getElementById("chatBox");

            if (message !== "") {
                chatBox.innerHTML += `<p class="message user"><strong>Du:</strong> ${message}</p>`;
                document.getElementById("userMessage").value = "";

                fetch(chatUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.response) {
                        chatBox.innerHTML += `<p class="message bot"><strong>Ollama:</strong> ${data.response}</p>`;
                    } else {
                        chatBox.innerHTML += `<p class="message bot"><strong>Ollama:</strong> Keine gültige Antwort erhalten.</p>`;
                    }
                    setTimeout(() => { chatBox.scrollTop = chatBox.scrollHeight; }, 100);
                })
                .catch(error => {
                    console.error("Fehler:", error);
                    chatBox.innerHTML += `<p class="message bot">Fehler: Konnte keine Antwort von Ollama erhalten.</p>`;
                });
            }
        }

        document.getElementById("userMessage").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        });
    </script>

</body>
</html>

