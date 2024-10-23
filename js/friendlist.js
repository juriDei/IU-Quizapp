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
        var filter = $(this).val().toLowerCase(); // Den eingegebenen Suchtext in Kleinbuchstaben konvertieren
        
        // Alle Einträge in der Freundesliste durchlaufen
        $('.friendlist-modal .entry').each(function() {
            var name = $(this).find('.name').text().toLowerCase();
            var email = $(this).find('.email').text().toLowerCase();
            
            // Prüfen, ob der Name oder die E-Mail-Adresse den Suchtext enthält
            if (name.includes(filter) || email.includes(filter)) {
                $(this).css('display', 'flex'); // Eintrag anzeigen
            } else {
                $(this).css('display', 'none'); // Eintrag ausblenden
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
            handle: ".chat-bubble-header", // Das Header-Element als Griffpunkt für das Verschieben nutzen
            containment: "window", // Bewegung des Fensters auf das Browserfenster beschränken
        });
    
        // Nachricht über Enter-Taste senden
        newChat.find('.chat-message').on('keypress', function (e) {
            if (e.which === 13) { // Prüfen, ob die Enter-Taste gedrückt wurde
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
    
    // Funktion zum Senden einer Nachricht im Chatfenster
    function sendMessage(chatId) {
        // Nachricht aus dem Eingabefeld abrufen
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
    
    // Funktion zum Schließen eines Chatfensters
    function closeChatWindow(chatId) {
        // Chatfenster langsam ausblenden und dann aus dem DOM entfernen
        openChats[chatId].fadeOut(300, function () {
            $(this).remove();
            delete openChats[chatId]; // Chat aus der Liste der offenen Chats entfernen
        });
    }

    // Beispiel: Öffnen eines neuen Chatfensters (dieser Code kann angepasst werden)
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
