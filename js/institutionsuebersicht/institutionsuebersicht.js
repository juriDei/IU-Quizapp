$(document).ready( function () {
    $('#table_id').DataTable({
        "language": {
            "url": "vendor/DataTable/German.json"
        }
    });
    $('#newAssociation').click(function(){
        $(".modal").remove();
        uid = $(this).data('uid');
        action = $(this).data('action');
        $.get("controller/modal/InstitutionsUebersichtModal.php",{uid:uid,action:action},function(result){
            $("body").append(result); 
            var newUserModal = new bootstrap.Modal(document.getElementById('newAssociationModal'), {
                keyboard: false
              })
            newUserModal.show();
        });
    });
    $(document).on("change","#userName",function(){
        var userName = $("#userName").val();

        new Promise(function(resolve,reject){
            checkFunction.dbUserExists(userName,resolve)
        }).then(function(result){
            if(result){
                $("#userNameError").text('Nutzer existiert bereits!');
            }
        });
    })
    $(document).on("click","#createNewAssociation",function(){
        var userName = $("#userName").val();
        var associationName = $("#associationName").val();
        $.post("controller/AssociationController.php",{userName:userName,associationName:associationName},function(result){
            //(result == "") ? 
        });
    });

} );