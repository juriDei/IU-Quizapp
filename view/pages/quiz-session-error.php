<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>
<body class="overflow-hidden">
    <!-- NAVBAR -->
    <?php include("component/navbar.php"); ?>
    <div id="main-content" class="container-fluid py-4 overflow-y-auto">
        <div class="container mt-5">
            <div class="card shadow-lg p-4">
                <h3 class="mb-4 border-bottom pb-4 text-danger">Fehler</h3>
                <p class="lead">Quizsession nicht gefunden oder ungültig.</p>
                <p>Bitte überprüfen Sie die eingegebene URL oder wenden Sie sich an den Support, falls das Problem weiterhin besteht.</p>
                <div class="text-end">
                    <a href="/quizapp/dashboard" class="btn btn-primary mt-3">Zurück zum Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("component/modal/friendListModal.php"); ?>
</body>
</html>