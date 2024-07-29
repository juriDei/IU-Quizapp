<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
class UploadController {
    private $model;

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

            // Überprüfen, ob es ein unterstützter Bildtyp ist
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime_type, $allowed_mime_types)) {
                $response['status'] = 'error';
                $response['message'] = 'Ungültiger Dateityp. Nur JPG, PNG, GIF, und WEBP sind erlaubt.';
                echo json_encode($response);
                return;
            }

            // Datei in einen Blob konvertieren
            $image_blob = file_get_contents($file_tmp_path);


            // Blob in der Datenbank über das UserModel speichern
            if ($this->model->setAvatar($image_blob)) {
                $response['status'] = 'success';
                $response['message'] = 'Avatar erfolgreich aktualisiert.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Fehler beim Aktualisieren des Avatars in der Datenbank.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Fehler beim Hochladen der Datei.';
        }

        // Rückgabe der Antwort als JSON
        echo json_encode($response);
    }
}
?>
