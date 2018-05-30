<?php
require_once "../config/database.php";
require_once "../app/imageManipulator.php";
require_once "../app/functions.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

// Define path where file will be uploaded to. User ID is set as directory name
$userID = $_SESSION['user_session'];
$target_dir = "../uploads/users_uploads/$userID/";

// Check to see if directory already exists
$exist = is_dir($target_dir);

// If directory doesn't exist, create directory
if (!$exist) {
    mkdir("$target_dir");
    chmod("$target_dir", 0777);
}
else {
    $error[] = "Folder already exists";
}

$tmp_file = $_FILES['file']['tmp_name'];
$uploadOk = 1;
$whitelist_type = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

// Check if image file is a actual image or fake image
if(isset($tmp_file)) {
    $file_info = new finfo(FILEINFO_MIME);
    $mime_type = $file_info->buffer(file_get_contents($tmp_file));
    $mime_type = explode(';', $mime_type);
    $mime_type = $mime_type[0];
    if (isset($mime_type) && in_array($mime_type, $whitelist_type)) {
        $uploadOk = 1;
        $success[] = 'This is an image file';
    } else {
        $uploadOk = 0;
        $error[] = "Uploaded file is not a valid image";
    }
}
$file = $_FILES['file']['name'];
$imageFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$name = date("Y-m-d H:i:s");
$target_file = $target_dir . $name . "." . $imageFileType;

// Check if file already exists
if (file_exists($target_file)) {
    $error[] = "Sorry, file already exists.";
    $uploadOk = 0;
}
if ($_FILES["file"]["size"] > 500000) {
    $error[] = "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    $error[] = "Sorry, your file was not uploaded.";
    exit;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $success[] = "The file ". basename($_FILES["file"]["name"]). " has been uploaded.";
    } else {
        exit;
    }
}

$manipulator = new ImageManipulator($target_file);
$newImage = $manipulator->resample(320, 240);

// saving file to uploads folder
$manipulator->save($target_file);
echo $target_file;
?>