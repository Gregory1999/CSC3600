<?php
//this script will respond to advanced searches
//the script will send the path to all images that satisfy the search criteria
//reply will be formatted in JSON
//search criteria to be passed to script using Get- each get key is to be a field in the db
//
//test querry string----> ?photo_name=dog

	$json = "{";
	
	// scan file system 
	$db = new SQLite3('../site.db');
	chdir('../');
	require_once "file_scanner.php";
	$value_set_flag = FALSE;
	
	//Join all talbes and create the querry string
	$query = "SELECT * FROM photo_file NATURAL JOIN photo_description NATURAL JOIN photo_origin NATURAL JOIN photo_image NATURAL JOIN photo_camera NATURAL JOIN photo_advanced WHERE ";
	//
	foreach($_GET as $key=>$value) {
		if ( $value != "") {
			$query .= $key . " LIKE '%" . $value . "%' OR ";
			$value_set_flag = TRUE;
		}
	}
	$query = rtrim($query, "OR ") . " ORDER BY date_taken DESC";
	
	//add all matches to JSON imageArray
	
	$json .= '"imageArray" : [';
	
	//Only perform db query if a value is supplied
	if( $value_set_flag ) {
		$result= $db->query($query);
	}
	
	while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
		$photo_path= $row["photo_path"];
		$json .= ' "' . $photo_path . '",';			
	}
	
	
	$json = rtrim($json,",") . "]";

	$json .=  "}";
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;
?>