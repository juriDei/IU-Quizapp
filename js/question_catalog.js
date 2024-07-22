var modulId;

function loadCatalogContent(moduleId) {
    // Load all questions
    $.ajax({
        url: 'questions/catalog-questions',
        type: 'GET',
        data: {
            module_id: moduleId
        },
        success: function(data) {
            var questions = JSON.parse(data);

            if (!questions.length) {
                $('#allQuestionsContent').html("<p class='text-center p-4'>Fragenkatalog enthält noch keine Fragen</p>");
            } else if (questions.error) {
                $('#allQuestionsContent').html('<p>' + questions.error + '</p>');
            } else {
                var content = '';
                questions.forEach(function(question) {
                    var possibleAnswers = JSON.parse(question.possible_answers);
                    var answersHtml = possibleAnswers.map(answer => `
                <li class="list-group-item text-black">${answer}</li>
            `).join('');
                    content += `
                <div class="question">
                    <p class="question-text fw-semibold"><b>Frage:</b> ${question.question_text}</p>
                    <div class="answers" style="display: none;">
                        <ul class="list-group mt-2">${answersHtml}</ul>
                    </div>
                    <button class="toggle-answers btn btn-link text-decoration-none float-end"><i class="fas fa-chevron-down"></i></button>
                </div>
            `;
                });
                $('#allQuestionsContent').html(content);
            }
        },
        error: function(error) {
            console.error('Error loading all questions:', error);
        }
    });

    // Load student questions
    $.ajax({
        url: 'questions/student-questions', // Endpunkt für Studentfragen
        type: 'GET',
        data: {
            module_id: moduleId
        },
        success: function(data) {
            var studentQuestions = JSON.parse(data);
            if (!studentQuestions.length) {
                $('#studentQuestionsContent').html("<p class='text-center p-4'>Zu diesem Katalog, wurden noch keine Studentenfragen hinzugefügt</p>");
            } else if (studentQuestions.error) {
                $('#studentQuestionsContent').html('<p>' + studentQuestions.error + '</p>');
            } else {
                var content = '';
                studentQuestions.forEach(function(question) {
                    var possibleAnswers = JSON.parse(question.possible_answers);
                    var answersHtml = possibleAnswers.map(answer => `
                <li class="list-group-item text-black">${answer.text}</li>
            `).join('');
                    content += `
                <div class="question border rounded p-2 mt-2 mb-2">
                    <p class="question-text text-black fw-semibold float-start"><b>Frage:</b> ${question.question_text}</p>
                    <button class="toggle-answers btn btn-link text-decoration-none float-end"><i class="fas fa-chevron-down"></i></button>
                    <div class="answers" style="display: none;">
                        <ul class="list-group mt-2 w-100">${answersHtml}</ul>
                    </div><br>
                    <button class="btn btn-link text-decoration-none text-success fs-5" title='Upvote'><i class="fas fa-thumbs-up"></i></button>
                    <button class="btn btn-link text-decoration-none text-danger fs-5" title='Downvote'><i class="fas fa-thumbs-down"></i></button>
                </div>
            `;
                });
                $('#studentQuestionsContent').html(content);
            }
        },
        error: function(error) {
            console.error('Error loading student questions:', error);
        }
    });
}

function updateSingleChoiceCorrectAnswerOptions() {
    var options = '';
    $('#singleChoiceContainer .form-control').each(function(index) {
        options += '<option value="' + index + '">Antwort ' + (index + 1) + '</option>';
    });
    $('#singleChoiceCorrectAnswer').html(options);
}

function resetModal() {
    $('#questionType').val('single');
    $('#questionText').val('');
    $('#singleChoiceContainer').html('<div class="input-group mb-2">' +
        '<input type="text" class="form-control" placeholder="Antwort">' +
        '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
        '</div>');
    updateSingleChoiceCorrectAnswerOptions();
    $('#multipleChoiceContainer').html('<div class="input-group mb-2">' +
        '<div class="input-group-text">' +
        '<input class="form-check-input mt-0" type="checkbox">' +
        '</div>' +
        '<input type="text" class="form-control" placeholder="Antwort">' +
        '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
        '</div>');
    $('#openQuestionAnswer textarea').val('');
    $('.answer-section').hide();
    $('#singleChoiceAnswers').show();
}

