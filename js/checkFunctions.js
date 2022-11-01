checkFunction = {
    dbUserExists: function(username,promise){
        var func = 'dbUserExists';
        var prop_arr = JSON.stringify({username:username});
        $.get("controller/CheckFunctionController.php",{prop_arr:prop_arr,func:func},function(result){
            promise(result);
        });
    }
}