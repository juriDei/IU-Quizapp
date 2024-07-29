<!-- Cropper.js CSS -->
<link rel="stylesheet" href="./css/cropper.min.css">
<link rel="stylesheet" href="./css/jquery.fileupload.css">

<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Benutzerprofil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="profile-avatar" id="change-avatar">
                    <img src="<?= ($avatar != null) ? $avatar : "images/avatar.png" ?>" alt="Avatar" id="avatar-profile-image">
                    <div class="avatar-overlay" title="Avatar ändern">
                        <i class="fas fa-camera fa-2x"></i>
                    </div>
                </div>
                <!-- Modal für Bild-Zuschneiden und Vorschau -->
                <div id="crop-modal" class="modal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bild Bearbeiten</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex">
                                    <!-- Container für das Bild -->
                                    <div class="img-container" style="max-width: 70%; max-height: 500px; overflow: hidden;">
                                        <img id="crop-image" src="" alt="Bild zum Bearbeiten" style="max-width: 100%; display: block;">
                                    </div>
                                    <!-- Vorschaufenster -->
                                    <div class="preview ms-3">
                                        <h6>Vorschau</h6>
                                        <div id="preview-container" style="width: 100px; height: 100px; overflow: hidden;">
                                            <img id="preview" src="" alt="Vorschau" style="max-width: 100%;">
                                        </div>
                                    </div>
                                </div>
                                <div class="controls mt-3">
                                    <button type="button" class="btn btn-secondary" id="rotate-left" title="Nach links drehen">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="rotate-right" title="Nach rechts drehen">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" id="reset" title="Zurücksetzen">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="crop-button" class="btn btn-primary">Zuschneiden & Hochladen</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal für Bild-Zuschneiden -->
                <h3 class="mb-1 mt-3"><?= $user->getFullname() ?></h3>
                <p><?= $user->getEmail() ?></p>
                <p><?= $user->getCourseOfStudy() ?></p>
            </div>
        </div>
    </div>
</div>
<input id="file-upload" type="file" name="files[]" data-url="avatar-upload"  style="display: none;" accept=".png, .jpg, .jpeg">
<!-- aktuell hier bei der Einbindung eines Tools um das Profilbild zu aktualisieren -->
<script src="./js/jquery.ui.widget.js"></script>
<script src="./js/jquery.iframe-transport.js"></script>
<script src="./js/jquery.fileupload.js"></script>
<!-- Cropper.js JS -->
<script src="./js/cropper.min.js"></script>