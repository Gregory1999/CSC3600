<?php
//this script will respond to simple searches
//the script will send the path to all images that satisfy the search criteria
//reply will be formatted in JSON
//search criteria to be passed to script using Get
//The script searches for the supplied string in the following metadata fields --photo_path, title, comments, subject, tags.
	$json = "{";
	
	// scan file system 
	$db = new SQLite3('../site.db');
	chdir('../');
	require_once "file_scanner.php";

	
	$simple_search= $_GET['simple'];
	
	//search db for simple search string
	//add all matches to JSON imageArray
	
	$json .= '"imageArray" : [';
	
	$query = "SELECT * FROM photo_origin NATURAL JOIN photo_description 
				WHERE photo_path LIKE '%${simple_search}%' 
				OR title LIKE '%${simple_search}%' 
				OR comments LIKE '%${simple_search}%'
				OR subject LIKE '%${simple_search}%'				
				OR tags LIKE '%${simple_search}%'
				ORDER BY date_taken DESC";
	
	
	$result= $db->query($query);
	
	while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
		$photo_path= json_encode($row["photo_path"]);
		$photo_date= json_encode($row["date_taken"]);
		$json .= '{"path" : ' . $photo_path . ', "date" :' . $photo_date . '}' .',';			
	}
	
	
	$json = rtrim($json,",") . "]";

	$json .=  "}";
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;
?>


