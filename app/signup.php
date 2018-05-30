<?php
header('Content-Type: application/json');
// obtain connnection to the database

require_once "../config/database.php";

$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST))
{   
    $email = htmlspecialchars($_POST['email']);
    try 
    {
        $statement = $conn->prepare("SELECT email FROM users WHERE email=:umail");
        $statement->execute(array(':umail' => $email));
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if($row['email'] == $email) {
            $error['email'] = "This email address is already taken";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

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
        $error['username1'] = "Username must contain between 5 and 16 characters";

    try {
        $statement = $conn->prepare('SELECT count(*) FROM users WHERE username = :username');
        $statement->execute(array(':username' => $username));
        $count = $statement->fetchColumn();

        if($count != '0') {
            $error['username2'] = "This username is already taken";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

	if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['password'])))
		$password = htmlspecialchars($_POST['password']);
    else
        $error['password1'] = "Password must contain at least 6 characters including one upper-case letter and one number";

    if (preg_match("#(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$#", htmlspecialchars($_POST['confirmed_password'])))
		$confirmed_password = htmlspecialchars($_POST['confirmed_password']);
    else
        $error['passsword2'] = "Password must contain at least 6 characters including one upper-case letter and one number";

	if($password != $confirmed_password){
		$error['password3'] = 'Confirmation password does not match';
    }
    


    if(!isset($error)){
        if($user->register($email, $firstname, $lastname, $username, $password)) 
        {
            echo json_encode("Registration successful");
            exit;
        }
    }
    else
    {
        echo json_encode($error);
        exit;
    }
}
else 
	$error[] = "Form not submitted"



?>