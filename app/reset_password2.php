<?php
require_once "../config/database.php";

$email = htmlspecialchars($_POST["email"]);
$token = htmlspecialchars($_POST["token"]);

// compare the token collected and the one in the database
$statement = $conn->prepare("SELECT token FROM users WHERE email=:umail");
if($statement->execute(array(':umail' => $email)) && $row = $statement->fetch())
{
	$token_bdd = $row['token'];	
}

// get the user id with the email address
$statement = $conn->prepare("SELECT user_id FROM users WHERE email=:umail");
if($statement->execute(array(':umail' => $email)) && $row = $statement->fetch())
{
	$user_id = $row['user_id'];	
}

if(isset($_POST['reset_password']))
{
    if($token == $token_bdd)
        echo "Passwords entered are corrects";
    else
    $error[] = 'Confirmation password does not match';

    if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['new_password'])))
    $new_password = htmlspecialchars($_POST['new_password']);
    else
        $error[] = "Password must contain at least 6 characters including one upper-case letter and one number";

    if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['confirmed_password'])))
        $confirmed_password = htmlspecialchars($_POST['confirmed_password']);
    else
        $error[] = "Password must contain at least 6 characters including one upper-case letter and one number";

    if($new_password == $confirmed_password)
        echo "Passwords entered are corrects";
    else
        $error[] = 'Confirmation password does not match';

    if(!isset($error)){
        if($user->change_password($user_id, $new_password)) 
        {
            $user->redirect('../index.php?action=reset_success');
            exit;
        }
    }
    else
    {
        $user->redirect('../index.php?action=reset_failed');
        exit; 
    }
}
?>


