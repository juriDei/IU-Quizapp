    <!-- Modal -->
    <div class="modal fade" id="questionCatalogModal" tabindex="-1" aria-labelledby="questionCatalogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionCatalogModalLabel">Fragenkatalog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="questionTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active fw-semibold" id="all-questions-tab" data-bs-toggle="tab" href="#all-questions" role="tab" aria-controls="all-questions" aria-selected="true">Alle Fragen</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-semibold" id="student-questions-tab" data-bs-toggle="tab" href="#student-questions" role="tab" aria-controls="student-questions" aria-selected="false">Studentenfragen</a>
                        </li>
                    </ul>
                    <!-- Tabs Content -->
                    <div class="tab-content" id="questionTabContent">
                        <div class="tab-pane fade show active" id="all-questions" role="tabpanel" aria-labelledby="all-questions-tab">
                            <div id="allQuestionsContent">
                                <!-- All questions will be loaded here -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="student-questions" role="tabpanel" aria-labelledby="student-questions-tab">
                            <div id="studentQuestionsContent">
                                <!-- Student questions will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="createQuestionBtn" data-module-id="<?= $module['id'] ?>">Frage hinzufügen</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>