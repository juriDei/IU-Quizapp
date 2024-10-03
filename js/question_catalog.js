var modulId;

function loadCatalogContent(moduleId) {
  debugger;
  // Load all questions
  $.ajax({
    url: "questions/catalog-questions",
    type: "GET",
    data: {
      module_id: moduleId,
    },
    success: function (data) {
      var questions = JSON.parse(data);

      if (!questions.length) {
        $("#allQuestionsContent").html(
          "<p class='text-center p-4'>Fragenkatalog enthält noch keine Fragen</p>"
        );
      } else if (questions.error) {
        $("#allQuestionsContent").html("<p>" + questions.error + "</p>");
      } else {
        var content = "";
        questions.forEach(function (question) {
          var possibleAnswers = JSON.parse(question.possible_answers);
          var answersHtml = possibleAnswers
            .map(
              (answer) => `
                <li class="list-group-item text-black">${answer}</li>
            `
            )
            .join("");
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
        $("#allQuestionsContent").html(content);
      }
    },
    error: function (error) {
      console.error("Error loading all questions:", error);
    },
  });

  // Load student questions
  $.ajax({
    url: "questions/student-questions", // Endpunkt für Studentfragen
    type: "GET",
    data: {
      module_id: moduleId,
    },
    success: function (data) {
      var studentQuestions = JSON.parse(data);
      if (!studentQuestions.length) {
        $("#studentQuestionsContent").html(
          "<p class='text-center p-4'>Zu diesem Katalog, wurden noch keine Studentenfragen hinzugefügt</p>"
        );
      } else if (studentQuestions.error) {
        $("#studentQuestionsContent").html(
          "<p>" + studentQuestions.error + "</p>"
        );
      } else {
        var content = "";
        studentQuestions.forEach(function (question) {
          var possibleAnswers = JSON.parse(question.possible_answers);
          if (question.question_type != "open") {
            //Wenn keine offene Frage, werden die Antworten aufgelistet und die richtige mit Haken markiert
            var answersHtml = possibleAnswers
              .map(
                (answer) => `
                            <li class="list-group-item text-black d-flex justify-content-between align-items-center">
                                ${answer.text}
                                ${
                                  answer.correct
                                    ? '<i class="fas fa-check text-success flex-start"></i>'
                                    : ""
                                }
                            </li>
                        `
              )
              .join("");
          } else {
            //Wenn offene Frage, dann ist wird direkt die Antwort ausgegeben
            var answersHtml = possibleAnswers
              .map(
                (answer) => `
                            <li class="list-group-item text-black d-flex justify-content-between align-items-center">
                                ${answer.correct}
                            </li>
                        `
              )
              .join("");
          }
          content += `
                    <div class="question p-3 mt-2 mb-2 border rounded shadow-sm">
                        <p class='d-inline'>${question.question_text}</p>
                        <button class="toggle-answers btn btn-link text-decoration-none float-end"><i class="fas fa-chevron-down"></i></button>
                        <div class="answers" style="display: none;">
                            <ul class="list-group mt-2 mb-2 w-100">${answersHtml}</ul>
                            <button class="btn btn-link text-decoration-none text-success fs-6" title='Upvote'>
                                <span class="badge p-2 text-bg-success"><i class="fas fa-thumbs-up"></i> Gefällt mir</span>
                            </button>
                            <button class="btn btn-link text-decoration-none text-danger fs-6" title='Downvote'>
                                <span class="badge p-2 text-bg-danger"><i class="fas fa-thumbs-down"></i>  Gefällt mir nicht</span>
                            </button>
                        </div>
                    </div>
                 `;
        });
        $("#studentQuestionsContent").html(content);
      }
    },
    error: function (error) {
      console.error("Error loading student questions:", error);
    },
  });
}

function updateSingleChoiceCorrectAnswerOptions() {
  var options = "";
  $("#singleChoiceContainer .form-control").each(function (index) {
    options +=
      '<option value="' + index + '">Antwort ' + (index + 1) + "</option>";
  });
  $("#singleChoiceCorrectAnswer").html(options);
}

function resetModal() {
  $("#questionType").val("single");
  $("#questionText").val("");
  $("#singleChoiceContainer").html(
    '<div class="input-group mb-2">' +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>"
  );
  updateSingleChoiceCorrectAnswerOptions();
  $("#multipleChoiceContainer").html(
    '<div class="input-group mb-2">' +
      '<div class="input-group-text">' +
      '<input class="form-check-input mt-0" type="checkbox">' +
      "</div>" +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>"
  );
  $("#openQuestionAnswer textarea").val("");
  $(".answer-section").hide();
  $("#singleChoiceAnswers").show();
}

function showToast(message, type) {
  const toastHTML = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
  const toastContainer = document.getElementById("toastContainer");
  const toastElement = document.createElement("div");
  toastElement.innerHTML = toastHTML;
  toastContainer.appendChild(toastElement);

  const toast = new bootstrap.Toast(toastElement.querySelector(".toast"));
  toast.show();

  // Remove toast from DOM after it hides
  toastElement.addEventListener("hidden.bs.toast", () => {
    toastElement.remove();
  });
}

$(document).ready(function () {
  $(".showQuestionCatalog").on("click", function () {
    modulId = $(this).data("modulid");
    loadCatalogContent(modulId);
  });

  $("#questionType").on("change", function () {
    var questionType = $(this).val();
    $(".answer-section").hide();
    if (questionType === "single") {
      $("#singleChoiceAnswers").show();
    } else if (questionType === "multiple") {
      $("#multipleChoiceAnswers").show();
    } else if (questionType === "open") {
      $("#openQuestionAnswer").show();
    }
  });

  // Show the add question modal
  $("#createQuestionBtn").on("click", function () {
    //Hier wird das Modal bei jedem Aufruf zurückgesetzt
    resetModal();
    $("#questionType").val("single").trigger("change");
    $("#addQuestionModal").modal("show");
  });

  // Add Single Choice Answer
  $("#addSingleChoiceAnswer").on("click", function () {
    var newAnswer =
      '<div class="input-group mb-2">' +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>";
    $("#singleChoiceContainer").append(newAnswer);
    updateSingleChoiceCorrectAnswerOptions();
  });

  // Add Multiple Choice Answer
  $("#addMultipleChoiceAnswer").on("click", function () {
    var newAnswer =
      '<div class="input-group mb-2">' +
      '<div class="input-group-text">' +
      '<input class="form-check-input mt-0" type="checkbox">' +
      "</div>" +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>";
    $("#multipleChoiceContainer").append(newAnswer);
  });

  // Remove answer
  $(document).on("click", ".remove-answer-btn", function () {
    $(this).closest(".input-group").remove();
    updateSingleChoiceCorrectAnswerOptions();
  });

  $("#saveQuestionBtn").on("click", function () {
    var questionType = $("#questionType").val();
    var questionText = $("#questionText").val();
    var possibleAnswers = [];
    var correctAnswer = "";

    if (questionType === "single") {
      $("#singleChoiceContainer .form-control").each(function (idx, val) {
        var correctAnswer =
          idx == $("#singleChoiceCorrectAnswer").val() ? true : false;
        possibleAnswers.push({
          text: $(this).val(),
          correct: correctAnswer,
        });
      });
    } else if (questionType === "multiple") {
      $("#multipleChoiceContainer .input-group").each(function () {
        var answerText = $(this).find(".form-control").val();
        var isChecked = $(this).find(".form-check-input").is(":checked");
        possibleAnswers.push({
          text: answerText,
          correct: isChecked,
        });
      });
    } else {
      possibleAnswers.push({
        text: $("#questionText").val(),
        correct: $("#sampleAnswer").val(),
      });
    }

    var data = {
      questionText: questionText,
      questionType: questionType,
      possibleAnswers: possibleAnswers,
      correctAnswer: correctAnswer,
      moduleId: modulId, // Hier können Sie die tatsächliche Modul-ID einfügen
    };

    $.ajax({
      url: "questions/create",
      type: "POST",
      data: JSON.stringify(data),
      contentType: "application/json; charset=utf-8",
      success: function (response) {
        $("#addQuestionModal").modal("hide");
        loadCatalogContent(modulId);
        showToast("Frage wurde erfolgreich erstellt", "success");
      },
      error: function (error) {
        console.error("Error saving question:", error);
        showToast(
          "Beim erstellen der Frage ist ein Fehler aufgetreten",
          "danger"
        );
      },
    });
  });

  setTimeout(function () {
    $("#skeleton-loader").hide();
    $("#main-content").show();
  }, 1000); // Simulated loading time

  // Function to handle toggle answers button click
  $(document).on("click", ".toggle-answers", function () {
    var answersDiv = $(this).siblings(".answers");
    if (answersDiv.is(":visible")) {
      answersDiv.hide();
      $(this).html('<i class="fas fa-chevron-down"></i>');
    } else {
      answersDiv.show();
      $(this).html('<i class="fas fa-chevron-up"></i>');
    }
  });

  $("#addQuestionCatalogForm").on("submit", function (event) {
    event.preventDefault(); // Verhindert das Standardformularverhalten

    var formData = new FormData(this);
    
    $.ajax({
      url: "questioncatalog/create",
      type: "POST",
      data: formData,
      contentType: false, // Notwendig für FormData
      processData: false, // Notwendig für FormData
      success: function (response) {
        // Erfolgsmeldung anzeigen und Modal schließen
        alert("Fragenkatalog erfolgreich erstellt.");
        $("#addQuestionCatalogModal").modal("hide");
        // Optional: Formular zurücksetzen
        $("#addQuestionCatalogForm")[0].reset();
        // Optional: Aktualisieren Sie die Katalogliste auf der Seite
      },
      error: function (xhr, status, error) {
        // Fehlermeldung anzeigen
        alert(
          "Fehler beim Erstellen des Fragenkatalogs: " +
            xhr.responseJSON.message
        );
      },
    });
  });
});
