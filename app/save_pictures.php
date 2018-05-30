<?php
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$userID = $_SESSION['user_session'];
$target_dir = "../uploads/users_pics/$userID/";

// Check to see if directory already exists
$exist = is_dir($target_dir);

// If directory doesn't exist, create directory
if (!$exist) {
    mkdir("$target_dir");
    chmod("$target_dir", 0755);
}
else {
    $success[] = "Folder already exists";
}

$_POST = json_decode(file_get_contents('php://input'), true);

$data = htmlspecialchars($_POST['image']);
$type = getimagesizefromstring(explode(',', base64_decode($data)[1], 2));
$name = date("Y-m-d H:i:s");

if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
    $data = substr($data, strpos($data, ',') + 1);
    $type = strtolower($type[1]); // jpg, png, gif

    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
        throw new \Exception('invalid image type');
    }

    $data = base64_decode($data);

    if ($data === false) {
        throw new \Exception('base64_decode failed');
    }
} else {
    throw new \Exception('did not match data URI with image data');
}

$target_file = "../uploads/users_pics/{$userID}/{$name}.{$type}";
if (file_put_contents($target_file, $data))
    $success[] = 'image saved';
else
    $error[] = 'failed to save image';
 
$caption = htmlspecialchars($_POST['caption']);

try {
    $statement = $conn->prepare('INSERT INTO photos (user_id, caption, image_path) VALUES (:user_id, :caption, :image_path)');
    $statement->bindParam(':user_id', $userID);
    $statement->bindParam(':caption', $caption);
    $statement->bindParam(':image_path', $target_file);
    if ($statement->execute()) {
        echo json_encode("image saved in database");
    }
    else
        echo json_encode("failed saving image in database");
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>