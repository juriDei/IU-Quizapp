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
        <div class="card shadow-sm w-100" style="max-width: 600px;">
            <div class="card-body p-5">
                <!-- Logo Section -->
                <div class="text-center mb-5">
                    <a href="#">
                        <img src="images/iu_quizapp_logo.png" class="img-fluid mb-4 shadow rounded" alt="Quizapp" style="max-width: 150px;" />
                    </a>
                    <h3 class="fw-bold">Neues Passwort setzen</h3>
                </div>

                <form action="forget-pass-reset" method="post">
                    <!-- Neues Passwort -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Neues Passwort</label>
                        <input type="password" class="form-control shadow-sm p-2" id="password" name="password" placeholder="Neues Passwort eingeben" required>
                    </div>

                    <!-- Passwort wiederholen -->
                    <div class="mb-3">
                        <label for="password_repeat" class="form-label">Passwort wiederholen</label>
                        <input type="password" class="form-control shadow-sm p-2" id="password_repeat" name="password_repeat" placeholder="Passwort wiederholen" required>
                    </div>

                    <!-- Sicherheitsschlüssel -->
                    <div class="mb-3">
                        <label for="key" class="form-label">Sicherheitsschlüssel</label>
                        <input type="text" class="form-control shadow-sm p-2" id="key" name="key" placeholder="Sicherheitsschlüssel eingeben" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn w-100 mb-4 mt-4 quizapp-blue p-2">OK</button>

                    <!-- Zurück zum Login -->
                    <div class="text-center">
                        <p><a href="login" class="text-decoration-none">Zurück zum Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include("component/quizapp-scripts.php"); ?>

</html>
<!-- end document-->