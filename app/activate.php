<?php
// obtain connnection to the database
require_once "../config/database.php";

//collect values from the url
if (isset($_GET['email'])) 
    $email = trim(htmlspecialchars($_GET['email']));
else {
    echo "Your account could not be activated.";   
    exit;
}


if (isset($_GET['hash']))
    $activation_code = trim(htmlspecialchars($_GET['hash']));
else {
    echo "Your account could not be activated.";  
    exit; 
}

// compare the activation key collected and the one in the database
$statement = $conn->prepare("SELECT activation_code, status FROM users WHERE email=:umail");
if($statement->execute(array(':umail' => $email)) && $row = $statement->fetch())
{
	$activation_code_bdd = $row['activation_code'];	
	$status = $row['status']; // $status is either 0 or 1
}

if($status == '1') 
{
    echo "Your account is already activated.";
}
else
{
	if($activation_code == $activation_code_bdd)	
    {
        echo "Your account has been activated.";
 
        // change status from 0 to 1
        $statement = $conn->prepare("UPDATE users SET status = 1 WHERE email=:umail");
        $statement->bindParam(':umail', $email);
		$statement->execute();
		
		// redirect to login page
		// header('Location: index.php?action=active');
		exit;
    }
    else
    {
        echo "Your account could not be activated.";
        exit;
	}
}
?>