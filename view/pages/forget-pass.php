<?php
function showError($msg){
    $error =  "<div class='sufee-alert alert with-close alert-danger alert-dismissible fade show'>
                <span class='badge badge-pill badge-danger'>Fehler</span>
                {$msg}
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
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

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">

    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

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
                            <form action="controller/passwordReset" method="post">
                                <div class="form-group">
                                    <label>E-Mail</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="E-Mail">
                                </div><br>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Abschicken</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>

</body>

</html>
<!-- end document-->