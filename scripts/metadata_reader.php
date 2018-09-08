<?php
//this script is only called from the file_scanner.php script
//it is used to group all the metadata extraction code

	ini_set('exif.encode_unicode', 'UTF-8');
	$exif = exif_read_data($image, 'ANY_TAG', true);

	//could probably process all sections using a loop<----------------

	//arrays of all stored metadata
	$ifd0_data = array("Title"=>"", "Comments"=>"","Keywords"=>"", "Subject"=>"", "Author"=>"", "Copyright"=>"");
	$exif_data = array("DateTimeOriginal"=>"", "ExifImageLength"=>"", "ExifImageWidth"=>"", "CompressedBitsPerPixel"=>"");
	$file_data = array("FileName"=>"", "FileDateTime"=>"","FileSize"=>"", "MimeType"=>"");

	//retrieve file  data
	foreach ( $file_data as $key => $value ){
		if (!empty($exif["FILE"]["$key"])) {
			$file_data["$key"] = $exif["FILE"]["$key"];
		}
	}

	//Pel classes
	use lsolesen\pel\PelDataWindow;
	use lsolesen\pel\PelJpeg;
	use lsolesen\pel\PelTag;
	use lsolesen\pel\PelIfd;
	use lsolesen\pel\PelEntryAscii;

	//retrieve exif section data
	foreach ( $exif_data as $key => $value ){
		if (!empty($exif["EXIF"]["$key"])) {
			$exif_data["$key"] = $exif["EXIF"]["$key"];
		}
	}
	//retrieve ifd0 data
	foreach ( $ifd0_data as $key => $value ){
		if (!empty($exif["IFD0"]["$key"])) {
			//windows put in a heap of randome chars
			$ifd0_data["$key"]= preg_replace("/[[:^print:]]/", "", $exif["IFD0"]["$key"]);
		}
	}

	$image_thumbnail = exif_thumbnail($image);

	//creates a thumbnail image if one does not exist
	if ($image_thumbnail === False) {
		//This code was referenced from exif_thumbnail php man page
		//viewed from <http://php.net/manual/en/function.exif-thumbnail.php>
		//from -thrustvector at &#39;gee&#39;mail dot com ¶

		$jpeg = new PelJpeg($image);
		$exif = $jpeg->getExif();
		$tiff = $exif->getTiff();
		$ifd0 = $tiff->getIfd();

		$ifd1 = $ifd0->getNextIfd();
		//if ifd1 doesnt exist then create it
		if (!$ifd1) {
			//create new ifd1 and point ifd0 to it
			$ifd1 = new PelIfd(1);
			$ifd0->setNextIfd($ifd1);
			$origImage = ImageCreateFromString($jpeg->getBytes());
			$width=imagesx($origImage);
			$height=imagesy($origImage);

			$widthMax = 150;
			$heightMax = 100;

			if ($width>$widthMax || $height>$heightMax) {
				$thumb_w=$widthMax;
				$thumb_h=$heightMax;
				if ($thumb_w/$width*$height>$thumb_h)
					$thumb_w=round($thumb_h*$width/$height);
				else
					$thumb_h=round($thumb_w*$height/$width);
			}
			else {
				$thumb_w=$width;
				$thumb_h=$height;
			}


			$thumb=imagecreatetruecolor($thumb_w,$thumb_h);
			imagecopyresampled($thumb,$origImage,
									   0,0,0,0,$thumb_w,$thumb_h,$width,$height);


			ob_start();
			ImageJpeg($thumb);

			$window = new PelDataWindow(ob_get_clean());

			if ($window) {

				$ifd1->setThumbnail($window);
				$outpath = $image;
				file_put_contents($outpath, $jpeg->getBytes());
			}
		}

		$image_thumbnail = exif_thumbnail($image);

	}

	//insert or replace the db
	//photo_file table                       <----- might need to move date created
	$query="INSERT OR REPLACE INTO photo_file(photo_path, date_created, photo_name, photo_type, date_modified, size) VALUES ( '$imagePath', '" . $exif_data['DateTimeOriginal'] . "', '" . $file_data['FileName'] . "', '" . $file_data['MimeType'] . "', '" . date ("d-m-Y h:m:s", $file_data['FileDateTime']) . "', '" . $file_data['FileSize'] . "' ) ";
	$db->query($query);
	//photo_description
	$query="INSERT  OR REPLACE INTO photo_description(photo_path, title, comments, tags, subject) VALUES ( '$imagePath', '" . $ifd0_data['Title'] . "','" . $ifd0_data['Comments'] . "', '" . $ifd0_data['Keywords'] . "', '" . $ifd0_data['Subject'] . "') ";
	$db->query($query);
	//photo_origin
	$query="INSERT  OR REPLACE INTO photo_origin(photo_path, authors, date_taken, copyright) VALUES ( '$imagePath', '" . $ifd0_data['Author'] . "','" . $exif_data['DateTimeOriginal'] . "', '" . $ifd0_data['Copyright'] . "') ";
	$db->query($query);
	//photo image
	$query="INSERT  OR REPLACE INTO photo_image(photo_path, width, height, compression) VALUES ( '$imagePath', '" . $exif_data['ExifImageWidth'] . "','" . $exif_data['ExifImageLength'] . "', '" . $exif_data['CompressedBitsPerPixel'] . "') ";
	$db->query($query);

	//photo_camera
	//photo_advanced

	//insert the thumnail image
	$query="INSERT OR REPLACE INTO photo_thumbnail(photo_path, photo_thumbnail) VALUES ( '$imagePath', :image_thumbnail) ";
	$stmt= $db->prepare($query);
	$stmt->bindParam(':image_thumbnail', $image_thumbnail, \PDO::PARAM_LOB);
	$stmt->execute();


?>
