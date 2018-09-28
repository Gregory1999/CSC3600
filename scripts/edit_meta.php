<?php
	/* 	Script is used to edit metadata
	/	called from imageOrganiser using GET 
	/	Receives the filename and metadata tag:values via the GET method
	/	Updates the metadata of the file directly on the file system- this data will be sync'ed to the db during next scan
	/	This script uses the PEL to write metadata to an image
	/	authors, date_Taken, camera_Maker, camera_Model, and rating
	*/ 
	
	//include the pel library
	set_include_path('../scripts/pel-master' . PATH_SEPARATOR . get_include_path());
	require_once "autoload.php";
	use lsolesen\pel\PelDataWindow;
	use lsolesen\pel\PelJpeg;
	use lsolesen\pel\PelTag;
	use lsolesen\pel\PelIfd;
	use lsolesen\pel\PelEntryAscii;
	use lsolesen\pel\PelEntryTime;
	
	$image = $_GET['path'];
	
	$db = new SQLite3('../site.db');
	$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
				$row = $result->fetchArray(SQLITE3_ASSOC);
	$root_path= $row["path"] . '/';
	
	//save cwd
	$current_dir = getcwd();
	//cd to root directory
	chdir($root_path);
	$image= str_replace($root_path, "" ,$image);
	
	//create a new pel object that points to the file
	$jpeg = new PelJpeg($image);
	$ifd0 = $jpeg->getExif()->getTiff()->getIfd();
	
	//array of all the tags for the metadata to edit
	$PelTagArray_ifd0 = ['comments'=>PelTag::XP_COMMENT, 'tags'=>PelTag::XP_KEYWORDS,'title'=>PelTag::XP_TITLE,'subject'=>PelTag::XP_SUBJECT,'authors'=>PelTag::XP_AUTHOR,  'copyright'=>PelTag::COPYRIGHT, 'camera_maker'=>PelTag::MAKE, 'camera_model'=>PelTag::MODEL];
	
	//loop over tag array and write in the new data
	foreach ($PelTagArray_ifd0 as $key => $tag) {
		if (array_key_exists($key, $_GET)) {
			$entry = $ifd0->getEntry($tag);
			//if tag does not exists add 
			if ($entry == null){
					$entry = new PelEntryAscii($tag, $_GET[$key]);
					$ifd0->addEntry($entry);
			}
			// if tag already exists then add new data
			else{
				$entry->setValue($_GET[$key]);
			}
		}
	}
	
	//if date_taken are to be edited
	if (array_key_exists('date_taken', $_GET)) {
		//$date_taken= DateTime::createFromFormat('Y-m-d H:i:s', strtotime($_GET['date_taken']));
		$date_taken= strtotime($_GET['date_taken']);
		$exif = $ifd0->getSubIfd(PelIfd::EXIF);

		$entry = $exif->getEntry(PelTag::DATE_TIME_ORIGINAL);
		//if date_taken doen not exists add
		if ($entry == null){
			//$entry = new PelEntryTime(PelTag::DATE_TIME_ORIGINAL, $date_taken);
			$entry = new PelEntryTime(PelTag::DATE_TIME_ORIGINAL, $date_taken);
			
			$exif->addEntry($entry);
		}
		// if date_taken already exists then change
		else{
			$entry->setValue($date_taken);
			
		}

	}
	
	//save data to the image
	$jpeg->saveFile($image);
	//change back to original directory before including the get_meta script
	chdir($current_dir);
	//return the new metadata
	include("get_meta.php");
	
?>