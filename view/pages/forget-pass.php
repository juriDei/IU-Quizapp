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
                                <form action="forget-pass-request" method="post">
                                    <div class="form-group">
                                        <label>E-Mail</label>
                                        <input class="au-input au-input--full" type="text" name="email" placeholder="E-Mail">
                                    </div><br/>
                                    <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Passwort zurücksetzen</button>
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