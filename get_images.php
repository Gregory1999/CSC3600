<?php
//this file will send all images on request

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
							
				//hard code root directory for testing- to be deleted
				$query="INSERT INTO root_directory(path, last_scan) VALUES ( './Test_Images', '" . $date->format('d-m-Y H:i:s') . "' ) ";
				$db->query($query);				
			
			// 
			print "no_directory";

			
			}
			
			//display sorted images
			else {
				
				// scan file system script
				include_once "scripts/file_scanner.php";
			
			
				//display images
				$query = "SELECT photo_path, date_created FROM photo ORDER BY date_created DESC";
				$result= $db->query($query);
				
				while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
					$photo_path= $row["photo_path"];
					print "<img src='$photo_path' class='img-thumbnail' >";
				
				}
				
				
				
			}










?>