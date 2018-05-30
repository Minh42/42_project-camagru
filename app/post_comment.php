<?php 
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$_POST = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_session'];
$photo_id = htmlspecialchars($_POST['photo_id']);
$comment = htmlspecialchars($_POST['comment']);

// insertion to comments table
$statement = $conn->prepare('INSERT INTO comments (comment, user_id) VALUES (:comment, :user_id)');
$statement->bindParam(':comment', $comment);
$statement->bindParam(':user_id', $user_id);
if ($statement->execute()) {
	$success[] = "comment saved in database";
}
else
	$error[] = "failed saving comment in database";

// insertion to photo_comments
$comment_id = $conn->lastInsertId();

$statement = $conn->prepare('INSERT INTO photos_comments (photo_id, comment_id) VALUES (:photo_id, :comment_id)');
$statement->bindParam(':photo_id', $photo_id);
$statement->bindParam(':comment_id', $comment_id);
if ($statement->execute()) {
	$success[] = "comment linked to photo in database";
}
else {
	$error[] = "failed linking comment to photo in database";
}

// send email
$statement = $conn->prepare('SELECT user_id FROM photos WHERE photo_id=:photo_id');
$statement->bindparam(':photo_id', $photo_id);
$statement->execute();
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    $contact = $row['user_id']; 
}

$statement = $conn->prepare('SELECT firstname, email, alert_notification FROM users WHERE user_id=:user_id');
$statement->bindParam(':user_id', $contact);
$statement->execute();
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
	$firstname = $row['firstname']; 
	$email = $row['email']; 
	$alert_notification = $row['alert_notification']; 
} 

if ($alert_notification == 1) {        
	
	$to      = $email;
	$subject = 'Message notification';
	$message = '
		 
	Hello '.$firstname.',

	You just received a comment. Please check out our website. 
		 
	'; 
							 
	$headers = 'From:mq.pham@hotmail.com' . "\r\n";

	if(mail($to, $subject, $message, $headers))
	{
		$success[] = "Email sent";
		echo json_encode("success");
	}
	else {
		$error = "Failed to send email";
		echo json_encode("failure");
	}
}
else {
	$success = "Email notification desactivated";
	echo json_encode("success");
}

?>