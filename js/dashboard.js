function animateProgressBarQuestions() {
    let $progressValue = $('#progress-value-questions');
    let $progressCircle = $('#progress-circle-questions');
    let [completed, total] = $progressValue.text().split(' / ').map(Number);
    let currentProgress = 0;
    let targetValue = Math.floor((completed / total) * 100);
    let interval = setInterval(() => {
        if (currentProgress >= targetValue) {
            clearInterval(interval);
        } else {
            currentProgress++;
            $progressValue.text(`${completed} / ${total}`);
            $progressCircle.css('background', `conic-gradient(#0d6efd ${currentProgress * 3.6}deg, #e9ecef 0deg)`);
        }
    }, 20); // Geschwindigkeit der Animation
}

function animateProgressBarQuizzes() {
    let $progressValue = $('#progress-value-quizzes');
    let $progressCircle = $('#progress-circle-quizzes');
    let targetValue = parseInt($progressValue.text()); // Prozentzahl aus dem Text extrahieren
    let currentProgress = 0;
    let interval = setInterval(() => {
        if (currentProgress >= targetValue) {
            clearInterval(interval);
        } else {
            currentProgress++;
            $progressValue.text(`${currentProgress}%`);
            $progressCircle.css('background', `conic-gradient(#0d6efd ${currentProgress * 3.6}deg, #e9ecef 0deg)`);
        }
    }, 10); // Geschwindigkeit der Animation
}

function animateCompletedQuizzes() {
    let $completedQuizzes = $('#completed-quizzes');
    let targetValue = 15; // Beispielwert für absolvierte Quizspiele
    let currentProgress = 0;
    let interval = setInterval(() => {
        if (currentProgress >= targetValue) {
            clearInterval(interval);
        } else {
            currentProgress++;
            $completedQuizzes.text(currentProgress);
        }
    }, 35); // Geschwindigkeit der Animation
}

function setAverageGradeColor() {
    let $averageGrade = $('#average-grade');
    let gradeText = $averageGrade.text().split(': ')[1]; // Note aus dem Text extrahieren
    let grade = parseFloat(gradeText);

    if (grade < 3) {
        $averageGrade.removeClass().addClass('average-grade grade-green');
    } else if (grade < 4) {
        $averageGrade.removeClass().addClass('average-grade grade-yellow');
    } else if (grade <= 4) {
        $averageGrade.removeClass().addClass('average-grade grade-orange');
    } else {
        $averageGrade.removeClass().addClass('average-grade grade-red');
    }
}

function animateProgressBarRecentGames() {
    $('#recent-games-wrapper .progress-circle').each(function() {
        let $progressValue = $(this).find('.progress-value');
        let $progressCircle = $(this);
        let targetValue = parseInt($progressValue.text()); // Prozentzahl aus dem Text extrahieren
        let currentProgress = 0;
        let circleColor = targetValue > 50 ? '#28a745' : '#dc3545'; // Grün, wenn > 50%, sonst Rot

        let interval = setInterval(() => {
            if (currentProgress >= targetValue) {
                clearInterval(interval);
            } else {
                currentProgress++;
                $progressValue.text(`${currentProgress}%`);
                $progressCircle.css('background', `conic-gradient(${circleColor} ${currentProgress * 3.6}deg, #e0e0e0 0deg)`);
                $progressValue.css('color', circleColor); // Textfarbe ändern
            }
        }, 10); // Geschwindigkeit der Animation
    });
}


