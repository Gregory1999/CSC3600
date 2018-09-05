<?php
	/* 	Script is used to edit metadata
	/	called from imageOrganiser using GET 
	/	Receives the filename and metadata tag:values via the GET method
	/	Updates the metadata of the file directly on the file system- this data will be sync'ed to the db during next scan
	/	This script uses the PEL to write metadata to an image
	*/
	
	//include the pel library
	set_include_path('../scripts/pel-master' . PATH_SEPARATOR . get_include_path());
	require_once "autoload.php";
	use lsolesen\pel\PelDataWindow;
	use lsolesen\pel\PelJpeg;
	use lsolesen\pel\PelTag;
	use lsolesen\pel\PelIfd;
	use lsolesen\pel\PelEntryAscii;
	
	$image = $_GET['path'];
	
	$db = new SQLite3('../site.db');
	$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
				$row = $result->fetchArray(SQLITE3_ASSOC);
	$root_path= $row["path"] . '/';
	
	//save cwd
	$current_dir = getcwd();
	//cd to root directory
	chdir($_SERVER['DOCUMENT_ROOT'] . $root_path);
	$image= str_replace($root_path, "" ,$image);
	
	//create a new pel object that points to the file
	$jpeg = new PelJpeg($image);
	$ifd0 = $jpeg->getExif()->getTiff()->getIfd();
	
	//if comments are to be edited
	if (array_key_exists('comments', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_COMMENT);
		//if comment doen not exists add 
		if ($entry == null){
			    $entry = new PelEntryAscii(PelTag::XP_COMMENT, $_GET['comments']);
				$ifd0->addEntry($entry);
		}
		// if comment already exists then change
		else{
			$entry->setValue($_GET['comments']);
		}
	}
	
	//if tags are to be edited
	if (array_key_exists('tags', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_KEYWORDS);
		//if tag doen not exists add 
		if ($entry == null){
			    $entry = new PelEntryAscii(PelTag::XP_KEYWORDS, $_GET['tags']);
				$ifd0->addEntry($entry);
		}
		// if tag already exists then change
		else{
			$entry->setValue($_GET['tags']);
		}
	}
	
	//if title is to be edited
	if (array_key_exists('title', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_TITLE);
		//if title does not exists add 
		if ($entry == null){
			    $entry = new PelEntryAscii(PelTag::XP_TITLE, $_GET['title']);
				$ifd0->addEntry($entry);
		}
		// if comment already exists then change
		else{
			$entry->setValue($_GET['title']);
		}
	}
	
	//if subject is to be edited
	if (array_key_exists('subject', $_GET)) {
		$entry = $ifd0->getEntry(PelTag::XP_SUBJECT);
		//if title does not exists add 
		if ($entry == null){
			    $entry = new PelEntryAscii(PelTag::XP_SUBJECT, $_GET['subject']);
				$ifd0->addEntry($entry);
		}
		// if comment already exists then change
		else{
			$entry->setValue($_GET['subject']);
		}
	}
	
	
	//save data to the image
	$jpeg->saveFile($image);
	//change back to original directory before including the get_meta script
	chdir($current_dir);
	//return the new metadata
	include("get_meta.php");
	
?>