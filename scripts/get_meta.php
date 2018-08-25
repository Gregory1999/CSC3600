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
	
	
	//array of tables to output then loop over
	$tableArray= ['photo', 'photo_description'];
	
	foreach($tableArray as $tableName){
		$query = "SELECT * FROM $tableName WHERE photo_path = '$photoPath'";
		$result= $db->query($query);
		$row = $result->fetchArray(SQLITE3_ASSOC);
		
		//output the whole table
		foreach($row as $key => $value){
			
			$json .= ' "' . $key . '" : "' . $value . '",';
		}
	}
	$json = rtrim($json,",") . "}";
	
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;


?>