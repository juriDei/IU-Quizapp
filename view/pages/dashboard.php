<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>

<body class="overflow-hidden">
    <!-- NAVBAR-->
    <?php include("component/navbar.php"); ?>
    <div class="page-wrapper vh-100 p-5 d-flex flex-row mb-3">
        <div class="container-sm bg-white h-50 w-25 rounded mb-5 shadow-lg p-3 d-flex flex-column align-items-left justify-content-between">
            <div>
                <p class="fs-5 p-2 text-black text-center fw-semibold">Dein aktueller Fortschritt</p><br />
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
        <div class="container-sm bg-white h-50 w-25 rounded mb-5 p-2 shadow-lg p-3" id="recent-games-wrapper">
            <p class="fs-5 p-2 text-black text-center fw-semibold">Zuletzt durchgeführte Quizspiele</p>
            <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
                <div class="progress-circle entry-status" data-progress="85">
                    <div class="progress-value">85%</div>
                </div>
                <div class="entry-details">
                    <div class="topic">BWL I</div>
                    <div class="details">
                        <span>Einzelspieler</span>
                        <span>Bestanden</span>
                        <span>15 min</span>
                    </div>
                </div>
            </div>
            <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
                <div class="progress-circle entry-status" data-progress="60">
                    <div class="progress-value">60%</div>
                </div>
                <div class="entry-details">
                    <div class="topic">BWL II</div>
                    <div class="details">
                        <span>Mehrspieler</span>
                        <span>Bestanden</span>
                        <span>10 min</span>
                    </div>
                </div>
            </div>
            <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
                <div class="progress-circle entry-status" data-progress="50">
                    <div class="progress-value">50%</div>
                </div>
                <div class="entry-details">
                    <div class="topic">Digitale Business-Modelle</div>
                    <div class="details">
                        <span>Mehrspieler</span>
                        <span>Durchgefallen</span>
                        <span>5 min</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-sm bg-white h-50 w-25 rounded mb-5 p-2 shadow-lg p-3" id="recent-player-wrapper">
            <p class="fs-5 p-2 text-black text-center fw-semibold">kürzliche Quizpartner</p>
            <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
                <img src="./images/quizapp_logo.png" class="avatar" alt="Avatar">
                <div class="entry-details">
                    <div class="name">Max Mustermann</div>
                    <div class="email">max.mustermann@iu.org</div>
                </div>
                <div class="entry-icons">
                    <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
                </div>
            </div>
            <div class="entry shadow-sm p-3 mb-2 bg-white rounded border border-1">
                <img src="./images/quizapp_logo.png" class="avatar" alt="Avatar">
                <div class="entry-details">
                    <div class="name">Anna Müller</div>
                    <div class="email">anna.mueller@iubh-fernstudium.de</div>
                </div>
                <div class="entry-icons">
                    <i class="fa-solid fa-paper-plane text-primary" title="erneut einladen"></i>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("component/modal/friendListModal.php"); ?>
    <script src="js/dashboard.js"></script>
</body>

</html>