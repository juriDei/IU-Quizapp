<?php

function showError($msg){
    $error =  "<div class='alert alert-danger d-flex align-items-center alert-dismissible' role='alert'>
                <div>{$msg}</div>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Schließen'></button>
               </div>";

    return $error;
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>Quizapp</title>

    <!-- Bootstrap CSS-->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <?= (isset($_SESSION['error'])) ? showError($_SESSION['error']) : ''; unset($_SESSION['error']) ?>
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/Quizapp_Logo.png" alt="Quizapp" />
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="controller/login" method="post">
                                <div class="form-group">
                                    <label>E-Mail</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="E-Mail">
                                </div><br/>
                                <div class="form-group">
                                    <label>Passwort</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Passwort">
                                </div><br/>
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Eingeloggt bleiben
                                    </label>
                                    <label>
                                        <a href="register">Registrieren</a>
                                    </label>
                                </div><br>
                                <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Login</button>
                                <p class="text-center fw-semibold">Noch kein Mitglied? <a class="text-decoration-none" href="register">Hier zur Registrierung</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
</body>

</html>
<!-- end document-->