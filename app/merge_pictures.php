<?php
header ("Content-type: image/png");

// echo json_encode("IM HERE");
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
$target_dir = "../uploads/tmp/$userID/";

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

$_POST = json_decode(file_get_contents('php://input'), true);

$canvas_width = $_POST['canvas_width'];
$canvas_height =  $_POST['canvas_height'];
$img_width = $_POST['img_width'];
$img_height =  $_POST['img_height'];
$x = $_POST['x'];
$y = $_POST['y'];

if ($_POST['upload'] == 'data:,') {
    $data = htmlspecialchars($_POST['photo']);
    $photo_filter = htmlspecialchars($_POST['filter']);
}
else {
    $data = htmlspecialchars($_POST['upload']);
    $photo_filter = htmlspecialchars($_POST['filter']);
}

$type = getimagesizefromstring(explode(',', base64_decode($data)[1], 2));
$name = date("Y-m-d H:i:s");

if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
    $data = substr($data, strpos($data, ',') + 1);
    $type = strtolower($type[1]);

    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
        $error[] = "invalid image type";
    }

    if($data = base64_decode($data)) {
        $success[] = "base64_decode succeeded";
    }
    else
        $error[] = "base64_decode failed";
} else {
    $error[] = "did not match data URI with image data";
}

$tmp_file = "../uploads/tmp/{$userID}/{$name}.{$type}";

if (file_put_contents($tmp_file, $data)) {
    $success[] = "tmp file saved";
}
else
    $error [] = "failed saving tmp file";

// Traitement de l'image source
$basename = basename($photo_filter);
$target_file = "../filtres/{$basename}";
$manipulator = new ImageManipulator($target_file);
$newImage = $manipulator->resample($img_width, $img_height);
$manipulator->save2($target_file, $userID, $type = IMAGETYPE_PNG);

$saved_file = "../uploads/tmp/{$userID}/{$basename}";

$source = imagecreatefrompng($saved_file);
$largeur_source = imagesx($source);
$hauteur_source = imagesy($source);
imagealphablending($source, true);
imagesavealpha($source, true);

// Traitement de l'image destination
$basename = basename($tmp_file);
$target_file = "../uploads/tmp/{$userID}/{$basename}";
$manipulator = new ImageManipulator($target_file);
$newImage = $manipulator->resample($canvas_width, $canvas_height);
$manipulator->save2($target_file, $userID, $type = IMAGETYPE_PNG);

$saved_file = "../uploads/tmp/{$userID}/{$basename}";

$destination = imagecreatefrompng($saved_file);
$largeur_destination = imagesx($destination);
$hauteur_destination = imagesy($destination);

// Calcul des coordonnées pour placer l'image source dans l'image de destination
// $destination_x = ($largeur_destination - $largeur_source)/2;
// $destination_y =  ($hauteur_destination - $hauteur_source)/2;
  
// On place l'image source dans l'image de destination
if ($x == null && $y == null) {
    imagecopy($destination, $source, 0, 0, 0, 0, $largeur_source, $hauteur_source);
}
else {
    imagecopy($destination, $source, $x, $y, 0, 0, $largeur_source, $hauteur_source);
}

// On affiche l'image de destination
imagepng($destination, $tmp_file);
 
imagedestroy($source);
imagedestroy($destination);

echo json_encode($tmp_file);

?>