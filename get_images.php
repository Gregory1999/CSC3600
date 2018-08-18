<?php
//this file will send all images on request

			//this is just here to test the load screen
			//sleep(5);
			
			
			$json = "{";
			
			include_once "scripts/db_setup.php";

			//check for for directory path, if one does not exist then ask for user to select
			$query = "SELECT path, last_scan FROM root_directory";
			$result= $db->query($query);
			$row = $result->fetchArray(SQLITE3_ASSOC);
			$root_path= $row["path"];
			$last_scan = $row["last_scan"];
			
			//get root directory
			if (!$root_path){
				#this will contain the code to set root path
			
				//initially sets the last scan time to force initial scan
				$date = new DateTime('1970-01-01');
				
				//retrieve the directory
				
				
							
				//hard code root directory for testing- to be deleted
				$query="INSERT INTO root_directory(path, last_scan) VALUES ( './Test_Images', '" . $date->format('d-m-Y H:i:s') . "' ) ";
				$db->query($query);	
				
				$json .= '"root":"NULL"';		
			
			// 
			//print "no_directory";
			

			
			}
			
			//display sorted images
			else {
				
				// scan file system script
				include_once "scripts/file_scanner.php";
			
				$json .= '"root":" ' . $root_path . '" ,"' . imageArray . '" : [';
								//display images
				$query = "SELECT photo_path, date_created FROM photo ORDER BY date_created DESC";
				$result= $db->query($query);
				
				while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
					$photo_path= $row["photo_path"];
					$json .= ' "' . $photo_path . '",';
					
					
					//print "<img src='$photo_path' class='img-thumbnail' >";
				
				}
				$json = rtrim($json,",") . "]";
				
				
			}
			$json .=  "}";
			//add json header and send the data
    		header("Content_type: text/json");
    		print $json;
				









?>