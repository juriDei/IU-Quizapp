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
    <div class="page-wrapper">
        <?php
        $error = MessageHandlerController::getError();
        $success = MessageHandlerController::getSuccess();

        if ($error) {
            showMessage($error, 'error');
        } else if ($success) {
            showMessage($success, 'success');
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
                                    <label for="courseOfStudy">Studiengänge</label>
                                    <select class="form-select au-input au-input--full" id="courseOfStudy" name="course_of_study">
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
                                <br />
                                <div class="form-group">
                                    <label>Vorname</label>
                                    <input class="au-input au-input--full" type="text" name="firstname" placeholder="Vorname">
                                </div><br />
                                <div class="form-group">
                                    <label>Nachname</label>
                                    <input class="au-input au-input--full" type="text" name="lastname" placeholder="Nachname">
                                </div><br />
                                <div class="form-group">
                                    <label>Passwort</label>
                                    <input class="au-input au-input--full" type="password" name="password" value="op998776t!" placeholder="Passwort">
                                </div><br />
                                <div class="form-group">
                                    <label>Passwort wiederholen</label>
                                    <input class="au-input au-input--full" type="password" name="password_repeat" value="op998776t!" placeholder="Passwort wiederholen">
                                </div><br />
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