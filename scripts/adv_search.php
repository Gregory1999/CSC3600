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
	$default_end_date = "2070:01:01";
	$default_start_date = "1970:01:01";
	$date_fields = array("to_date_taken"=>$default_end_date, "from_date_taken"=>$default_start_date, "from_date_created"=>$default_start_date, "to_date_created"=>$default_end_date, "from_date_modified"=>$default_start_date, "to_date_modified"=>$default_end_date);
	$date_fields_set = array("date_taken"=>false, "date_modified"=>false, "date_created"=>false);
	
	//Join all talbes and create the querry string
	$query = "SELECT * FROM photo_file NATURAL JOIN photo_description NATURAL JOIN photo_origin NATURAL JOIN photo_image NATURAL JOIN photo_camera NATURAL JOIN photo_advanced WHERE ";
	//
	foreach($_GET as $key=>$value) {
		if ( $value != "") {
			//if the field is a date field then will be a rang so handle differently
			if (array_key_exists($key, $date_fields)){
				$date_fields[$key] = $value;		
				$field_id = preg_replace("(to_|from_)","",$key);
				$date_fields_set[$field_id]= true;
			}	
			else{
				$query .= $key . " LIKE '%" . $value . "%' AND ";
			}
			$value_set_flag = TRUE;
		}
	}
	
	// if a date value was recieved then add to the sqlite querry string
	foreach($date_fields_set as $key=>$value) {
		if($value){
			$to= preg_replace("(-)", ":" ,$date_fields['to_' . $key]);
			//$to= $date_fields['to_' . $key];
			$from = preg_replace("(-)", ":" ,$date_fields['from_' . $key]);
			//$from = $date_fields['from_' . $key];
			$field = $key;
			$query .= $field .' between "' . $from . '" and "' . $to . '" AND';
		}
	}
	
	//$query .= " ORDER BY date_taken DESC";
	$query = rtrim($query, "AND ") . " ORDER BY date_taken DESC";
	
	//add all matches to JSON imageArray
	
	$json .= '"imageArray" : [';
	
	//Only perform db query if a value is supplied
	if( $value_set_flag ) {
		$result= $db->query($query);
	}
	
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