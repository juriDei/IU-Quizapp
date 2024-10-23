$(function () {
    var cropper;
    var cropModal = $('#crop-modal');
    var cropImage = $('#crop-image');
    var avatarImage = $('#avatar-image');
    var preview = $('#preview');
    var avatarProfileImage = $("#avatar-profile-image");
    var fileUpload = $('#file-upload');

    // Klick-Event, um das Datei-Upload-Input zu öffnen
    $('#change-avatar').click(function (e) {
        fileUpload.click();
        e.stopImmediatePropagation(); // Verhindert, dass andere Klick-Handler ebenfalls ausgelöst werden
    });

    // Datei-Upload-Event, wenn eine Datei ausgewählt wird
    fileUpload.on('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
            cropImage.attr('src', url); // Bildquelle des Cropper-Elements setzen
            cropModal.modal('show'); // Modal für den Zuschnitt des Bildes öffnen
        };
        var reader;
        var file;

        // Überprüfen, ob Dateien vorhanden sind
        if (files && files.length > 0) {
            file = files[0];

            // Browserkompatibilität: URL-Objekt verwenden oder FileReader nutzen
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function () {
                    done(reader.result);
                };
                reader.readAsDataURL(file); // Datei als DataURL lesen
            }
        }
    });

    // Cropper initialisieren, wenn das Modal angezeigt wird
    cropModal.on('shown.bs.modal', function () {
        cropper = new Cropper(cropImage[0], {
            aspectRatio: 1, // Seitenverhältnis 1:1
            viewMode: 1, // Begrenzung auf den sichtbaren Bereich des Bildes
            autoCropArea: 0.65, // Bereich, der automatisch beschnitten wird
            scalable: true, // Erlaubt das Skalieren des Bildes
            zoomable: true, // Erlaubt das Zoomen des Bildes
            movable: true, // Erlaubt das Bewegen des Bildes
            preview: '#preview-container', // Vorschau-Container festlegen
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy(); // Cropper-Instanz zerstören, um Speicher freizugeben
        cropper = null; // Cropper-Variable auf null setzen
    });

    // Linksrotation um 45 Grad
    $('#rotate-left').click(function () {
        if (cropper) cropper.rotate(-45);
    });

    // Rechtsrotation um 45 Grad
    $('#rotate-right').click(function () {
        if (cropper) cropper.rotate(45);
    });

    // Bild zurücksetzen auf den Ursprungszustand
    $('#reset').click(function () {
        if (cropper) cropper.reset();
    });

    // Bild zuschneiden und hochladen
    $('#crop-button').click(function () {
        if (cropper) {
            var canvas = cropper.getCroppedCanvas({
                width: 300, // Zielbreite des zugeschnittenen Bildes
                height: 300 // Zielhöhe des zugeschnittenen Bildes
            });

            // Sofortige Anzeige des zugeschnittenen Bildes
            var base64Image = canvas.toDataURL('image/png');
            avatarImage.attr('src', base64Image); // Aktualisiert das Avatarbild in der Benutzeroberfläche
            avatarProfileImage.attr('src', base64Image); // Aktualisiert die Vorschau im Profil

            // Bild in Blob umwandeln und an den Server senden
            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('croppedImage', blob); // Blob zur Formulardaten hinzufügen

                // AJAX-Anfrage zum Hochladen des zugeschnittenen Bildes
                $.ajax({
                    url: 'avatar-upload', // Server-Endpunkt zum Speichern des Bildes
                    method: 'POST',
                    data: formData,
                    processData: false, // Verhindert, dass jQuery die Daten verarbeitet
                    contentType: false, // Kein Content-Type setzen, da wir FormData verwenden
                    success: function (data) {
                        var response = JSON.parse(data);
                        if (data && response.status === 'success') {
                            cropModal.modal('hide'); // Modal schließen
                            fileUpload.val(''); // Datei-Input zurücksetzen
                        } else {
                            alert('Fehler beim Upload: ' + (data.message || 'Unbekannter Fehler'));
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText); // Fehlerdetails in der Konsole anzeigen
                        alert('Upload fehlgeschlagen. Bitte versuche es erneut.');
                    }
                });
            }, 'image/png'); // Blob-Typ festlegen
        }
    });
});
