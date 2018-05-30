<?php
// obtain connnection to the database
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$user_id = $_SESSION['user_session'];

if(isset($_POST['change_password']))
{
    if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['old_password'])))
		$old_password = htmlspecialchars($_POST['old_password']);
    else
        $error[] = "Password must contain at least 6 characters including one upper-case letter and one number";

    if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['new_password'])))
		$new_password = htmlspecialchars($_POST['new_password']);
    else
        $error[] = "Password must contain at least 6 characters including one upper-case letter and one number";

    if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['confirmed_password'])))
		$confirmed_password = htmlspecialchars($_POST['confirmed_password']);
    else
        $error[] = "Password must contain at least 6 characters including one upper-case letter and one number";

    /* Recupération de l'ancien mot de passe dans la base de données */
    try 
    {
        $statement = $conn->prepare("SELECT password FROM users WHERE user_id=:user_id");
        $statement->execute(array(':user_id' => $user_id));
        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
            $old_password_db = $row['password'];
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    if(password_verify($old_password, $old_password_db))
    {
        if($new_password == $confirmed_password)
            echo "Passwords entered are corrects";
        else
            $error[] = 'Confirmation password does not match';
    }
    else 
        $error[] = "Old password does not match with the one in the database";

    if(!isset($error)){
        if($user->change_password($user_id, $new_password)) 
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
?>