<?php 
// obtain connnection to the database
require_once "../config/database.php";

//logout
$user->logout(); 

//logged in return to index page
header('Location: ../index.php');
exit;
?>