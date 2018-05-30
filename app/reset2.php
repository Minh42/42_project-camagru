<?php
// obtain connnection to the database
require_once __DIR__ . "/config/database.php";
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();
$conn->query("use camagru");

// include the user class, pass in the database connection
require_once __DIR__ . "/user.php";
$user = new User($conn);


if(isset($_POST['search']))
{
    $email = htmlspecialchars($_POST['email']);
}

if(isset($_POST['cancel']))
{
    $user->redirect('index.php');
    exit;  
}


?>