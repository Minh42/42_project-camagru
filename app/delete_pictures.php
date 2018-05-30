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

// delete picture
$statement = $conn->prepare('SELECT user_id, caption FROM photos WHERE photo_id=:photo_id');
$statement->bindparam(':photo_id', $photo_id);
$statement->execute();
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    $user_id_photo = $row['user_id'];
}

if ($user_id == $user_id_photo) {
	$statement = $conn->prepare('DELETE FROM photos WHERE photo_id=:photo_id');
	$statement->bindparam(':photo_id', $photo_id);
	if ($statement->execute()) {
		$success[] = ("success");
	}
	else
		$error[] = ("failure");

	$statement = $conn->prepare('DELETE FROM likes WHERE photo_id=:photo_id');
	$statement->bindparam(':photo_id', $photo_id);
	if ($statement->execute()) {
		echo json_encode("success");
	}
	else
		echo json_encode("failure");

}

?>