<?php
//this file will send the path for all images on request
//called from the imageOrganiser using GET method
//path to images will be sent in JSON format
//
			
			$json = "{";
			
			//this script sets up the db
			include_once "scripts/db_setup.php";
	
			//array to hold root paths
			$root_path_array = array();
			
			//if root directory is supplied then add to db
			if(array_key_exists('root', $_GET)) {
				
	
				//need to deal with absolute file paths
				$scriptPath = getcwd();
				$root_path_supplied=$_GET['root'];
				
				//finds path from document root
				$root_path_supplied= realpath($root_path_supplied);		
				
				//initially sets the last scan time to force initial scan
				$last_scan = new DateTime('1970-01-01');
				
				//insert the root path into the db
				$query="INSERT OR REPLACE INTO root_directory(path, last_scan) VALUES ( '" . $root_path_supplied . "', '" . $last_scan->format('d-m-Y H:i:s') . "' ) ";
				$db->query($query);	
			}

			//retrieve the root paths from the db
			else {
				$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
				while($row = $result->fetchArray(SQLITE3_ASSOC)){
					$path = $row["path"];
					array_push($root_path_array, $path);
				}
			}
			
			//root not set- add or is not a valid directory
			if (empty($root_path_array)){
				$json .= '"root":"NULL"';		
			}
			
			//send back the images using JSON format
			else {
				
				// scan file system 
				include_once "scripts/file_scanner.php";
				
				//this allows the images to be displayed in reverse order
				$sort = "DESC";
				if (ISSET($_GET['reverse'])){
					$sort = "ASC";
				}
				
				//output a root path array
				$json .= '"root": [' ;
				
				foreach( $root_path_array as $path ){
					$json .= json_encode($path) . ',';
				}
				
				$json = rtrim($json,",") . '] ,"imageArray" : [';
				
				//display images
				$query = "SELECT photo_path, date_taken FROM photo_origin WHERE date_taken != '' ORDER BY date_taken $sort";
				$result= $db->query($query);
				
				while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
					$photo_path= json_encode($row["photo_path"]);
					$photo_date= json_encode($row["date_taken"]);
					$json .= '{"path" : ' . $photo_path . ', "date" :' . $photo_date . '}' .',';	
				}
				//add images with no date metadata to the end
				$query = "SELECT photo_path, date_taken FROM photo_origin WHERE date_taken == '' ";
				$result= $db->query($query);
				while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
					$photo_path= json_encode($row["photo_path"]);
					$photo_date= json_encode($row["date_taken"]);
					$json .= '{"path" : ' . $photo_path . ', "date" :' . $photo_date . '}' .',';	
				}
				
				
				$json = rtrim($json,",") . "]";
			}
			$json .=  "}";
			
			//add json header and send the data
    		header("Content_type: text/json");
    		print $json;

?>