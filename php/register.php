<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$registerController = new RegisterController();

$registerController->register();
?>