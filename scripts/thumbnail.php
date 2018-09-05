<?php
// this file retrieves the thumbnail image from the db and returns
// reqires the photo path in the Get querry string

header('Content-Type: image/jpeg');

$db = new PDO('sqlite:../site.db');

if (isset($_GET['path'])){
	$photo_path= $_GET['path'];
	
	$query = "SELECT photo_path, photo_thumbnail FROM photo_thumbnail WHERE photo_path = '$photo_path'";
	$stmt= $db->prepare($query);
	$stmt->execute();
	
	$stmt->bindColumn(2, $thumbnail, PDO::PARAM_LOB);
	$stmt->fetch(PDO::FETCH_BOUND);
	
	echo $thumbnail;
	
}
else{echo "Not set";}
?>