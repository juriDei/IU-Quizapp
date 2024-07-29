$(function () {
    var cropper;
    var cropModal = $('#crop-modal');
    var cropImage = $('#crop-image');
    var avatarImage = $('#avatar-image');
    var preview = $('#preview');
    var avatarProfileImage = $("#avatar-profile-image");
    var fileUpload = $('#file-upload');

    $('#change-avatar').click(function (e) {
        fileUpload.click();
        e.stopImmediatePropagation();
    });

    fileUpload.on('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
            cropImage.attr('src', url);
            cropModal.modal('show');
        };
        var reader;
        var file;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function () {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    cropModal.on('shown.bs.modal', function () {
        cropper = new Cropper(cropImage[0], {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 0.65,
            scalable: true,
            zoomable: true,
            movable: true,
            preview: '#preview-container',
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    $('#rotate-left').click(function () {
        if (cropper) cropper.rotate(-45);
    });

    $('#rotate-right').click(function () {
        if (cropper) cropper.rotate(45);
    });

    $('#reset').click(function () {
        if (cropper) cropper.reset();
    });

    $('#crop-button').click(function () {
        if (cropper) {
            var canvas = cropper.getCroppedCanvas({
                width: 300, // Zielgröße
                height: 300
            });

            // Sofortige Anzeige des zugeschnittenen Bildes
            var base64Image = canvas.toDataURL('image/png');
            avatarImage.attr('src', base64Image); // Zeigt das Bild sofort in der UI an
            avatarProfileImage.attr('src', base64Image); // Aktualisiert die Vorschau

            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('croppedImage', blob);

                $.ajax({
                    url: 'avatar-upload', // Server-Endpunkt zum Speichern
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var response = JSON.parse(data);
                        debugger;
                        if (data && response.status === 'success') {
                            cropModal.modal('hide'); // Modal schließen
                            fileUpload.val(''); // Leert das file input nach dem erfolgreichen Upload
                        } else {
                            alert('Fehler beim Upload: ' + (data.message || 'Unbekannter Fehler'));
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText); // Log für detaillierte Fehleranalyse
                        alert('Upload fehlgeschlagen. Bitte versuche es erneut.');
                    }
                });
            }, 'image/png');
        }
    });
});

