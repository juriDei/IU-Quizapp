<?php
require dirname(__DIR__, 2 ) . "/controller/MessageHandlerController.php";

function showMessage($msg,$type){
    $messageClass = '';

    switch($type){
        case 'error':
            $messageClass = 'alert-danger';
            break; 
        case 'success':
            $messageClass = 'alert-success';
            break;
    }

    $message =  "<div class='alert {$messageClass} d-flex align-items-center alert-dismissible' role='alert'>
                <div>{$msg}</div>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Schließen'></button>
               </div>";

    echo $message;
}
?>
<!DOCTYPE html>
<html lang="de">
    <?php include("component/head.php"); ?>
    <body class="animsition">
        <div class="page-wrapper">
            <?php 
                $error = MessageHandlerController::getError();
                $success = MessageHandlerController::getSuccess();

                if($error){
                    showMessage($error,'error'); 
                }
                else if($success){
                    showMessage($success,'success'); 
                }
             ?>
            <div class="page-content--bge5">
                <div class="container">
                    <div class="login-wrap">
                        <div class="login-content">
                            <div class="login-logo">
                                <a href="#">
                                    <img src="images/iu_quizapp_logo.png" class="rounded" alt="Quizapp" />
                                </a>
                            </div>
                            <div class="login-form">
                                <form action="login" method="post">
                                    <h3 class="text-center">Herzlich Willkommen!</h3><br/><br/>
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
                                            <a href="forget-pass" class="text-black text-decoration-none">Passwort vergessen</a>
                                        </label>
                                    </div><br>
                                    <button class="submit-btn p-2 w-100 m-b-20 text-white rounded fw-semibold" type="submit">Login</button>
                                    <p class="text-center fw-semibold">Noch kein Mitglied? <a class="text-decoration-none text-black" href="register">Hier zur Registrierung</a></p>
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