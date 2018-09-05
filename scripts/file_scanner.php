<?php
// this script will scan the file system and update any new or changed images
// it is included in multiple scripts- get_meta.php, search.php,

 
	//This library will be required to update metadata images
	//include the pel library----------------------------------------------------------------
	$scriptDir= getcwd();
	set_include_path('./scripts/pel-master' . PATH_SEPARATOR . get_include_path());
	require_once "autoload.php";
	use lsolesen\pel\PelDataWindow;
	use lsolesen\pel\PelJpeg;
	use lsolesen\pel\PelTag;
	use lsolesen\pel\PelIfd;
	use lsolesen\pel\PelEntryAscii;

	$query = "SELECT path, last_scan FROM root_directory";
	$result= $db->query($query);
	$row = $result->fetchArray(SQLITE3_ASSOC);
	$root_path= $row["path"];
	$last_scan = $row["last_scan"];
	
	
	//mark all entries in the photo_file table to delete- this flag is used to identify if a photo has been deleted	
	$query="UPDATE photo_file SET deleted= 'TRUE'";
	$db->query($query);
	//go to the photo directory
	chdir($_SERVER['DOCUMENT_ROOT'] . $root_path);
	
	//create an array of files ending in either jpg or jpeg
	//$pattern =  realpath($root_path) . "/*.{jpg,jpeg}";
	$pattern = '*.{jpg,jpeg}';
	$images= glob_recursive($pattern, GLOB_BRACE);
	
	foreach($images as $image)
	{
		$imagePath = $root_path . "/" . ltrim($image, "./");
  		//$imagePath = $root_path . ltrim($image, ".");
  		//	if modified since last scan then update db data
  		$modified_date=date("d F Y H:i:s.", filemtime($image));
  		
  		//check if a new photo has been added to directory but is not in db
  		$query = "SELECT COUNT(*) as numRows FROM photo_file WHERE photo_path='$imagePath'";
  		$result = $db->query($query);
  		$row = $result->fetchArray(SQLITE3_ASSOC);
  		$numRows = $row['numRows'];
  		
  		//update db if photo modified or not in db add to database
		if (strtotime($modified_date) > strtotime($last_scan) || $numRows == 0){
			
			//read in metadaa
			include "metadata_reader.php";  				 	
		}
		//mark file as not deleted
		$query="UPDATE photo_file SET deleted = 'FALSE' WHERE photo_path = '$imagePath'";
		$db->query($query);	
		
	}
	//remove deleted files from the db
	$query="DELETE FROM photo_file WHERE deleted = 'TRUE'";
	$db->query($query);
			
	
	//update last scan time
	$last_scan = date("d F Y H:i:s.");
	$query="UPDATE root_directory SET last_scan= '$last_scan' WHERE path= '$root_path'";
	$db->query($query);	
	
	
//recursively searches directories to find all files matching the supplied pattern
//@param $pattern- the pattern to use to match files
//@param $flags- the flags to use to with glob
//@return- all matching files within directory and subdirectories
function glob_recursive($pattern, $flags = 0){
	//find all images in the current directory
	$images = glob($pattern, $flags);
	//find all direct child directories in the current directory
	$directoryArray = glob(dirname($pattern) . '/*', GLOB_ONLYDIR|GLOB_NOSORT);
	//recursively call function for all child directories
	foreach ($directoryArray as $directory){
		//combine results
		$images = array_merge($images, glob_recursive($directory . "/" . basename($pattern), $flags));
	}
	//return all images
	return $images;
}

?>