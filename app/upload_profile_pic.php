<?php
// obtain connnection to the database
require_once "../config/database.php";
require_once "../app/functions.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

// Define path where file will be uploaded to. User ID is set as directory name
$userID = $_SESSION['user_session'];
$target_dir = "../uploads/profile_pics/$userID/";

// Check to see if directory already exists
$exist = is_dir($target_dir);

// If directory doesn't exist, create directory
if (!$exist) {
    mkdir("$target_dir");
    chmod("$target_dir", 0755);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    header('Location: account.php?action=upload_failed');
    exit;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.";
        header('Location: account.php?action=upload_success');
    } else {
        echo "Sorry, there was an error uploading your file.";
        header('Location: account.php?action=upload_failed');
        exit;
    }
}

// Store filepath in database
$tmp = $target_dir . "hero.jpg";
resize($target_file, $tmp, 128, 128);
try {
    $statement = $conn->prepare("UPDATE users SET profile_pic_url=:target_file WHERE user_id=:user_id");
    $statement->execute(array(':target_file' => $tmp, ':user_id' => $userID));
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>