<?php
	//this script will output all metadata for an image
	//the path will be passed to the script using the GET method
	//the script will return a JSON formatted string

	// scan file system 
	$db = new SQLite3('../site.db');
	include_once "scripts/file_scanner.php";
	$photoPath = $_GET['path'];
	$json = "{";
	// array of field to not output
	
	
	//will create an array of tables to output then loop over
	
	
	//Will extend-just getting  the date the photo was taken-- will be able to do this row-by-row in a loop
	//$query = "SELECT date_created FROM photo WHERE photo_path = '$photoPath'";
	$query = "SELECT * FROM photo WHERE photo_path = '$photoPath'";
	$result= $db->query($query);
	$row = $result->fetchArray(SQLITE3_ASSOC);
	
	//output the whole table
	foreach($row as $key => $value){
		
		$json .= ' "' . $key . '" : "' . $value . '",';
	}
	$json = rtrim($json,",") . "}";
	
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;


?>