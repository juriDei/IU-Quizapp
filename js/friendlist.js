$(document).ready(function() {
    let openChats = {}; // Speichert geöffnete Chats
    var $friendlistModal = $('#friendlist-modal');
    var $floatingIcon = $('#friendlist-toggle');

    // Öffnen der Freundesliste, wenn man auf das Icon klickt
    $('#toggle-friendlist').on('click', function() {
        $('#friendlist-modal').toggleClass('active');
    });

    // Schließen der Freundesliste durch X
    $('#close-friendlist').on('click', function() {
        $('#friendlist-modal').toggleClass('active');
    });

    // Suchen von Freunden in der Liste
    $('#friend-search').on('input', function() {
        var filter = $(this).val().toLowerCase();
        $('.friendlist-modal .entry').each(function() {
            var name = $(this).find('.name').text().toLowerCase();
            var email = $(this).find('.email').text().toLowerCase();
            if (name.includes(filter) || email.includes(filter)) {
                $(this).css('display', 'flex');
            } else {
                $(this).css('display', 'none');
            }
        });
    });

    
    // Funktion zum Erstellen eines neuen Chatfensters
    function openChatWindow(chatName, chatId) {
        // Prüfen, ob der Chat schon offen ist
        if (openChats[chatId]) {
            // Chat ist bereits offen, ihn hervorheben
            openChats[chatId].css('z-index', 2000).animate({ opacity: 1 }, 200);
            return;
        }
    
        // Neues Chatfenster aus der Vorlage klonen
        const newChat = $('.chat-bubble-template .chat-bubble').clone();
        
        // Setze eine eindeutige ID und das Attribut data-chat-with
        newChat.attr('id', `chat-${chatId}`);
        newChat.data('chat-with', chatId);
        
        // Setze den Namen des Chats und weise eindeutige IDs zu
        newChat.find('.chat-name').text(`${chatName}`);
        newChat.find('#chat-history').attr('id', `history-${chatId}`);
        newChat.find('.chat-message').attr('id', `message-${chatId}`);
    
        // Chatfenster anzeigen und zum DOM hinzufügen
        newChat.css('display', 'flex'); // Da das Template versteckt ist, zeigen wir es jetzt an
        $('body').append(newChat);
        
        // Chat-Fenster in die Liste der offenen Fenster aufnehmen
        openChats[chatId] = newChat;
    
        // Chat-Fenster verschiebbar machen
        newChat.draggable({
            handle: ".chat-bubble-header",
            containment: "window",
        });
    
        // Nachricht über Enter-Taste senden
        newChat.find('.chat-message').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                sendMessage(chatId);
            }
        });
    
        // Nachricht über Senden-Button senden
        newChat.find('.send-message').on('click', function () {
            sendMessage(chatId);
        });
    
        // Chatfenster schließen
        newChat.find('.close-chat').on('click', function () {
            closeChatWindow(chatId);
        });
    }
    

    function sendMessage(chatId) {
        // Nachricht holen
        let message = $('#message-' + chatId).val().trim();
        
        // Sicherstellen, dass die Nachricht nicht leer ist
        if (message !== "") {
            // Erstellen einer neuen Nachrichten-Bubble
            let messageBubble = $('<div class="chat-message-bubble sent"></div>').text(message);
            
            // Nachrichten-Bubble in den Verlauf einfügen
            let chatHistory = $('#history-' + chatId);
            chatHistory.append(messageBubble);
    
            // Chatverlauf scrollen, damit die neueste Nachricht sichtbar ist
            chatHistory.scrollTop(chatHistory.prop("scrollHeight"));
    
            // Eingabefeld leeren
            $('#message-' + chatId).val('');
        }
    }
    

    // Chatfenster schließen
    function closeChatWindow(chatId) {
        openChats[chatId].fadeOut(300, function () {
            $(this).remove();
            delete openChats[chatId];
        });
    }

    // Beispiel: Öffnen eines neuen Chatfensters (ersetze mit deinem eigenen Code)
    $('.start-chat').on('click', function () {
        let chatName = $(this).data('name');
        let chatId = $(this).data('chat-id');

        // Überprüfen, ob chatId und chatName korrekt gesetzt sind
        if (chatId && chatName) {
            openChatWindow(chatName, chatId);
        } else {
            console.error('Chat ID oder Chat Name fehlen!');
        }
    });

});
