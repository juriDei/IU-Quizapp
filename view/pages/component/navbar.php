<?php
$user = new UserModel($_SESSION['uid']);
$avatar = $user->getAvatar();
?>

<link href="./css/navbar.css" rel="stylesheet" media="all">

<nav class="navbar navbar-white bg-white p-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between w-100">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="d-flex align-items-center ms-3">
                <img id="avatar-image" src="<?= ($avatar != null) ? $avatar : "images/avatar.png" ?>" alt="Avatar" class="rounded-circle" style="width: 50px; height: 50px;"  data-bs-toggle="modal" data-bs-target="#avatarModal" title="Profil anzeigen">
            </div>
        </div>
    </div>
</nav>
<?php /*
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="bg-white border-top p-2 ps-3 shadow-lg border-bottom">
    <div class="container-fluid">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item active link-hover p-2" aria-current="page"><?= $_SESSION['view'] ?></li>
        </ol>
    </div>
</nav>
 */ ?>
<div class="offcanvas offcanvas-start bg-white text-dark" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
    <div class="offcanvas-header">
        <img src="images/iu_quizapp_logo.png" class="img-fluid mb-4 shadow rounded mt-4 ms-2" alt="Quizapp" style="max-width: 75px;" />
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        <ul class="list-unstyled mt-3 flex-grow-1">
            <li class="p-3 link-hover"><i class="fa-solid fa-user"></i>&emsp;<a href="dashboard" class="text-dark text-decoration-none">Dashboard</a></li>
            <li class="p-3 link-hover"><i class="fa-solid fa-layer-group"></i>&emsp;<a href="question-catalog-overview" class="text-dark text-decoration-none">Fragenkataloge</a></li>
            <li class="p-3 link-hover"><i class="fa-solid fa-chart-pie"></i>&emsp;<a href="statistics" class="text-dark text-decoration-none">Statistiken</a></li>
        </ul>
        <div class="offcanvas-footer p-3 link-hover">
            <i class="fa-solid fa-right-from-bracket"></i>&emsp;<a href="logout" class="text-dark text-decoration-none">Logout</a>
        </div>
    </div>
</div>
<?php require_once("quizapp-scripts.php"); ?>
<script src="./js/navbar.js"></script>
<?php include("modal/profileModal.php"); ?>