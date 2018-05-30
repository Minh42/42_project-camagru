<?php
// obtain connnection to the database
require_once "../config/database.php";

if(isset($_POST['reset']))
{
    $email = htmlspecialchars($_POST['email']);

    $statement = $conn->prepare('SELECT count(*) FROM users WHERE email = :email');
    $statement->execute(array(':email' => $email));
    $count = $statement->fetchColumn();

    if($count == '0') {
        echo "Email does not exist";
        $user->redirect('../index.php?action=reset_failed');
        exit;
    }

    $statement = $conn->prepare('SELECT status FROM users WHERE email = :email');
    $statement->execute(array(':email' => $email));
    $status = $statement->fetchColumn();

    if($status == '0') {
        echo "Account not activated yet";
        $user->redirect('../index.php?action=reset_failed');
        exit;
    }

    if($user->forgot_password($email)) {
        echo "Instructions for resetting password sent";
        $user->redirect('../index.php?action=reset_success');
        exit;   
    }
    else
    {
        echo "Failed to send instructions for resetting password";
        $user->redirect('../index.php?action=reset_failed');
        exit;          
    }
}

if(isset($_POST['cancel']))
{
    $user->redirect('../index.php');
    exit;  
}

?>