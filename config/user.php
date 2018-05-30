<?php
class User
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
    public function register($email, $firstname, $lastname, $username, $password)
    {
       try
       {
            $activation_code = md5(uniqid(rand(),true));
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
            $statement = $this->db->prepare("INSERT INTO `users` (`email`, `firstname`, `lastname`, `username`, `password`, `activation_code`) 
            VALUES(:email, :firstname, :lastname, :username, :password, :activation_code)");
            $statement->bindparam(':email', $email);
            $statement->bindparam(':firstname', $firstname);
            $statement->bindparam(':lastname', $lastname); 
            $statement->bindparam(':username', $username);  
            $statement->bindparam(':password', $hashed_password);
            $statement->bindparam(':activation_code', $activation_code);                  
            $statement->execute(); 

            // send an email to user with verification link
            $to      = $email; // Send email to our user
            $subject = 'Activate your account'; // Give the email a subject 
            $message = '
             
            Thanks for signing up!
            Your account has been created, you can login with the following credentials after you have activated your account by 
            pressing the url below.
             
            http://localhost:8080/camagru/app/activate.php?email='.$email.'&hash='.$activation_code.'
             
            '; // Our message above including the link
                                 
            $headers = 'From:mq.pham@hotmail.com' . "\r\n"; // Set from headers

            if(mail($to, $subject, $message, $headers))
            {
                $success[] = "Email sent";
            }
            else
                $error[] = "Failed to send email";
            return $statement;
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

    public function edit_info($user_id, $email, $firstname, $lastname, $username)
    {
       try 
       {
            $statement = $this->db->prepare("UPDATE users SET email=:umail, firstname=:firstname, lastname=:lastname, username=:username
            WHERE user_id=:user_id");
            $statement->execute(array(':umail' => $email, ':firstname' => $firstname, ':lastname' => $lastname, ':username' => $username, ':user_id' => $user_id));
            echo $statement->rowCount() . "records UPDATED successfully";     

            return $statement;
        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function change_password($user_id, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        try 
        {
            $statement = $this->db->prepare("UPDATE users SET password=:new_password WHERE user_id=:user_id");
            $statement->execute(array(':new_password' => $hashed_password, ':user_id' => $user_id));
            echo $statement->rowCount() . "records UPDATED successfully";     

            return $statement;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function forgot_password($email)
    {
        // generate a random token and put it in the database
        $token = md5(uniqid(rand(),true));
        try {
        $statement = $this->db->prepare("UPDATE users SET token=:token WHERE email=:email");
        $statement->execute(array(':token' => $token, ':email' => $email));
        echo $statement->rowCount() . "records UPDATED successfully";     
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        // obtain information about the user
        try {
            $statement = $this->db->prepare("SELECT firstname FROM users WHERE email=:email");
            $statement->execute(array(':email' => $email));
            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                $firstname = $row['firstname']; 
            } 
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        // send an email to user with token link
        $to      = $email; // Send email to our user
        $subject = 'Forgot password'; // Give the email a subject 
        $message = '
             
        Hello '.$firstname.',

        We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below.
        
        Click on the link below to reset your password:
        
        http://localhost:8080/camagru/app/reset_password.php?email='.$email.'&hash='.$token.'
        
        If you did not request to have your password reset you can safely ignore this email.
             
        '; // Our message above including the link
                                 
        $headers = 'From:mq.pham@hotmail.com' . "\r\n"; // Set from headers

        if(mail($to, $subject, $message, $headers))
        {
            echo "Email sent";
        }
        else
            echo "Failed to send email";
        return $statement;
    }

    public function login($email, $password)
    {
       try
       {
            $statement = $this->db->prepare("SELECT * FROM users WHERE email=:umail LIMIT 1");
            $statement->execute(array(':umail' => $email));
            $userRow = $statement->fetch(PDO::FETCH_ASSOC);
            if($statement->rowCount() > 0)
            {
                if(password_verify($password, $userRow['password']))
                {
                    $_SESSION['user_session'] = $userRow['user_id'];
                    return true;
                }
                else
                {
                    return false;
                }
            }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
    }

    function username_exists($username)
	{
		if(!$username)
            return FALSE;

        try 
        {
            $statement = $this->db->prepare("SELECT count(*) as username_exists FROM users WHERE username=:username");
            $statement->execute(array(':username' => $username));
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            if (($row['username_exists'] > '0'))
                return TRUE;
            else
                return FALSE;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    function email_exists($email)
    {
		if(!$email)
            return FALSE;        
    
        try 
        {
            $statement = $this->db->prepare("SELECT count(*) as email_exists FROM users WHERE email=:umail");
            $statement->execute(array(':umail' => $email));
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            if (($row['email_exists'] > '0'))
                return True;
            else
                return False;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }
      
    public function is_logged_in()
    {
       if(isset($_SESSION['user_session']))
       {
          return true;
       }
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
         session_destroy();
         unset($_SESSION['user_session']);
         return true;
    }
}
?>