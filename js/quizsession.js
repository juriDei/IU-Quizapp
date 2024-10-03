$(document).ready(function() {
    let studentAnswers = {};
    let currentQuestionIndex = 0;
    const totalQuestions = $('.question').length;

    // Funktion zum Abrufen der gespeicherten Antworten
    function loadStudentAnswers() {
        $.ajax({
            url: 'quizsession/get-student-answers',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response) {
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

    // Funktion zum Anzeigen der gespeicherten Antwort für eine Frage
    function displayAnswer(questionId) {
        const selectedAnswer = studentAnswers[questionId];
        const $currentQuestion = $(`.question[data-question-id="${questionId}"]`);

        if (selectedAnswer) {
            const questionType = $currentQuestion.data('question-type');

            if (questionType === 'multiple') {
                let selectedAnswers = selectedAnswer;
                if (typeof selectedAnswer === 'string') {
                    selectedAnswers = JSON.parse(selectedAnswer);
                }
                selectedAnswers.forEach(function(answer) {
                    $currentQuestion.find(`input[value="${answer}"]`).prop('checked', true);
                });
            } else if (questionType === 'single') {
                $currentQuestion.find(`input[value="${selectedAnswer}"]`).prop('checked', true);
            } else if (questionType === 'open') {
                $currentQuestion.find('textarea[name^="answer_"]').val(selectedAnswer);
            }
        } else {
            // Keine gespeicherte Antwort, Felder zurücksetzen
            $currentQuestion.find('input[type="radio"], input[type="checkbox"]').prop('checked', false);
            $currentQuestion.find('textarea').val('');
        }
    }

    // Funktionen zum Navigieren zwischen den Fragen
    function showQuestion(index) {
        $('.question').hide();
        $('.question').eq(index).show();
        $('#prev-button').prop('disabled', index === 0);
        $('#next-button').text(index === totalQuestions - 1 ? 'Abschließen' : 'Weiter');
        displayAnswer(getCurrentQuestionId());
    }

    function getCurrentQuestionId() {
        return $('.question').eq(currentQuestionIndex).data('question-id');
    }



    // Antwort speichern
    function saveCurrentAnswer() {
        const questionId = getCurrentQuestionId();
        const $currentQuestion = $(`.question[data-question-id="${questionId}"]`);
        let selectedAnswer;

        const questionType = $currentQuestion.find('input[name^="answer_"]').attr('type') || ($currentQuestion.find('textarea[name^="answer_"]').length ? 'textarea' : null);

        if (questionType === 'checkbox') {
            selectedAnswer = $currentQuestion.find('input[name^="answer_"]:checked').map(function() {
                return $(this).val();
            }).get();
        } else if (questionType === 'radio') {
            selectedAnswer = $currentQuestion.find('input[name^="answer_"]:checked').val();
        } else if (questionType === 'textarea') {
            selectedAnswer = $currentQuestion.find('textarea[name^="answer_"]').val();
        }

        if (selectedAnswer !== undefined) {
            studentAnswers[questionId] = selectedAnswer;

            // Speichern der Antwort im Backend
            saveStudentAnswer(questionId, selectedAnswer);
        }
    }

    function saveStudentAnswer(questionId, selectedAnswer) {
        const dataToSend = {
            question_id: questionId,
            selected_answer: selectedAnswer,
            session_id: $('#session-id').val()
        };
    
        $.ajax({
            url: '/quizapp/quizsession/save-answer',
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

    $('#next-button').on('click', function() {
        saveCurrentAnswer();
        if (currentQuestionIndex < totalQuestions - 1) {
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
        } else {
            // Quizsession abschließen
            $.ajax({
                url: '/quizapp/quizsession/complete',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({}),
                success: function(data) {
                    // Alle Fragen beantwortet, zur Auswertung weiterleiten
                    window.location.href = 'quizsessionresult?session_id=' + $('#session-id').val();
                }
            });
        }
    });

    $('#prev-button').on('click', function() {
        saveCurrentAnswer();
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
        }
    });

    $(document).on("click",".cancelQuizsession",function(e){
        var quiz_session_id = $(this).data("quizsessionid");
        if (confirm('Möchten Sie diese Quizsession wirklich abbrechen?')) {
            $.ajax({
                url: '/quizapp/quizsession/cancel',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ quiz_session_id: quiz_session_id }),
                success: function(data) {
                    location.reload();
                }
            });
        }
        e.stopImmediatePropagation();
    })

    // Initialisierung
    showQuestion(currentQuestionIndex);
    loadStudentAnswers();
});
