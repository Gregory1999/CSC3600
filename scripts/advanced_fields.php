<?php
//repliees JSON in format- {"camera_maker":[],"camera_model":[], "photo_type": []}
//recieves data from Get method in format ?camera_maker=xxxxxx&camera_model=xxxxx&photo_type=xxxxx

// initialise database
$db = new SQLite3('../site.db');

//array of fields camera_maker 
$fields_array=array("camera_model"=>"" , "photo_type"=>"" , "camera_maker"=>"");
$json="{";


foreach ( $fields_array as $search_field => $search_value ){

	$query1 = "SELECT DISTINCT $search_field FROM photo_camera NATURAL JOIN photo_file WHERE $search_field IS NOT NULL AND $search_field <> '' ";

	//add additional where clauses if values are supplied
	foreach ( $_GET as $field => $value ){
		if ($value != ""){
			$query1 .= "AND $field = '$value'";
		}
	}
		
	$result1 = $db->query($query1);

	$json .= '"' . $search_field . '" : [';

	while( $row = $result1->fetchArray(SQLITE3_ASSOC)) {
		$field = json_encode($row["$search_field"]);
		$json .= $field . ',';	
	}

	$json = rtrim($json,",") . '],';
}

$json = rtrim($json,",") . "}";


//add json header and send the data
header("Content_type: text/json");
print $json;

?>