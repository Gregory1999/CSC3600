<?php
# this script will scan the file system and update any new or changed images

//This library will be required to update metadata images
//	require_once "lib/pel-master/src/PelJpeg.php"

	$query = "SELECT path, last_scan FROM root_directory";
	$result= $db->query($query);
	$row = $result->fetchArray(SQLITE3_ASSOC);
	$root_path= $row["path"];
	$last_scan = $row["last_scan"];
	
	//mark all entries in the photo table to delete- this flag is used to identify if a photo has been deleted	
	$query="UPDATE photo SET deleted= 'TRUE'";
	$db->query($query);
		
	//create an array of files ending in either jpg or jpeg
	$pattern =  $root_path . "/*.{jpg,jpeg}"; 
	$images= glob_recursive($pattern, GLOB_BRACE);

	foreach($images as $image)
	{
  			
  		//	if modified since last scan then update db data
  		$modified_date=date("d F Y H:i:s.", filemtime($image));
  		
  		//check if a new photo has been added to directory but is not in db
  		$query = "SELECT COUNT(*) as numRows FROM photo WHERE photo_path='$image'";
  		$result = $db->query($query);
  		$row = $result->fetchArray(SQLITE3_ASSOC);
  		$numRows = $row['numRows'];
  		
  		//update db if photo modified or not in db add to database
		if (strtotime($modified_date) > strtotime($last_scan) || $numRows == 0){
  			
			
			//read in metadaa
			$exif = exif_read_data($image);
			
        	
       	// get the time that the photo was taken
			if (!empty($exif['DateTimeOriginal'])) {
   			$exif_date = $exif['DateTimeOriginal'];
   			
   			
			}
			else{
				//no photo date data 
				
				$exif_date= '';	
				
			}
			
			$query="INSERT or IGNORE INTO photo(photo_path, date_created) VALUES ( '$image', '$exif_date') ";
			$db->query($query);
				 	
   	}
   	//mark file as not deleted
   	$query="UPDATE photo SET deleted = 'FALSE' WHERE photo_path = '$image'";
		$db->query($query);	
		
	}
	//remove deleted files from the db
	$query="DELETE FROM photo WHERE deleted = 'TRUE'";
	$db->query($query);
			
	
	//update last scan time
	$last_scan = date("d F Y H:i:s.");
	$query="UPDATE root_directory SET last_scan= '$last_scan' WHERE path= '$root_path'";
	$db->query($query);	
	
	
//recursively searches directories to find all files matching the supplied pattern
function glob_recursive($pattern, $flags = 0){
     $images = glob($pattern, $flags);
     $directoryArray = glob(dirname($pattern) . '/*', GLOB_ONLYDIR|GLOB_NOSORT);
     foreach ( $directoryArray as $directory)
     {
       $images = array_merge($images, glob_recursive($directory . '/' . basename($pattern), $flags));
     }
     return $images;
 }

?>