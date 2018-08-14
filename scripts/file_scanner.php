<?php
# this script will scan the file system and update any new or changed images
	
	//create an array of images- will need to add jpg 	
	$images = glob($root_path . "/*.jpeg");

	foreach($images as $image)
	{
  		
  		//if modified since last scan then update db data
  		$modified_date=date("d F Y H:i:s.", filemtime($image));
		if (strtotime($modified_date) > strtotime($last_scan)){
		}
  		$query="INSERT or IGNORE INTO photo(photo_path) VALUES ( '$image' ) ";
		$db->query($query);
	}	
	
	//update last scan time
	$last_scan = date("d F Y H:i:s.");
	$query="UPDATE root_directory SET last_scan= '$last_scan' WHERE path= '$root_path'";
	$db->query($query);	

?>