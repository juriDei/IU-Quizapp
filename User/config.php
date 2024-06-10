<?php 
$user = explode("/",$_SERVER['REQUEST_URI'])[3];
$ini = parse_ini_file("../../user_ini_files/{$user}.ini"); 
return (object) array(
    'host' => 'localhost',
    'user' => $ini['DB_User'],
    'pw' => $ini['DB_Password'],
    'db' => $ini['DB_Name']
);
?> 