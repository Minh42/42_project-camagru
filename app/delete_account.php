<?php
// obtain connnection to the database
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$user_id = $_SESSION['user_session'];

if(isset($_POST['delete_account'])) {

    $statement = $conn->prepare('DELETE FROM users WHERE user_id=:user_id');
    if ($statement->execute(array(':user_id' => $user_id))) {
        unset($_SESSION['user_session']);
        $success[] = "account deleted successfully";
        $user->redirect('../index.php?action=delete');
    }
    else
        $error[] = "Error deleting account";
}