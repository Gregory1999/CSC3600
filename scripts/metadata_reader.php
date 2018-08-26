<?php
//this script is only called from the file_scanner.php script
//it is used to group all the metadata extraction code
	ini_set('exif.encode_unicode', 'UTF-8');
	$exif = exif_read_data($image, 'ANY_TAG', true);
	
	//$exif_ifd0 = read_exif_data($image ,'IFD0' ,0);       
    //$exif_exif = read_exif_data($image ,'EXIF' ,0);	
	  
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
			//windows put in a heap of randome chars, so had to remove-- may improve this
			$raw_title= $exif['IFD0']['Title'];
			$exif_title = substr(mb_convert_encoding($raw_title,"auto","byte2le"), 0, -1);
	}
	else{
		//no photo date data 	
		$exif_title= '';		
	}
	
	// get the Comments tag
	if (!empty($exif['IFD0']['Comments'])) {
			//windows put in a heap of randome chars, so had to remove-- may improve this
			$raw_comments= $exif['IFD0']['Comments'];
			$exif_comments = substr(mb_convert_encoding($raw_comments,"auto","byte2le"), 0, -1);		
	}
	else{
		//no photo date data 	
		$exif_comments= '';		
	}
	
	
	$query="INSERT or IGNORE INTO photo(photo_path, date_created) VALUES ( '$imagePath', '$exif_date') ";
	$db->query($query);
	$query="INSERT or IGNORE INTO photo_description(photo_path, title, comments) VALUES ( '$imagePath', '$exif_title','$exif_comments') ";
	$db->query($query);
	


?>