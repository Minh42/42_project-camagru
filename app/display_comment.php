<?php 
require_once "../config/database.php";

$_POST = json_decode(file_get_contents('php://input'), true);
$photo_id = $_POST['photo_id'];

// get caption from photo id
$statement = $conn->prepare('SELECT user_id, caption FROM photos WHERE photo_id=:photo_id');
$statement->bindparam(':photo_id', $photo_id);
$statement->execute();
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    $user_id = $row['user_id']; 
    $caption = $row['caption']; 
}

// get info from user
$statement = $conn->prepare('SELECT username, profile_pic_url FROM users WHERE user_id=:user_id');
$statement->bindparam(':user_id', $user_id);
$statement->execute();
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    $username = $row['username']; 
    $profile_pic_url = $row['profile_pic_url']; 
} 

// get comments for a photo_id
$sql = "SELECT * FROM photos 
    RIGHT JOIN photos_comments ON photos_comments.photo_id = photos.photo_id
    INNER JOIN comments ON photos_comments.comment_id = comments.comment_id
    INNER JOIN users ON users.user_id = comments.user_id
    WHERE photos.photo_id = :photo_id";
$statement = $conn->prepare($sql);
$statement->bindparam(':photo_id', $photo_id);
$statement->execute();
if ($comments = $statement->fetchAll(PDO::FETCH_ASSOC)) {
    $success = "data retrieve";
}
else
    $error = "failed to retrieve data";

// get numbers of likes
$statement = $conn->prepare('SELECT count(*) FROM likes WHERE photo_id = :photo_id');
$statement->execute(array(':photo_id' => $photo_id));
$likes= $statement->fetchColumn();

$array = array('username' => $username, 'caption' => $caption, 'profile_pic_url' => $profile_pic_url, 'comments' => $comments, 'likes' => $likes);
echo json_encode($array);


?>