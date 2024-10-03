<!-- Floating Friendlist Button -->
<div class="floating-friendlist" id="toggle-friendlist" title="Freundesliste anzeigen">
    <i class="fas fa-users"></i>
</div>

<!-- Friendlist Modal -->
<div class="friendlist-modal" id="friendlist-modal">
    <div class="friendlist-modal-header">
        <span class="text-white fs-6">Freundesliste</span>
        <i class="fas fa-times" id="close-friendlist" style="cursor: pointer;"></i>
    </div>
    
    <!-- Scrollable Friendlist -->
    <div class="friendlist-modal-body">
        <input type="text" id="friend-search" class="search-bar" placeholder="Freunde suchen...">

        <!-- Friend Entries -->
        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
            <img src="./images/iu_quizapp_logo.png" class="avatar" alt="Avatar">
            <div class="entry-details">
                <div class="name">Max Mustermann</div>
                <div class="email">max.mustermann@iu.org</div>
            </div>
            <div class="entry-icons">
                <i class="fa-solid fa-message text-primary me-2 start-chat" title="Konversation beginnen" data-chat-id="1" data-name="Max Mustermann" data-avatar="./images/iu_quizapp_logo.png"></i>
                <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
            </div>
        </div>

        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
            <img src="./images/iu_quizapp_logo.png" class="avatar" alt="Avatar">
            <div class="entry-details">
                <div class="name">Anna Müller</div>
                <div class="email">anna.mueller@iubh-fernstudium.de</div>
            </div>
            <div class="entry-icons">
                <i class="fa-solid fa-message text-primary me-2 start-chat" title="Konversation beginnen" data-chat-id="2" data-name="Anna Müller" data-avatar="./images/iu_quizapp_logo.png"></i>
                <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
            </div>
        </div>

        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
            <img src="./images/iu_quizapp_logo.png" class="avatar" alt="Avatar">
            <div class="entry-details">
                <div class="name">Moritz Texter</div>
                <div class="email">moritz.texter@iubh-fernstudium.de</div>
            </div>
            <div class="entry-icons">
                <i class="fa-solid fa-message text-primary me-2 start-chat" title="Konversation beginnen" data-chat-id="3" data-name="Moritz Texter" data-avatar="./images/iu_quizapp_logo.png"></i>
                <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chat Bubble Vorlage -->
<div class="chat-bubble-template" id="chat-container" style="display: none;">
    <div class="chat-bubble" id="" data-chat-with="">
        <div class="chat-bubble-header">
            <img src="./images/iu_quizapp_logo.png" alt="Avatar" class="chat-avatar">
            <span class="chat-name">Chat mit Max Mustermann</span>
            <i class="fas fa-times close-chat" style="cursor: pointer;"></i>
        </div>
        <div class="chat-bubble-body p-2">
            <div id="chat-history-container">
                <div id="chat-history">
                    <!-- Hier werden die Nachrichten angezeigt -->
                </div>
            </div>
            <div class="chat-input">
                <textarea id="" class="chat-message" placeholder="Nachricht schreiben..."></textarea>
                <button class="send-message">Senden</button>
            </div>
        </div>
    </div>
</div>

<script src="./js/friendlist.js"></script>