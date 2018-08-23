<?php
//this script will respond to simple and complex searches
//the script will send the path to all images that satisfy the search criteria
//reply will be formatted in JSON
//search criteria to be passed to script using Get
	$json = "{";
	
	// scan file system 
	include_once "scripts/file_scanner.php";
	$db = new SQLite3('../site.db');
	
	$simple_search= $_GET['simple'];
	
	//search db for simple search string
	//add all matches to JSON imageArray
	
	$json .= '"imageArray" : [';
	// very basic search-
	$query = "SELECT photo_path, date_created FROM photo WHERE photo_path LIKE '%${simple_search}%' ORDER BY date_created DESC";
	
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

