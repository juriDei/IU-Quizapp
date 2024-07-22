<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addQuestionModalLabel">Frage hinzufügen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Question Type Selection -->
        <div class="mb-3">
          <label for="questionType" class="form-label">Fragetyp wählen</label>
          <select class="form-select" id="questionType" aria-label="Fragetyp wählen">
            <option value="single">Single Choice</option>
            <option value="multiple">Multiple Choice</option>
            <option value="open">Offene Frage</option>
          </select>
        </div>
        <!-- Question Text -->
        <div class="mb-3">
          <label for="questionText" class="form-label">Frage</label>
          <textarea class="form-control rounded" id="questionText" rows="3"></textarea>
        </div>
        <!-- Single Choice Answers -->
        <div id="singleChoiceAnswers" class="answer-section">
          <label class="form-label">Antwortmöglichkeiten</label>
          <div class="mb-2" id="singleChoiceContainer">
            <div class="input-group mb-2">
              <input type="text" class="form-control" placeholder="Antwort">
              <button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>
            </div>
          </div><br/>
          <button class="btn btn-outline-primary mb-3" type="button" id="addSingleChoiceAnswer"><i class="fa-solid fa-plus"></i> Antwortmöglichkeit hinzufügen</button><br/>
          <label class="form-label">Richtige Antwort</label>
          <select class="form-select rounded" id="singleChoiceCorrectAnswer">
            <!-- Options werden dynamisch hinzugefügt -->
          </select>
        </div>
        <!-- Multiple Choice Answers -->
        <div id="multipleChoiceAnswers" class="answer-section" style="display: none;">
          <label class="form-label">Antwortmöglichkeiten</label>
          <div class="mb-2" id="multipleChoiceContainer">
            <div class="input-group mb-2">
              <div class="input-group-text">
                <input class="form-check-input mt-0" type="checkbox">
              </div>
              <input type="text" class="form-control rounded" placeholder="Antwort">
              <button class="btn btn-outline-danger remove-answer-btn" type="button"><i class="fa-solid fa-xmark" title="Frage löschen"></i></button>
            </div>
          </div><br/>
          <button class="btn btn-outline-primary mb-3" type="button" id="addMultipleChoiceAnswer"><i class="fa-solid fa-plus"></i> Antwortmöglichkeit hinzufügen</button><br/>
        </div>
        <!-- Open Question -->
        <div id="openQuestionAnswer" class="answer-section" style="display: none;">
          <label class="form-label">Musterantwort</label>
          <textarea class="form-control rounded" id="sampleAnswer" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveQuestionBtn">Frage speichern</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>