<?php
//An dieser Stelle die Modelinstanziierung durch eine Controller ersetzen, da dieser mit dem Model kommuniziert und nicht die View mit dem Model
$user = new UserModel($_SESSION['uid']);
$questionCatalog = new QuestionCatalogModel();
?>

<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>

<body class="overflow-hidden">
    <!-- NAVBAR-->
    <?php include("component/navbar.php"); ?>

    <!-- Skeleton Loader -->
    <div id="skeleton-loader" class="container py-5">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="skeleton-loader rounded" style="height: 350px;"></div>
            </div>
            <div class="col-12 col-md-4">
                <div class="skeleton-loader rounded" style="height: 350px;"></div>
            </div>
            <div class="col-12 col-md-4">
                <div class="skeleton-loader rounded" style="height: 350px;"></div>
            </div>
            <div class="col-12 col-md-4">
                <div class="skeleton-loader rounded" style="height: 350px;"></div>
            </div>
            <div class="col-12 col-md-4">
                <div class="skeleton-loader rounded" style="height: 350px;"></div>
            </div>
            <div class="col-12 col-md-4">
                <div class="skeleton-loader rounded" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <div id="main-content" class="container-fluid py-4 overflow-y-auto" style="display: none;">
        <!-- Grid Container -->
        <div id="grid-container">
            <!-- Fragenkatalog hinzufügen Karte -->
            <div class="card add-question-catalog-card bg-light text-center rounded bg-secondary" style="cursor:pointer;">
                <div class="card-body d-flex justify-content-center align-items-center flex-column">
                    <div class="icon-plus mb-3">
                        <i class="bi bi-plus-circle" style="font-size: 4rem; color: #6c757d;"></i>
                    </div>
                    <i class="fa-solid fa-plus fs-1 text-secondary"></i><br>
                    <h5 class="card-title text-secondary">Fragenkatalog hinzufügen</h5>
                    <a href="#" class="stretched-link" data-bs-toggle="modal" data-bs-target="#addQuestionCatalogModal"></a>
                </div>
            </div>
            <?php foreach ($questionCatalog->getAllModules() as $module) { ?>
                <div class="card rounded">
                    <div class="card-img-container rounded-top">
                        <img src="<?= $module['image'] ?>" class="card-img-top" alt="...">
                    </div>
                    <div class="card-body">
                        <h6 class="text-secondary"><?= $module['module_alias'] ?></h6>
                        <h4 class="card-title pb-2"><?= $module['module_name'] ?></h4>
                        <p class="card-text question-count text-secondary">
                            <b>Anzahl der Fragen: <?= $questionCatalog->getQuestionCountByModuleId($module['id']) ?></b>
                        </p>
                        <p class="card-text tutor text-secondary">
                            <b>Tutor:</b> <?= $module['tutor'] ?>
                        </p>
                        <a href="#" class="btn btn-primary mt-4 font-size-14 showQuestionCatalog" data-bs-toggle="modal" data-bs-target="#questionCatalogModal" data-modulid='<?= $module['id'] ?>'>Fragenkatalog anzeigen</a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="toast-container" id="toastContainer"></div>
    </div>
    <?php require_once("component/modal/friendListModal.php"); ?>
    <?php include("component/modal/questionCatalogModal.php"); ?>
    <?php include("component/modal/addQuestionModal.php"); ?>
    <?php include("component/modal/addQuestionCatalogModal.php"); ?>
    <?php require_once("component/quizapp-scripts.php"); ?>

    <script src="js/question_catalog.js"></script>
</body>

</html>

<!-- end document-->