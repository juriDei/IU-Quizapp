$(document).ready(function() {
    var $friendlistModal = $('#friendlist-modal');
    var $floatingIcon = $('#friendlist-toggle');

    // Freundesliste ein- und ausblenden
    $floatingIcon.on('click', function() {
        if ($friendlistModal.hasClass('open')) {
            // Modal schließen
            $friendlistModal.removeClass('open').addClass('close');
            setTimeout(function() {
                $friendlistModal.hide();
            }, 300); // Timeout muss mit der CSS-Transition-Dauer übereinstimmen
        } else {
            // Position des Modals setzen
            var offset = $floatingIcon.offset();
            $friendlistModal.css({
                top: offset.top - $friendlistModal.outerHeight(),
                left: offset.left - $friendlistModal.outerWidth() + $floatingIcon.outerWidth()
            });

            // Modal öffnen
            $friendlistModal.show().removeClass('close').addClass('open');
        }
    });

    // Schließen der Freundesliste durch X
    $('#close-friendlist').on('click', function() {
        $friendlistModal.removeClass('open').addClass('close');
        setTimeout(function() {
            $friendlistModal.hide();
        }, 300); // Timeout muss mit der CSS-Transition-Dauer übereinstimmen
    });

    // Starten eines Chats
    $('.start-chat').on('click', function() {
        var chatName = $(this).data('friend');
        var chatAvatar = $(this).data('avatar');

        $('#chat-name').text(chatName);
        $('#chat-avatar').attr('src', chatAvatar);
        $('#chat-modal').fadeIn();
    });

    // Schließen des Chat-Modals
    $('#close-chat').on('click', function() {
        $('#chat-modal').fadeOut();
    });
});
