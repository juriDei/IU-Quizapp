<?php 
class InstitutionsUebersichtModal{
    public static function newAssociation(): string{
        $modal = '
        <div class="modal fade" id="newAssociationModal" tabindex="-1" role="dialog" aria-labelledby="newAssociationModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Neue Institution hinzufügen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Institution" id="associationName">
                        <label for="associationName">Name der Institution</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Username" id="userName">
                        <label for="userName">DB-Username</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="createNewAssociation">Erstellen</button>
                    <button type="button" class="btn btn-secondary" id="closeModal" data-dismiss="modal">Schließen</button>
                </div>
                </div>
            </div>
        </div>';
        
        return $modal;
    }
}
echo InstitutionsUebersichtModal::{"{$_GET['action']}"}();
?>