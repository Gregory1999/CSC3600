<?php
//this script will provide db stats for use on the nerd page
//reply will be formatted in JSON--->{"image_count":"", "db_size":"", "root_path":""}
//

	$json = "{";
	$root_path_array = array();
	include_once "db_setup.php";

	
	//if a root folder has been selected to be deleted, remove it form the db and then rescan the db
	if (isset($_GET['delete'])){
		$root_path = $_GET['delete'];
		$query = 'DELETE FROM root_directory WHERE path = "' . $root_path . '"';
		$db->exec($query);
		//rescan the images to remove images in deleted folder
		$current_dir = getcwd ();
		chdir('../');
		include "scripts/file_scanner.php";
		chdir($current_dir);
	}
	
	//Create querry strint that counts number of images in db
	$query = "SELECT COUNT(*) FROM photo_file";
	$result= $db->query($query);
	$row = $result->fetchArray(SQLITE3_NUM);
	$count = json_encode($row[0]);
	
	//find the size in bytes of the db
	$file = '../site.db';
	$filesize = filesize($file);
	
	//find the root paths of the directories
	$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
	while($row = $result->fetchArray(SQLITE3_ASSOC)){
		$path = $row["path"];
		array_push($root_path_array, $path);
	}
	
	$root_path= json_encode($row["path"]);
	
	//create json formatted string
	$json .= '"image_count" : "' . $count . '", "db_size" : "' . $filesize . '", "root_path" : [' ;
	
	foreach($root_path_array as $root_path ){
		$root_path= json_encode($root_path);
		$json .= $root_path . ',';
	}
	$json = rtrim($json,",") . "]}";
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;
?>