<?php
//An dieser Stelle die Modelinstanziierung durch eine Controller ersetzen, da dieser mit dem Model kommuniziert und nicht die View mit dem Model
$user = new UserModel($_SESSION['uid']);
$questionCatalog = new QuestionCatalogModel();
?>
<style>
    .link-hover:hover {
        background-color: #f0f0f0;
        /* Adjust the color as needed */
        border-radius: 5px;
        /* Optional: to add rounded corners */
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
    }

    .search-form .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .search-form .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .skeleton-loader {
        display: inline-block;
        height: 20px;
        width: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    .card-img-container {
        width: 100%;
        height: 225px;
        overflow: hidden;
        position: relative;
    }

    .card-img-container img {
        width: 100%;
        height: auto;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-text {
        font-size: 14px;
    }

    #main-content {
        height: calc(100vh - 150px);
        /* Adjust this value as needed */
        overflow: auto;
        padding: 275px;
    }

    .font-size-14 {
        font-size: 14px !important;
    }
</style>
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

    <!-- Main Content -->
    <div id="main-content" class="container-fluid py-4 overflow-y-auto" style="display: none;">
        <div class="row g-">
            <?php foreach ($questionCatalog->getAllModules() as $module) { ?>
                <div class="col-12 col-md-4">
                    <div class="card rounded">
                        <div class="card-img-container rounded-top">
                            <img src="<?= $module['image'] ?>" class="card-img-top" alt="...">
                        </div>
                        <div class="card-body">
                            <h6 class="text-secondary"><?= $module['module_alias'] ?></h6>
                            <h4 class="card-title pb-2"><?= $module['module_name'] ?></h4>
                            <p class="card-text question-count text-secondary">
                                <b>Anzahl der Fragen: <?= $questionCatalog->getQuestionCountByModuleId($module['id'])?></b>
                            </p>
                            <p class="card-text tutor text-secondary">
                                <b>Tutor:</b> <?= $module['tutor'] ?>
                            </p>
                            <a href="#" class="btn btn-primary mt-4 font-size-14 showQuestionCatalog" data-bs-toggle="modal" data-bs-target="#questionCatalogModal" data-modulid='<?= $module['id'] ?>'>Fragenkatalog anzeigen</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="toast-container" id="toastContainer"></div>
    </div>
    <?php require_once("component/modal/friendListModal.php"); ?>
    <?php include("component/modal/questionCatalogModal.php"); ?>
    <?php include("component/modal/addQuestionModal.php"); ?>
    <?php require_once("component/quizapp-scripts.php"); ?>

    <script src="js/question_catalog.js"></script>
</body>

</html>

<!-- end document-->