//Implementiert eine Chatbot-Interaktion, bei der der Benutzer Nachrichten sendet, 
//der Server darauf antwortet und die Nachrichten im Chatverlauf angezeigt werden.
jQuery(document).ready(function($) {
    $('#chat-send').click(function() {
        let message = $('#chat-input').val();
        $('#chat-input').val('');

        $('#chat-messages').append('<div>User: ' + message + '</div>');

        $.post(aiChatbotAjax.ajaxurl, { action: 'ai_chatbot_send', message: message }, function(response) {
            if (response.success) {
                $('#chat-messages').append('<div>Bot: ' + response.data.bot_response + '</div>');
            } else {
                $('#chat-messages').append('<div>Fehler: ' + response.data.message + '</div>');
            }
        });
    });
});

