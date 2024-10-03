<!-- Modal: Neuen Fragenkatalog hinzufügen -->
<div class="modal fade" id="addQuestionCatalogModal" tabindex="-1" aria-labelledby="addQuestionCatalogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionCatalogModalLabel">Neuen Fragenkatalog hinzufügen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>
            <div class="modal-body">
                <form id="addQuestionCatalogForm" class="p-2" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="moduleName" class="form-label">Modul</label>
                        <input type="text" class="form-control" id="moduleName" name="moduleName" placeholder="Geben Sie den Namen des Moduls ein" required>
                    </div>
                    <div class="mb-3">
                        <label for="moduleAlias" class="form-label">Modul-Alias</label>
                        <input type="text" class="form-control" id="moduleAlias" name="moduleAlias" placeholder="Geben Sie den Modul-Alias ein" required>
                    </div>
                    <div class="mb-3">
                        <label for="tutorName" class="form-label">Tutor(en)</label>
                        <input type="text" class="form-control" id="tutorName" name="tutorName" placeholder="Geben Sie den Namen des Tutors oder der Tutoren ein" required>
                    </div>
                    <div class="mb-3">
                        <label for="moduleImage" class="form-label">Bild</label>
                        <input type="file" class="form-control" id="moduleImage" name="moduleImage" accept="image/*">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Hinzufügen</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
