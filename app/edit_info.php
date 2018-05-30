<?php
// obtain connnection to the database
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$user_id = $_SESSION['user_session'];

if(isset($_POST['edit_info']))
{   
    if (preg_match("#^[a-zA-Z_]{1,30}$#", htmlspecialchars($_POST['firstname'])))
        $firstname = htmlspecialchars($_POST['firstname']);
    else
        $error['firstname'] = "Firstname must contain less than 30 characters and only alphabetic characters";

    if (preg_match("#^[a-zA-Z_]{1,30}$#", htmlspecialchars($_POST['lastname'])))
        $lastname = htmlspecialchars($_POST['lastname']);
    else
        $error['lastname'] = "Lastname must contain less than 30 characters and only alphabetic characters";

    if (preg_match("#^[a-zA-Z0-9_]{5,16}$#", htmlspecialchars($_POST['username'])))
        $username = htmlspecialchars($_POST['username']);
    else
        $error[] = "Username must contain at least 5 characters";

    $statement = $conn->prepare('SELECT username FROM users WHERE user_id=:user_id');
    $statement->execute(array(':user_id' => $user_id));
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $username_db = $row['username'];
    }

    if($user->username_exists($username) == TRUE && $username != $username_db)
        $error[] = "This username is already taken";

    $email = htmlspecialchars($_POST['email']);

    $statement = $conn->prepare('SELECT email FROM users WHERE user_id=:user_id');
    $statement->execute(array(':user_id' => $user_id));
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $email_db = $row['email'];
    }

    if($user->email_exists($email) == TRUE && $email != $email_db)
        $error[] = "This email is already taken"; 

    if(isset($_POST['alert'])) {
        $statement = $conn->prepare('UPDATE users SET alert_notification=FALSE WHERE user_id=:user_id');
        $statement->execute(array(':user_id' => $user_id));
    }
    else {
        $statement = $conn->prepare('UPDATE users SET alert_notification=TRUE WHERE user_id=:user_id');
        $statement->execute(array(':user_id' => $user_id));
    }

    if(!isset($error)){
        if($user->edit_info($user_id, $email, $firstname, $lastname, $username)) 
        {
            $user->redirect('../app/connexion.php?action=edit_success');
            exit;
        }
    }
    else
    {
        $user->redirect('../app/connexion.php?action=edit_failed');
        exit; 
    }
}
else
	echo "ERROR\n";

?>
