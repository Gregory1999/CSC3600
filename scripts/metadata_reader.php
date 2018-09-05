<?php
//this script is only called from the file_scanner.php script
//it is used to group all the metadata extraction code
	ini_set('exif.encode_unicode', 'UTF-8');
	$exif = exif_read_data($image, 'ANY_TAG', true);	

  
	// get the time that the photo was taken
	if (!empty($exif['EXIF']['DateTimeOriginal'])) {
		//$exif_date = $exif_exif['DateTimeOriginal'];
		$exif_date = $exif['EXIF']['DateTimeOriginal'];
	}
	else{
		//no photo date data 	
		$exif_date= '';		
	}
	
	// get the title tag
	if (!empty($exif['IFD0']['Title'])) {
			//windows put in a heap of randome chars
			$exif_title= preg_replace('/[[:^print:]]/', '', $exif['IFD0']['Title']);	
	}
	else{
		//no photo date data 	
		$exif_title= '';		
	}
	
	// get the Comments tag
	if (!empty($exif['IFD0']['Comments'])) {
			//windows put in a heap of randome chars, so  remove
			$exif_comments= preg_replace('/[[:^print:]]/', '', $exif['IFD0']['Comments']);		
	}
	else{
		//no photo date data 	
		$exif_comments= '';		
	}
	//get the tags/Keywords
	if (!empty($exif['IFD0']['Keywords'])) {
			//windows put in a heap of randome chars, so remove
			$exif_keywords= preg_replace('/[[:^print:]]/', '', $exif['IFD0']['Keywords']);				
	}
	else{
		//no photo date data 	
		$exif_keywords = '';		
	}
	
	$image_thumbnail = exif_thumbnail($image);
	
	if ($image_thumbnail === False) {
		$image_thumbnail = "";
	}
	
	//insert or replace the db
	$query="INSERT OR REPLACE INTO photo(photo_path, date_created) VALUES ( '$imagePath', '$exif_date') ";
	$db->query($query);
	$query="INSERT  OR REPLACE INTO photo_description(photo_path, title, comments, tags) VALUES ( '$imagePath', '$exif_title','$exif_comments', '$exif_keywords') ";
	$db->query($query);
	
	//insert the thumbnail image
	$query="INSERT OR REPLACE INTO photo_thumbnail(photo_path, photo_thumbnail) VALUES ( '$imagePath', :image_thumbnail) ";
	$stmt= $db->prepare($query);
	$stmt->bindParam(':image_thumbnail', $image_thumbnail, \PDO::PARAM_LOB);
	$stmt->execute();
	
	
?>