<?php

// Autoload-Funktionalität von Composer laden, um alle benötigten Bibliotheken und Abhängigkeiten bereitzustellen
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Controller-Klasse zur Verwaltung von Datei-Uploads
class UploadController {
    // Instanz des UserModel, um Benutzerinformationen zu verwalten
    private $model;

    // Konstruktor: Initialisiert das UserModel basierend auf der Benutzer-ID aus der Session
    public function __construct() {
        $this->model = new UserModel($_SESSION['uid']);
    }

    // Methode zum Hochladen von Dateien
    public function upload() {
        $response = array();

        // Überprüfen, ob eine Datei hochgeladen wurde und keine Fehler aufgetreten sind
        if (isset($_FILES["croppedImage"]) && $_FILES["croppedImage"]["error"] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES["croppedImage"]["tmp_name"];
            $mime_type = $_FILES["croppedImage"]["type"];

            // Überprüfen, ob es sich um einen unterstützten Bildtyp handelt
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime_type, $allowed_mime_types)) {
                // Ungültiger Dateityp
                $response['status'] = 'error';
                $response['message'] = 'Ungültiger Dateityp. Nur JPG, PNG, GIF, und WEBP sind erlaubt.';
                echo json_encode($response);
                return;
            }

            // Datei in einen Blob konvertieren
            $image_blob = file_get_contents($file_tmp_path);

            // Blob in der Datenbank über das UserModel speichern
            if ($this->model->setAvatar($image_blob)) {
                // Erfolgreiches Speichern des Avatars
                $response['status'] = 'success';
                $response['message'] = 'Avatar erfolgreich aktualisiert.';
            } else {
                // Fehler beim Speichern des Avatars in der Datenbank
                $response['status'] = 'error';
                $response['message'] = 'Fehler beim Aktualisieren des Avatars in der Datenbank.';
            }
        } else {
            // Fehler beim Hochladen der Datei
            $response['status'] = 'error';
            $response['message'] = 'Fehler beim Hochladen der Datei.';
        }

        // Rückgabe der Antwort als JSON
        echo json_encode($response);
    }
}
?>
