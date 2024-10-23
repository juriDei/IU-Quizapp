$(document).ready(function() {
    let studentAnswers = {}; // Objekt zum Speichern der Antworten des Studenten
    let currentQuestionIndex = 0; // Index der aktuellen Frage
    const totalQuestions = $('.question').length; // Gesamtanzahl der Fragen

    // Funktion zum Abrufen der gespeicherten Antworten des Studenten
    function loadStudentAnswers() {
        $.ajax({
            url: 'quizsession/get-student-answers', // Endpunkt zum Abrufen der gespeicherten Antworten
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    // Antworten in das studentAnswers-Objekt speichern
                    studentAnswers = response.reduce((acc, answer) => {
                        acc[answer.question_id] = answer.selected_answer;
                        return acc;
                    }, {});
                    // Anzeige der Antwort für die aktuelle Frage
                    displayAnswer(getCurrentQuestionId());
                }
            },
            error: function() {
                console.log('Fehler beim Laden der Antworten');
            }
        });
    }

    // Funktion zum Anzeigen der gespeicherten Antwort für eine bestimmte Frage
    function displayAnswer(questionId) {
        const selectedAnswer = studentAnswers[questionId];
        const $currentQuestion = $(`.question[data-question-id="${questionId}"]`);

        if (selectedAnswer) {
            const questionType = $currentQuestion.data('question-type');

            if (questionType === 'multiple') {
                // Wenn es sich um eine Multiple-Choice-Frage handelt
                let selectedAnswers = selectedAnswer;
                if (typeof selectedAnswer === 'string') {
                    selectedAnswers = JSON.parse(selectedAnswer);
                }
                selectedAnswers.forEach(function(answer) {
                    $currentQuestion.find(`input[value="${answer}"]`).prop('checked', true);
                });
            } else if (questionType === 'single') {
                // Wenn es sich um eine Single-Choice-Frage handelt
                $currentQuestion.find(`input[value="${selectedAnswer}"]`).prop('checked', true);
            } else if (questionType === 'open') {
                // Wenn es sich um eine offene Frage handelt
                $currentQuestion.find('textarea[name^="answer_"]').val(selectedAnswer);
            }
        } else {
            // Keine gespeicherte Antwort, Felder zurücksetzen
            $currentQuestion.find('input[type="radio"], input[type="checkbox"]').prop('checked', false);
            $currentQuestion.find('textarea').val('');
        }
    }

    // Funktion zum Anzeigen der Frage basierend auf dem Index
    function showQuestion(index) {
        $('.question').hide(); // Alle Fragen ausblenden
        $('.question').eq(index).show(); // Nur die aktuelle Frage anzeigen
        // Vor- und Zurück-Buttons entsprechend aktivieren oder deaktivieren
        $('#prev-button').prop('disabled', index === 0);
        $('#next-button').text(index === totalQuestions - 1 ? 'Abschließen' : 'Weiter');
        displayAnswer(getCurrentQuestionId()); // Gespeicherte Antwort für die aktuelle Frage anzeigen
    }

    // Funktion zum Abrufen der aktuellen Frage-ID
    function getCurrentQuestionId() {
        return $('.question').eq(currentQuestionIndex).data('question-id');
    }

    // Funktion zum Speichern der aktuellen Antwort
    function saveCurrentAnswer() {
        const questionId = getCurrentQuestionId();
        const $currentQuestion = $(`.question[data-question-id="${questionId}"]`);
        let selectedAnswer;

        // Bestimmen des Fragetypen (checkbox, radio, textarea)
        const questionType = $currentQuestion.find('input[name^="answer_"]').attr('type') || ($currentQuestion.find('textarea[name^="answer_"]').length ? 'textarea' : null);

        if (questionType === 'checkbox') {
            // Antworten für Multiple-Choice-Fragen sammeln
            selectedAnswer = $currentQuestion.find('input[name^="answer_"]:checked').map(function() {
                return $(this).val();
            }).get();
        } else if (questionType === 'radio') {
            // Antwort für Single-Choice-Fragen sammeln
            selectedAnswer = $currentQuestion.find('input[name^="answer_"]:checked').val();
        } else if (questionType === 'textarea') {
            // Antwort für offene Fragen sammeln
            selectedAnswer = $currentQuestion.find('textarea[name^="answer_"]').val();
        }

        if (selectedAnswer !== undefined) {
            // Antwort in das studentAnswers-Objekt speichern
            studentAnswers[questionId] = selectedAnswer;

            // Speichern der Antwort im Backend
            saveStudentAnswer(questionId, selectedAnswer);
        }
    }

    // Funktion zum Speichern der Antwort im Backend
    function saveStudentAnswer(questionId, selectedAnswer) {
        const dataToSend = {
            question_id: questionId,
            selected_answer: selectedAnswer,
            session_id: $('#session-id').val() // ID der aktuellen Sitzung
        };
    
        $.ajax({
            url: '/quizapp/quizsession/save-answer', // Endpunkt zum Speichern der Antwort
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dataToSend),
            success: function() {
                console.log('Antwort erfolgreich gespeichert');
            },
            error: function() {
                console.log('Fehler beim Speichern der Antwort');
            }
        });
    }

    // Event-Listener für den Weiter-Button
    $('#next-button').on('click', function() {
        saveCurrentAnswer(); // Speichern der aktuellen Antwort
        if (currentQuestionIndex < totalQuestions - 1) {
            // Zur nächsten Frage wechseln, falls vorhanden
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
        } else {
            // Quizsession abschließen, wenn alle Fragen beantwortet wurden
            $.ajax({
                url: '/quizapp/quizsession/complete', // Endpunkt zum Abschließen der Quizsession
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({}),
                success: function(data) {
                    // Weiterleiten zur Auswertung
                    window.location.href = 'quizsessionresult?session_id=' + $('#session-id').val();
                }
            });
        }
    });

    // Event-Listener für den Zurück-Button
    $('#prev-button').on('click', function() {
        saveCurrentAnswer(); // Speichern der aktuellen Antwort
        if (currentQuestionIndex > 0) {
            // Zur vorherigen Frage wechseln, falls vorhanden
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
        }
    });

    // Event-Listener zum Abbrechen der Quizsession
    $(document).on("click", ".cancelQuizsession", function(e) {
        var quiz_session_id = $(this).data("quizsessionid");
        if (confirm('Möchten Sie diese Quizsession wirklich abbrechen?')) {
            $.ajax({
                url: '/quizapp/quizsession/cancel', // Endpunkt zum Abbrechen der Quizsession
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ quiz_session_id: quiz_session_id }),
                success: function(data) {
                    location.reload(); // Seite neu laden nach erfolgreichem Abbruch
                }
            });
        }
        e.stopImmediatePropagation(); // Verhindert, dass das Ereignis weiter verbreitet wird
    });

    // Initialisierung der ersten Frage und Laden der gespeicherten Antworten
    showQuestion(currentQuestionIndex); // Erste Frage anzeigen
    loadStudentAnswers(); // Gespeicherte Antworten laden
});