$(document).ready(() => {
    animateProgressBarQuestions();
    animateProgressBarQuizzes();
    animateCompletedQuizzes();
    setAverageGradeColor();
    animateProgressBarRecentGames();

    // Initialisiere Select2 für alle Select-Elemente mit der Klasse .select2
    $('.select2').select2({
        placeholder: "Optionen auswählen",
        allowClear: true,
        width: '100%'
    });

    // Wechsel zu Einzelspieler-Einstellungen und setze Modus
    $('#btn-singleplayer').click(function() {
        $('#mode').val('singleplayer'); // Setze den Modus
        $('#mode-selection').addClass('d-none');
        $('#singleplayer-settings').removeClass('d-none');
    });

    // Wechsel zu Mehrspieler-Einstellungen und setze Modus
    $('#btn-multiplayer').click(function() {
        $('#mode').val('multiplayer'); // Setze den Modus
        $('#mode-selection').addClass('d-none');
        $('#multiplayer-settings').removeClass('d-none');
    });

    // Zurück zur Modusauswahl
    $('#back-to-mode-selection').click(function() {
        $('#singleplayer-settings').addClass('d-none');
        $('#mode-selection').removeClass('d-none');
        $('#mode').val(''); // Modus zurücksetzen
    });

    $('#back-to-mode-selection-multiplayer').click(function() {
        $('#multiplayer-settings').addClass('d-none');
        $('#mode-selection').removeClass('d-none');
        $('#mode').val(''); // Modus zurücksetzen
    });

     // AJAX-Anfrage beim Klicken auf "Quiz starten" im Einzelspieler-Modus
     $('#start-singleplayer-quiz-btn').click(function() {
        // Sammle die Formulardaten
        var formData = $('#quiz-form').serialize();

        // Sende die Daten per AJAX an /quizsession/create
        $.ajax({
            url: 'quizsession/create',  // Neue Route
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                window.location.href = 'quizsession?session_id=' + data.game_id;
            },
            error: function(xhr, status, error) {
                console.error('Fehler beim Senden der Anfrage:', error);
                $('#quiz-result').html('<div class="alert alert-danger">Fehler beim Senden der Anfrage: ' + error + '</div>');
            }
        });
    });

    // Multiplayer-Modus: Lobbysuche und Loader anzeigen
    $('#start-multiplayer-quiz-btn').click(function() {
        // Sammle die Formulardaten (inklusive Teilnehmeranzahl)
        var formData = $('#quiz-form').serialize();

        // Multiplayer-Einstellungen ausblenden und Loader anzeigen
        $('#multiplayer-settings').addClass('d-none');
        $('#lobby-search-loader').removeClass('d-none');

        // Simuliere die Lobbysuche für 5 Sekunden oder führe eine AJAX-Anfrage aus
        setTimeout(function() {
            // Beispielhafte Simulation: Du kannst hier eine echte AJAX-Anfrage ausführen, um eine Lobby zu suchen
            $.ajax({
                url: 'quizsession/create',  // Neue Route für den Multiplayer
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    // Weiterleitung zur Multiplayer-Session, sobald eine Lobby gefunden wurde
                    //window.location.href = 'quizsession?session_id=' + data.game_id;
                },
                error: function(xhr, status, error) {
                    console.error('Fehler beim Senden der Anfrage:', error);
                    $('#quiz-result').html('<div class="alert alert-danger">Fehler beim Senden der Anfrage: ' + error + '</div>');
                    
                    // Zeige die Mehrspieler-Einstellungen wieder an, wenn ein Fehler auftritt
                    $('#multiplayer-settings').removeClass('d-none');
                    $('#lobby-search-loader').addClass('d-none');
                }
            });
        }, 5000); // 5 Sekunden warten, um die Lobbysuche zu simulieren
    });

    // Event Listener für die Scroll-Pfeile
    $('.scroll-arrow').on('click', function() {
        var $container = $(this).siblings('.module-names-container');
        var scrollAmount = 100; // Die Scrollmenge bei jedem Klick

        // Überprüfen, ob der linke oder rechte Pfeil geklickt wurde
        if ($(this).hasClass('fa-chevron-left')) {
            // Scrollen nach links
            $container.animate({
                scrollLeft: '-=' + scrollAmount
            }, 300);
        } else if ($(this).hasClass('fa-chevron-right')) {
            // Scrollen nach rechts
            $container.animate({
                scrollLeft: '+=' + scrollAmount
            }, 300);
        }
    });
});
