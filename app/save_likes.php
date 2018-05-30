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

// insertion to likes table
$statement = $conn->prepare('SELECT count(*) FROM likes WHERE user_id=:user_id AND photo_id=:photo_id');
$statement->execute(array(':user_id' => $user_id, ':photo_id' => $photo_id));
$count = $statement->fetchColumn();

if ($count == 0) {
    $statement = $conn->prepare('INSERT INTO likes (user_id, photo_id) VALUES (:user_id, :photo_id)');
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':photo_id', $photo_id);
    if ($statement->execute()) {
        $success[] = "likes saved in database";
    }
    else {
        $error[] = "failed saving likes in database";
    }
}

// get numbers of likes
$statement = $conn->prepare('SELECT count(*) FROM likes WHERE photo_id = :photo_id');
$statement->execute(array(':photo_id' => $photo_id));
$likes= $statement->fetchColumn();

echo json_encode($likes);

?>