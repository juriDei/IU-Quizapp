<?php
    $questionCatalog = new QuestionCatalogModel();
?>

<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>
<body class="overflow-hidden" id="dashboard-body">
    <!-- NAVBAR-->
    <?php include("component/navbar.php"); ?>
    <div id="main-content" class="container-fluid py-4 overflow-y-auto">
        <div class="page-wrapper vh-100 p-5 d-flex flex-wrap gap-3 mb-3">
            <!-- Fortschritt Container -->
            <div class="container-sm bg-white rounded shadow-lg p-3 d-flex flex-column align-items-left justify-content-between col-12 col-md-6 col-lg-4 mb-3 flex-grow-1">
                <div>
                    <p class="fs-5 p-2 text-black text-center fw-semibold">Dein aktueller Fortschritt</p>
                    <div class="d-flex flex-column align-items-left">
                        <div class="progress-container">
                            <div class="progress-circle" id="progress-circle-questions">
                                <div class="progress-value" id="progress-value-questions">1 / 6</div>
                            </div>
                            <div class="progress-label">Abgeschlossene Fragenkataloge</div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-circle" id="progress-circle-quizzes">
                                <div class="progress-value" id="progress-value-quizzes">99%</div>
                            </div>
                            <div class="progress-label">Bestandene Quizspiele</div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-value" id="completed-quizzes">0</div>
                            <div class="progress-label">Absolvierte Quizspiele</div>
                        </div>
                    </div>
                </div>
                <div id="average-grade" class="average-grade p-3">
                    Durchschn. Gesamtnote: 1.0
                </div>
            </div>

            <!-- Container für die Tabs -->
            <div class="container-sm bg-white rounded p-2 shadow-lg p-3 col-12 col-md-6 col-lg-4 mb-3 flex-grow-1" id="recent-games-wrapper">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs justify-content-center w-100" id="quizSessionsTab" role="tablist">
                    <li class="nav-item w-50" role="presentation">
                        <button class="nav-link active w-100 shadow-sm" id="open-sessions-tab" data-bs-toggle="tab" data-bs-target="#open-sessions" type="button" role="tab" aria-controls="open-sessions" aria-selected="true">Offene Quizsessions</button>
                    </li>
                    <li class="nav-item w-50" role="presentation">
                        <button class="nav-link w-100 shadow-sm" id="completed-sessions-tab" data-bs-toggle="tab" data-bs-target="#completed-sessions" type="button" role="tab" aria-controls="completed-sessions" aria-selected="false">Abgeschlossene Quizsessions</button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content p-2" id="quizSessionsTabContent">
                    <!-- Offene Quizsessions -->
                    <div class="tab-pane fade show active" id="open-sessions" role="tabpanel" aria-labelledby="open-sessions-tab">
                        <?php if(!empty($user->getOpenQuizSessions())) {
                                    foreach($user->getOpenQuizSessions() as $openQuizSession){ 
                                        $selectedCatalogs = json_decode($openQuizSession['question_catalogs']);
                                    ?>
                                        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1 d-flex">
                                            <!-- First column: Game ID -->
                                            <div class="col-3 d-flex align-items-center justify-content-center p-2 bg-light text-dark rounded" style="min-width: 100px;">
                                                <div><?= $openQuizSession['game_id'] ?></div>
                                            </div>

                                            <!-- Second column: Module name, mode, and time -->
                                            <div class="col-8 d-flex flex-column justify-content-center px-3">
                                                <div class="topic font-weight-bold d-flex align-items-center">
                                                    <!-- Pfeil links (optional) -->
                                                    <i class="fas fa-chevron-left me-2 scroll-arrow" style="cursor: pointer;"></i>
                                                    
                                                    <!-- Scrollbarer Bereich für die Modulnamen -->
                                                    <div class="module-names-container d-flex overflow-auto" style="white-space: nowrap;">
                                                        <?php
                                                        // Schleife durch alle ausgewählten Kataloge, um die Modulnamen anzuzeigen
                                                        foreach ($selectedCatalogs as $catalogId) {
                                                            $moduleName = $questionCatalog->getModuleById($catalogId)['module_name'];
                                                            echo "<div class='module-name mx-2 badge text-bg-secondary p-2'>" . htmlspecialchars($moduleName) . "</div>";
                                                        }
                                                        ?>
                                                    </div>

                                                    <!-- Pfeil rechts (optional) -->
                                                    <i class="fas fa-chevron-right ms-2 scroll-arrow" style="cursor: pointer;"></i>
                                                </div>
                                                <div class="details text-muted">
                                                    <span><?= ucfirst($openQuizSession['mode']) ?></span>
                                                    <span class="ms-2"><?=  $openQuizSession['time_limit'] ?> Min. verbl.</span>
                                                </div>
                                            </div>

                                            <!-- Third column: Link and button -->
                                            <div class="col-1 d-flex justify-content-end align-items-center">
                                                <!-- Link to quiz session -->
                                                <a href="quizsession?session_id=<?= $openQuizSession['game_id'] ?>" class="btn btn-primary btn-sm me-2" title="Quizsession fortsetzen">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                                <!-- Button to cancel quiz session -->
                                                <button class="btn btn-danger btn-sm cancelQuizsession" data-quizsessionid="<?= $openQuizSession['game_id'] ?>" title="Quizsession abbrechen">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php 
                                    } 
                        } else { ?>
                            <h5 class='text-center mt-5'>Keine offenen Quizsessions vorhanden</h5>
                        <?php }?>
                    </div>
                    <!-- Abgeschlossene Quizsessions mit Scrollbar -->
                    <div class="tab-pane fade" id="completed-sessions" role="tabpanel" aria-labelledby="completed-sessions-tab" style="max-height: 300px; overflow-y: auto;">
                        <?php
                        $completedSessions = $user->getCompletedQuizSessions();
                        if (!empty($completedSessions)) {
                            foreach ($completedSessions as $completedQuizSession) {
                                $selectedCatalogs = json_decode($completedQuizSession['question_catalogs']);
                                $module = $questionCatalog->getModuleById($selectedCatalogs[0]);

                                // Instanziieren des QuizSessionModel mit der Quizsession-ID
                                $quizSessionModel = new QuizSessionModel($completedQuizSession['id'], false);

                                // Fortschritt für den Benutzer berechnen
                                $progress = $quizSessionModel->calculateProgress($user->getUserId());
                        ?>
                        <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1 d-flex">
                            <!-- Left side: Progress circle or cancelled icon -->
                            <div class="col-2 d-flex align-items-center">
                                <?php if ($completedQuizSession['status'] === 'cancelled'): ?>
                                    <!-- Anzeige eines Kreuzes bei abgebrochenem Quiz -->
                                    <div class="text-left w-100 ms-2">
                                        <i class="fas fa-times fa-3x text-secondary" title="Abgebrochen"></i>
                                    </div>
                                <?php else: ?>
                                    <!-- Anzeige des Fortschrittsbalkens, wenn nicht abgebrochen -->
                                    <div class="progress-circle entry-status" data-progress="<?= $progress ?>">
                                        <div class="progress-value"><?= $progress ?>%</div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Middle: Module name, mode, status, and time limit -->
                            <div class="col-8 d-flex flex-column justify-content-start">
                                <div class="topic font-weight-bold">
                                    <?= $module['module_name'] ?>
                                    <span class='text-secondary ms-2' style='font-size:14px;'><?= date('d.m.Y H:m', strtotime($completedQuizSession['updated_at'])) ?></span>
                                </div>
                                <div class="details text-muted">
                                    <span><?= ucfirst($completedQuizSession['mode']) ?></span>
                                    <span class="ms-5">
                                        <?php
                                        if ($completedQuizSession['status'] == 'completed' && $progress >= 50) {
                                            echo '<span class="badge text-bg-success">Bestanden</span>';
                                        } else if ($completedQuizSession['status'] == 'completed' && $progress < 50) {
                                            echo '<span class="badge text-bg-danger">Durchgefallen</span>';
                                        } elseif ($completedQuizSession['status'] == 'cancelled') {
                                            echo '<span class="badge text-bg-secondary">Abgebrochen</span>';
                                        } else {
                                            echo 'Unbekannter Status';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Right side: Icon to view results -->
                            <div class="col-2 d-flex justify-content-end align-items-center">
                                <a href="quizsessionresult?session_id=<?= $completedQuizSession['game_id'] ?>" class="btn btn-link">
                                    <i class="fas fa-chart-line fa-1x" style='font-size:24px;' title='Zur Auswertung'></i>
                                </a>
                            </div>
                        </div>
                        <?php }
                        } else {
                            echo "<p>Keine abgeschlossenen Quizsessions gefunden.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Kürzliche Quizpartner Container -->
            <div class="container-sm bg-white rounded p-2 shadow-lg p-3 col-12 col-md-6 col-lg-4 mb-3 flex-grow-1" id="recent-player-wrapper">
                <p class="fs-5 p-2 text-black text-center fw-semibold">kürzliche Quizpartner</p>
                <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1 d-flex align-items-center">
                    <img src="./images/iu_quizapp_logo.png" class="avatar me-2" alt="Avatar">
                    <div class="entry-details">
                        <div class="name">Max Mustermann</div>
                        <div class="email">max.mustermann@iu.org</div>
                    </div>
                    <div class="entry-icons ms-auto">
                        <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
                    </div>
                </div>
                <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1 d-flex align-items-center">
                    <img src="./images/iu_quizapp_logo.png" class="avatar me-2" alt="Avatar">
                    <div class="entry-details">
                        <div class="name">Anna Müller</div>
                        <div class="email">anna.mueller@iubh-fernstudium.de</div>
                    </div>
                    <div class="entry-icons ms-auto">
                        <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
                    </div>
                </div>
                <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1 d-flex align-items-center">
                    <img src="./images/iu_quizapp_logo.png" class="avatar me-2" alt="Avatar">
                    <div class="entry-details">
                        <div class="name">Moritz Quizer</div>
                        <div class="email">moritz.quizer@iubh-fernstudium.de</div>
                    </div>
                    <div class="entry-icons ms-auto">
                        <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
                    </div>
                </div>
            </div>
            <!-- Quiz Start Container -->
            <div class="container-sm bg-white rounded p-2 shadow-lg col-12 col-md-6 col-lg-4 mb-3 flex-grow-1" id="quiz-start-wrapper" style="max-height: 80vh; overflow-y: auto;">
                <form id="quiz-form" onsubmit="return false;"> <!-- Verhindert Standard-Submit-Verhalten -->
                    <div class="bg-white p-2">
                        <p class="fs-5 p-2 text-black text-center fw-semibold">Neues Quizspiel starten</p>
                    </div>

                    <!-- Modusauswahl -->
                    <div id="mode-selection" class="d-flex flex-column align-items-center justify-content-center vh-50 mt-5">
                        <button type="button" class="btn btn-primary mb-3 w-75 p-3" id="btn-singleplayer" style="margin: 0 auto;">
                            <i class="fa-solid fa-user fs-5 pe-3"></i> Einzelspieler
                        </button>
                        <button type="button" class="btn btn-secondary w-75 p-3" id="btn-multiplayer" style="margin: 0 auto;">
                            <i class="fa-solid fa-users fs-5 pe-3"></i> Mehrspieler
                        </button>
                    </div>
                    <!-- Verstecktes Input-Feld für den Modus -->
                    <input type="hidden" id="mode" name="mode" value="">

                    <!-- Lobbysuche-Loader -->
                    <div id="lobby-search-loader" class="d-none text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 fw-bold">Lobbysuche läuft...</p>
                    </div>

                    <!-- Einzelspieler-Einstellungen -->
                    <div id="singleplayer-settings" class="d-none flex-grow-1 overflow-auto p-2">
                        <button type="button" class="btn btn-link text-decoration-none mb-3" id="back-to-mode-selection">Zurück zur Modusauswahl</button>
                        <div class="d-flex flex-column align-items-center overflow-y-auto ps-3 pe-3" style="height: 200px; overflow-y: auto;">
                            <!-- Fragenkatalog Auswahl -->
                            <div class="mb-3 w-100">
                                <label for="question-catalog" class="form-label fs-6">Fragenkatalog wählen:</label>
                                <select class="select2 form-select fs-6" id="question-catalog" name="question_catalogs[]" multiple aria-label="Fragenkatalog Auswahl" style="width: 100%;">
                                    <?php foreach ($questionCatalog->getAllModules() as $module) { ?>
                                        <option value="<?= $module['id'] ?>"><?= $module['module_name'] ?> (<?= $module['module_alias'] ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- Fragenanzahl -->
                            <div class="mb-3 w-100">
                                <label for="question-count" class="form-label fs-6">Fragenanzahl:</label>
                                <input type="number" class="form-control" id="question-count" name="question_count" min="1" max="50" value="10">
                            </div>
                            <!-- Zeitlimit -->
                            <div class="mb-3 w-100">
                                <label for="time-limit" class="form-label fs-6">Zeitlimit (Minuten):</label>
                                <select class="form-select" id="time-limit" name="time_limit" aria-label="Zeitlimit Auswahl">
                                    <option value="5">5 Minuten</option>
                                    <option value="10">10 Minuten</option>
                                    <option value="15">15 Minuten</option>
                                    <option value="25">25 Minuten</option>
                                </select>
                            </div>
                            <!-- Fragetypen Auswahl -->
                            <div class="mb-3 w-100">
                                <label for="question-types" class="form-label fs-6">Fragetypen wählen:</label>
                                <select class="select2 form-select" id="question-types" name="question_types[]" multiple aria-label="Fragetypen Auswahl" style="width: 100%;">
                                    <option value="single">Multiple Choice (Einfachantworten)</option>
                                    <option value="multiple">Multiple Choice (Mehrfachantworten)</option>
                                    <option value="open">Offene Fragen</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100 mt-3" id="start-singleplayer-quiz-btn">Quiz starten</button>
                    </div>

                    <!-- Mehrspieler-Einstellungen -->
                    <div id="multiplayer-settings" class="d-none flex-grow-1 overflow-auto p-2">
                        <button type="button" class="btn btn-link text-decoration-none mb-3" id="back-to-mode-selection-multiplayer">Zurück zur Modusauswahl</button>
                        <div class="d-flex flex-column align-items-center overflow-y-auto ps-3 pe-3" style="height: 200px; overflow-y: auto;">
                            <!-- Teilnehmeranzahl Auswahl -->
                            <div class="mb-3 w-100">
                                <label for="participant-count" class="form-label fs-6">Anzahl der Teilnehmer:</label>
                                <input type="number" class="form-control" id="participant-count" name="participant_count" min="2" max="10" value="2">
                            </div>
                            <!-- Fragenkatalog Auswahl -->
                            <div class="mb-3 w-100">
                                <label for="question-catalog-multiplayer" class="form-label fs-6">Fragenkatalog wählen:</label>
                                <select class="select2 form-select fs-6" id="question-catalog-multiplayer" name="question_catalogs[]" multiple aria-label="Fragenkatalog Auswahl" style="width: 100%;">
                                    <?php foreach ($questionCatalog->getAllModules() as $module) { ?>
                                        <option value="<?= $module['id'] ?>"><?= $module['module_name'] ?> (<?= $module['module_alias'] ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!-- Fragenanzahl -->
                            <div class="mb-3 w-100">
                                <label for="question-count-multiplayer" class="form-label fs-6">Fragenanzahl:</label>
                                <input type="number" class="form-control" id="question-count-multiplayer" name="question_count" min="1" max="50" value="10">
                            </div>
                            <!-- Zeitlimit -->
                            <div class="mb-3 w-100">
                                <label for="time-limit-multiplayer" class="form-label fs-6">Zeitlimit (Minuten):</label>
                                <select class="form-select" id="time-limit-multiplayer" name="time_limit" aria-label="Zeitlimit Auswahl">
                                    <option value="5">5 Minuten</option>
                                    <option value="10">10 Minuten</option>
                                    <option value="15">15 Minuten</option>
                                    <option value="25">25 Minuten</option>
                                </select>
                            </div>
                            <!-- Fragetypen Auswahl -->
                            <div class="mb-3 w-100">
                                <label for="question-types-multiplayer" class="form-label fs-6">Fragetypen wählen:</label>
                                <select class="select2 form-select" id="question-types-multiplayer" name="question_types[]" multiple aria-label="Fragetypen Auswahl" style="width: 100%;">
                                    <option value="single">Multiple Choice (Einfachantworten)</option>
                                    <option value="multiple">Multiple Choice (Mehrfachantworten)</option>
                                    <option value="open">Offene Fragen</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100 mt-3" id="start-multiplayer-quiz-btn">Quiz starten</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once("component/quizapp-scripts.php"); ?>
    <?php require_once("component/modal/friendListModal.php"); ?>
    <!-- Select2 CSS und JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/quizsession.js"></script>
    </div>
</body>

</html>