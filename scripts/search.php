<?php
//this script will respond to simple and complex searches
//the script will send the path to all images that satisfy the search criteria
//reply will be formatted in JSON
//search criteria to be passed to script using Get
	$json = "{";
	
	// scan file system 
	$db = new SQLite3('../site.db');
	chdir('../');
	require_once "file_scanner.php";

	
	$simple_search= $_GET['simple'];
	
	//search db for simple search string
	//add all matches to JSON imageArray
	
	$json .= '"imageArray" : [';
	// very basic search- add to this
	$query = "SELECT photo_path, date_taken FROM photo_origin WHERE photo_path LIKE '%${simple_search}%' ORDER BY date_taken DESC";
	
	$result= $db->query($query);
	
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


