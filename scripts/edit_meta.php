<?php
	/* 	Script is used to edit metadata
	/	Receives the filename and metadata tag:values via the get method
	/	Updates the metadata of the file directly on the file system- this data will be sync'ed to the db during next scan
	/	This script uses the PEL to write metadata to an image
	*/
	
	//include the pel library
	set_include_path('../scripts/pel-master' . PATH_SEPARATOR . get_include_path());
	require_once "autoload.php";
	use lsolesen\pel\PelJpeg;
	use lsolesen\pel\PelTag;
	use lsolesen\pel\PelIfd;
	
	//create a new pel object that points to the file
	$image = $_GET['path'];
	
	$db = new SQLite3('../site.db');
	$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
				$row = $result->fetchArray(SQLITE3_ASSOC);
	$root_path= $row["path"] . '/';
	
	//save cwd
	$current_dir = getcwd();
	//cd to root

	chdir($_SERVER['DOCUMENT_ROOT'] . $root_path);
	$image= str_replace($root_path, "" ,$image);
	
	//chdir($_SERVER['DOCUMENT_ROOT']);
	
	//remove root from path
	
	
	$jpeg = new PelJpeg($image);
	
	$ifd0 = $jpeg->getExif()->getTiff()->getIfd();
	
	//if comments to be edited
	if (array_key_exists('comments', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_COMMENT);
		$entry->setValue($_GET['comments']);
	}
	
	//if comments to be edited
	if (array_key_exists('tags', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_KEYWORDS);
		$entry->setValue($_GET['tags']);
	}
	
	//if comments to be edited
	if (array_key_exists('title', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_TITLE);
		$entry->setValue($_GET['title']);
	}
	
	//save data to the image
	$jpeg->saveFile($image);
	
	chdir($current_dir);
	//return the image with new metadata
	include("get_meta.php");
	
?>