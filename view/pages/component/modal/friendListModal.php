<!-- Floating Friendlist Button -->
<div class="floating-friendlist" id="toggle-friendlist">
    <i class="fas fa-users"></i>
</div>

<!-- Friendlist Modal -->
<div class="friendlist-modal" id="friendlist-modal">
    <div class="friendlist-modal-header">
        <h5 class="text-white">Freundesliste</h5>
        <i class="fas fa-times" id="close-friendlist" style="cursor: pointer;"></i>
    </div>
    <div class="friendlist-modal-body">
        <input type="text" id="friend-search" class="search-bar" placeholder="Freunde suchen...">
        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
            <img src="./images/quizapp_logo.png" class="avatar" alt="Avatar">
            <div class="entry-details">
                <div class="name">Max Mustermann</div>
                <div class="email">max.mustermann@iu.org</div>
            </div>
            <div class="entry-icons">
                <i class="fa-solid fa-message text-primary me-2 start-chat" title="Konversation beginnen" data-name="Max Mustermann" data-email="max.mustermann@iu.org"></i>
                <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
            </div>
        </div>
        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
            <img src="./images/quizapp_logo.png" class="avatar" alt="Avatar">
            <div class="entry-details">
                <div class="name">Anna Müller</div>
                <div class="email">anna.mueller@iubh-fernstudium.de</div>
            </div>
            <div class="entry-icons">
                <i class="fa-solid fa-message text-primary me-2 start-chat" title="Konversation beginnen" data-name="Anna Müller" data-email="anna.mueller@iubh-fernstudium.de"></i>
                <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
            </div>
        </div>
    </div>
</div>