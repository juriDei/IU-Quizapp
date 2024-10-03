<?php
require dirname(__DIR__, 2) . "/controller/MessageHandlerController.php";

function showMessage($msg, $type)
{
    $messageClass = '';

    switch ($type) {
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

$error = MessageHandlerController::getError();
$success = MessageHandlerController::getSuccess();

?>
<!DOCTYPE html>
<html lang="de">
<?php include("component/head.php"); ?>

<body>
    <?php
    if ($error) {
        showMessage($error, 'error');
    } else if ($success) {
        showMessage($success, 'success');
    }
    ?>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="card shadow-sm w-100" style="max-width: 550px;">
            <div class="card-body p-5">
                <!-- Login Logo Section -->
                <div class="text-center mb-4">
                    <a href="#">
                        <img src="images/iu_quizapp_logo.png" class="img-fluid mb-4 shadow rounded" alt="Quizapp" style="max-width: 150px;" />
                    </a>
                    <h3 class="fw-bold">Herzlich Willkommen!</h3>
                </div>

                <form action="login" method="post">
                    <!-- E-Mail Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label">E-Mail</label>
                        <input type="email" class="form-control shadow-sm p-2" id="email" name="email" placeholder="E-Mail" required>
                    </div>

                    <!-- Passwort Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="password" class="form-control shadow-sm p-2" id="password" name="password" placeholder="Passwort" required>
                    </div>

                    <!-- Checkbox und Passwort vergessen -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Eingeloggt bleiben</label>
                        </div>
                        <a href="forget-pass" class="text-decoration-none">Passwort vergessen?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn w-100 mb-4 mt-4 quizapp-blue p-2">Login</button>

                    <!-- Registrierung -->
                    <div class="text-center">
                        <p class="fw-bold">Noch kein Mitglied? <a href="register" class="text-decoration-none">Hier zur Registrierung</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include("component/quizapp-scripts.php"); ?>

</html>
<!-- end document-->