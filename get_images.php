<?php
//this file will send all images on request
//path to images will be sent in JSON format

			//this is just here to test the load screen
			//sleep(5);

			
			$json = "{";
			
			//this script sets up the db
			include_once "scripts/db_setup.php";
			
			//if root directory is supplied then add to db
			if(array_key_exists('root', $_GET)) {
				
				//store this as the absolute path
				//$root_path= realpath($_GET['root']);
				$root_path= realpath($_GET['root']);
				$servRoot=$_SERVER["DOCUMENT_ROOT"];
				$root_path = str_replace($servRoot, "", str_replace('\\', '/', $root_path));
				//$root_path= $_GET['root'];
				
				//initially sets the last scan time to force initial scan
				$last_scan = new DateTime('1970-01-01');
				
				//insert the root path into the db
				$query="INSERT INTO root_directory(path, last_scan) VALUES ( '" . $root_path . "', '" . $last_scan->format('d-m-Y H:i:s') . "' ) ";
				$db->query($query);	
			}

			//retrieve the root path from the db
			else {
				$query = "SELECT path, last_scan FROM root_directory";
				$result= $db->query($query);
				$row = $result->fetchArray(SQLITE3_ASSOC);
				$root_path= $row["path"];
			}
			
			//root not set- add or is not a valid directory
			if (!$root_path){
				$json .= '"root":"NULL"';		
			}
			
			//send back the images using JSON format
			else {
				
				// scan file system 
				include_once "scripts/file_scanner.php";
			
				$json .= '"root": ' . json_encode($root_path) . ' ,"imageArray" : [';
				//display images
				$query = "SELECT photo_path, date_created FROM photo ORDER BY date_created DESC";
				$result= $db->query($query);
				
				//$servRoot=$_SERVER["DOCUMENT_ROOT"];
				
				while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
					//$photo_path= json_encode(str_replace($servRoot, "", str_replace('\\', '/', $row["photo_path"])));
					//$photo_path= json_encode($servRoot);
					$photo_path= json_encode( ltrim($row["photo_path"], "."));
					$json .= $photo_path . ',';	
				}
				$json = rtrim($json,",") . "]";
			}
			$json .=  "}";
			
			//add json header and send the data
    		header("Content_type: text/json");
    		print $json;

?>