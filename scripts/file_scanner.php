<?php
# this script will scan the file system and update any new or changed images
//	require_once "lib/pel-master/src/PelJpeg.php"
	
	
	//create an array of images- will need to add jpg 	
	$images = glob($root_path . "/*.{jpg,jpeg}", GLOB_BRACE);

	foreach($images as $image)
	{
  		
  		//if modified since last scan then update db data
  		$modified_date=date("d F Y H:i:s.", filemtime($image));
		if (strtotime($modified_date) > strtotime($last_scan)){
  			$query="INSERT or IGNORE INTO photo(photo_path) VALUES ( '$image' ) ";
			$db->query($query);
			
			//read in metadaa
			$exif = exif_read_data($image);
			
			
			//need to check and handle if no exif data=============================================
			//if($exif===false) {
			
        	
       	// get the time that the photo was taken
			if (!empty($exif['DateTimeOriginal'])) {
   			$exif_date = $exif['DateTimeOriginal'];
   			
   			echo "$exif_date<br />\n";
   			
   			//add date to db
			}
			else{
				//no photo date data so use modified date??
				
				
				
				//read in the image data
				//$image_data = imagecreatefromjpeg($image);
				
				//set the date to modified date
				$exif_date = $exif['FileDateTime'];
				echo "$exif_date<br />\n";
				
				//add the date to the file data
				//$pelJpeg = new PelJpeg($image);
				
				
				
				
			}
			
        	
        	
   	}
	}
			
			
	
	//update last scan time
	$last_scan = date("d F Y H:i:s.");
	$query="UPDATE root_directory SET last_scan= '$last_scan' WHERE path= '$root_path'";
	$db->query($query);	

?>