$(document).ready(function() {
    $('.showQuestionCatalog').on('click', function() {
        modulId = $(this).data('modulid');
        loadCatalogContent(modulId);
    });

    $('#questionType').on('change', function() {
        var questionType = $(this).val();
        $('.answer-section').hide();
        if (questionType === 'single') {
            $('#singleChoiceAnswers').show();
        } else if (questionType === 'multiple') {
            $('#multipleChoiceAnswers').show();
        } else if (questionType === 'open') {
            $('#openQuestionAnswer').show();
        }
    });

    // Show the add question modal
    $('#createQuestionBtn').on('click', function() {
        //Hier wird das Modal bei jedem Aufruf zurückgesetzt
        resetModal();
        $('#questionType').val('single').trigger('change');
        $('#addQuestionModal').modal('show');
    });

    // Add Single Choice Answer
    $('#addSingleChoiceAnswer').on('click', function() {
        var newAnswer = '<div class="input-group mb-2">' +
            '<input type="text" class="form-control" placeholder="Antwort">' +
            '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
            '</div>';
        $('#singleChoiceContainer').append(newAnswer);
        updateSingleChoiceCorrectAnswerOptions();
    });

    // Add Multiple Choice Answer
    $('#addMultipleChoiceAnswer').on('click', function() {
        var newAnswer = '<div class="input-group mb-2">' +
            '<div class="input-group-text">' +
            '<input class="form-check-input mt-0" type="checkbox">' +
            '</div>' +
            '<input type="text" class="form-control" placeholder="Antwort">' +
            '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
            '</div>';
        $('#multipleChoiceContainer').append(newAnswer);
    });

    // Remove answer
    $(document).on('click', '.remove-answer-btn', function() {
        $(this).closest('.input-group').remove();
        updateSingleChoiceCorrectAnswerOptions();
    });

    $('#saveQuestionBtn').on('click', function() {
        var questionType = $('#questionType').val();
        var questionText = $('#questionText').val();
        var possibleAnswers = [];
        var correctAnswer = '';

        if (questionType === 'single') {
            $('#singleChoiceContainer .form-control').each(function(idx,val) {
                var correctAnswer = (idx == $("#singleChoiceCorrectAnswer").val()) ? true : false;
                possibleAnswers.push({
                    text: $(this).val(),
                    correct: correctAnswer
                });
            });
        } else if (questionType === 'multiple') {
            $('#multipleChoiceContainer .input-group').each(function() {
                var answerText = $(this).find('.form-control').val();
                var isChecked = $(this).find('.form-check-input').is(':checked');
                possibleAnswers.push({
                    text: answerText,
                    correct: isChecked
                });
            });
        }
        else{
            possibleAnswers.push({
                text: $("#questionText").val(),
                correct:$("#sampleAnswer").val()
            });
        }

        var data = {
            questionText: questionText,
            questionType: questionType,
            possibleAnswers: possibleAnswers,
            correctAnswer: correctAnswer,
            moduleId: modulId // Hier können Sie die tatsächliche Modul-ID einfügen
        };
        debugger;
        $.ajax({
            url: 'questions/create',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            success: function(response) {
                alert('Frage erfolgreich gespeichert');
                $('#addQuestionModal').modal('hide');
            },
            error: function(error) {
                console.error('Error saving question:', error);
                alert('Fehler beim Speichern der Frage');
            }
        });
    });

    setTimeout(function() {
        $('#skeleton-loader').hide();
        $('#main-content').show();
    }, 1000); // Simulated loading time

    // Function to handle toggle answers button click
    $(document).on('click', '.toggle-answers', function() {
        var answersDiv = $(this).siblings('.answers');
        if (answersDiv.is(':visible')) {
            answersDiv.hide();
            $(this).html('<i class="fas fa-chevron-down"></i>');
        } else {
            answersDiv.show();
            $(this).html('<i class="fas fa-chevron-up"></i>');
        }
    });
});