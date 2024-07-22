<style>
    .link-hover:hover{
        background-color: #f0f0f0;
        /* Adjust the color as needed */
        border-radius: 5px;
        border-left: 5px solid #52E0FF;
        /* Optional: to add rounded corners */
    }
    .breadcrumb-item:hover{
        border-left: none;
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
</style>
<nav class="navbar navbar-white bg-white p-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between w-100">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <form class="d-flex ms-auto search-form">
                <input class="form-control me-0 rounded-start " type="search" placeholder="Suche" aria-label="Search">
                <button class="btn btn-primary rounded-end rounded-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </div>
</nav>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="bg-white border-top p-2 ps-3">
    <div class="container-fluid">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item active link-hover p-2" aria-current="page"><?= $_SESSION['view'] ?></li>
        </ol>
    </div>
</nav>

<div class="offcanvas offcanvas-start bg-white text-dark" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
    <div class="offcanvas-header">
        <div class="profile-section">
            <img src="./images/quizapp_logo.png" alt="Avatar" width="90" height="90">
            <div class="profile-info">
                <strong><?= $user->getFullname() ?></strong><br>
                <?= $user->getEmail() ?>
            </div>
        </div>
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
