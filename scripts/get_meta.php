<?php
	//this script will output all EDITABLE metadata for an image
	//the path will be passed to the script using the GET method
	//the script will return a JSON formatted string

	//point to the database
	$db = new SQLite3('../site.db');
	// scan file system 
	chdir('../');
	require("file_scanner.php");

	$photoPath = $_GET['path'];
	
	$json = "{";
	
	// array of field to not output
	
	//array of tables to output
	$tableArray= ['photo_description', 'photo_origin', 'photo_camera'];
	
	//output each
	foreach($tableArray as $tableName){
		$query = "SELECT * FROM $tableName WHERE photo_path = '$photoPath'";
		$result= $db->query($query);
		$row = $result->fetchArray(SQLITE3_ASSOC);
		
		//output the whole table
		foreach($row as $key => $value){
			$json .= json_encode($key) . ' : ' . json_encode($value) . ',';
		}
	}
	$json = rtrim($json,",") . "}";
	
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;


?>