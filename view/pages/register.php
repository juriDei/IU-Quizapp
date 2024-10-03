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
?>
<!DOCTYPE html>
<html lang="de">
<?php include("component/head.php"); ?>

<body class="animsition">
    <?php
    $error = MessageHandlerController::getError();
    $success = MessageHandlerController::getSuccess();

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
                    <h3 class="fw-bold">Registrieren Sie sich!</h3>
                </div>

                <form action="register" method="post">
                    <!-- E-Mail mit Domain Auswahl -->
                    <div class="mb-3">
                        <label for="email-input" class="form-label">E-Mail</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control shadow-sm p-2" id="email-input" name="email" placeholder="E-Mail" aria-label="E-Mail">
                            <select id="email-domain" class="form-select shadow-sm p-2" name="domain">
                                <option value="@iubh-fernstudium.de">@iubh-fernstudium.de</option>
                                <option value="@iu.org">@iu.org</option>
                            </select>
                        </div>
                    </div>

                    <!-- Studiengang Auswahl -->
                    <div class="mb-3">
                        <label for="courseOfStudy" class="form-label">Studiengang</label>
                        <select class="form-select shadow-sm p-2" id="courseOfStudy" name="course_of_study">
                            <option value="" selected></option>
                            <optgroup label="Architektur & Bau">
                                <option value="Immobilienwirtschaft, B.A.">Immobilienwirtschaft, B.A.</option>
                                <option value="Architektur, B.A.">Architektur, B.A.</option>
                                <option value="Bauingenieurwesen, B.Eng.">Bauingenieurwesen, B.Eng.</option>
                                <option value="Immobilienmanagement, M.A.">Immobilienmanagement, M.A.</option>
                                <option value="Architektur, M.A.">Architektur, M.A.</option>
                            </optgroup>
                            <optgroup label="Design & Medien">
                                <option value="Mediendesign, B.A.">Mediendesign, B.A.</option>
                                <option value="Kommunikationsdesign, B.A.">Kommunikationsdesign, B.A.</option>
                                <option value="Game Design, B.A.">Game Design, B.A.</option>
                            </optgroup>
                            <optgroup label="Gesundheit & Soziales">
                                <option value="Soziale Arbeit, B.A.">Soziale Arbeit, B.A.</option>
                                <option value="Ernährungswissenschaften, B.Sc.">Ernährungswissenschaften, B.Sc.</option>
                                <option value="Gesundheitsmanagement, B.A.">Gesundheitsmanagement, B.A.</option>
                                <option value="Soziale Arbeit, M.A.">Soziale Arbeit, M.A.</option>
                                <option value="Gesundheitsmanagement, M.A.">Gesundheitsmanagement, M.A.</option>
                            </optgroup>
                            <optgroup label="IT & Technik">
                                <option value="Wirtschaftsinformatik, B.Sc.">Wirtschaftsinformatik, B.Sc.</option>
                                <option value="Informatik, B.Sc.">Informatik, B.Sc.</option>
                                <option value="Wirtschaftsingenieurwesen Industrie 4.0, B.Eng.">Wirtschaftsingenieurwesen Industrie 4.0, B.Eng.</option>
                                <option value="Wirtschaftsinformatik, M.Sc.">Wirtschaftsinformatik, M.Sc.</option>
                                <option value="Data Science, M.Sc.">Data Science, M.Sc.</option>
                                <option value="Digitale Transformation, M.Sc.">Digitale Transformation, M.Sc.</option>
                            </optgroup>
                            <optgroup label="Marketing & Kommunikation">
                                <option value="Marketing, B.A.">Marketing, B.A.</option>
                                <option value="Kommunikationspsychologie, B.A.">Kommunikationspsychologie, B.A.</option>
                                <option value="Marketingmanagement, M.A.">Marketingmanagement, M.A.</option>
                                <option value="Projektmanagement, M.A.">Projektmanagement, M.A.</option>
                            </optgroup>
                            <optgroup label="Personalwesen & Recht">
                                <option value="Wirtschaftsrecht, LL.B.">Wirtschaftsrecht, LL.B.</option>
                                <option value="Public Management, B.A.">Public Management, B.A.</option>
                                <option value="Wirtschaftspsychologie, M.Sc.">Wirtschaftspsychologie, M.Sc.</option>
                                <option value="Personalmanagement, M.A.">Personalmanagement, M.A.</option>
                            </optgroup>
                            <optgroup label="Pädagogik & Psychologie">
                                <option value="Psychologie, B.Sc.">Psychologie, B.Sc.</option>
                                <option value="Wirtschaftspsychologie, B.Sc.">Wirtschaftspsychologie, B.Sc.</option>
                            </optgroup>
                            <optgroup label="Tourismus & Hospitality">
                                <option value="Tourismusmanagement, B.A.">Tourismusmanagement, B.A.</option>
                                <option value="Hotelmanagement, B.A.">Hotelmanagement, B.A.</option>
                            </optgroup>
                            <optgroup label="Wirtschaft & Management">
                                <option value="Betriebswirtschaftslehre (BWL), B.A.">Betriebswirtschaftslehre (BWL), B.A.</option>
                                <option value="Wirtschaftspsychologie, B.Sc.">Wirtschaftspsychologie, B.Sc.</option>
                            </optgroup>
                            <optgroup label="MBA Programme">
                                <option value="Master of Business Administration (MBA)">Master of Business Administration (MBA)</option>
                                <option value="MBA - IT-Management">MBA - IT-Management</option>
                                <option value="MBA - Big Data Management">MBA - Big Data Management</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Vorname -->
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Vorname</label>
                        <input type="text" class="form-control shadow-sm p-2" id="firstname" name="firstname" placeholder="Vorname" required>
                    </div>

                    <!-- Nachname -->
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nachname</label>
                        <input type="text" class="form-control shadow-sm p-2" id="lastname" name="lastname" placeholder="Nachname" required>
                    </div>

                    <!-- Passwort -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="password" class="form-control shadow-sm p-2" id="password" name="password" placeholder="Passwort" required>
                    </div>

                    <!-- Passwort Wiederholen -->
                    <div class="mb-3">
                        <label for="password_repeat" class="form-label">Passwort wiederholen</label>
                        <input type="password" class="form-control shadow-sm p-2" id="password_repeat" name="password_repeat" placeholder="Passwort wiederholen" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn w-100 mb-4 mt-4 quizapp-blue p-2">Registrieren</button>
                </form>

                <!-- Zurück zum Login -->
                <div class="text-center">
                    <p><a href="login" class="text-decoration-none">Zurück zum Login</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include("component/quizapp-scripts.php"); ?>

</html>
<!-- end document-->