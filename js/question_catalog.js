var modulId;

// Funktion zum Laden des Kataloginhalts basierend auf der Modul-ID
function loadCatalogContent(moduleId) {
  debugger;
  // Laden aller Fragen im Katalog
  $.ajax({
    url: "questions/catalog-questions",
    type: "GET",
    data: {
      module_id: moduleId,
    },
    success: function (data) {
      var questions = JSON.parse(data);

      if (!questions.length) {
        // Falls keine Fragen vorhanden sind, eine entsprechende Nachricht anzeigen
        $("#allQuestionsContent").html(
          "<p class='text-center p-4'>Fragenkatalog enthält noch keine Fragen</p>"
        );
      } else if (questions.error) {
        // Falls ein Fehler auftritt, den Fehler anzeigen
        $("#allQuestionsContent").html("<p>" + questions.error + "</p>");
      } else {
        var content = "";
        // Jede Frage durchlaufen und HTML-Inhalt erstellen
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
        // Den erstellten Inhalt in das entsprechende HTML-Element einfügen
        $("#allQuestionsContent").html(content);
      }
    },
    error: function (error) {
      console.error("Fehler beim Laden aller Fragen:", error);
    },
  });

  // Laden der Studentenfragen
  $.ajax({
    url: "questions/student-questions", // Endpunkt für Studentenfragen
    type: "GET",
    data: {
      module_id: moduleId,
    },
    success: function (data) {
      var studentQuestions = JSON.parse(data);
      if (!studentQuestions.length) {
        // Falls keine Studentenfragen vorhanden sind, eine entsprechende Nachricht anzeigen
        $("#studentQuestionsContent").html(
          "<p class='text-center p-4'>Zu diesem Katalog wurden noch keine Studentenfragen hinzugefügt</p>"
        );
      } else if (studentQuestions.error) {
        // Falls ein Fehler auftritt, den Fehler anzeigen
        $("#studentQuestionsContent").html(
          "<p>" + studentQuestions.error + "</p>"
        );
      } else {
        var content = "";
        // Jede Studentenfrage durchlaufen und HTML-Inhalt erstellen
        studentQuestions.forEach(function (question) {
          var possibleAnswers = JSON.parse(question.possible_answers);
          if (question.question_type != "open") {
            // Wenn es keine offene Frage ist, werden die Antworten aufgelistet und die richtige mit einem Haken markiert
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
            // Wenn es eine offene Frage ist, wird die Antwort direkt ausgegeben
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
        // Den erstellten Inhalt in das entsprechende HTML-Element einfügen
        $("#studentQuestionsContent").html(content);
      }
    },
    error: function (error) {
      console.error("Fehler beim Laden der Studentenfragen:", error);
    },
  });
}

// Funktion zum Aktualisieren der Auswahlmöglichkeiten für die richtige Antwort bei Single-Choice-Fragen
function updateSingleChoiceCorrectAnswerOptions() {
  var options = "";
  $("#singleChoiceContainer .form-control").each(function (index) {
    options +=
      '<option value="' + index + '">Antwort ' + (index + 1) + "</option>";
  });
  $("#singleChoiceCorrectAnswer").html(options);
}

// Funktion zum Zurücksetzen des Frage-Modals auf die Standardeinstellungen
function resetModal() {
  $("#questionType").val("single"); // Fragetyp auf "Single Choice" setzen
  $("#questionText").val(""); // Fragentext leeren
  // Container für Single-Choice-Antworten initialisieren
  $("#singleChoiceContainer").html(
    '<div class="input-group mb-2">' +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>"
  );
  updateSingleChoiceCorrectAnswerOptions();
  // Container für Multiple-Choice-Antworten initialisieren
  $("#multipleChoiceContainer").html(
    '<div class="input-group mb-2">' +
      '<div class="input-group-text">' +
      '<input class="form-check-input mt-0" type="checkbox">' +
      "</div>" +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>"
  );
  $("#openQuestionAnswer textarea").val(""); // Textarea für offene Fragen leeren
  $(".answer-section").hide(); // Alle Antwortbereiche ausblenden
  $("#singleChoiceAnswers").show(); // Single-Choice-Bereich anzeigen
}

// Funktion zum Anzeigen eines Toast-Nachrichts
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

  // Toast aus dem DOM entfernen, nachdem es ausgeblendet wurde
  toastElement.addEventListener("hidden.bs.toast", () => {
    toastElement.remove();
  });
}

// Warten, bis das Dokument bereit ist
$(document).ready(function () {
  // Event-Listener für das Anzeigen des Fragenkatalogs
  $(".showQuestionCatalog").on("click", function () {
    modulId = $(this).data("modulid");
    loadCatalogContent(modulId);
  });

  // Event-Listener für die Änderung des Fragetyp-Auswahlfeldes
  $("#questionType").on("change", function () {
    var questionType = $(this).val();
    $(".answer-section").hide(); // Alle Antwortbereiche ausblenden
    if (questionType === "single") {
      $("#singleChoiceAnswers").show(); // Single-Choice-Bereich anzeigen
    } else if (questionType === "multiple") {
      $("#multipleChoiceAnswers").show(); // Multiple-Choice-Bereich anzeigen
    } else if (questionType === "open") {
      $("#openQuestionAnswer").show(); // Offene Fragen-Bereich anzeigen
    }
  });

  // Event-Listener zum Anzeigen des Modals zum Erstellen einer Frage
  $("#createQuestionBtn").on("click", function () {
    // Modal wird bei jedem Aufruf zurückgesetzt
    resetModal();
    $("#questionType").val("single").trigger("change");
    $("#addQuestionModal").modal("show");
  });

  // Event-Listener zum Hinzufügen einer neuen Single-Choice-Antwort
  $("#addSingleChoiceAnswer").on("click", function () {
    var newAnswer =
      '<div class="input-group mb-2">' +
      '<input type="text" class="form-control" placeholder="Antwort">' +
      '<button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>' +
      "</div>";
    $("#singleChoiceContainer").append(newAnswer);
    updateSingleChoiceCorrectAnswerOptions();
  });

  // Event-Listener zum Hinzufügen einer neuen Multiple-Choice-Antwort
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

  // Event-Listener zum Entfernen einer Antwort
  $(document).on("click", ".remove-answer-btn", function () {
    $(this).closest(".input-group").remove();
    updateSingleChoiceCorrectAnswerOptions();
  });

  // Event-Listener zum Speichern einer neuen Frage
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
      moduleId: modulId, // Modul-ID setzen
    };

    // AJAX-Anfrage zum Speichern der Frage
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
        console.error("Fehler beim Erstellen der Frage:", error);
        showToast(
          "Beim Erstellen der Frage ist ein Fehler aufgetreten",
          "danger"
        );
      },
    });
  });

  // Lade-Skelett nach einer gewissen Zeit ausblenden und Hauptinhalt anzeigen
  setTimeout(function () {
    $("#skeleton-loader").hide();
    $("#main-content").show();
  }, 1000); // Simulierte Ladezeit

  // Event-Listener für den Button, um die Antworten einer Frage umzuschalten
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

  // Event-Listener für das Absenden des Formulars zum Erstellen eines Fragenkatalogs
  $("#addQuestionCatalogForm").on("submit", function (event) {
    event.preventDefault(); // Verhindert das Standardformularverhalten

    var formData = new FormData(this);
    
    // AJAX-Anfrage zum Erstellen eines Fragenkatalogs
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
