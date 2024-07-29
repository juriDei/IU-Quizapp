<?php
    require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

    // Route für den Upload-Endpoint
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'upload') {
        $controller = new UploadController();
        $controller->upload();
    }
?>