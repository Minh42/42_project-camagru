<?php
// obtain connnection to the database
require_once "../config/database.php";

if(isset($_POST['login']) == "Log in")
{
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    $statement = $conn->prepare("SELECT status FROM users WHERE email=:umail");
    if($statement->execute(array(':umail' => $email)) && $row = $statement->fetch())
    {
        $status = $row['status']; // $status is either 0 or 1
    }
    if($user->login($email, $password) && $status == 1)
    {
        $user->redirect('../app/connexion.php');
        exit;
    }
    else
    {
        $user->redirect('../index.php');
        $error = "Wrong username or password or your account has not been activated.";
        exit;
    }
}
?>