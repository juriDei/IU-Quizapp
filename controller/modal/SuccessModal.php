<?php 
class SuccessModal{
    public static function showSuccessModal(): string{
        $modal = '
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Neue Institution erstellen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Name der Organisation" id="associationName" aria-label="Name der Institution"><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeModal" data-dismiss="modal">Schließen</button>
                    <button type="button" class="btn btn-primary" id="createNewUser">Erstellen</button>
                </div>
                </div>
            </div>
        </div>';
        
        return $modal;
    }
}
echo NutzerUebersichtModal::{"{$_GET['action']}"}();
?>