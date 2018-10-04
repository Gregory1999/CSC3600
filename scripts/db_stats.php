<?php
//this script will provide db stats for use on the nerd page
//reply will be formatted in JSON--->{"image_count":"", "db_size":"", "root_path":""}
//

	$json = "{";
	
	// scan file system 
	$db = new SQLite3('../site.db');
	
	//JCreate querry strint that counts number of images in db
	$query = "SELECT COUNT(*) FROM photo_file";
	$result= $db->query($query);
	$row = $result->fetchArray(SQLITE3_NUM);
	$count = json_encode($row[0]);
	
	//find the size in bytes of the db
	$file = '../site.db';
	$filesize = filesize($file);
	
	//find the root path of the directory
	$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
				$row = $result->fetchArray(SQLITE3_ASSOC);
	$root_path= json_encode($row["path"]);
	
	//create json formatted string
	$json .= '"image_count" : "' . $count . '", "db_size" : "' . $filesize . '", "root_path" : ' . $root_path . '}';
	
	
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;
?>