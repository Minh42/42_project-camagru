<?php
require_once "../config/database.php";

$_POST = json_decode(file_get_contents('php://input'), true);
$offset = htmlspecialchars($_POST['offset']);

$statement = $conn->prepare('SELECT photo_id, image_path FROM photos ORDER BY date_created DESC LIMIT 20 OFFSET '.$offset.'');
$statement->execute();
$pictures = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($pictures);

?>