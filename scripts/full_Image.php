<?php
// this file retrieves the thumbnail image from the db and returns
// reqires the photo path in the Get querry string

header('Content-Type: image/jpeg');

if (isset($_GET['path'])){
	$photo_path= $_GET['path'];
	
	
	readfile($photo_path);
	
}

?>