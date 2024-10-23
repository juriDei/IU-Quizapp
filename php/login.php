<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Erstellen eines LoginController-Objekts
$loginController = new LoginController();

// Aufruf der Login-Methode des LoginControllers
$loginController->login();
?>
