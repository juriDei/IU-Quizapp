<?php
$user = new UserModel($_SESSION['uid']);
?>
<style>
    .progress-circle {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .progress-circle::before {
        content: "";
        width: 70px;
        height: 70px;
        background: white;
        border-radius: 50%;
        position: absolute;
    }

    #recent-games-wrapper .progress-circle{
        width: 60px;
        height: 60px;
        font-size: 14px;
    }

    #recent-games-wrapper .progress-circle::before{
        width: 45px;
        height: 45px;
    }

    #recent-games-wrapper .progress-value{
        font-size: 0.9rem !important;
    }

    .progress-value {
        position: relative;
        font-size: 1rem;
        font-weight: bold;
        color: #0d6efd;
        text-align: center;
    }

    .progress-container {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .progress-label {
        margin-left: 10px;
        font-size: 1rem;
        font-weight: bold;
        color: #000;
        width: 180px;
        /* Feste Breite für die Labels */
    }

    .average-grade {
        width: 100%;
        background-color: #046F47;
        text-align: center;
        padding: 10px;
        font-size: 1.2rem;
        font-weight: bold;
        color: #000;
        border-radius: 5px;
    }

    .grade-green {
        background-color: #046F47 !important;
        color: white;
    }

    .grade-yellow {
        background-color: yellow !important;
        color: black;
    }

    .grade-orange {
        background-color: orange !important;
        color: black;
    }

    .grade-red {
        background-color: red !important;
        color: white;
    }

    #completed-quizzes {
        margin-left: 24px;
        font-size: 1.8rem;
        margin-right: 32px;
    }

    .entry {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
    }

    .entry-status {
        margin-right: 10px;
        color: #fff;
        padding: 5px 10px;
        border-radius: 50%;
        background: conic-gradient(rgb(13, 110, 253) 57.6deg, rgb(233, 236, 239) 0deg);
    }

    .entry-status.passed {
        color: #28a745;
        /* Green for passed */
    }

    .entry-status.passed~.entry-details {
        color: #28a745;
    }

    .entry-status.failed {
        color: #dc3545;
        /* Red for failed */
    }

    .entry-status.failed~.entry-details {
        color: #dc3545;
    }

    .entry-status {
        font-size: 30px;
    }

    .entry-details {
        flex-grow: 1;
    }

    .entry-details .topic {
        font-weight: bold;
    }

    .entry-details .details {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        /* Smaller font size */
    }

    .entry-details .details span {
        margin-right: 25px;
        /* Reduced space between details */
    }

    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .entry-details {
        flex-grow: 1;
    }

    .entry-details .name {
        font-weight: bold;
        margin-bottom: 2px;
        /* Reduced space between name and email */
    }

    .entry-details .email {
        font-size: 0.875rem;
        /* Smaller font size */
        margin-top: 2px;
        /* Reduced space between name and email */
    }

    #recent-player-wrapper .entry-icons i {
        font-size: 20px;
        cursor: pointer;
    }

    .floating-friendlist {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .friendlist-modal {
        position: fixed;
        bottom: -100%;
        right: 5px;
        width: 425px;
        max-height: 400px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        overflow-y: auto;
        transition: bottom 0.3s ease-in-out;
    }

    .friendlist-modal.active {
        bottom: 0;
    }

    .friendlist-modal-header {
        background-color: #0d6efd;
        color: white;
        padding: 15px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .friendlist-modal-body {
        padding: 10px;
    }

    .search-bar {
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>
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
    <?php require_once("component/quizapp-scripts.php"); ?>
    <script src="js/dashboard.js"></script>
</body>

</html>