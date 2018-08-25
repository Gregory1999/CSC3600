<?php
	//this script will output all metadata for an image
	//the path will be passed to the script using the GET method
	//the script will return a JSON formatted string

	//point to the database
	$db = new SQLite3('../site.db');
	// scan file system 
	require_once("file_scanner.php");
	
	//get the path of the image
	$photoPath = $_GET['path'];
	$json = "{";
	
	// array of field to not output
	
	
	//array of tables to output
	$tableArray= ['photo', 'photo_description'];
	
	//output each
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