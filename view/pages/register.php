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
                                <form action="register" method="post">
                                    <div class="form-group">
                                        <label>E-Mail</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control au-input au-input--full" id="email-input" name="email" placeholder="E-Mail" aria-label="E-Mail" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <select id="email-domain" class="custom-select input-group-text rounded-0 h-100" name="domain">
                                                    <option value="@iubh-fernstudium.de">@iubh-fernstudium.de</option>
                                                    <option value="@iu.org">@iu.org</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Vorname</label>
                                        <input class="au-input au-input--full" type="text" name="firstname" placeholder="Vorname">
                                    </div><br/>
                                    <div class="form-group">
                                        <label>Nachname</label>
                                        <input class="au-input au-input--full" type="text" name="lastname" placeholder="Nachname">
                                    </div><br/>
                                    <div class="form-group">
                                        <label>Passwort</label>
                                        <input class="au-input au-input--full" type="password" name="password" placeholder="Passwort">
                                    </div><br/>
                                    <div class="form-group">
                                        <label>Passwort wiederholen</label>
                                        <input class="au-input au-input--full" type="password" name="password_repeat" placeholder="Passwort wiederholen">
                                    </div><br/>
                                    <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Registrieren</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jquery JS-->
        <script src="js/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    </body>
</html>
<!-- end document-->